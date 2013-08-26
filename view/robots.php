<?php
header("Content-type: text/plain;charset=utf-8");
?>
# This robots.txt is generated on demand. You can rep
User-agent: *
Disallow: /cache
Disallow: /controller
Disallow: /css
Disallow: /img
Disallow: /js
Disallow: /lib
Disallow: /model
Disallow: /view
Disallow: /
<?php 
if(isset($sitemap)){
    print "Sitemap: $sitemap\n"; 
}
