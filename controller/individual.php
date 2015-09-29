<?php

function individual($indiId){

    $gedcom = model('ttgedcom',Array(__FILE__ . '/../family.ged'));

    $individual = $gedcom->getIndividual($indiId);

    if(!$individual){
        return controller('_404',Array("Individual $indiId"));
    }

    $page = model('page');
    controller('standard_meta_tags',Array(&$gedcom,&$page));

    $dates = Array();
    if($birth = $individual->getEvent('BIRT')){
        $dates[] = $birth->getdate();
    }

    if($death = $individual->getEvent('DEAT')){
        $dates[] = $death->getDate();
    }

    $page->description .= "Genealogy, history and notes about " . $individual->firstName();

    if(count($dates) > 0){
        $page->description .= " who lived " . implode(' - ', $dates);
    }

    if($names = $individual->getName()){
        foreach($names as $name){
            if($givn = $name->getGivn()){
                $page->keywords[] = $givn;
            }
            if($surn = $name->getSurn()){
                $page->keywords[] = $surn;
            }
        }
    }


    foreach($individual->places() as $place){
        $page->keywords[] = $place;
    }

    $page->canonical($individual->link());

    $page->css("//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css");
    $page->css("css/tabs.css");
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

    // Most people probably won't want this online
    // $ord = $individual->ordinances();
    // if($ord != ''){
    //     $navigation .= "<li><a href='#ordinances'>Ordinances</a></li>";
    //     $details .= $ord;
    // }

    $navigation .= "</ul>";

    $page->body = $navigation . $details;

    $scripts = Array(
        "//code.jquery.com/jquery-1.11.3.min.js",
        "//code.jquery.com/ui/1.11.4/jquery-ui.min.js",
        "js/tabs.js",
    );

    foreach($scripts as $script){
        $page->js($script);
    }

    view('page',Array('page' => $page,'menu' => 'individual'));
}
