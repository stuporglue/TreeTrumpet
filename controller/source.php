<?php

function source($sourId){

    $page = model('page');

    $gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));

    $source = $gedcom->getSource($sourId);

    controller('standard_meta_tags',Array(&$gedcom,&$page));

    $page->description .= "Source details for " . $source->getName();

    $page->keywords[] = $name;
    if($publ = $source->getPubl()){
        $page->keywords[] = $publ;
    }

    $page->canonical($source->link());
    $page->title("All about " . $source->getName());
    $page->css("http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");
    $page->css("css/tabs.css");
    $page->h1("All about " . $source->getName());

    $pretty = model('pretty_gedcom',Array($gedcom));

    $details = "";
    $navigation = "<ul>";

    $overview = $source->overview();
    if($overview != ''){
        $navigation .= "<li><a href='#overview'>Overview</a>";
        $details .= $overview;
    }

    $notes = $source->notes();
    if($notes != ''){
        $navigation .= "<li><a href='#notes'>Notes</a></li>";
        $details .= $notes;
    }

    $mm = $source->multimedia();
    if($mm != ''){
        $navigation .= "<li><a href='#multimedia'>Multimedia</a></li>";
        $details .= $mm;
    }

    $meta = $source->metadata();
    if($meta != ''){
        $navigation .= "<li><a href='#metadata'>Metadata</a></li>";
        $details .= $meta;
    }

    $navigation .= "</ul>";

    $page->body = $navigation . $details;

    $scripts = Array(
        "http://code.jquery.com/jquery-1.9.1.js",
        "http://code.jquery.com/ui/1.10.3/jquery-ui.js",
        "js/tabs.js",
    );

    foreach($scripts as $script){
        $page->js($script);
    }

    view('page',Array('page' => $page,'menu' => 'source'));
}
