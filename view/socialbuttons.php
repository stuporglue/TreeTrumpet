<?php

global $_BASEURL;
$shareUrl = urlencode(linky($_BASEURL . $_SERVER['REQUEST_URI']));
print "<div class='socialButtons'>";
print "<a href='https://www.facebook.com/sharer/sharer.php?u=$shareUrl' target='_blank'><img title='Share on Facebook' alt='Share on Facebook' src='$_BASEURL/img/elegantthemes_icons/facebook.png'/></a>";
print "<a href='https://plus.google.com/share?url=$shareUrl' target='_blank'><img title='Share on Google' alt='Share on Google' src='$_BASEURL/img/elegantthemes_icons/googleplus.png'/></a>";
print "<a href='https://twitter.com/share?url=$shareUrl' target='_blank'><img title='Share on Twitter' alt='Share on Twitter' src='$_BASEURL/img/elegantthemes_icons/twitter.png'/></a>";
print "</div>";
