<?php
namespace cb;
class Promo{
    protected $codes=[];
    public function __construct(){
        $this->loadCodes();
    }
    public function get($promo){
        $res=["discount"=>0,"response"=>false];
        if(isset($this->codes[strtoupper($promo)])){
            if($this->codes[strtoupper($promo)]["type"]=="once"){
                if($this->codes[strtoupper($promo)]["used"] == true) $res["response"] = "used";
                else {
                    $res = [
                        "discount"=>$this->codes[strtoupper($promo)]["discount"],
                        "response"=>"ok"
                    ];
                    //$this->codes[strtoupper($promo)]["used"] == true;
                    //$this->codes[strtoupper($promo)]["who"] == $_SERVER["REMOTE_ADDR"];
                    file_put_contents("store/promo.json",json_encode($this->codes,JSON_PRETTY_PRINT));
                }
            }
        }
        return $res;
    }
    public function used($promo){
        $res=["discount"=>0,"response"=>false];
        if(isset($this->codes[strtoupper($promo)])){
            if($this->codes[strtoupper($promo)]["type"]=="once"){
                if($this->codes[strtoupper($promo)]["used"] != true) {
                    $res = [
                        "discount"=>$this->codes[strtoupper($promo)]["discount"],
                        "response"=>"ok"
                    ];
                    $this->codes[strtoupper($promo)]["used"] = true;
                    $this->codes[strtoupper($promo)]["date"] = date("Y-m-d H:i:s");
                    $this->codes[strtoupper($promo)]["who"] = $_SERVER["REMOTE_ADDR"];
                    $this->storeCodes();
                }
            }
        }
        return $res;
    }
    public function generate($c,$n,$a){
        $def = [
            "used"=> false,
            "discount"=> $a,
            "type"=> "once"
        ];
        ;
        $val = '';
        for($i=0;$i<$c;$i++){
            $val .= chr( rand( 65, 90 ) );
            $promo = $n.strtoupper(substr(sha1($val),rand(0,33),5));
            $this->codes[$promo] = $def;
            echo $promo."\n";
        }
        file_put_contents("store/promo.json",json_encode($c,JSON_PRETTY_PRINT));
    }
    protected function loadCodes(){
        $this->codes = json_decode(file_get_contents("store/promo.json"),true);
    }
    protected function storeCodes(){
        file_put_contents("store/promo.json",json_encode($this->codes,JSON_PRETTY_PRINT));
    }
};
?>
