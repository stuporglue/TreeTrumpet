<?php
global $_CONFIG;
// Include dirs

// Our models
spl_autoload_register(function ($class) {
    $file = __DIR__ . "/../model/$class.php";
    if(file_exists($file)){
        require_once($file);
    }
});

// php-gedcom override
// use to extend php-gedcom
spl_autoload_register(function ($class) {
    if (!substr(ltrim($class, '\\'), 0, 7) == 'PhpGedcom\\') {
        return;
    }

    $pathToPhpGedcom = __DIR__ . '/../lib/php-gedcom-custom/'; 

    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($pathToPhpGedcom . $class)) {
        require_once($pathToPhpGedcom . $class);
    }
});

// php-gedcom
spl_autoload_register(function ($class) {
    if (!substr(ltrim($class, '\\'), 0, 7) == 'PhpGedcom\\') {
        return;
    }

    $pathToPhpGedcom = __DIR__ . '/../lib/3rdparty/php-gedcom/library/'; 

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

if($_CONFIG['debug_mode'] !== TRUE){
    set_error_handler('catchAnError');
}

if(!file_exists(__DIR__ . '/../family.ged')){
    controller('firstrun');
    exit();
}
