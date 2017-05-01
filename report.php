<?php
header('Content-Type: application/json; charset=utf-8');
include("autoload.php");
ob_start();
$vin = isset($_REQUEST["vin"])?$_REQUEST["vin"]:"WF0RXXGCDR8R45807";
$type = isset($_REQUEST["type"])?$_REQUEST["type"]:"small";
$rq = ["vin" => $vin,"report"=>"","type"=>$type,"status"=>"new"];
$data = $rq;
$rca = new cb\parse\Rca();
$zalog = new cb\parse\Zalog();
$gibdd = new cb\parse\Gibdd();
$cb = new cb\ClientBase();
$current = $cb->get($rq);
$result = ["status"=>"unknown"];
$resultJson = "{}";
//if(file_exists("store/".$rq["vin"].".json"))echo file_get_contents("store/".$rq["vin"].".json"); exit;
// проверка на существование записи
if(count($current)){
    foreach($current as $row){
        $data = $row["line"];
        break;
    }
}
else $cb->create($rq);
if($type == "full"){
    if($type == $data["type"] && $data["status"] == "full"){
        $result = array_merge(["status"=>"ok"],json_decode($data["report"],true));
    }
    else {
        if($data["payed"] == "1"){
            $result = [
                "status"=>"ok",
                "history"=> json_decode($gibdd->history($rq),true),
                "dtp"=> json_decode($gibdd->dtp($rq),true),
                "wanted"=> json_decode($gibdd->wanted($rq),true),
                "restrict"=> json_decode($gibdd->restrict($rq),true),
                "rca"=>json_decode($rca->get($rq),true),
                "zalog"=>json_decode($zalog->get($rq),true)
            ];
            $data["status"] = "full";
        }
        else {
            $result = ["status"=>"notpayed"];
        }
    }
}else {
    $dataReport = json_decode($data["report"],true);
    if($type == $data["type"] && $data["status"] == "first" && isset($dataReport["history"]) && isset($dataReport["history"]["status"]) && $dataReport["history"]["status"]=="200"){
        $result = array_merge(["status"=>"ok","order"=>["id"=>$data["ID"]]],$dataReport);
    }
    else {
        $data["status"] = "first";
        $result = array_merge(["status"=>"ok","order"=>["id"=>$data["ID"]]],["history"=> json_decode($gibdd->history($rq),true)]);
    }
}
$resultJson = json_encode($result,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
$data["report"]=$resultJson;
$cb->update($data);
if($data["status"]=="full")file_put_contents("store/".$rq["vin"].".json",$resultJson);
ob_end_clean();
echo $resultJson;
?>
