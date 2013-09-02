<?php

global $_BASEURL;

print "<div id='tt-header'><ul>";
foreach($menus as $ctrl => $label){
    $curClass = '';
    if($ctrl == $current){
        $curClass = 'current_page';
    }
    print "<li class='$curClass'><a href='" . linky("$_BASEURL/$ctrl.php") . "' title='" . htmlentities($label) . "' alt='" . htmlentities($label) . "'>" . htmlentities($label) . "</a></li>";
}
print "</ul><div class='socialButtons'>";

global $_BASEURL;
$shareUrl = urlencode(linky($_BASEURL . $_SERVER['REQUEST_URI']));

print "<a href='https://www.facebook.com/sharer/sharer.php?u=$shareUrl' target='_blank'><img title='Share on Facebook' alt='Share on Facebook' src='$_BASEURL/img/elegantthemes_icons/facebook.png'/></a>";
print "<a href='https://plus.google.com/share?url=$shareUrl' target='_blank'><img title='Share on Google' alt='Share on Google' src='$_BASEURL/img/elegantthemes_icons/googleplus.png'/></a>";
print "<a href='https://twitter.com/share?url=$shareUrl' target='_blank'><img title='Share on Twitter' alt='Share on Twitter' src='$_BASEURL/img/elegantthemes_icons/twitter.png'/></a>";

print "</div></div>";
