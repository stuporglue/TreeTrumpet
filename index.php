<?php
require_once(__DIR__ . '/lib/config.php');

if(!file_exists('family.ged')){
    header('Location: lib/firstrun.php',TRUE,307);  
    exit();
}


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
$parsedgedcom = $parser->parse('family.ged');


$firstName = NULL;
$firstSex = NULL;
$count = 0;
$lastUpdated = NULL;
$createdBy = NULL;

foreach($parsedgedcom->getIndi() as $individual){
    if(is_null($firstName)){
        foreach($individual->getName() as $name){
            $firstName = $name->getName();
            break;
        }
        switch($individual->getSex()){
        case 'M':
            $firstSex = 'his';
            break;
        case 'F':
            $firstSex = 'her';
            break;
        default:
            $firstSex = 'their';
        }
    } 
    $count++;
}

$head = $parsedgedcom->getHead();
$lastUpdated = $head->getDate()->getDate();

foreach($parsedgedcom->getSubm() as $subm){
    $createdBy = $subm->getName();
}

if(is_null($firstName)){
    require_once('about.php');
    exit();
}

$firstBold = preg_replace('|/(.*)/|',"<span class='pvln'>$1</span>",$firstName);

?><!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8"/>
        <title>The Genealogy of <?php print $firstName; ?></title>
        <link href="css/pv.css" rel="stylesheet" media="all"/>
        </head>
    <body>
    <div id="pv-content">
        <div id='pv-left-content' class='pv-content'>
        <h1>The Genealogy of <?php print $firstBold; ?></h1>
            <?php require_once('lib/header.php'); ?>
            <h2>(and <?php print $count - 1; ?> of <?php print $firstSex;?> relatives)</h2>
            <p>
            This is the genealogy website for <?php print $firstBold; ?> and <?php print $firstSex;?> relatives. It is maintained by <a href='contact.php'><?php print $createdBy;?></a> and was last updated on <?php print $lastUpdated; ?>.
            </p>

            <h2>Explore The Family of <?php print $firstBold; ?></h2>
                <div class='pv-preview'>
                    <h3>Tree View</h3>
                    <p>Pan, zoom and click around this interactive tree view of the family tree.</p>
                    <img src='img/tree.png' alt='TreeTrumpet Tree View'/>
                    <a href='tree.php'>TreeTrumpet Tree View</a>
                </div>
                <div class='pv-preview'>
                    <h3>Map View</h3>
                    <p>See where ancestors important events occurred on this map. 
                    <img src='img/map_preview.png' alt='TreeTrumpet Map View'/>
                    <a href='map.php'>TreeTrumpet Map View</a>
                </div>
                <div class='pv-preview'>
                    <h3>Table View</h3>
                    <p>Filter and sort genealogical details from this tree to get right to the information you want.</p>
                    <img src='img/table.png' alt='TreeTrumpet Table View'/>
                    <a href='table.php'>TreeTrumpet Table View</a>
                </div>
        </div>


        <div id='pv-right-content' class='pv-content'>
        </div>
    </div>
    <?php require_once('lib/footer.php'); ?>
</body>
</html>
