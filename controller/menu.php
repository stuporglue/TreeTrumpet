<?php
/*
 * Here's where the menu and any content that you want at the top of every page should go
 */

function menu($current){
    global $_CONFIG,$_BASEURL;
    $menu = '';

    $pages = Array(
        'tree'      => 'Tree',
        'map'       => 'Map',
        'table'     => 'People',
        'contact'   => 'Contact Me',
        'gedcom'    => 'GEDCOM'
    );

    $moduleMenu = Array();

    foreach($pages as $ctrl => $label){
        $current_page = "";
        if($_CONFIG[$ctrl]){
            $moduleMenu[$ctrl] = $label;
        }
    }

    view('menu',Array('menus' => $moduleMenu,'current' => $current));
}
