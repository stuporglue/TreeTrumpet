<?php

function source($sourId){

    $page = model('page');
    $gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));

    $source = $gedcom->getSource($sourId);

    controller('standard_meta_tags',Array(&$gedcom,&$page));

    /*
    $page->title("List of Relatives of " . $focus->firstName());
    $page->h1("List of Relatives of " . $focus->firstBold());

    $fourNames = Array();
    foreach($fourClose as $close){
        $one = $gedcom->getIndividual($close);
        $fourNames[] = $one->firstName();
        $page->keywords[] = $one->surname();
    }

    $page->description .= "A list of relatives of " . $focus->firstName() . ", including " . implode(',',$fourNames);

    $csses = Array(
        "http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css",
        "css/table.css",
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
    $page->js("$(document).ready(function(){ tt = $('#tt-people').ttTable('lib/ged2json.php','family.ged'); });",TRUE);


    $page->body .= "<div id='tt-people'>";
    $page->body .= controller('table_noscript',Array($ttgedcom));
    $page->body .= "</div>";

    view('page',Array('page' => $page,'menu' => 'people'));
     */
}
