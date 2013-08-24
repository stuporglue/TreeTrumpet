<?php

$page = model('page');
$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));
$focusId = $gedcom->getFocusId();
$focus = $gedcom->getIndividual($focusId);

$page->title("List of Relatives of " . $focus->firstName());
$page->h1("List of Relatives of " . $focus->firstBold());

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
$page->js("$(document).ready(function(){
    tt = $('#tt-people').ttTable('lib/ged2json.php','family.ged');
});",TRUE);


$page->body .= "<p>Clicking on Parent or Children names to show that person.  Click an individual's own name will bring you to the person's information page.  </p>";

$page->body .= "<div id='tt-people'>";
$page->body .= controller('table_noscript');
$page->body .= "</div>";

view('page',Array('page' => $page,'menu' => 'people'));
