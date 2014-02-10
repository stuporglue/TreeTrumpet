<?php
global $_CONFIG,$_BASEURL;

/**
 * @brief Tell crawlers that we have a new sitemap
 *
 * This gets called any time that a new xmlsitemap cache file is successfully written
 * 
 * If cache is not enabled then the sitemap will just get generated whenever crawlers 
 * ask for it after noticing it in robots.txt and this will never get called
 */
function pingCrawlersAboutSitemap(){
    global $_BASEURL;
    $pings = Array(
        "http://www.google.com/webmasters/sitemaps/ping?sitemap=",
        "http://www.bing.com/webmaster/ping.aspx?siteMap=",
    );

    foreach($pings as $ping){
        $ping .= urlencode("$_BASEURL/xmlsitemap.php");
        file_get_contents($ping);
    }
}

// Prepare a new xmlsitemap and serve it to the user

// This file should produce a valid sitemap.xml for the enabled modules and auto-generated pages
$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));

function printSingleUrl($loc,$lastmod,$priority){
    $url = '';
    $url .= "<url>";
    $url .= "<loc>" . linky($loc) . "</loc>";
    if($lastmod){
        $url .= "<lastmod>";
        $url .= date('Y-m-d\TH:i:s+00:00',$lastmod);
        $url .= "</lastmod>";
    }
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
if($_CONFIG['people']){   $urls[] .= printSingleUrl("$_BASEURL/people.php",$gedcom->updated(),'1.0'); }
if($_CONFIG['contact']){ $urls[] .= printSingleUrl("$_BASEURL/contact.php",$gedcom->updated(),'0.2'); }

// Individual ancestors
while($indi = $gedcom->nextIndividual()){
    $urls[] = printSingleUrl($indi->link(),$indi->updated(),'0.5'); 
}

// Families
while($fam = $gedcom->nextFamily()){
    $urls[] = printSingleUrl($fam->link(),$fam->updated(),'0.4'); 
}

// // Media
// while($obje = $gedcom->nextObje()){
//     $urls[] = printSingleUrl($obje->href(),$obje->updated(),'0.4'); 
// }

// Sources
while($sour = $gedcom->nextSour()){
    $urls[] = printSingleUrl($sour->link(),$sour->updated(),'0.2');
}

header("Content-Type: text/xml; charset=utf-8");
view('xmlsitemap',Array('urls' => $urls));
