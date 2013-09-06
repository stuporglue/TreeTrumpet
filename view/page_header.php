<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<?php 
global $_BASEURL;

// Link tags

// CSS
print "<link type='text/css' href='$_BASEURL/css/tt.css' rel='stylesheet' media='all'/>";
print $page->printCss();
print "<link rel='stylesheet' type='text/css' media='handheld, only screen and (max-device-width: 768px)' href='$_BASEURL/css/small_screens.css' />";

// favicon
print "<link type='image/x-icon' rel='shortcut icon' href='$_BASEURL/img/favicon.png'/>";

// Canonical
if($can = $page->canonical()){
    print "<link rel='canonical' href='$can'/>";
}

// Meta Tags
if($desc = $page->description){
    print "<meta name='description' content='$desc'/>";
}

if($keywords = $page->keywords){
    print "<meta name='keywords' content='".implode(',',array_unique(str_replace(',','  ',$keywords)))."'/>";
}

if($copyright = $page->copyright){
    print "<meta name='copyright' content='$copyright'/>";
}

if($author = $page->author){
    print "<meta name='author' content='$author'/>";
}

print "<meta name='application-name' content='TreeTrumpet'/>";
print "<meta name='generator' content='TreeTrumpet'/>";
print "<meta name='viewport' content='width=device-width'/>";

// Title
print "<title>$page->title</title>";

// Anything else destined for the head
print $page->head;
?></head>
<body>
<div id='tt-content'>
<header>
    <h1><?php print $page->h1;?></h1>
    <menu><?php controller('menu',Array($menu)); ?></menu>
</header>
