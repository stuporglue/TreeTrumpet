<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<?php 
global $_BASEURL;
print "<link type='text/css' href='$_BASEURL/css/tt.css' rel='stylesheet' media='all'/>";
print $page->printCss();
print "<link rel='stylesheet' type='text/css' media='handheld, only screen and (max-device-width: 768px)' href='$_BASEURL/css/small_screens.css' />";

if($can = $page->canonical()){
    print "<link type='canonical' href='$can'/>";
}

print "<link type='image/x-icon' rel='shortcut icon' href='$_BASEURL/img/favicon.png'/>";
print "<meta name='viewport' content='width=device-width'/>";
print "<title>$page->title</title>";
print $page->head;
?></head>
<body>
<div id='tt-content'>
<header>
    <h1><?php print $page->h1;?></h1>
    <menu><?php controller('menu',Array($menu)); ?></menu>
</header>

