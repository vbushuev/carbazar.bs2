<?php
namespace cb;
use core\Config as Config;
use core\Log as Log;
use core\HTTPConnector as Http;
class ClientBase{
    protected $access_id = false;
    protected $accessTime = 0;
    protected $key;
    protected $host;
    protected $login;
    protected $version;
    protected $reportTable;
    public function __construct(){
        $cfg = Config::clientBase();
        $this->key = $cfg["key"];
        $this->host = $cfg["host"];
        $this->login = $cfg["login"];
        $this->version = $cfg["version"];
        $this->reportTable = intval($cfg["reportTable"]);
        $this->auth();
    }
    public function auth(){
        $res = $this->call("auth","request",["login"=>$this->login]);
        if($res["code"]==0){
            $res = $this->call("auth","auth",["login"=>$this->login,"hash"=>md5($res["salt"].$this->key)]);
            if($res["code"]==0){
                $this->access_id = $res["access_id"];
                $this->accessTime = time();
            }
            else Log::debug($res);
        }
        else Log::debug($res);
        return $this->access_id;
    }
    public function send($d){
        $this->checkAuth();
        $res = $this->call("data","create",[
            "table_id"=>$this->reportTable,
            "cals"=>false,
            "data"=>[
                "line" => $d
            ]
        ]);
        if(!$res["code"]==0)Log::debug($res);
    }
    public function get($d){
        $this->checkAuth();
        $res = $this->call("data","read",[
            "table_id"=>$this->reportTable,
            "cals"=>false,
            "fields" => [
                "line"=>[]
            ],
            "filter"=>[
                "line" => [
                    "vin"=>[
                        "term"=> "=",
                        "value"=> $d["vin"],
                        "union"=> "AND"
                    ],
                    "Статус записи"=>[
                        "term"=> "=",
                        "value"=> 0,
                        "union"=> "AND"
                    ]
                ]
            ],
            "sort"=>[
                "line"=>[
                    "ID"=>"DESC"
                ]
            ],
            "start"=>0,
            "limit"=>1
        ]);
        // $res = $this->call("data","read",[
        //     "table_id"=>$this->reportTable,
        //     "cals"=>false,
        //     "fields" => ["row"=>[]],
        //     "filter"=>[
        //         "row"=>[
        //             "f4660"=>[
        //                 "term"=> "=",
        //                 "value"=> $d["vin"],
        //                 "union"=> "AND"
        //             ]
        //         ]
        //     ],
        //     "sort"=>["row"=>["id"=>"DESC"]],
        //     "start"=>0,"limit"=>1
        // ]);
        if($res["code"]==0)return $res["data"];
        else Log::debug($res);
        return [];
    }
    public function call($mod,$met,$data=[]){
        $http = new Http(["json"=>true]);
        $res = [];
        $http->headers = ["Accept"=>"application/json, text/javascript, */*; q=0.01"];

        if($this->access_id!==false)$data["access_id"] = $this->access_id;
        else $data["v"] = $this->version;
        $res = $http->fetch($this->host."/{$mod}/{$met}/","POST",$data);
        $http->close();
        return json_decode($res,true);
    }
    protected function checkAuth(){
        if((time()-$this->accessTime)>5){
            $this->access_id = false;
            $this->auth();
        }
    }
};
?>