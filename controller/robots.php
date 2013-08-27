<?php

// Re-make robots.txt if it doesn't exist or if family.ged has been updated 

if(file_exists(__DIR__ . '/../robots.txt')){
    header("Content-type: text/plain;charset=utf-8");
    readfile(__DIR__ . '/../robots.txt');
    exit();
}

global $_BASEURL;
$robots = view('robots',Array('sitemap' => $_BASEURL . '/xmlsitemap.xml'),TRUE);
@file_put_contents(__DIR__ . '/../robots.txt',$robots);
print $robots;
exit();
