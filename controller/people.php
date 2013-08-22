<?php

$page = model('page');
$page->title("TreeTrumpet Ancestors");
$page->h1("TreeTrumpet Ancestors");

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


$page->body .= "<p>This page contains a sortable, filterable table of ancestors, relatives and events in the gedcom file.</p>
<p>
Clicking on Parent or Children names will filter the table to show just that parent 
or child. Clicking on an individual's own name will bring you to the person's individual 
information page.
</p>";

$page->body .= "<div id='tt-people'>";
$page->body .= controller('table_noscript');
$page->body .= "</div>";

view('page',Array('page' => $page,'menu' => 'people'));
