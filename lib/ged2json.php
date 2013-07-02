<?php

spl_autoload_register(function ($class) {
    $file = __DIR__ . "/3rdparty/$class.php";
    if(file_exists($file)){
        require_once($file);
    }
});

$ged2json = new ged2json(__DIR__ . '/../' . $_GET['g']);

header("Content-Type: application/json; charset=utf-8");
print $ged2json;
