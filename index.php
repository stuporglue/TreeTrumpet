<?php
require_once('lib/treetrumpet.php');

// Some pretty simple processing to handle getting here either via
// tree.php or tree

$endpoint = basename($_SERVER['SCRIPT_FILENAME']); // index.php if htaccess is working, or tree.php

// If htaccess is working and the page was tree then ruri (REQUEST_URI) should be filled in. 
if(array_key_exists('ruri',$_GET)){
    $path_info = str_replace(dirname($_SERVER['SCRIPT_NAME']),'',$_GET['ruri']);
    $path_info = trim($path_info,"/'");
    $path_info = explode('/',$path_info);
    $endpoint = array_shift($path_info);
    $endpoint = preg_replace('/(.*)\..*/',"$1",$endpoint);
    $_SERVER['PATH_INFO'] = implode('/',$path_info);
}

// At this point PATH_INFO should be present, either organically or with the the ruri fix above
$args = Array();
if(array_key_exists('PATH_INFO',$_SERVER)){
    $args = explode('/',trim($_SERVER['PATH_INFO'],'/'));
}

// Call the requested controller
controller('setup');
controller($endpoint,$args);
