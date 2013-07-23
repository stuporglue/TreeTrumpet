<?php
header("Content-type: text/plain;charset=utf-8");
?>
# This robots.txt is generated on demand. You can rep
User-agent: *
Disallow: /lib
Disallow: setup.php

<? 
if(isset($sitemap)){
    print "Sitemap: $sitemap\n"; 
}
