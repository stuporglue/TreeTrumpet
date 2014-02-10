<?php

function multimedia($id){

    $_GET = array_merge(Array(
        'h' => NULL,
        'w' => NULL,
        'a' => NULL
    ),$_GET);

    $file = join('/',func_get_args());
    return obje::readfile($file,$_GET['h'],$_GET['w'],$_GET['a']);
}
