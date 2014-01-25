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
            foreach($crawlInstructions as $relation => $depthIncrement){
                foreach($json[$id][$relation] as $childId => $relativeId){
                    if(is_null($depthIncrement) && !isset($seen[$relativeId]) && !isset($queue[$relativeId])){
                        $queue[$relativeId] = NULL; // We don't crawl spouses and we only want to crawl them once
                    }else if(($depthIncrement * $depth) >= 0 && ($depthIncrement * $depth) <= $limit){

                        if($relation == 'children'){
                            $relativeId = $childId;
                        }

                        $queue[$relativeId] = $depth + $depthIncrement;
                    }
                }
            }
        }

        $seen[$id] = $id;
        unset($queue[$id]);
    }
}

view('json',Array('json' => array_values($json)));
