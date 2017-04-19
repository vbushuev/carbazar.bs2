<?php
namespace core;
class Config{
    public static function __callStatic($n,$a){
        if(!isset($cbConfig)){
            include("config.php");
        }
        return isset($cbConfig[$n])?$cbConfig[$n]:false;
    }
};
?>
