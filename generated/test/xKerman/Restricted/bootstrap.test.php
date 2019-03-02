<?php

function xKerman_Restricted_Test_bootstrap($classname)
{
    if (strpos($classname, 'xKerman_Restricted_Test') !== 0) {
        return false;
    }
    $sep = DIRECTORY_SEPARATOR;
    $namespace = explode('_', $classname);
    $filename = array_pop($namespace);
    $path = dirname(__FILE__) . "{$sep}{$filename}.php";
    if (file_exists($path)) {
        require_once $path;
    }
}

$sep = DIRECTORY_SEPARATOR;
require_once dirname(dirname(dirname(dirname(__FILE__)))) . "{$sep}src{$sep}xKerman{$sep}Restricted{$sep}bootstrap.php";
spl_autoload_register('xKerman_Restricted_Test_bootstrap');
