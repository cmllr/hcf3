<?php
namespace hitchhike2;
require __BASEDIR__."/header.agpl";

class Manager implements IUnit, IManager{
    public function getName(){
        return "Manager";
    }
    public function getVersion(){
        return "0.1";
    }
    public function getDescription(){
        return "Website Metadata manager";
    }
    public function _install(){
        var_dump(__BASEDIR__);
    }
    public function _remove(){

    }
    public function getCLIMethods(){
        return [];
    }
    public function getMeta(){
        $file = file_get_contents(__BASEDIR__."/content/blog.json");
        return json_Decode($file);
    }
}