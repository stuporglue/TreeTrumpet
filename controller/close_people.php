<?php

function close_people(&$gedcom,&$focus,$count = 4){

    // Get some people close to the individual
    $closePeople = Array();
    $parentIds = $focus->parentIds();
    foreach($parentIds as $parentId){
        $closePeople[] = $parentId;
    }

    // parents
    foreach($parentIds as $parentId){
        $parent = $gedcom->getIndividual($parentId);
        foreach($parent->parentIds() as $grandparentId){
            $closePeople[] = $grandparentId;
        }
    }

    // Get children
    if($fams = $focus->getFams()){
        foreach($fams as $famc){
            $famId = $famc->getFams();

            if(!$famId){
                continue;
            }        

            $fam = $gedcom->getFamily($famId);

            if($chils = $fam->getChil()){
                foreach($chils as $chil){
                    array_unshift($closePeople,$chil);
                }
            }
        }
    }

    if(count($closePeople) > $count){
        $closePeople = array_slice($closePeople,$count * -1);
    }
    return $closePeople;
}

