<?php
namespace cb\parse;
use core\Log as Log;
use core\HTTPConnector as Http;
//use cb\AntiCaptcha as Captcha;

class Carprice{
    public function get($d){
        $http = new Http();
        $html = $http->fetch("https://www.reestr-zalogov.ru/search/index");
        $uuid = 'b6fa0009-2777-461c-94b1-7482368990dc';
        //<input type="hidden" id="uuid" name="uuid" value="b6fa0009-2777-461c-94b1-7482368990dc">
        if(preg_match("/id=\"uuid\"\s+name=\"uuid\"\s+value=\"([^\"]+)\"/im",$html,$m)){
            $uuid = $m[1];
            //echo "FOUND UUID:".$uuid;
        }
        $d["captcha"] = $http->fetch("https://www.reestr-zalogov.ru/captcha/generateCaptcha?".$this->random());
        file_put_contents("store/zalog_captcha.jpg",$d["captcha"]);
        // solve captcha

        $word = $captcha->get($d);
        // checkdata http://check.gibdd.ru/proxy/check/auto/history
        $res = $http->fetch("https://www.carprice.ru/local/components/linemedia.carsale/evaluate.main/ajax/ajax.php?action=get-price","GET",[
            "action"=>"get-price"
        ]);
        $http->close();
        return $res;
    }
    protected function random(){
        return round(microtime(true) * 1000)."";
    }
};
?>
