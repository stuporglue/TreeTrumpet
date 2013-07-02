<?php

require_once(__DIR__ . '/config.php');

if(!file_exists(__DIR__ . '/../family.ged')){
    header("Location: $_BASEURL",TRUE,307);
    exit();
}

require_once(__DIR__ . '/robots.php');

spl_autoload_register(function ($class) {
    $pathToPhpGedcom = __DIR__ . '/3rdparty/php-gedcom/library/'; 

    if (!substr(ltrim($class, '\\'), 0, 7) == 'PhpGedcom\\') {
        return;
    }

    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($pathToPhpGedcom . $class)) {
        require_once($pathToPhpGedcom . $class);
    }
});

