<?php
namespace hitchhike2;
use \hitchhike2;
define("__BASEDIR__",__DIR__);
require __DIR__."/header.agpl";
require __DIR__."/unit.interface";
require __DIR__."/vendor/autoload.php";
if (php_sapi_name() !== 'cli'){
	//Prevent browsing users from accessing hm.php directly
	header("Location: index.php");
}

$hm = new HM($argv);

class HM{
	public function __construct($argv){
		if (count($argv) !== 1){
			$params = array_slice($argv,1);
			$className = array_shift($params);
			$method = $argv[2];
			$existing = class_exists("\\hitchhike2\\".$className);
			if ($existing){
				$name ="\\hitchhike2\\".$className;
				$obj = new $name();
				$args = array_slice($argv, 3,count($argv)-3);
				call_user_func(array($obj,$method),$args);
			}else{
				echo "Unit not found. Use hm.php help for more information\n";
			}

		}else{
			$this->help();
			$this->runComposerUpdate();
		}
	}
	/**
	* Displays logo and get further help texts
	*/
	public function help(){
        echo "	  _                       _            \n";
        echo "	 | |                     | |           \n";
        echo "	 | |__  _ __ ___    _ __ | |__  _ __   \n";
        echo "	 | '_ \| '_ ` _ \  | '_ \| '_ \| '_ \  \n";
        echo "	 | | | | | | | | |_| |_) | | | | |_) | \n";
        echo "	 |_| |_|_| |_| |_(_) .__/|_| |_| .__/  \n";
        echo "	                   | |         | |     \n";
        echo "	                   |_|         |_|     \n";
		$units = $this->getUnits();
		$this->getList($units);
	}
	private function getList($units){
		foreach($units as $unit){
			if (strpos($unit,"HM") === false){
				$name ="\\hitchhike2\\".$unit;
				$obj = new $name();
				echo $obj->getName().": ".$obj->getDescription()." [".$obj->getVersion()."]\n";
			}
		}
	}
	private function runComposerUpdate(){
		$result = -1;
		$output = [];
		exec("composer dumpautoload -o 2>&1",$output,$result);
		return $result === 0;
	}
	public function getUnits(){
		$units = [];
		$files = scandir(__BASEDIR__."/units");
		$blacklist = [
			".",
			"..",
			"systemerror.php"
		];
		foreach($files as $file){
			if (!in_array($file,$blacklist)){
				$units[] = str_replace(".php","",$file);
			}
		}
		return $units;
	}

}