<?php

function family($familyId){
    $gedcom = model('gedcom',Array(__FILE__ . '/../family.ged'));

    $family = $gedcom->getFamily($familyId);

    if(!$family){
        controller('_404',Array("Family $familyId"));
        exit();
    }

    $familyName = $family->familyName();

    $page = model('page');
    $page->css('css/family.css');
    $page->title("All about $familyName");
    $page->h1("All about $familyName");

    $details = "";
    $navigation = "<ul>";

    $parents = $family->parents();
    if($parents != ''){
        $navigation .= "<li><a href='#parents'>Parents</a></li>";
        $details .= $parents;
    }

    $children = $family->children();
    if($children != ''){
        $navigation .= "<li><a href='#children'>Children</a></li>";
        $details .= $children;
    }

    $events = $family->events();
    if($events != ''){
        $navigation .= "<li><a href='#events'>Events</a></li>";
        $details .= $events;
    }

    $refs = $family->references();
    if($refs != ''){
        $navigation .= "<li><a href='#references'>References and Sources</a></li>";
        $details .= $refs;
    }

    $notes = $family->notes();
    if($notes != ''){
        $navigation .= "<li><a href='#notes'>Notes</a></li>";
        $details .= $notes;
    }

    $mm = $family->multimedia();
    if($mm != ''){
        $navigation .= "<li><a href='#multimedia'>Multimedia</a></li>";
        $details .= $mm;
    }

    $md = $family->metadata();
    if($md != ''){
        $navigation .= "<li><a href='#metadata'>Metadata</a></li>";
        $details .= $md;
    }

    // Most people probably won't want this online
    // $ord = $family->ordinances();
    // if($ord != ''){
    //     $navigation .= "<li><a href='#ordinances'>Ordinances</a></li>";
    //     $details .= $ord;
    // }

    $navigation .= "</ul>";

    $page->body = $navigation .= $details;
    view('page',Array('page' => $page,'menu' => 'family'));
}
