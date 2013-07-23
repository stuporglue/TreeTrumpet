<?php

global $_BASEURL;

print "<div id='tt-header'><ul>";
foreach($menus as $ctrl => $label){
    print "<li><a href='$_BASEURL/$ctrl.php' title='$label' alt='$label'>$label</a></li>";
}
print "</ul></div>";
