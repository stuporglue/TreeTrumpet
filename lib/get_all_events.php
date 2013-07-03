<?php

require_once(__DIR__ . '/pretty-print_php-gedcom.php');

function parseDateString($string){
    // might be a year!
    $ts = date_create_from_format('Y',$string);
    if($ts === FALSE){
        $ts = strtotime($string);
    }else{
        $ts = $ts->getTimestamp();
    }
    if((int)$ts == 0){ return FALSE; }
        return $ts;
}


function makeGedcomEventsArray(){

    $parser = new PhpGedcom\Parser();
    global $parsedgedcom; // this will have to change later. For now, let's get things working
    $parsedgedcom = $parser->parse('family.ged');

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

    foreach($events_raw as $event){
        if($date = $event->getDate()){
            if($parsedDate = parseDateString($date)){
                $events[$parsedDate][] = $event;
            }
        }
    }

    ksort($events);

    return $events;
}

function printTimelineEvent($event){
    $ret = "<div>";
    $ret .= print_r($event,TRUE);
    $ret .= "</div>";
    return $ret;
}
