<?php
namespace hitchhike2;
require __BASEDIR__."/header.agpl";

class Skeleton implements IUnit, ISkeleton{
    private $hm;
    public function __construct($hm){
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
        return [
            "sitemap" => "Creates the sitemap.xml"
        ];
    }
    public function sitemap(){
        $sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
        "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
         xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
         xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
        $contentDir = __BASEDIR__."/content/";
        $posts = $this->hm->PostUnit->getPosts($contentDir);
        $meta = $this->hm->ManagerUnit->getMeta();
        $entry = "<url>\n".
                "<loc>".$meta->URL."</loc>\n".
                "<lastmod>".date("Y-m-d",filectime($contentDir."blog.json"))."</lastmod>\n".
                "<changefreq>monthly</changefreq>\n".
                "<priority>1</priority>\n".
            "</url>\n";
        $sitemap .= $entry;
        
        foreach($posts as $post){
            $url = $meta->URL."post/".$post->URL."/";
            $entry = "<url>\n".
                    "<loc>".$url."</loc>\n".
                    "<lastmod>".$post->Date."</lastmod>\n".
                    "<changefreq>monthly</changefreq>\n".
                    "<priority>1</priority>\n".
                "</url>\n";
            $sitemap .= $entry;
        }
        $sitemap .="</urlset>\n";
        $result = file_put_contents(__BASEDIR__."/sitemap.xml",$sitemap);
        if ($result > 0){
            echo "Sitemap written.\n";
        }else{
            echo "Generation failed!\n";
        }
    }
    public function run(){
        $meta = $this->hm->ManagerUnit->getMeta();
        $f3 = \Base::instance();
        $template = __BASEDIR__."/themes/".$meta->Theme;
        $title = $meta->Name;
        $hm = $this->hm;
        $pages = $hm->PostUnit->getPages(__BASEDIR__."/content/");
        $f3->route('GET /',
            function($f3) use ($template,$meta,$title,$hm,$pages){
                $inner = $template."/index.tpl.php";
                $posts = $hm->PostUnit->getPosts(__BASEDIR__."/content/");
                require_once $template."/template.tpl.php";
            }
        );
        $f3->route([
                'GET /tag/@needle',
                'GET /search/@needle'
            ],
            function($f3) use ($template,$meta,$title,$hm,$pages){
                $inner = $template."/index.tpl.php";
                $tag = $f3->get("PARAMS.needle");
                $title = $tag ."-".$meta->Name;
                $posts = $hm->PostUnit->getPosts(__BASEDIR__."/content/");
                $posts = array_filter($posts,function($p) use ($tag){
                    return in_array($tag,$p->Tags) || strpos(strtolower($p->Content),strtolower($tag)) !== false;
                });
                require_once $template."/template.tpl.php";
            }
        );
        $f3->route('GET /post/@name',
            function($f3) use ($template,$meta,$title,$hm,$pages) {
                $name = $f3->get("PARAMS.name");
                $post = $hm->PostUnit->getPostByURL(urlencode($name));
                $authors = $meta->Authors;
                $author = null;
                foreach($authors as $a){
                    if ($a->Signature === $post->Author){
                        $post->Author = $a;
                    }
                }
                if (is_null($post)){
                    //header("Location: ../404");
                    die();
                }
                $title = $post->Title ."-".$meta->Name;
                $inner = $template."/post.tpl.php";
                require_once $template."/template.tpl.php";
            }
        );
         $f3->route('GET /404',
            function($f3) use ($template,$meta,$title,$hm,$pages) {
                http_response_code(404);
                echo "Not found";
            }
        );
        $f3->run();
    }
}