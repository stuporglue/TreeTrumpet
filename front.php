<?php
require_once(__DIR__ . '/lib/setup.php');

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

$firstBold = preg_replace('|/(.*)/|',"<span class='ttln'>$1</span>",$firstName);

?><!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8"/>
        <title>The Genealogy of <?php print $firstName; ?></title>
        <link href="css/tt.css" rel="stylesheet" media="all"/>
        </head>
    <body>
    <div id="tt-content">
        <div id='tt-left-content' class='tt-content'>
        <h1>The Genealogy of <?php print $firstBold; ?></h1>
            <?php require_once('lib/header.php'); ?>
            <h2>(and <?php print $count - 1; ?> of <?php print $firstSex;?> relatives)</h2>
            <p>
            This is the genealogy website for <?php print $firstBold; ?> and <?php print $firstSex;?> relatives. It is maintained by <a href='contact.php'><?php print $createdBy;?></a> and was last updated on <?php print $lastUpdated; ?>.
            </p>

            <h2>Explore The Family of <?php print $firstBold; ?></h2>
                <div class='tt-preview'>
                    <h3>Tree View</h3>
                    <p>Pan, zoom and click around this interactive tree view of the family tree.</p>
                    <img src='img/tree.png' alt='TreeTrumpet Tree View'/>
                    <a href='tree.php'>TreeTrumpet Tree View</a>
                </div>
                <div class='tt-preview'>
                    <h3>Map View</h3>
                    <p>See where ancestors important events occurred on this map. 
                    <img src='img/map_preview.png' alt='TreeTrumpet Map View'/>
                    <a href='map.php'>TreeTrumpet Map View</a>
                </div>
                <div class='tt-preview'>
                    <h3>Table View</h3>
                    <p>Filter and sort genealogical details from this tree to get right to the information you want.</p>
                    <img src='img/table.png' alt='TreeTrumpet Table View'/>
                    <a href='table.php'>TreeTrumpet Table View</a>
                </div>
        </div>


        <div id='tt-right-content' class='tt-content'>
        </div>
    </div>
    <?php require_once('lib/footer.php'); ?>
</body>
</html>
