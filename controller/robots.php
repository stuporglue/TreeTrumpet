<?php

// Re-make robots.txt if it doesn't exist or if family.ged has been updated 

if(file_exists(__DIR__ . '/../robots.txt')){
    header("Content-type: text/plain;charset=utf-8");
    readfile(__DIR__ . '/../robots.txt');
    exit();
}

$sitemap = '';
if(empty($_SERVER['HTTPS'])){
    $sitemap .= 'http://';
}else{
    $sitemap .= 'https://';
} 
$sitemap .= $_SERVER['SERVER_NAME']; 
$sitemap .= dirname($_SERVER['SCRIPT_NAME']);
$sitemap .= '/lib/sitemap.php';

global $_BASEURL;
view('robots',Array('sitemap' => $_BASEURL . '/sitemap.php'),TRUE);
file_put_contents(__DIR__ . '/../robots.txt');
exit();
