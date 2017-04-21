<?php
header('Content-Type: application/json; charset=utf-8');
include("autoload.php");

$vin = isset($_REQUEST["vin"])?$_REQUEST["vin"]:"WF0RXXGCDR8R45807";
$type = isset($_REQUEST["type"])?$_REQUEST["type"]:"small";
$rq = ["vin" => $vin,"report"=>""];

$rca = new cb\parse\Rca();
$zalog = new cb\parse\Zalog();
$gibdd = new cb\parse\Gibdd();
$cb = new cb\ClientBase();
$tables = $cb->call("table","get_list");
$result = $tables["data"];
// foreach ($tables["data"] as $key => $value) {
//     $result[$key] = $cb->call("table","info",["id"=>intval($key)]);
// }
$resultJson = json_encode($result,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
echo $resultJson;
?>
