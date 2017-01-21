<?php
namespace hitchhike2;
use \hitchhike2;
define("__BASEDIR__",__DIR__);
define("__VERSION__","2.0a");
require __DIR__."/header.agpl";
require __DIR__."/unit.interface";
require __DIR__."/vendor/autoload.php";
$hm = new HM($argv);

class HM{
	public $SkeletonUnit = null;
	public $ManagerUnit = null;
	public $PostUnit = null;
	public $Units = [];
	public function __construct($argv = null){
		if (php_sapi_name() !== 'cli'){
			$this->setUpForWeb();
			return;
		}
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
	private function setUpForWeb(){
		$units = $this->getUnits();
		foreach($units as $unit){
			if (strpos($unit,"HM") === false){
				$name ="\\hitchhike2\\".$unit;
				$obj = new $name();
				$implements = class_implements($obj);
				foreach($implements as $interface){
					if (!isset($this->Units[$interface])){
						$this->Units[$interface] = [];
					}
					$this->Units[$interface][] = $obj;
				}
			}
		}
		$this->SkeletonUnit = $this->Units["hitchhike2\\ISkeleton"][0];
		$this->ManagerUnit = $this->Units["hitchhike2\\IManager"][0];
		$this->PostUnit = $this->Units["hitchhike2\\IPostUnit"][0];
	}
	private function getList($units){
		foreach($units as $unit){
			if (strpos($unit,"HM") === false){
				$name ="\\hitchhike2\\".$unit;
				$obj = new $name();
				$implements = class_implements($obj);
				if (in_array("hitchhike2\\IUnit",$implements)){
					if (in_array("hitchhike2\\ISkeleton",$implements)){
						$this->SkeletonUnit = $obj;
					}
					echo $obj->getName().": ".$obj->getDescription()." [".$obj->getVersion()."]\n";
					$methods = $obj->getCLIMethods();
					foreach($methods as $method => $description){
						echo "- ".$method .": ".$description."\n";
					}		
				}
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