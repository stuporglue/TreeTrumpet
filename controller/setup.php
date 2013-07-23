<?php

// Include dirs

// Our models
spl_autoload_register(function ($class) {
    $file = __DIR__ . "/../model/$class.php";
    if(file_exists($file)){
        require_once($file);
    }
});



// php-gedcom
spl_autoload_register(function ($class) {
    $pathToPhpGedcom = __DIR__ . '/../lib/3rdparty/php-gedcom/library/'; 

    if (!substr(ltrim($class, '\\'), 0, 7) == 'PhpGedcom\\') {
        return;
    }

    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($pathToPhpGedcom . $class)) {
        require_once($pathToPhpGedcom . $class);
    }
});

// ged2json/ged2geojson
spl_autoload_register(function ($class) {
    $file = __DIR__ . "/../lib/3rdparty/$class.php";
    if(file_exists($file)){
        require_once($file);
    }
});



controller('config');

if(!file_exists(__DIR__ . '/../family.ged')){
    header("Location: $_BASEURL/lib/firstrun.php",TRUE,307);
    view('firstrun');
    exit();
}

// Hey! New content! -- Auto submit sitemap to these URLs: 
// http://www.google.com/webmasters/tools/ping?sitemap=http://www.example.com/sitemap.gz -- 200
// http://www.bing.com/ping?sitemap=http%3A%2F%2Fwww.example.com/sitemap.xml -- 200
// Others???
