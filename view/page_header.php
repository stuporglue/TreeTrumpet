<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<?php 
global $_BASEURL;
print "<link type='text/css' href='$_BASEURL/css/tt.css' rel='stylesheet' media='all'/>";
print "<link type='image/x-icon' rel='shortcut icon' href='$_BASEURL/img/favicon.png'/>";
print $page->printCss();
print "<title>$page->title</title>";
print $page->head;
?></head>
<body>
<div id='tt-content'>
<header>
    <h1><?php print $page->h1;?></h1>
    <menu><?php controller('menu',Array($menu)); ?></menu>
</header>

