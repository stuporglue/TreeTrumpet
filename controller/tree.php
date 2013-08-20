<?php

$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));

$ancestors = $gedcom->alphabeticByName();
$focusId = $gedcom->getFocusId();

$focus = $ancestors[$focusId];

$treeNav .= "<p>Back to the home person: <a href='".$focus->link()."' onclick=\"return refocusTree('{$focusId}');\">" . $focus->firstBold() . "</a></p>";

$treeNav .= "<ul class='shortlist'>";
foreach($ancestors as $id => $ancestor){
    $treeNav .= "<li><a href='".$ancestor->link()."' onclick=\"return refocusTree('$id');\">" . $ancestor->firstBold() . "</a></li>";
}
$treeNav .= "</ul>";


$hidden = "
<div id='details' style='display:none'>
    <h3>All about <span class='name'></span></h3>
    <a id='refocuslink' onclick='pt.refocus(this.className);return false;' class=''>Focus Tree on Me</a><br>
    <a id='gotopage' href='#' class=''>See all details</a>
    <h4>Gender</h3>
    <div class='gender'></div>
    <h4>Events</h4>
    <div class='events'></div>
</div>
";

$page = model('page');

$csses = Array(
    'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css',
    'css/3rdparty/ui/ui.slider.css',
    'css/3rdparty/tree.css',
    'css/tree.css'
);

foreach($csses as $css){
    $page->css($css);
}

$scripts = Array(
    "js/3rdparty/excanvas.js",
    "http://code.jquery.com/jquery-1.9.1.js",
    "http://code.jquery.com/ui/1.10.3/jquery-ui.js",
    "js/3rdparty/jquery.mousewheel.js",
    "js/3rdparty/sharing-time.js",
    "js/3rdparty/sharing-time-ui.js",
    "js/3rdparty/sharing-time-chart.js",
    "js/3rdparty/jsZoom.js",
    "js/3rdparty/make_chart.js",
    "js/3rdparty/tree.js",
    "js/tree.js"
);

foreach($scripts as $script){
    $page->js($script);
}

$page->title("Treetrumpet Tree Demo");
$page->h1("Treetrumpet Tree Demo");
$page->body .= "<h2>Interactive Tree Temo</h2>";
$page->body .= $treeNav;
$page->body .= "<div id='tt-tree'>Please wait...loading</div>";
$page->hidden($hidden);

view('page',Array('page' => $page,'menu' => 'tree'));
