<?php
namespace hitchhike2;
require __DIR__."/hm.php";
global $hm;
$hm = new HM();
foreach($hm->Units["hitchhike2\\IWebUnit"] as $unit){
    if (in_array("before-skeleton",$unit->getEntryPoints())){
        $unit->run();
    }
}
$hm->SkeletonUnit->run();
