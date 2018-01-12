<?php
global $_BASEURL;
$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));
$page = model('page');

$subm = $gedcom->getSubmitter();
if($name = $subm->name()){
    $page->description .= " Prepared by $name.";
}

$focus;
$totalPeople = 0;
foreach($gedcom->gedcom->getIndi() as $individual){
    if(!isset($focus)){
        $focus = $gedcom->getIndividual($individual->getId());
    }
    $totalPeople++;
}

$page->canonical($_BASEURL);

$page->title("The Genealogy of " . $focus->firstName());

$page->h1("The Genealogy of " . $focus->firstBold() . " and " . ($totalPeople - 1) . " of " . $focus->posessive() . " relatives");

$page->description .= "Genealogy website about " . $focus->firstName() . " and " . ($totalPeople - 1) . " of " . $focus->posessive() . " relatives.";

$page->keywords[] = "genealogy";
$page->keywords[] = "family history";
$page->keywords[] = "ancestors";
$page->keywords[] = $focus->firstName();


$fourClose = controller('close_people',Array($gedcom,$focus,4));

if(count($fourClose) > 4){
    $fourClose = array_slice($fourClose,count($fourClose) - 5);
}
$fourNames = Array();
foreach($fourClose as $close){
    $one = $gedcom->getIndividual($close);
    $fourNames[] = $one->firstName();
    $page->keywords[] = $one->surname();
}

$page->body .= "<h2>Explore the family of " . $focus->firstBold() . "</h2>";

$page->body .= "<p>";
$page->body .= "This website was created to share the genealogy of ancestors I";
$page->body .= " care about, including " . $focus->firstName() . ". It was generated automatically from a GEDCOM file";
$page->body .= " so if you don't see something you're looking for please contact me, it may just not have made it into the GEDCOM!";
$page->body .= "</p>";
$page->body .= "<p>";
$page->body .= "If we have a shared research interest, I'd be happy to cross-reference our notes.";
$page->body .= "</p>";
$page->body .= "<p>";
$page->body .= "If you have suggestions on how to improve this site, please let";
$page->body .= "  the folks over at <a href='https://github.com/stuporglue/TreeTrumpet'>TreeTrumpet</a> know and I'll get the new features the next time I upgrade.";
$page->body .= "</p>";
$page->body .= "<p>";
$page->body .= "Thanks for stopping by!";
$page->body .= "</p>";

$page->bodyright .= view('feature_preview',Array(
    'id' => 'overview',
    'title' => 'Features',
    'text' => '<p>You can explore this pedigree with these tools. Each ancestor and family have their own personal pages to explore as well.</p>
    <p>If you have other tools you\'d like to see here, please let <a href="https://github.com/stuporglue/TreeTrumpet">TreeTrumpet</a> know.</p>',
    'link' => linky($_BASEURL . '/tree.php'),
    'img' => 'img/treetrumpet.png'
),true);

$page->bodyright .= view('feature_preview',Array(
    'id' => 'pedigreetree',
    'title' => 'Pedigree Tree',
    'text' => '<p>Pan, zoom and click around this interactive tree view of the family tree.</p>
    <p>This page view will help you visualize your family tree.</p>',
    'link' => linky($_BASEURL . '/tree.php'),
    'img' => 'img/tree.png'
),true);

$page->bodyright .= view('feature_preview',Array(
    'id' => 'eventsmap',
    'title' => 'Events Map',
    'text' => '<p>See where ancestors important events occurred on this interactive map.</p>
    <p>Currently the map defaults to one event per person.</p>
    ',
    'link' => linky($_BASEURL . '/map.php'),
    'img' => 'img/map.png'
),true);

$page->bodyright .= view('feature_preview',Array(
    'id' => 'people',
    'title' => 'People',
    'text' => '<p>Drill down to the ancestor you\'re looking for with the filter and sorting tools on this interactive table.</p>',
    'link' => linky($_BASEURL . '/table.php'),
    'img' => 'img/people.png'
),true);


$csses = Array(
    "//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css",
    "css/index.css",
);
foreach($csses as $css){
    $page->css($css);
}


view('page_v_split',Array('page' => $page,'menu' => 'index'));
