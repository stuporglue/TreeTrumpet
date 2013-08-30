<?php

$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));
$subm = $gedcom->getSubmitter();

$focus;
$totalPeople = 0;
foreach($gedcom->gedcom->getIndi() as $individual){
    if(!isset($focus)){
        $focus = $gedcom->getIndividual($individual->getId());
    }
    $totalPeople++;
}

$page = model('page');
global $_BASEURL;
$page->canonical($_BASEURL);

$page->title("The Genealogy of " . $focus->firstName());

$page->h1("The Genealogy of " . $focus->firstBold() . " and " . ($totalPeople - 1) . " of " . $focus->posessive() . " relatives");

$page->description .= "Genealogy website about " . $focus->firstName() . " and " . ($totalPeople - 1) . " of " . $focus->posessive() . " relatives.";

if($name = $subm->name()){
    $page->description .= " Prepared by $name.";
}

$page->body .= "<h2>Explore the family of " . $focus->firstBold() . "</h2>";

$page->bodyright = "<ul>
    <li><a href='#pedigreetree'>Pedigree Tree</a></li>
    <li><a href='#eventsmap'>Ancestor Events Map</a></li>
    <li><a href='#people'>Ancestor Table</a></li>
    </ul>";

$page->bodyright .= view('feature_preview',Array(
    'id' => 'pedigreetree',
    'title' => 'Pedigree Tree',
    'text' => 'Pan, zoom and click around this interactive tree view of the family tree.',
    'link' => linky('tree.php'),
    'img' => 'img/tree.png'
),true);

$page->bodyright .= view('feature_preview',Array(
    'id' => 'eventsmap',
    'title' => 'Ancestor Events Map',
    'text' => 'See where ancestors important events occurred on this map.',
    'link' => linky('map.php'),
    'img' => 'img/map_preview.png'
),true);

$page->bodyright .= view('feature_preview',Array(
    'id' => 'people',
    'title' => 'People',
    'text' => 'Drill down to the ancestor you\'re looking for with the filter and sorting tools on this interactive table.',
    'link' => linky('table.php'),
    'img' => 'img/table.png'
),true);


$csses = Array(
    "http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css",
    "css/index.css",
);
foreach($csses as $css){
    $page->css($css);
}


$scripts = Array(
    "http://code.jquery.com/jquery-1.9.1.js",
    "http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js",
);

foreach($scripts as $script){
    $page->js($script);
}

$page->js("$('h3.blocktitle').hide(); $('.tt-content-right').tabs();",TRUE);

view('page_v_split',Array('page' => $page,'menu' => 'index'));
