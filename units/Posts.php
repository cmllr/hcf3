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
    public $Hidden = false;
    public $Page = false;
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
        $result = unlink(__BASEDIR__."/units/Posts.php");
        if ($result){
            echo "Posts removed.\n";
        }else{
            echo "Posts NOT removed.\n";
        }
    }
    public function getCLIMethods(){
        return [
           
        ];
    }
    public function getFiles($path){
        $list = array();
        $files = scandir($path);
        $blacklist = array(".","..");
        foreach($files as $file){
            if (!in_array($file,$blacklist)){
                $dir = is_dir($path.$file."/");
                if ($dir){
                    $list = array_merge($this->getFiles($path.$file."/"),$list);
                }else{
                    if (strpos($file,".md") !== false && $file[0] !== "."){
                        $list[] = $file;
                    }
                }
            }
        }
        return $list;
    }
    public function GetPosts($path){
        $files = $this->getFiles($path);
        $posts = [];
        foreach($files as $post){
            $posts[] = $this->getPost($post);
        }
        usort($posts,function($a,$b){
            return $a->Date < $b->Date;
        });
        return $posts;
    }
    public function getPages($path){
        $files = $this->getFiles($path);
        $posts = [];
        foreach($files as $post){
            $p  = $this->getPost($post);
            if ($p->Page){
                $posts[] = $p;
            }
        }
        usort($posts,function($a,$b){
            return $a->Date < $b->Date;
        });
        return $posts;
    }
    public function getPostByURL($url){
        $posts = $this->GetPosts(__BASEDIR__."/content");
        foreach($posts as $post){
            if ($post->URL === $url){
                return $post;
            }
        }
        return null;
    }
    public function getPost($name){
        $path = __BASEDIR__."/content/$name";
        if (!file_exists($path)){
            return null;
        }else{
            $meta = \json_decode(file_get_contents(__BASEDIR__."/content/blog.json"));
            $content = file_get_contents($path);
            $lines = explode("\n",$content);
            $post = new Post();
            $post->Title = trim(str_replace("#","",$lines[0]));
            $post->Content = str_replace($lines[0],"",$content);            
            $post->Tags = [];
            $tags = [];
            preg_match_all("/(?<tag>~[^\s\#]+)/",$content,$tags);

            $this->runContentCommands($post);
            if(count($tags["tag"]) > 0){
                foreach($tags["tag"] as $tag){
                    //fallback for 0.4
                    if (strpos($tag,"(") === false && !($tag === "#hidden" || $tag === "~hidden")){
                        $post->Tags[] = strtolower(str_replace(["#","~"],"",$tag));
                        $post->Content = str_replace($tag,"",$post->Content);
                    }
                    if ($post->Hidden === false){
                        $post->Hidden = $tag === "#hidden" || $tag === "~hidden";
                        $post->Content =  str_replace($tag,"",$post->Content);
                    }
                }
            }
            $post->URL = urlencode(str_replace([".md","?"],"",$name));
            $post->Date = filemtime($path);
            if ($post->Author === null){
                foreach($meta->Authors as $a){
                    if (strpos($post->Content,$a->Signature) !== false){
                        $post->Author = $a;
                        break;
                    }
                }
            }
            if ($post->Author === null){
                $post->Author = $meta->Authors[0];
            }
            return $post;
        }
    }
    public function runContentCommands($post){
        $pattern = "/~(?<what>[a-zA-Z]+)\((?<param>[^\)]+)\)/";
        $matches  = [];
        preg_match_all($pattern,$post->Content,$matches);
        $propsSet = [];
        foreach($matches["what"] as $index => $match){
            $rawparam = $matches["param"][$index];
            if ($match === "date"){
                $param = $rawparam === "today" ? time() : strtotime($rawparam); //"Sticky post"
            }else{
                $param = $rawparam;
            }
            if ($match === "page"){
                $param = $rawparam === "true";
            }
            foreach($post as $prop => $val){
                if (strtolower($prop) === strtolower($match)){
                    $post->{$prop} = $param;
                    $propsSet[] = $match;
                }
            }
            $post->Content = str_replace("~".$match."(".$rawparam.")","",$post->Content);
        }
        foreach($matches["what"] as $index => $match){
            $rawparam = $matches["param"][$index];
            $post->{$match} = $param;
            $post->Content = str_replace("~".$match."(".$rawparam.")","",$post->Content);
        }
    }
}