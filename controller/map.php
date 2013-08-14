<?php

$gedcom = model('ged2geojson',Array(__DIR__ . '/../family.ged'));

$ancestors = $gedcom->toJsonArray(FALSE);

// Now get all the places by popularity
$eventTypesWithPlaces = Array();
$popularPlaces = Array();
foreach($ancestors['features'] as $ancestorId => $ancestorInfo){
    $ancestor = $ancestorInfo['properties'];
    if(array_key_exists('events',$ancestor)){
        foreach($ancestor['events'] as $event){
            if(array_key_exists('place',$event) && array_key_exists('raw',$event['place'])){
                $popularPlaces[$event['place']['raw']][$ancestor['id']] = $ancestor['name'];  
                if(array_key_exists('geo',$event['place'])){
                    $popularPlaces[$event['place']['raw']]['geo'] = $event['place']['geo'];
                    $eventTypesWithPlaces[] = $event['type'];
                }
            }
        }
    }
}

$eventTypeMenu = '<menu><h2>Map Events of Type...</h2><ul>';
$eventTypeMenu .= "<li class='ttfakelink' onclick='tm.usePlaceFrom(\"Any\",\"first\")'>Any</li>";
$eventTypesWithPlaces = array_unique($eventTypesWithPlaces);
foreach($eventTypesWithPlaces as $type){
    $eventTypeMenu .= "<li class='tteventfilter ttfakelink'>$type</li>";
}
$eventTypeMenu .= "</ul></menu>";

foreach($popularPlaces as $place => $people){
    if(array_key_exists('geo',$people)){
        $geo = $people['geo'];
        unset($people['geo']);
        $people = array_unique($people);
        $people['geo'] = $geo;
    }else{
        $people = array_unique($people);
    }
    $popularPlaces[$place] = $people;
}

uasort($popularPlaces,function($a,$b){
    return count($b) - count($a);
});

$placesList = "";
foreach($popularPlaces as $place => $people){
    if(array_key_exists('geo',$people)){
        $placesList .= "<h3 data-geo='{$people['geo']['geometry']['coordinates'][0]},{$people['geo']['geometry']['coordinates'][1]}' class='tthasgeo ttfakelink'>$place</h3>";
        unset($people['geo']);
    }else{
        $placesList .= "<h3>$place</h3>";
    }

    asort($people);
    $boldLastName = Array();
    foreach($people as $id => $person){
        $boldLastName[] = "<a href='" . linky("individual.php?$id") . "' title='Go to the individual page for $person'>" . preg_replace('|/(.*)/|',"<span class='ttln'>$1</span>",$person) . "</a>";
    }
    $placesList .= "<ul class='ttnamelist'><li>";
    $placesList .= implode('</li><li>',$boldLastName);
    $placesList .= "</li></ul>";
}


$page = model('page');

$csses = Array(
    "http://cdn.leafletjs.com/leaflet-0.5/leaflet.css",
    "css/3rdparty/MarkerCluster.css",
    "css/3rdparty/MarkerCluster.Default.css",
    'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/ui-lightness/jquery-ui.css',
    "css/3rdparty/iThing.css",
    "css/map.css"
);

foreach($csses as $css){
    $page->css($css);
}

$iecss = Array(
    "http://cdn.leafletjs.com/leaflet-0.5/leaflet.ie.css",
    "css/3rdparty/MarkerCluster.Default.ie.css"
);
foreach($iecss as $css){
    $page->css($css,'all','if lte IE 8');
}

$scripts = Array(
    "http://code.jquery.com/jquery-1.9.1.js",
    "http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js",
    "http://cdn.leafletjs.com/leaflet-0.6/leaflet.js",
    "js/3rdparty/leaflet.markercluster.js",
    "js/3rdparty/jquery.mousewheel.min.js",
    "js/3rdparty/jQEditRangeSlider-min.js",
    'js/map.js'
);

foreach($scripts as $script){
    $page->js($script);
}

$page->js("
$(document).ready(function(){
    tm = $('#tt-map').ttMap('lib/ged2geojson.php','family.ged');

    $('.tthasgeo').on('click',function(e){
        var coords = e.target.getAttribute('data-geo').split(',');
        tm.ttmap.panTo([parseFloat(coords[1]),parseFloat(coords[0])]);
        tm.ttmap.setZoom(10);
        document.location.hash='tt-map';
    });

    $('.tteventfilter').on('click',function(e){
        tm.usePlaceFrom(e.target.innerHTML);
    });
});
    ",TRUE);

$page->title("TreeTrumpet Ancestors Map");

$page->h1("A Map of My Ancestors");

$page->body .= "<h2>Important Places and My People Who Lived there</h2>
    <p>This map and list show the places my ancestors lived and died. On the map you can filter by the year events occurred, and in the list you can see a list of places. 
    <p>Clicking on the blue place names will zoom there on the map. </p>";
$page->body .= $placesList;

$page->body .= "<div id='tt-map'>Hang on! The map is loading!</div>";
$page->body .= $eventTypeMenu;


view('page',Array('page' => $page,'menu' => 'map'));
