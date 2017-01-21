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
        $template = __BASEDIR__."/themes/".$meta->Theme;
        $title = $meta->Name;
        $hm = $this->hm;
        $f3->route('GET /',
            function($f3) use ($template,$meta,$title,$hm){
                $inner = $template."/index.tpl.php";
                $posts = $hm->PostUnit->getPosts(__BASEDIR__."/content/");
                require_once $template."/template.tpl.php";
            }
        );
        $f3->route('GET /post/@name',
            function($f3) use ($template,$meta,$title,$hm) {
                $name = $f3->get("PARAMS.name");
                $post = $hm->PostUnit->getPost($name);
                $inner = $template."/post.tpl.php";
                require_once $template."/template.tpl.php";
            }
        );
        $f3->run();
    }
}