<?php
header('Content-Type: application/json; charset=utf-8');
include("autoload.php");
$vin = isset($_REQUEST["vin"])?$_REQUEST["vin"]:"WF0RXXGCDR8R45807";
$type = isset($_REQUEST["type"])?$_REQUEST["type"]:"small";
$rq = ["vin" => $vin,"report"=>""];

$gibdd = new cb\parse\Gibdd();
$cb = new cb\ClientBase();
$found = $cb->get($rq);
if(count($found)){
    foreach($found as $k=>$val){
        $resultJson = $val["line"]["report"];
        break;
    }

}
//else{
else if(false){
    $result = ($type=="full")?[
        "history"=> json_decode($gibdd->history($rq),true),
        "dtp"=> json_decode($gibdd->dtp($rq),true),
        "wanted"=> json_decode($gibdd->wanted($rq),true),
        "restrict"=> json_decode($gibdd->restrict($rq),true),
    ]:[
        "history"=> json_decode($gibdd->history($rq),true)
    ];
    $resultJson = json_encode($result,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    $rq["report"]=$resultJson;
    $cb->send($rq);
}
echo $resultJson;
?>
