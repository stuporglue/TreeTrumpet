<?php

function individual($indiId){

$gedcom = model('gedcom',Array(__FILE__ . '/../family.ged'));

$individual = $gedcom->getIndividual($indiId);


$page = model('page');
$page->css("css/individual.css");
$page->title("All about " . $individual->firstName());
$page->h1("All about " . $individual->firstBold());

$details = "";
$navigation = "<ul>";

$overview = $individual->overview();
if($overview != ''){
    $navigation .= "<li><a href='#overview'>Overview</a></li>";
    $details .= $overview;
}

$attributes = $individual->attributes();
if($attributes != ''){
    $navigation .= "<li><a href='#attributes'>Attributes</a></li>";
    $details .= $attributes;
}

$parents = $individual->parents();
if($parents != ''){
    $navigation .= "<li><a href='#parents'>Parents</a></li>";
    $details .= $parents;
}

$spouseAndKids = $individual->spouseAndKids();
if($spouseAndKids != ''){
    $navigation .= "<li><a href='#spouses'>Spouses</a></li>";
    $details .= $spouseAndKids;
}

$events = $individual->events();
if($events != ''){
    $navigation .= "<li><a href='#events'>Events</a></li>";
    $details .= $events;
}

$associates = $individual->associates();
if($associates != ''){
    $navigation .= "<li><a href='#associates'>Associates</a></li>";
    $details .= $associates;
}


$notes = $individual->notes();
if($notes != ''){
    $navigation .= "<li><a href='#notes'>Notes</a></li>";
    $details .= $notes;
}

$refs = $individual->references();
if($refs != ''){
    $navigation .= "<li><a href='#references'>References and Sources</a></li>";
    $details .= $refs;
}


$mm = $individual->multimedia();
if($mm != ''){
    $navigation .= "<li><a href='#multimedia'>Multimedia</a></li>";
    $details .= $mm;
}

$meta = $individual->metadata();
if($meta != ''){
    $navigation .= "<li><a href='#metadata'>Metadata</a></li>";
    $details .= $meta;
}

$navigation .= "</ul>";

// Most people probably won't want this online
// $ord = $individual->ordinances();
// if($ord != ''){
//     $navigation .= "<li><a href='#ordinances'>Ordinances</a></li>";
//     $details .= $ord;
// }

$page->body = $navigation .= $details;
view('page',Array('page' => $page,'menu' => 'individual'));
}
