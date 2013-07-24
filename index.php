<?php

// This is the router for TreeTrumpet. 
// Since TreeTrumpet is supposed to be 0-config we need to work where .htaccess doesn't
// PHP files such as tree.php or individual.php should require index.php which will take over

function controller($controller,$args = Array()){
    $controller = strtolower($controller);
    $controller = basename($controller,'.php');
    $controller = basename($controller,'.ged');

    // If our controller doesn't exist, 404 'em
    if(!file_exists(__DIR__ . "/controller/$controller.php")){
        $args = Array($controller);
        $controller = '_404';
    }

    require(__DIR__ . "/controller/$controller.php");
    if(function_exists($controller)){
        call_user_func_array($controller,$args);
    }
}

function view($view,$vars = Array(),$asString){
    if($asString){
        ob_start();
    }
    extract($vars);
    require(__DIR__ . "/view/$view.php");
    if($asString){
        return ob_get_clean();
    }
}

function model($model,$args = Array()){
    // Any require_once business for models should be handled by spl_autoload_register in controller/setup.php
    $rc = new ReflectionClass($model);
    return $rc->newInstanceArgs($args);
}


// Some pretty simple processing to handle getting here either via
// tree.php or tree

$endpoint = basename($_SERVER['SCRIPT_FILENAME']); // index.php if htaccess is working, or tree.php

// If htaccess is working and the requested page was tree.php for some reason we should have $_GET['controller']
if(array_key_exists('controller',$_GET)){
    $endpoint = $_GET['controller'];
}

// If htaccess is working and the page was tree then ruri (REQUEST_URI) should be filled in. 
if(array_key_exists('ruri',$_GET)){
    $path_info = str_replace(dirname($_SERVER['SCRIPT_NAME']),'',$_GET['ruri']);
    $path_info = trim($path_info,'/');
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
