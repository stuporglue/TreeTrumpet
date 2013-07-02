<?php

global $_BASEURL,$_CONFIG;

// Get the base URL
$_BASEURL = '';
if(empty($_SERVER['HTTPS'])){
    $_BASEURL .= 'http://';
}else{
    $_BASEURL .= 'https://';
} 
$_BASEURL .= $_SERVER['SERVER_NAME']; 
$scriptdir = dirname($_SERVER['SCRIPT_NAME']);

if(basename($scriptdir) == 'lib'){
    $scriptdir = dirname($scriptdir);
}

$_BASEURL .= $scriptdir;

if(!file_exists(__DIR__ . '../family.ged')){
    header("Location: $_BASEURL",TRUE,307);
}

require_once(__DIR__ . '/robots.php');
require_once(__DIR__ . '/config.php');

