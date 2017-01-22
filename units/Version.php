<?php
namespace hitchhike2;
require __BASEDIR__."/header.agpl";

class Version implements IUnit,IWebUnit{
    public function __construct(){
        define("__VERSION__","0.5");
        define("__CODENAME__","Colin");
    }
    public function getName(){
        return "Version";
    }
    public function getVersion(){
        return "0.5";
    }
    public function getDescription(){
        return "Version meta package";
    }
    public function _install(){

    }
    public function _remove(){
        echo "unlink".__BASEDIR__."/units/Packages.php";
    }
    public function getCLIMethods(){
        return [
            "version" => "Get the version of Hitchhike",
            "codename" => "Get the version codename of Hitchhike"
        ];
    }

    public function version(){
        echo __VERSION__."\n";
    }
    public function codename(){
        echo __CODENAME__."\n";
    }
    public function getEntryPoints(){
        return [
            "before-skeleton"
        ];
    }
    public function run(){  
        //needed by implementation. Does nothing.
    }
}