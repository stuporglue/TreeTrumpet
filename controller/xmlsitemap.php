<?php
global $_CONFIG,$_BASEURL;


// This file should produce a valid sitemap.xml for the enabled modules and auto-generated pages
$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));

function printSingleUrl($loc,$lastmod,$priority){
    $url = '';
    $url .= "<url>";
    $url .= "<loc>$loc</loc>";
    $url .= "<lastmod>";
    $url .= date('Y-m-d\TH:i:s+00:00',$lastmod);
    $url .= "</lastmod>";
    $url .= "<priority>$priority</priority>";
    $url .= "</url>";
    return $url;
}

// Print xml preamble
// Homepage
$urls[] = printSingleUrl($_BASEURL,$lastmod,'0.2');


// Enabled Modules, except contact
if($_CONFIG['tree']){    $urls[] .= printSingleUrl("$_BASEURL/tree.php",$lastmod,'0.8'); }
if($_CONFIG['map']){     $urls[] .= printSingleUrl("$_BASEURL/map.php",$lastmod,'0.8'); }
if($_CONFIG['people']){   $urls[] .= printSingleUrl("$_BASEURL/table.php",$lastmod,'1.0'); }
if($_CONFIG['contact']){ $urls[] .= printSingleUrl("$_BASEURL/contact.php",$lastmod,'0.2'); }


// Individual ancestors
while($indi = $gedcom->nextIndividual()){
    $urls[] = printSingleUrl($indi->link(),$lastmod,'0.5'); 
}

// Families
while($fam = $gedcom->nextFamily()){
    $urls[] = printSingleUrl($fam->link(),$lastmod,'0.4'); 
}

view('xmlsitemap',Array('urls' => $urls));
