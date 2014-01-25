<?php

// Start with this guy: 
$queue = Array(); // track who we need to track. id => depth
$seen = Array();  // avoid loops/recursion by keeping tabs of who we've seen
$json = Array(); // The result
$queue[$_GET['focus']] = 0;
$limit = 7; // How many generations to get in both direcitons. We'll go with 7 up, 7 down

$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));

$crawlInstructions = Array(
    'wife' => NULL,
    'husb' => NULL,
    'mothers' => 1,
    'fathers' => 1,
    'children' => -1
);

while(count($queue) > 0){
    foreach($queue as $id => $depth){
        $json[$id] = $gedcom->json($id);
        if(is_numeric($depth)){
            if((isset($seen[$id]) && !is_null($seen[$id]))){
                continue;
                unset($queue[$id]);
            }

            foreach($crawlInstructions as $relation => $depthIncrement){
                foreach($json[$id][$relation] as $childId => $relativeId){
                    if($relation == 'children'){
                        $relativeId = $childId;
                    }

                    // If we've already queued/seen this person
                    if(
                        is_null($depthIncrement) && 
                        !isset($seen[$relativeId]) && 
                        !isset($queue[$relativeId])
                    ){
                        $queue[$relativeId] = NULL; // We don't crawl spouses and we only want to crawl them once
                    }else if(
                        !is_null($depthIncrement) && 
                        ($depthIncrement * $depth) >= 0 && 
                        ($depthIncrement * $depth) <= $limit
                    ){
                        $queue[$relativeId] = $depth + $depthIncrement;
                    }
                }
            }
        }

        $seen[$id] = $depth;
        unset($queue[$id]);
    }
}

view('json',Array('json' => array_values($json)));
