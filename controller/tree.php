<?php

controller('setup');


require_once(__DIR__ . '/../lib/3rdparty/ged2json.php');
$ged2json = new ged2json(__DIR__ . '/../family.ged');
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


view('page.php');
