<?php
require_once(__DIR__ . '/lib/setup.php');

// TODO: Individual map

require_once('lib/pretty-print_php-gedcom.php');

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

global $parsedgedcom; // this will have to change later. For now, let's get things working
$parsedgedcom = $parser->parse('family.ged');


$individual = NULL;
foreach($parsedgedcom->getIndi() as $individual){
    if($individual->getId() == $_GET['id']){
        break;
    }
}

if($names = $individual->getName()){
    $firstName = $names[0]->getName();
    $firstBold = preg_replace('|/(.*)/|',"<span class='pvln'>$1</span>",$firstName);

}


?><!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8"/>
        <title>All About <?php print $firstName; ?></title>
        <link href="css/pv.css" rel="stylesheet" media="all"/>
        <link href="css/individual.css" rel="stylesheet" media="all"/>
        </head>
    <body>
    <div id="pv-content">
        <h1>All About <?php print $firstBold; ?></h1>
            <?php require_once('lib/header.php'); ?>
        <div id='navigation'>
            <ul>
            <li><a href='#overview'>Overview</a></li>
            <li><a href='#attributes'>Attributes</a></li>
            <li><a href='#parents'>Parents</a></li>
            <li><a href='#spouses'>Spouses</a></li>
            <li><a href='#events'>Events</a></li>
            <li><a href='#associates'>Associates</a></li>
            <li><a href='#notes'>Notes</a></li>
            <li><a href='#references'>References</a></li>
            <li><a href='#multimedia'>Multimedia</a></li>
            <li><a href='#metadata'>Metadata</a></li>
            <li><a href='#ordinances'>LDS Ordinances</a></li>
            </ul>
        </div>
<?php 
// ID


// Overview
{
    $overview = "";
    if($names = $individual->getName()){
        $overview .= "<h3>Names</h3>";
        $overview .= "<ul>";
        foreach($names as $name){
            $overview .= "<li>" . $name->getName() . "</li>";
        }
        $overview .= "</ul>";
    }

    if($sex = $individual->getSex()){
        $overview .= "<h3>Gender</h3>";
        switch($sex){
        case 'M':
            $overview .= "Male";
            break;
        case 'F':
            $overview .= "Female";
            break;
        default:
            $overview .= "Unknown ($sex)";
        }
    }

    // getAlia
    if($aliases = $individual->getAlia()){
        $overview .= "<h3>Possible Duplicates</h3>";
        $overview .= "<ul>";
        foreach($aliases as $alias){
            $overview .= "<li><a href='individual.php?id=$alias'>$alias</a></li>";
        }
        $overview .= "</ul>";
    }

    if($overview != ''){
        print "<h2 id='overview'>Overview</h2>";
        print "<div class='block'>";
        print $overview;
        print "</div>"; // End Overview block
    }
}

// Attributes
{
    if($attrs = $individual->getAttr()){
        print "<h2 id='attributes'>Attributes</h2>";
        print "<div class='block'>";
        foreach($attrs as $attr){
            print printAttr($attr);
        }
        print "</div>";
    }
}

// Parents
{
    if($fams = $individual->getFamc()){
        print "<h2 id='parents'>Parents</h2>";
        print "<div class='block'>";
        foreach($fams as $famc){
            print printFamc($famc,$individual->getId());
        }
        print "</div>";
    }
}

// Spouses and kids
{
    if($fams = $individual->getFams()){
        print "<h2 id='spouses'>Spouses and Children</h2>";
        print "<div class='block'>";
        foreach($fams as $famc){
            print printFams($famc,$individual->getId());
        }
        print "</div>";
    }
}

// Events block
{
    if($evens = $individual->getEven()){
        print "<h2 id='events'>Events in the life of $firstName</h2>";
        print "<div class='block'>";
        foreach($evens as $even){
            print printEven($even);   
        }
        print "</div>";
    }
}

// Associates Block
{
    if($assos = $individual->getAsso()){
        print "<h2 id='associates'>Associates of $firstName</h2>";
        print "<div class='block'>";
        foreach($assos as $asso){
            print printAsso($asso);
        }
        print "</div>";
    }
}

// Notes block
{
    if($notes = $individual->getNote()){
        print "<h2 id='notes'>Notes</h2>";
        print "<div class='block'>";
        foreach($notes as $note){
            print printNote($note);
        }
        print "</div>";
    }
}

// References and sources block
{
    $refs = '';
    if($refns = $individual->getRefn()){
        $refs .= "<h3>References</h3>";
        foreach($refns as $refn){
            $refs .= printRefn($refn);
        }
    }

    // Sources
    if($sours = $individual->getSour()){
        $refs .= "<h3>Sources</h3>";
        foreach($sours as $sour){
            $refs .= printSour($sour);
        }
    }

    if($refs != ''){
        print "<h2 id='references'>References and Sources</h2>";
        print "<div class='block'>";
        print $refs;
        print "</div>";
    }
}



// Multimedia block
{
    if($objes = $individual->getObje()){
        print "<h2 id='multimedia'>Multimedia</h2>";
        print "<div class='block'>";
        foreach($objes as $obje){
            print printObje($obje);
        }
        print "</div>";
    }
}


// Ancestor Metadata

{
    print "<h2 id='metadata'>Metadata</h2>";
    print "<div class='block'>";

    // GEDCOM ID
    print "<h3>GEDCOM ID</h3>";
    print "<ul><li>{$individual->getId()}</li></ul>";

    // Anci
    if($ancis = $individual->getAnci()){
        print "<h3>Interest in This Ancestor</h3>";
        print "<ul>";
        foreach($ancis as $anci){
            print "<li>$anci</li>";
        }
        print "</ul>";
    }

    // Desi
    if($descs = $individual->getDesi()){
        print "<h3>Interest in This Descendant</h3>";
        print "<ul>";
        foreach($descs as $desc){
            print "<li>$desc</li>";
        }
        print "</ul>";
    }

    // Subm
    if($subms = $individual->getSubm()){
        print "<h3>Who Submitted This Name</h3>";
        print "<ul>";
        foreach($subms as $subm){
            print "<li>$subm</li>";
        }
        print "</ul>";
    } 

    // Resn
    if($resns = $individual->getResn()){
        print "<h3>Restricted!</h3>";
        print "<dl><dt>Reason</dt><dd>$resns</dd></dl>";
    } 

    // Rfn
    if($rfns = $individual->getRfn()){
        print "<h3>Record File Number</h3>";
        print "<dl><dt>RFN</dt><dd>$rfns</dd></dl>";
    }

    // Afn
    if($afn = $individual->getAfn()){
        print "<h3>Ancestor File Number";
        print "<dl><dt>AFN</dt><dd>$afn</dd></dl>";
    }

    // Chan
    if($chan = $individual->getChan()){
        print "<h3>Last Changed</h3>";
        printChan($chan);
    }

    // Rin
    if($rin = $individual->getRin()){
        print "<h3>RIN</h3>";
        print "<dl><dt>RIN</dt><dd>$rin</dd></dl>";
    }
    print "</div>";
}


// Ordinances Block
// Most people probably won't want this online
//{
//    $ord = "";
//    // Bapl
//    if($bapl = $individual->getBapl()){
//        $ord .= "<h3>LDS Baptism</h3>";
//        $ord .= printOrdinance($bapl);
//    }
//
//    // conl
//    if($conl = $individual->getConl()){
//        $ord .= "<h3>LDS Confirmation</h3>";
//        $ord .= printOrdinance($conl);
//    }
//
//    // endl
//    if($endl = $individual->getEndl()){
//        $ord .= "<h3>LDS Endowment</h3>";
//        $ord .= printOrdinance($endl);
//    }
//
//    // slgc
//    if($slgc = $individual->getSlgc()){
//        $ord .= "<h3>LDS Sealing To Parents</h3>";
//        $ord .= printOrdinance($endl);
//    }
//
//    if($ord != ''){
//        print "<h2 id='ordinances'>LDS Ordinances</h2>";
//        print "<div class='block'>";
//        print $ord;
//        print "</div>";
//    }
//}



?>
    </div>
    <?php require_once('lib/footer.php'); ?>
</body>
</html>
