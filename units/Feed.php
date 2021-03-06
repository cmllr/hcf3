<?php
namespace hitchhike2;
require __BASEDIR__."/header.agpl";

class Feed implements IUnit,IWebUnit{
    private $hm;
    public function __construct(){
        global $hm;
        $this->hm = $hm;
    }
    public function getName(){
        return "Feed";
    }
    public function getVersion(){
        return "0.1";
    }
    public function getDescription(){
        return "Feed unit";
    }
    public function _install(){
		exec("composer require suin/php-rss-writer");
    }
    public function _remove(){
		exec("composer remove suin/php-rss-writer");
        $result = unlink(__BASEDIR__."/units/Feed.php");
        if ($result){
            echo "Feed removed.\n";
        }else{
            echo "Feed NOT removed.\n";
        }
    }
    public function getCLIMethods(){
        return [
        ];
    }
    public function getEntryPoints(){
        return [
            "before-skeleton"
        ];
    }
    public function run(){
        $meta = $this->hm->ManagerUnit->getMeta();
        $hm = $this->hm;
        $f3 = \Base::instance();
        $f3->route([
                'GET /feed/@tag',
                'GET /feed'
            ],
            function($f3) use ($template,$meta,$title,$hm){
                header("Content-type: text/xml");
                $feed = new \Suin\RSSWriter\Feed();
                $channel = new \Suin\RSSWriter\Channel();
                $channel
                        ->title($meta->Name)
                        ->description($meta->Title)
                        ->url($meta->URL)
                        ->pubDate(time())
                        ->lastBuildDate(time())
                        ->ttl(60)
                        ->appendTo($feed);
                $Parsedown = new \Parsedown();
                $posts = array_filter($hm->PostUnit->getPosts(__BASEDIR__."/content/"),function($p){
                    return !$p->Hidden;
                });
                $needles = null;
                if ($f3->get("PARAMS.tag") !== null){
                    $needle = $f3->get("PARAMS.tag");
                }
                //TODO: TAG
                foreach ($posts as $entry) {
                    $display = true;
                    if ($needle !== null){
                        if (!in_array($needle,$entry->Tags)){
                            $display = false;
                        }
                        if (!$display && strpos($entry->Content,$needle) !== false){
                            $display = true;
                        }
                    }
                    if ($entry->Page === true){
                        $display = false;
                    }
                    if ($display){
                        $item = new \Suin\RSSWriter\Item();
                        $name = !empty($entry->Author->Name)? $entry->Author->Name : $meta->Name;
                        $item
                                ->title($entry->Title)
                                ->description($Parsedown->text($entry->Content))
                                ->url($meta->URL."post/" . $entry->URL."/")
                                ->pubDate($entry->Date)
                                ->guid($meta->URL."post/" . $entry->URL."/", true)
                                ->author($name)
                                ->appendTo($channel);
                    }

                }
                echo $feed;
            }
        );
    }
}