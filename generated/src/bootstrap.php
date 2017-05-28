<?php

function xKerman_Restricted_bootstrap($classname)
{
    if (strpos($classname, 'xKerman_Restricted_') !== 0) {
        return false;
    }
    $path = dirname(__FILE__) . "/{$classname}.php";
    if (file_exists($path)) {
        require_once $path;
    }
}

spl_autoload_register('xKerman_Restricted_bootstrap');
require_once dirname(__FILE__) . '/xKerman_Restricted_function.php';