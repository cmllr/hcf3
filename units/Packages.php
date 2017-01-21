<?php
namespace hitchhike2;
require __BASEDIR__."/header.agpl";

class Packages implements IUnit{
    private $packageServer = "https://packages.0fury.de/";
    private $hm;
    public function __construct($hm){
        $this->hm = $hm;
    }
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
        $failed = 0;
        foreach($packages as $package){
            echo "Installing $package...";
            //TODO: Server interactions
            $data = $this->info($package);
            if ($data === null){
                echo "failed!\n";
                $failed++;
            }else{
                $content = "";
                $fileName = "";
                foreach($data->Files as $file){
                    if (strpos($file,".src") !== false){
                        $content = file_get_contents($file);
                        $parts = explode("/",$file);
                        $fileName = str_replace(".src",".php",$parts[count($parts) -1]);
                    }
                }
                if (!file_exists(__BASEDIR__."/units/".$fileName)){
                    file_put_contents(__BASEDIR__."/units/".$fileName,$content);
                    require_once(__BASEDIR__."/units/".$fileName);
                    $this->hm->runComposerUpdate();
                    $name = "\\".$package; //TODO: For deployment: Change to hitchhike2-ns
                    $obj = new $name();
                    $obj->_install();
                    echo "done.\n";
                }else{
                    echo "failed! \n";
                    $failed++;
                }
            }
        }
        echo "$failed installs failed\n";
    }
    public function search($packages){
        $result = file_get_contents($this->packageServer."?/list/");
        $data = json_decode($result);
        echo "Server: ".$this->packageServer."\n";
        foreach($packages as $package){
            if (in_array($package,$data)){
                $info = $this->info($package);
                if ($info === null){
                    echo $package." not found!\n";
                }else{
                    echo "Found: \"" .$info->Name."\": ".$info->Description." [".$info->Version."]\n";
                }
            }
        }
    }
    public function info($package){
       $result = @file_get_contents($this->packageServer."?/package/".strtolower($package)."/");
       $data = json_decode($result);
       return $data;
    }
    public function remove($packages){
        foreach($packages as $package){
            echo "Removing $package...\n";    
            if (!file_exists(__BASEDIR__."/units/".$package.".php")){
                echo "Not found!\n";
            }else{
                require_once(__BASEDIR__."/units/".$package.".php");
                $name = "\\".$package; //TODO: For deployment: Change to hitchhike2-ns
                $obj = new $name();
                $obj->_remove();
                $this->hm->runComposerUpdate();
            }
        }
    }
}