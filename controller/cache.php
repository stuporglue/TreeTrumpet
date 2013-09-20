<?php
// We're going to try to optimize requests as much as possible
// Parsing the GEDCOM is SLOW, using SQLite is slow
// If we can just grab a pre-rendered file, that's nice
// Buffer output so we can cache the file
//
// To determine cache validity we will use the filemtime of the cached file vs. the filemtime of lib/version.txt
// This assumes that most users aren't modifying the code. 
//
// To not cache a file, simply flush the buffer before the final return. This 
// could be done in a controller, a view or anywhere
//
// To clear the cache, delete the contents of cache/pages

function cache($endpoint,$args){
    $cacheDir = __DIR__ . '/../cache/pages/';
    @mkdir($cacheDir,0777,TRUE);

    if(!is_dir($cacheDir)){
        return;
    }

    $cache_file_name = $endpoint . '--' . implode('_',array_merge($args,$_GET));
    $cache_file_name = preg_replace('|[^a-zA-Z0-9_-]+|','_',$cache_file_name);
    $cache_file_name = $cacheDir . $cache_file_name;

    if(file_exists($cache_file_name)){
        $cache_ts = filemtime($cache_file_name);
        $code_ts = filemtime(__DIR__ . '/../lib/version.txt');
        $config_ts = (file_exists(__DIR__ . '/../config.php') ? filemtime(__DIR__ . '/../config.php') : 0);

        if($code_ts > $cache_ts || $config_ts > $cache_ts){
            unlink($cache_file_name);
        }else{
            readfile($cache_file_name);
            exit();
        }
    }

    // Buffer the output so we can cache it later
    ob_start();
    register_shutdown_function(create_function('',"writeCache('$cache_file_name');"));
}

function writeCache($cache_file_name){
    $cacheContents = ob_get_contents();
    ob_end_clean();
    print $cacheContents;

    $errors = error_get_last();

    if(count($errors) > 0){
        error_log("We got errors, not caching!");
        return; 
    }

    if(strlen($cacheContents) > 0){
        @file_put_contents($cache_file_name,$cacheContents);
    }
}
