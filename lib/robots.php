<?php

// Re-make robots.txt if it doesn't exist or if family.ged has been updated 
if(    
    file_exists(__DIR__ . '/../family.ged') &&
    (
        !file_exists(__DIR__ . '/../robots.txt') || 
        filemtime(__DIR__ . '/../robots.txt') < filemtime(__DIR__ . '/../family.ged') ||
        filemtime(__FILE__) > filemtime(__DIR__ . '/../robots.txt')
    )
){

    $sitemap = '';
    if(empty($_SERVER['HTTPS'])){
        $sitemap .= 'http://';
    }else{
        $sitemap .= 'https://';
    } 
    $sitemap .= $_SERVER['SERVER_NAME']; 
    $sitemap .= dirname($_SERVER['SCRIPT_NAME']);
    $sitemap .= '/lib/sitemap.php';

$robots = "
User-agent: *
Disallow: /lib
Disallow: setup.php

Sitemap: $sitemap
";

$res = file_put_contents(__DIR__ . '/../robots.txt',$robots);

// Hey! New content! -- Auto submit sitemap to these URLs: 
// http://www.google.com/webmasters/tools/ping?sitemap=http://www.example.com/sitemap.gz -- 200
// http://www.bing.com/ping?sitemap=http%3A%2F%2Fwww.example.com/sitemap.xml -- 200
// Others???

}
