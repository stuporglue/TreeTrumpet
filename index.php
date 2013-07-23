<?php

// This is the router for TreeTrumpet. 
// Since TreeTrumpet is supposed to be 0-config we need to work where .htaccess doesn't
// PHP files such as tree.php or individual.php should require index.php which will take over
//
print __FILE__ . ':' . __LINE__ . "    (" . time() . ")<br/>\n";
print_r($_SERVER);

function controller($controller,$args){
    $controller = strtolower($controller);
    $controller = basename($controller,'.php');
    $controller = basename($controller,'.ged');
    print "$controller\n";
    print_r($args);
    print_r($_GET);
    exit();


    // If our controller doesn't exist, 404 'em
    if(!file_exists(__DIR__ . "/controller/$controller.php")){
        $endpoint = '404';
    }

    require(__DIR__ . "/controller/$controller.php");
    $controller($args);
}

function view($view){
    require(__DIR__ . "/view/$view");
}

function model($model){
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
controller($endpoint,$args);
