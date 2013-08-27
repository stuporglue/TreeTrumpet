<?php
global $_CONFIG,$_BASEURL;

header("Content-Type: text/xml; charset=utf-8");

// Three cases


// Case 1: Cached xmlsitemap exists and is newer than the GEDCOM file. Serve that instead
if(file_exists(__DIR__ . '/../cache/xmlsitemap.xml') && filemtime(__DIR__ . '/../cache/xmlsitemap.xml') > filemtime(__DIR__ . '/../family.ged')){
    readfile(__DIR__ . '/../cache/xmlsitemap.xml'); 
    return;
}

// Case 2: We need to prepare a new xmlsitemap and serve it to the user

// This file should produce a valid sitemap.xml for the enabled modules and auto-generated pages
$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));

function printSingleUrl($loc,$lastmod,$priority){
    $url = '';
    $url .= "<url>";
    $url .= "<loc>" . linky($loc) . "</loc>";
    $url .= "<lastmod>";
    $url .= date('Y-m-d\TH:i:s+00:00',$lastmod);
    $url .= "</lastmod>";
    $url .= "<priority>$priority</priority>";
    $url .= "</url>";
    return $url;
}

// Print xml preamble
// Homepage
$urls[] = printSingleUrl($_BASEURL,$gedcom->updated(),'0.2');


// Enabled Modules, except contact
if($_CONFIG['tree']){    $urls[] .= printSingleUrl("$_BASEURL/tree.php",$gedcom->updated(),'0.8'); }
if($_CONFIG['map']){     $urls[] .= printSingleUrl("$_BASEURL/map.php",$gedcom->updated(),'0.8'); }
if($_CONFIG['people']){   $urls[] .= printSingleUrl("$_BASEURL/peopl.php",$gedcom->updated(),'1.0'); }
if($_CONFIG['contact']){ $urls[] .= printSingleUrl("$_BASEURL/contact.php",$gedcom->updated(),'0.2'); }


// Individual ancestors
while($indi = $gedcom->nextIndividual()){
    $urls[] = printSingleUrl($indi->link(),$indi->updated(),'0.5'); 
}

// Families
while($fam = $gedcom->nextFamily()){
    $urls[] = printSingleUrl($fam->link(),$fam->updated(),'0.4'); 
}


$xml = view('xmlsitemap',Array('urls' => $urls),TRUE);
print $xml;


// Case 2b: We also need to notify search engines about it
@unlink(__DIR__ . '/../cache/xmlsitemap.xml');
@file_put_contents(__DIR__ . '/../cache/xmlsitemap.xml',$xml);

// Case 2b.1: Cache is empty because we can't write to it. Randomly decide if we should notify search engines
// Since most users who are requesting the xmlsitemap are ALREADY search engines, we'll only do this once in a while
if(!file_exists(__DIR__ . '/../cache/xmlsitemap.xml')){
    if(rand(1,100) != 100){
        return; 
    }
}

$pings = Array(
    "http://www.google.com/webmasters/sitemaps/ping?sitemap=",
    "http://www.bing.com/webmaster/ping.aspx?siteMap=",
);

foreach($pings as $ping){
    $ping .= urlencode('http://stuporglue.com/xmlsitemap.xml');
    file_get_contents($ping);
}
