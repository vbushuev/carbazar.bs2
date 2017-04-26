<?php
namespace cb\parse;
use core\Log as Log;
use core\HTTPConnector as Http;
//use cb\AntiCaptcha as Captcha;

class Carprice{
    protected $encodes = [];
    public function __construct(){
        if(file_exists("store/cp.encodes.json")){
            $this->encodes = json_decode(file_get_contents("store/cp.encodes.json"),true);
        }
    }
    public function get($d=[]){
        $http = new Http();
        $brands = json_decode($http->fetch("https://evaluate-api.carprice.ru/evaluate-form/brands?type_id=0"),true);
        $d["mark"] = $this->encodeMark($d["mark"]);
        $d["model"] = mb_strtolower(preg_replace(["/С/m","/А/m"],["C","A"],$d["model"]));
        foreach($brands["data"] as $brand){
            if(mb_strtolower($brand["text"])==$d["mark"]){
                $d["brand_id"] = $brand["value"];
                break;
            }
        }

        $models = json_decode($http->fetch("https://evaluate-api.carprice.ru/evaluate-form/models?brand_id=".$d["brand_id"]."&year=".$d["year"]),true);

        foreach($models["data"] as $model){
            if(preg_match("/".preg_quote($d["model"])."/imu",mb_strtolower($model["text"]))){
                $d["model_id"] = $model["value"];
                break;
            }
        }
        $res = $http->fetch("https://www.carprice.ru/local/components/linemedia.carsale/evaluate.main/ajax/ajax.php","GET",[
            'action' => 'set-user-data',
            'type_tech_id' => '0',
            'brand_id' => $d["brand_id"],
            'year' => $d["year"],
            'model_id' => $d["model_id"],
            'email' => 'yanusdnd@inbox.ru',
            'terms' => 'Y'
        ]);
        $http->close();
        return $res;
    }
    protected function encodeMark($m){
        return mb_strtolower(isset($this->encodes[$m])?$this->encodes[$m]:$m);
    }
};
?>
