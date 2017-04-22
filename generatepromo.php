<?php
$def = [
    "used"=> false,
    "date"=> "2017-04-23 00=>11=>07",
    "who"=> "127.0.0.1",
    "discount"=> 100,
    "type"=> "once"
];
$c = [];
$val = '';
for($i=0;$i<10;$i++){
    $val .= chr( rand( 65, 90 ) );
    $promo = "CARBAZ".strtoupper(substr(sha1($val),rand(0,33),5));
    $c[$promo] = $def;
    echo $promo."\n";
}
file_put_contents("store/promo.json",json_encode($c,JSON_PRETTY_PRINT));
?>
