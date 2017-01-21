<?php
namespace hitchhike2;
require __BASEDIR__."/header.agpl";

class Skeleton implements IUnit{
    public function getName(){
        return "Skeleton";
    }
    public function getVersion(){
        return "0.1";
    }
    public function getDescription(){
        return "Website Skeleton";
    }
    public function _install(){
        var_dump(__BASEDIR__);
    }
    public function _remove(){

    }
}