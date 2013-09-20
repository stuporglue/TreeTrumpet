<?php

// Start with this guy: 
$queue = Array(); // track who we need to track. id => depth
$seen = Array();  // avoid loops/recursion by keeping tabs of who we've seen
$json = Array(); // The result
$queue[$_GET['focus']] = 0;
$limit = 7; // How many generations to get in both direcitons. We'll go with 7 up, 7 down

$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));

while(count($queue) > 0){
    foreach($queue as $id => $depth){
        $json[$id] = $gedcom->json($id);

        if(is_numeric($depth)){
            // Queue up spouse if not already queued up
            if( isset($json[$id]['wife']) && 
                !isset($seen[$json[$id]['wife']]) && 
                !isset($queue[$json[$id]['wife']])
            ){
                $queue[$json[$id]['wife']] = NULL;
            }
            if( isset($json[$id]['husb']) && 
                !isset($seen[$json[$id]['husb']]) && 
                !isset($queue[$json[$id]['husb']])
            ){
                $queue[$json[$id]['husb']] = NULL;
            }

            if($depth >= 0 && $depth <= $limit){
                if(isset($json[$id]['mothers'])){
                    foreach($json[$id]['mothers'] as $motherId){
                        $queue[$motherId] = $depth + 1;
                    }
                }
                if(isset($json[$id]['fathers'])){
                    foreach($json[$id]['fathers'] as $fatherId){
                        $queue[$fatherId] = $depth + 1;
                    }
                }
            }
            if($depth <= 0 && $depth >= ($limit * -1)){
                if(isset($json[$id]['children'])){
                    foreach($json[$id]['children'] as $childId => $parentId){
                        $queue[$childId] = $depth - 1;
                    }
                }
            }
        }
        $seen[$id] = $id;
        unset($queue[$id]);
    }
}

view('json',Array('json' => $json));
