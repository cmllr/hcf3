<?php
namespace hitchhike2;
require __BASEDIR__."/header.agpl";

class Packages implements IUnit{
    public function getName(){
        return "Packages";
    }
    public function getVersion(){
        return "0.1";
    }
    public function getDescription(){
        return "Add or remove packages from your installation";
    }
    public function _install(){

    }
    public function _remove(){
        echo "unlink".__BASEDIR__."/units/Packages.php";
    }
    public function getCLIMethods(){
        return [
            "install <package 1> <package n>" => "Installs given package(s) to your intallation."
        ];
    }
    public function install($packages){
        foreach($packages as $package){
            echo "Installing $package...";
            //TODO: Server interactions
            echo "done.\n";
        }
    }
}