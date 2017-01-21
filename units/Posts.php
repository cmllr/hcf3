<?php
namespace hitchhike2;
require __BASEDIR__."/header.agpl";

class Post {
    public $Title;
    public $URL;
    public $Content;
    public $Date;
    public $Author;
    public $Image;
    public $Tags;
    public $Hidden;
}

class Posts implements IUnit, IPostUnit{
    public function getName(){
        return "Posts";
    }
    public function getVersion(){
        return "0.1";
    }
    public function getDescription(){
        return "Manage and use site content (e. g. posts, sites)";
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
    public function getPosts($path){
        $list = array();
        $files = scandir($path);
        $blacklist = array(".","..");
        foreach($files as $file){
            if (!in_array($file,$blacklist)){
                $dir = is_dir($path.$file."/");
                if ($dir){
                    $list = array_merge($this->getFiles($path.$file."/"),$list);
                }else{
                    if (strpos($file,".md") !== false){
                        $list[] = $file;
                    }
                }
            }
        }
        return $list;
    }
    public function getPost($name){
        $path = __BASEDIR__."/content/$name";
        if (!file_exists($path)){
            return null;
        }else{
            $content = file_get_contents($path);
            $lines = explode("\n",$content);
            $post = new Post();
            $post->Title = trim(str_replace("#","",$lines[0]));
            $post->Content = str_replace($lines[0],"",$content);            
            $post->Tags = [];
            $tags = [];
            preg_match_all("/(?<tag>(#|\~)[^\s]+)/",$content,$tags);
            if(count($tags["tag"]) > 0){
                foreach($tags["tag"] as $tag){
                    $post->Tags[] = str_replace("#","",$tag);
                    $post->Hidden = $tag === "#hidden";
                    $post->Content = str_replace($tag,"",$post->Content);
                }
            }
            $post->URL = str_replace(".md","",$name);
            $post->Date = filemtime($path);
            $post->Author = null;
            $this->runContentCommands($post);
            return $post;
        }
    }
    public function runContentCommands($post){
        $pattern = "/(?<what>[a-zA-z]+)\((?<param>[^\)]+)\)/";
        $matches  = [];
        preg_match_all($pattern,$post->Content,$matches);
        foreach($matches["what"] as $index => $match){
            $param = $matches["param"][$index];
            $post->{$match} = $param;
            $post->Content = str_replace($match."(".$param.")","",$post->Content);
        }
    }
}