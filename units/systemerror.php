<?php
namespace hitchhike2;
require __BASEDIR__."/header.agpl";


abstract class SystemError{
    const NO_UNIT_FOLDER = 1;
    public static function ThrowError($error){
        die("System Error: $error\n");
    }
}