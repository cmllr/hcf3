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
        $result = unlink(__BASEDIR__."/units/Meta.php");
        if ($result){
            echo "Meta removed.\n";
        }else{
            echo "Meta NOT removed.\n";
        }
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