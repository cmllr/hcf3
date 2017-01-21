<?php
namespace hitchhike2;
require __BASEDIR__."/header.agpl";

class Skeleton implements IUnit, ISkeleton{
    private $hm;
    public function __construct(){
        global $hm;
        $this->hm = $hm;
    }
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
    public function getCLIMethods(){
        return [];
    }

    public function run(){
        $meta = $this->hm->ManagerUnit->getMeta();
        $f3 = \Base::instance();
        $f3->route('GET /',
            function() {
                echo 'Hello, world!';
            }
        );
        $f3->route('GET /fooo',
            function() {
                echo 'Hello, worlfasfasfd!';
            }
        );
        $f3->run();
        require_once __BASEDIR__."/themes/".$meta->Theme."/index.tpl.php";
    }
}