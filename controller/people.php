<?php

$page = model('page');
$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));

controller('standard_meta_tags',Array(&$gedcom,&$page));

$focusId = $gedcom->getFocusId();
$focus = $gedcom->getIndividual($focusId);

$page->title("List of Relatives of " . $focus->firstName());
$page->h1("List of Relatives of " . $focus->firstBold());

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

$page->description .= "A list of relatives of " . $focus->firstName() . ", including " . implode(',',$fourNames);

$csses = Array(
    "http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css",
    "css/table.css",
);
foreach($csses as $css){
    $page->css($css);
}

$scripts = Array(
    "//code.jquery.com/jquery-1.11.3.min.js",
    "http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.js",
    "js/people.js"
);
foreach($scripts as $script){
    $page->js($script);
}
$page->js("$(document).ready(function(){
    tt = $('#tt-people').ttTable('lib/ged2json.php','family.ged');
});",TRUE);



$page->body .= "<div id='tt-people'>";
$page->body .= controller('table_noscript',Array($ttgedcom));
$page->body .= "</div>";

view('page',Array('page' => $page,'menu' => 'people'));
