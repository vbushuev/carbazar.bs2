<?php
include("autoload.php");
$http = new core\HTTPConnector();
$amount = isset($_REQUEST["amount"])?$_REQUEST["amount"]:false;
$email = isset($_REQUEST["email"])?$_REQUEST["email"]:false;
$phone = isset($_REQUEST["phone"])?$_REQUEST["phone"]:false;
$promo = isset($_REQUEST["promo"])?$_REQUEST["promo"]:false;
echo $http->fetch("https://money.yandex.ru/eshop.xml","POST",[
    "shopId" => 113311,
    "scid"=>97661,
    "sum" => $amount,
    "customerNumber" => 'cb_'.$email,
    "shopSuccessURL" => 'https://cars-bazar.ru/payment/yk_test/success',
    "shopFailURL" => 'https://cars-bazar.ru/payment/yk_test/fail'
]);
?>
