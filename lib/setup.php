<?php

require_once(__DIR__ . '/config.php');

if(!file_exists(__DIR__ . '/../family.ged')){
    header("Location: $_BASEURL/lib/firstrun.php",TRUE,307);
    exit();
}

require_once(__DIR__ . '/robots.php');

