<?php

global $_BASEURL;

print "<div id='tt-header'>"; 

print "<nav><ul>";
foreach($menus as $ctrl => $label){
    $curClass = '';
    if($ctrl == $current){
        $curClass = 'current_page';
    }
    print "<li class='$curClass'><a href='" . linky("$_BASEURL/$ctrl.php") . "' title='" . htmlentities($label) . "' alt='" . htmlentities($label) . "'>" . htmlentities($label) . "</a></li>";
}
print "</ul></nav>";

view('socialbuttons');

print "</div>";
