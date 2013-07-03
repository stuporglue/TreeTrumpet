<?php

function makeGedcomEventsArray(){

    $parser = new PhpGedcom\Parser();
    global $parsedgedcom; // this will have to change later. For now, let's get things working
    $parsedgedcom = $parser->parse('family.ged');

    $places = Array();
    $indis = Array();
    $fams = Array();

    $events_raw = Array();
    $events = Array();

    /*
     * One event:
      Array(
        'type' => TYPE,
        'indi' => Array(),
        'date' => DATE,
        'place' => Array(),
      )
     */

    foreach($parsedgedcom->getIndi() as $indi){
        if($evens = $indi->getEven()){
            foreach($evens as $even){
                $even->people[] = $indi->getId();
                $events_raw[] = $even;
            }
        }

        $name = "Person {$indi->getId()}";
        if($names = $indi->getName()){
            $name = $names[0]->getName();
        }
        $indis[$indi->getId()] = $name;
    }

    foreach($parsedgedcom->getFam() as $fam){
        if($evens = $fam->getEven()){
            foreach($evens as $even){
                if($husb = $even->getHusb()){
                    $even->people[] = $husb;
                }
                if($wife = $even->getWife()){
                    $even->people[] = $wife;
                }
                $events_raw[] = $even;
            }
        }
    }

    print_r($events_raw);

    foreach($events_raw){
    
    }

    return $events;
}
