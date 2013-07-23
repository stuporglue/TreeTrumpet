<?php

controller('setup');

$ged2json = model('ged2json',Array(__DIR__ . '/../family.ged'));

$ancestors = $ged2json->toJsonArray(TRUE);
usort($ancestors,function($a,$b){
    return str_replace('/','',$a['name']) > str_replace('/','',$b['name']);
});
$ids = Array();
foreach($ancestors as $ancestor){
    $ids[$ancestor['id']] = $ancestor['name'];
}
ksort($ids);

$treeNav = "";
foreach($ids as $id => $name){
    $treeNav .= "<p>Back to the home person: <a href='individual.php?id={$id}' onclick=\"return refocusTree('{$id}');\">" . preg_replace('|/(.*)/|',"<span class='ttln'>$1</span>",$name) . "</a></p>";
    break;
}

$treeNav .= "<ul class='shortlist'>";
foreach($ancestors as $ancestor){
    $treeNav .= "<li><a href='individual.php?id={$ancestor['id']}' onclick=\"return refocusTree('{$ancestor['id']}');\">" . preg_replace('|/(.*)/|',"<span class='ttln'>$1</span>",$ancestor['name']) . "</a></li>";
}
$treeNav .= "</ul>";



$hidden = "
<div id='details' style='display:none'>
    <h3>All about <span class='name'></span></h3>
    <span id='refocuslink' onclick='pt.refocus(this.className);' class=''>Focus Tree on Me</span><br>
    <span id='gotopage' onclick='document.location=\"individual.php?id=\" + this.className;' class=''>See all details</span>
    <h4>Gender</h3>
    <div class='gender'></div>
    <h4>Events</h4>
    <div class='events'></div>
</div>
";

$page = model('page');

$csses = Array(
    'css/tt.css',
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

$page->h1("Treetrumpet Tree Demo");
$page->body .= "<h2>Interactive Tree Temo</h2>";
$page->body .= $treeNav;
$page->body .= "<div id='tt-tree'>Please wait...loading</div>";
$page->hidden($hidden);

view('page',Array('page' => $page,'menu' => 'tree'));
