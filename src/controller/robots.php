<?php

global $_BASEURL;

$robots = file(__DIR__ . '/../robots.txt');
foreach($robots as $lineno => $line){
    if(strpos($line,'TreeTrumpet xmlsitemap')){
        $robots[$lineno] = "Sitemap: $_BASEURL/xmlsitemap.xml\n";
    }
}

print implode("",$robots);
