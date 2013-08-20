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

$page->body .= view('feature_preview',Array(
    'title' => 'Tree View',
    'text' => 'Pan, zoom and click around this interactive tree view of the family tree.',
    'link' => 'tree.php',
    'img' => 'img/tree.png'
),true);

$page->body .= view('feature_preview',Array(
    'title' => 'Map View',
    'text' => 'See where ancestors important events occurred on this map.',
    'link' => 'map.php',
    'img' => 'img/map_preview.png'
),true);

$page->body .= view('feature_preview',Array(
    'title' => 'Table View',
    'text' => 'Drill down to the ancestor you\'re looking for with the filter and sorting tools on this interactive table.',
    'link' => 'table.php',
    'img' => 'img/table.png'
),true);

view('page',Array('page' => $page,'menu' => 'index'));
