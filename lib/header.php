<?php
/*
 * Here's where the menu and any content that you want at the top of every page should go
 */

require_once(__DIR__ . '/config.php');

$current = basename($_SERVER['SCRIPT_NAME']);

$moduleMenu = Array();
    if($_CONFIG['tree']){
        if($current == 'tree.php'){
            $current_page = " class='current_page'";
        }else{
            $current_page = "";
        }
        $moduleMenu[] = "<a href='$_BASEURL/tree.php'$current_page>Tree View</a>";
    }
    if($_CONFIG['map']){
        if($current == 'map.php'){
            $current_page = " class='current_page'";
        }else{
            $current_page = "";
        }
        $moduleMenu[] = "<a href='$_BASEURL/map.php'$current_page>Map View</a>";
    }
    if($_CONFIG['table']){
        if($current == 'table.php'){
            $current_page = " class='current_page'";
        }else{
            $current_page = "";
        }
        $moduleMenu[] = "<a href='$_BASEURL/table.php'$current_page>Table View</a>";
    }
    if($_CONFIG['contact']){
        if($current == 'contact.php'){
            $current_page = " class='current_page'";
        }else{
            $current_page = "";
        }
        $moduleMenu[] = "<a href='$_BASEURL/contact.php'$current_page>Contact Me</a>";
    }
    if($_CONFIG['gedcom']){
        $moduleMenu[] = "<a href='$_BASEURL/gedcom.php'$current_page>GEDCOM</a>";
    }


print "<div id='tt-header'>";
print implode(' | ',$moduleMenu);
print "</div>";
