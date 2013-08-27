<?php
header("Content-type: text/plain;charset=utf-8");
?>
# Hello robot!
User-agent: *
Disallow: /cache
Disallow: /controller
Disallow: /css
Disallow: /img
Disallow: /js
Disallow: /lib
Disallow: /model
Disallow: /view
<?php 
if(isset($sitemap)){
    print "Sitemap: $sitemap\n"; 
}
