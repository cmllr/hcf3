<?php
namespace hitchhike2;
require __BASEDIR__."/header.agpl";

class H1H2 implements IUnit,IWebUnit{
    private $hm;
    public function __construct(){
        global $hm;
        $this->hm = $hm;
    }
    public function getName(){
        return "H1H2";
    }
    public function getVersion(){
        return "0.1";
    }
    public function getDescription(){
        return "Redirect H1a requests to H2a ";
    }
    public function _install(){

    }
    public function _remove(){
        echo "unlink".__BASEDIR__."/units/Packages.php";
    }
    public function getCLIMethods(){
        return [
        ];
    }
    public function getEntryPoints(){
        return [
            "before-skeleton"
        ];
    }
    public function run(){
        $meta = $this->hm->ManagerUnit->getMeta();
        $target = $_SERVER["QUERY_STRING"];
        $target = urldecode(str_replace(["/post/","/"],"",$target));
        if(strpos($_SERVER["REQUEST_URI"],"?/post/") !== false){
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$meta->URL."post/".$target);
        }
        if(strpos($_SERVER["REQUEST_URI"],"?/feed/") !== false){
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$meta->URL.str_replace("/feed","feed",$target));
        }
    }
}