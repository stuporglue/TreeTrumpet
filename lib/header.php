<?php
/*
 * Here's where the menu and any content that you want at the top of every page should go
 */

require_once(__DIR__ . '/config.php');


$moduleMenu = Array();
    if($_CONFIG['tree']){
        $moduleMenu[] = "<a href='$_BASEURL/tree.php'>Tree View</a>";
    }
    if($_CONFIG['map']){
        $moduleMenu[] = "<a href='$_BASEURL/map.php'>Map View</a>";
    }
    if($_CONFIG['table']){
        $moduleMenu[] = "<a href='$_BASEURL/table.php'>Table View</a>";
    }
    if($_CONFIG['contact']){
        $moduleMenu[] = "<a href='$_BASEURL/contact.php'>Contact Me</a>";
    }
    if($_CONFIG['gedcom']){
        $moduleMenu[] = "<a href='$_BASEURL/gedcom.php'>GEDCOM</a>";
    }


print "<div id='tt-header'>";
print implode(' | ',$moduleMenu);
print "</div>";
