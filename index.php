<?php

// This is the router for TreeTrumpet. 
// Since TreeTrumpet is supposed to be 0-config we need to work where .htaccess doesn't
// PHP files such as tree.php or individual.php should require index.php which will take over

function controller($controller,$args = Array()){
    $controller = strtolower($controller);
    $controller = basename($controller,'.php');
    $controller = basename($controller,'.ged');

    if(!is_array($args)){
        $args = Array($args);
    }

    // If our controller doesn't exist, 404 'em
    if(!file_exists(__DIR__ . "/controller/$controller.php")){
        $args = Array($controller);
        $controller = '_404';
    }

    require(__DIR__ . "/controller/$controller.php");
    if(function_exists($controller)){
        return call_user_func_array($controller,$args);
    }
}

function view($view,$vars = Array(),$asString = FALSE){
    if($asString){
        ob_start();
    }
    foreach($vars as $k => $v){
        if(is_string($v)){
            $vars[$k] = htmlentities($v);
        }
    }
    extract($vars);
    require(__DIR__ . "/view/$view.php");
    if($asString){
        return ob_get_clean();
    }
}

function model($model,$args = Array()){
    // Any require_once business for models should be handled by spl_autoload_register in controller/setup.php
    if(!is_array($args)){
        $args = Array($args);
    }
    $rc = new ReflectionClass($model);
    return $rc->newInstanceArgs($args);
}

function linky($url){
    if(!array_key_exists('ruri',$_GET)){
        return $url;
    }else{
        return str_replace('.php','',$url);
    }
}

// Some pretty simple processing to handle getting here either via
// tree.php or tree

$endpoint = basename($_SERVER['SCRIPT_FILENAME']); // index.php if htaccess is working, or tree.php

// If htaccess is working and the page was tree then ruri (REQUEST_URI) should be filled in. 
if(array_key_exists('ruri',$_GET)){
    $path_info = str_replace(dirname($_SERVER['SCRIPT_NAME']),'',$_GET['ruri']);
    $path_info = trim($path_info,"/'");
    $path_info = explode('/',$path_info);
    $endpoint = array_shift($path_info);
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

