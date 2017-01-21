<?php
namespace hitchhike2;
require __BASEDIR__."/header.agpl";

class OGP implements IUnit,IWebUnit{
    private $hm;
    public function __construct(){
        global $hm;
        $this->hm = $hm;
    }
    public function getName(){
        return "OGP-Tags";
    }
    public function getVersion(){
        return "0.1";
    }
    public function getDescription(){
        return "Display OGP-Tags";
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
            "head"
        ];
    }
    public function run(){
        $meta = json_decode(file_get_contents(__BASEDIR__."/content/blog.json"));
        $Parsedown = new \Parsedown();
        $f3 = \Base::instance();
        $name = $f3->get("PARAMS.name");
        $posts = new Posts();
        $post = $posts->getPostByURL(urlencode($name));
        $description  = is_null($post) ? (!empty($meta->Title) ? $meta->Title : "") : substr(strip_tags($Parsedown->text($post->Content)),0,300)."...";
        $ogp = array();
        if (is_null($post)){
            //start page
            $ogp["og:title"] = $meta->Name;
            $ogp["og:type"] = "website";
            $ogp["og:url"] = $meta->URL;
            if (!empty($meta->Image)){
                $ogp["og:image"] = $meta->Image;
            }
            $ogp["og:description"] = $description;
        }
        else{
            //article
            $ogp["og:title"] = $post->Title;
            $ogp["og:type"] = "article";
            $ogp["og:url"] = $meta->URL."post/".$post->URL."/";
            if (!empty($post->Image)){
                $ogp["og:image"] = $post->Image;
            }
            $ogp["og:description"] = $description;
            $ogp["og:article:published"] = date("c",$post->Date);
            if (count($post->Tags) != 0){
                $ogp["og:article:tag"] = array();
                foreach($post->Tags as $tag){
                    $ogp["og:article:tag"][] = $tag;
                }
            }
            $ogp["og:article:author:username"] = $post->Author->Signature; 
            $nameParts = explode(" ",$post->Author->Name);
            if (count($nameParts) === 2){
                 $ogp["og:article:author:first_name"] = $nameParts[0]; 
                 $ogp["og:article:author:last_name"] = $nameParts[1]; 
            }
        }
        foreach($ogp as $key => $value){
            if (!is_array($value)){
                echo "<meta property=\"".$key."\" content=\"".$value."\" />\n";
            }
            else{
                foreach($value as $arrTag){
                    echo "<meta property=\"".$key."\" content=\"".$arrTag."\" />\n";
                }
            }
        }
    }
}