<?php

$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));

$focus;
$totalPeople = 0;
foreach($gedcom->gedcom->getIndi() as $individual){
    if(!isset($focus)){
        $focus = $gedcom->getIndividual($individual->getId());
    }
    $totalPeople++;
}


$page = model('page');
$page->title("The Genealogy of " . $focus->firstName());

$page->h1("The Genealogy of " . $focus->firstBold() . " and " . ($totalPeople - 1) . " of " . $focus->posessive() . " relatives");

$page->body .= "<h2>Explore the family of " . $focus->firstBold() . "</h2>";
$page->body .= "<div>
    This site has 
    </div>"

$page->bodyright .= view('feature_preview',Array(
    'title' => 'Pedigree Tree',
    'text' => 'Pan, zoom and click around this interactive tree view of the family tree.',
    'link' => linky('tree.php'),
    'img' => 'img/tree.png'
),true);

$page->bodyright .= view('feature_preview',Array(
    'title' => 'Ancestor Events Map',
    'text' => 'See where ancestors important events occurred on this map.',
    'link' => linky('map.php'),
    'img' => 'img/map_preview.png'
),true);

$page->bodyright .= view('feature_preview',Array(
    'title' => 'People',
    'text' => 'Drill down to the ancestor you\'re looking for with the filter and sorting tools on this interactive table.',
    'link' => linky('table.php'),
    'img' => 'img/table.png'
),true);


$csses = Array(
    "http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css",
    "css/index.css",
);
foreach($csses as $css){
    $page->css($css);
}


$scripts = Array(
    "http://code.jquery.com/jquery-1.9.1.js",
    "http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.js",
    "js/people.js"
);
foreach($scripts as $script){
    $page->js($script);
}
$page->js("$(document).ready(function(){
    tt = $('#tt-people').ttTable('lib/ged2json.php','family.ged');
});",TRUE);




view('page_v_split',Array('page' => $page,'menu' => 'index'));
