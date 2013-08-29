<?php

function multimedia($id){
    if(!array_key_exists('h',$_GET)){
        $_GET['h'] = NULL;
    }
    if(!array_key_exists('w',$_GET)){
        $_GET['w'] = NULL;
    }
    if(!array_key_exists('a',$_GET)){
        $_GET['a'] = FALSE;
    }

    $file = join('/',func_get_args());
    return obje::readfile($file,$_GET['h'],$_GET['w'],$_GET['a']);
}
