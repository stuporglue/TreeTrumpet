<?php

$page = model('page');
$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));

controller('standard_meta_tags',Array(&$gedcom,&$page));

$focusId = $gedcom->getFocusId();
$focus = $gedcom->getIndividual($focusId);

$page->title("Most Popular Names for the Relatives of " . $focus->firstName());
$page->h1("Most Popular for the Relatives of " . $focus->firstBold());
$page->canonical(linky($_BASEURL . '/contact.php'));

// Keep track of given names, surnames and all together
$given = Array();
$sur = Array();

while($indi = $gedcom->nextIndividual()){
    if($name = $indi->surname()){
        if(!isset($sur[$name])){
            $sur[$name] = Array();
        }
        $sur[$name][$indi->firstName()] = $indi->prettyLink();
    }
    foreach($indi->givenArray() as $name){
        if(!isset($given[$name])){
            $given[$name] = Array();
        }
        $given[$name][$indi->firstName()] = $indi->prettyLink();
    }
}

function sortByCount($a,$b){
    return count($b) - count($a);
}

uasort($given,'sortByCount');
uasort($sur,'sortByCount');

foreach($given as $k => $v){
    ksort($v);
}

foreach($sur as $k => $v){
    ksort($v);
}

// print_r($given);
// print_r($sur);

$page->body .= "<h2>Popular Given Names</h2>";

foreach($given as $name => $links){
    $page->body .= "<h3>$name</h3><div><ul>";
    foreach($links as $fullname => $link){
        $page->body .= "<li>$link</li>";
    }
    $page->body .= "</ul></div>";
}

$page->body .= "<h3>Popular Surnames</h3>";

foreach($sur as $name => $links){
    $page->body .= "<h3>$name</h3><div><ul>";
    foreach($links as $surname => $link){
        $page->body .= "<li>$link</li>";
    }
    $page->body .= "</ul></div>"; 
}

view('page',Array('page' => $page,'menu' => 'name_cloud'));
