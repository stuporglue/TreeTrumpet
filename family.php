<?php
require_once(__DIR__ . '/lib/setup.php');

require_once('lib/pretty-print_php-gedcom.php');

$parser = new PhpGedcom\Parser();

global $parsedgedcom; // this will have to change later. For now, let's get things working
$parsedgedcom = $parser->parse('family.ged');


foreach($parsedgedcom->getFam() as $family){
    if($family->getId() == $_GET['id']){
        break;
    }
}


$familyName = Array();

if($husb = $family->getHusb()){
    $husbo = findIndi($husb);
    $husbname = $husb;
    if($names = $husbo->getName()){
        $husbname = $names[0]->getName();
    }
    $familyName[] = $husbname;
}

if($wife = $family->getWife()){
    $wifeo = findIndi($wife);
    $wifename = $wife;
    if($names = $wifeo->getName()){
        $wifename = $names[0]->getName();
    }
    $familyName[] = $wifename;
}

$familyName = implode(' and ',$familyName);
$familyName = str_replace('/','',$familyName);

?><!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8"/>
        <title>All About <?php print $familyName; ?></title>
        <link href="css/tt.css" rel="stylesheet" media="all"/>
        <link href="css/family.css" rel="stylesheet" media="all"/>
        </head>
    <body>
    <div id="tt-content">
    <h1>All About <?php print $familyName; ?></h1>
<?php require_once('lib/header.php'); 

ob_start();
$navigation = "";

// Parents
{
    print "<h2 id='parents'>Parents</h2>";
    $navigation .= "<li><a href='#parents'>Parents</a></li>";
    print "<div class='block'>";
    print "<dl>";

    if($husb = $family->getHusb()){
        $husbo = findIndi($husb);
        $name = "Husband $husb";
        if($names = $husbo->getName()){
            $name = $names[0]->getName();
        }

        print "<dt>Husband</dt><dd>";
        print "<a href='individual.php?id=$husb'>$name</a>";
        print "</dd>";
    }

    if($wife = $family->getWife()){
        $wifeo = findIndi($wife);
        $name = "Wife $wife";
        if($names = $wifeo->getName()){
            $name = $names[0]->getName();
        }
        print "<dt>Wife</dt><dd>";
        print "<a href='individual.php?id=$wife'>$name</a>";
        print "</dd>";
    }

    print "</dl>";
    print "</div>";
}

// children
{
    if($chils = $family->getChil()){
        print "<h2 id='children'>Children</h2>";
        $navigation .= "<li><a href='#children'>Children</a></li>";
        print "<div class='block'>";

        if($nchi = $family->getNchi()){
            print "<p>$nchi total children in this family</p>";
        }

        print "<ol>";
        foreach($chils as $chil){
            $chilo = findIndi($chil);
            $name = "Child $chil";
            if($names = $chilo->getName()){
                $name = $names[0]->getName();
            }
            print "<li><a href='individual.php?id=$chil'>$name</a></li>";
        }
        print "</ol>";
        print "</div>";
    }
}

// Events block
{
    if($evens = $family->getEven()){
        print "<h2 id='events'>Events</h2>";
        $navigation .= "<li><a href='#events'>Events</a></li>";
        print "<div class='block'>";
        foreach($evens as $even){
            print printEven($even);   
        }
        print "</div>";
    }
}

// References and sources block
{
    $refs = '';
    if($refns = $family->getRefn()){
        $refs .= "<h3>References</h3>";
        foreach($refns as $refn){
            $refs .= printRefn($refn);
        }
    }

    // Sources
    if($sours = $family->getSour()){
        $refs .= "<h3>Sources</h3>";
        foreach($sours as $sour){
            $refs .= printSour($sour);
        }
    }

    if($refs != ''){
        print "<h2 id='references'>References and Sources</h2>";
        $navigation .= "<li><a href='#references'>References</a></li>";
        print "<div class='block'>";
        print $refs;
        print "</div>";
    }
}

// Notes block
{
    if($notes = $family->getNote()){
        print "<h2 id='notes'>Notes</h2>";
        $navigation .= "<li><a href='#notes'>Notes</a></li>";
        print "<div class='block'>";
        foreach($notes as $note){
            print printNote($note);
        }
        print "</div>";
    }
}


// Multimedia block
{
    if($objes = $family->getObje()){
        print "<h2 id='multimedia'>Multimedia</h2>";
        $navigation .= "<li><a href='#multimedia'>Multimedia</a></li>";
        print "<div class='block'>";
        foreach($objes as $obje){
            print printObje($obje);
        }
        print "</div>";
    }
}


// Metadata
{
    print "<h2 id='metadata'>Family Metadata</h2>";
    $navigation .= "<li><a href='#Metadata'>Metadata</a></li>";
    print "<div class='block'>";

    print "<h3>GEDCOM ID</h3>";
    print "<ul><li>{$family->getId()}</li></ul>";

    // Chan
    if($chan = $family->getChan()){
        print "<h3>Last Changed</h3>";
        printChan($chan);
    }

    // Subm
    if($subms = $family->getSubm()){
        print "<h3>Who Submitted This Name</h3>";
        print "<ul>";
        foreach($subms as $subm){
            print "<li>$subm</li>";
        }
        print "</ul>";
    } 
    // Rin
    if($rin = $family->getRin()){
        print "<h3>RIN</h3>";
        print "<dl><dt>RIN</dt><dd>$rin</dd></dl>";
    }


    print "</div>";
}


// Ordinances Block
// Most people probably won't want this online
//{
//    $ord = "";
//    // slgs
//    if($slgs = $family->getSlgs()){
//        $ord .= "<h3>LDS Sealing to Spouse</h3>";
//        $ord .= printOrdinance($slgs);
//    }
//    if($ord != ''){
//        print "<h2 id='ordinances'>LDS Ordinances</h2>";
//        $navigation .= "<li><a href='#ordinances'>Ordinances</a></li>";
//        print "<div class='block'>";
//        print $ord;
//        print "</div>";
//    }
//}

$body = ob_get_clean();

print "<div id='navigation'><ul>";
print $navigation;
print "</ul></div>";
print $body;

?>
    </div>
    <?php require_once('lib/footer.php'); ?>
</body>
</html>
