<?php
namespace hitchhike2;
require __BASEDIR__."/header.agpl";

class Meta implements IUnit,IWebUnit{
    public function getName(){
        return "Meta";
    }
    public function getVersion(){
        return "0.1";
    }
    public function getDescription(){
        return "Adds common meta tags to <head> section";
    }
    public function _install(){

    }
    public function _remove(){
        echo "unlink".__BASEDIR__."/units/Packages.php";
    }
    public function getCLIMethods(){
        return [];
    }
    public function getEntryPoints(){
        return [
            "head"
        ];
    }
    public function run(){
        echo "<meta name=\"generator\" content=\"Hitchhike ".__VERSION__."\">\n";
    }
}