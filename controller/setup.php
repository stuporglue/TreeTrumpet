<?php

controller('config');

if(!file_exists(__DIR__ . '/../family.ged')){
    header("Location: $_BASEURL/lib/firstrun.php",TRUE,307);
    view('firstrun');
    exit();
}

// Hey! New content! -- Auto submit sitemap to these URLs: 
// http://www.google.com/webmasters/tools/ping?sitemap=http://www.example.com/sitemap.gz -- 200
// http://www.bing.com/ping?sitemap=http%3A%2F%2Fwww.example.com/sitemap.xml -- 200
// Others???
