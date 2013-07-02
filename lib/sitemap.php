<?php
header("Content-Type: text/xml; charset=utf-8");


// This file should produce a valid sitemap.xml for the enabled modules and auto-generated pages
require_once('setup.php');
require_once('3rdparty/ged2json.php');


// Get last modified date
$releasemtime = filemtime('version.txt');
if(file_exists('../family.ged')){
    $gedcommtime = filemtime('../family.ged');
}else{
    $gedcommtime = 0;
}
$lastmod = ($releasemtime > $gedcommtime ? $releasemtime : $gedcommtime);


// Get all the people
spl_autoload_register(function ($class) {
    $pathToPhpGedcom = __DIR__ . '/lib/3rdparty/php-gedcom/library/'; 

    if (!substr(ltrim($class, '\\'), 0, 7) == 'PhpGedcom\\') {
        return;
    }

    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($pathToPhpGedcom . $class)) {
        require_once($pathToPhpGedcom . $class);
    }
});

$parser = new PhpGedcom\Parser();
$parsedgedcom = $parser->parse('../family.ged');




function printSingleUrl($loc,$lastmod,$priority){
    print "<url>";
    print "<loc>$loc</loc>";
    print "<lastmod>";
    print date('Y-m-d\TH:i:s+00:00',$lastmod);
    print "</lastmod>";
    print "<priority>$priority</priority>";
    print "</url>";
}

// Print xml preamble
print '<?xml version="1.0" encoding="UTF-8"?><urlset 
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 
http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" 
xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Homepage
printSingleUrl($_BASEURL,$lastmod,'0.2');


// Enabled Modules, except contact
if($_CONFIG['tree']){ printSingleUrl("$_BASEURL/tree.php",$lastmod,'0.8'); }
if($_CONFIG['map']){ printSingleUrl("$_BASEURL/map.php",$lastmod,'0.8'); }
if($_CONFIG['table']){ printSingleUrl("$_BASEURL/table.php",$lastmod,'1.0'); }
if($_CONFIG['contact']){ printSingleUrl("$_BASEURL/contact.php",$lastmod,'0.2'); }

// Link to the about page!
printSingleUrl("$_BASEURL/about.php",$lastmod,'0.2'); 

// Individual ancestors
foreach($parsedgedcom->getIndi() as $indi){
    $id = $indi->getId();
    printSingleUrl("$_BASEURL/individual.php?id=$id",$lastmod,'0.5'); 
}

foreach($parsedgedcom->getFam() as $fam){
    $id = $fam->getId();
    printSingleUrl("$_BASEURL/family.php?id=$id",$lastmod,'0.4'); 
}

print "</urlset>";
