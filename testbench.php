<?php
header('Content-Type: application/json; charset=utf-8');
include("autoload.php");

$vin = isset($_REQUEST["vin"])?$_REQUEST["vin"]:"WF0RXXGCDR8R45807";
$type = isset($_REQUEST["type"])?$_REQUEST["type"]:"small";
$rq = ["vin" => $vin,"report"=>""];

$cp = new cb\parse\Carprice();
$result = $cp->get();
$resultJson = json_encode($result,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
echo $resultJson;
?>
