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
    $file = __DIR__ . "/../controller/$controller.php";
    if(!file_exists($file)){
        $args = Array($controller);
        $controller = '_404';
    }

    // If it's a function controller, we don't want to require it twice
    if(!function_exists($controller)){
        require(__DIR__ . "/../controller/$controller.php");
    }

    if(function_exists($controller)){
        return call_user_func_array($controller,$args);
    }
}

function view($view,$vars = Array(),$asString = FALSE){
    if($asString){
        ob_start();
    }
    extract($vars);
    require(__DIR__ . "/../view/$view.php");
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

function linkPath($string){
    return preg_replace(
        '/[-]+/','-',
        trim(
            preg_replace(
                '/[^A-Za-z0-9-_\.]+/',
                '-',
                $string
            ),
            '-'
        )
    );
}
