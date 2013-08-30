<?php

$page = model('page');
$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));

global $_BASEURL;
$page->canonical(linky($_BASEURL . '/map.php'));

controller('standard_meta_tags',Array(&$gedcom,&$page));

$focusId = $gedcom->getFocusId();
$focus = $gedcom->getIndividual($focusId);

$geocoder = model('geocoder');

$eventTypes = Array();
$allPlaces = Array();

$pretty_gedcom = model('pretty_gedcom',$gedcom->gedcom);
foreach($gedcom->gedcom->getIndi() as $individual){
    $indi = model('individual',Array($individual,$gedcom->gedcom,$pretty_gedcom));
    foreach($indi->eventsList() as $event){
        if($plac = $event->getPlac()){
            $eventTypes[$event->getType()][] = $plac->getPlac();
            $allPlaces[$plac->getPlac()][$indi->sortName()] = "<a href='" . $indi->link() . "' title='" . $indi->firstName() . "'>" . $indi->firstBold() . "</a>";
        }
    }
}

// Find top 5 used places for description
$mostUsedPlaces = Array();
foreach($allPlaces as $k => $people){
    $mostUsedPlaces[$k] = count($people);
}
ksort($mostUsedPlaces);
if(count($mostUsedPlaces) > 10){
    $mostUsedPlaces = array_slice($mostUsedPlaces,-10);
}
foreach($mostUsedPlaces as $place){
    $page->keywords[] = $place;
}
if(count($mostUsedPlaces) > 4){
    $mostUsedPlaces = array_slice($mostUsedPlaces,-4);
}
array_flip($mostUsedPlaces);

$page->description .= "Places " . $focus->firstName() . " and " . $focus->posessive() . " relatives lived, including " . implode($mostUsedPlaces);

$places = $geocoder->geocode(array_keys($allPlaces));
$foundPlaces = array_keys($places);

foreach($eventTypes as $type => $placeList){
    $eventTypes[$type] = array_intersect(array_unique($placeList),$foundPlaces);
    if(count($eventTypes[$type]) == 0){
        unset($eventTypes[$type]);
    }
}

$eventTypesWithPlaces = array_keys($eventTypes);
sort($eventTypesWithPlaces);

$eventTypeMenu = '<h2>Filter Map by Event Type</h2><div><ul>';
$eventTypeMenu .= "<li class='ttfakelink onclick='tm.usePlaceFrom(\"Any\",\"first\")'>Any</li>";
foreach($eventTypesWithPlaces as $type){
    $eventTypeMenu .= "<li class='tteventfilter ttfakelink'>$type</li>";
}
$eventTypeMenu .= "</ul></div>";

ksort($allPlaces); 

$placesList = "";
foreach($allPlaces as $place => $people){
    if(array_key_exists($place,$places)){
        $placesList .= "<h2 data-geo='{$places[$place]['geometry']['coordinates'][0]},{$places[$place]['geometry']['coordinates'][1]}' class='tthasgeo ttfakelink'>$place</h2>";
    }else{
        $placesList .= "<h2>$place</h2>";
    }

    ksort($people);
    $placesList .= "<div><ul class='ttnamelist'><li>";
    $placesList .= implode('</li><li>',$people);
    $placesList .= "</li></ul></div>";
}

$intro = "<h2>Important Places and Relatives Who Lived there</h2>
    <div>
    <p>This map and list show the places " . $focus->firstName() .  " and " . $focus->hisher() . " relatives lived and died. On the map you can filter by the year events occurred, and in the list you can see a list of places. 
    <p>Clicking on the blue place names will zoom there on the map. </p></div>";


// $page->body .= $eventTypeMenu;
$page->body .= "<div id='places'>$intro$eventTypeMenu$placesList</div>";

$csses = Array(
    "http://cdn.leafletjs.com/leaflet-0.5/leaflet.css",
    "css/3rdparty/MarkerCluster.css",
    "css/3rdparty/MarkerCluster.Default.css",
    "http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css",
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
    "http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js",
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
        tm = $('#tt-map').ttMap('lib/ged2geojson.php');

        $('.tthasgeo').on('click',function(e){
            var coords = e.target.getAttribute('data-geo').split(',');
            tm.ttmap.panTo([parseFloat(coords[1]),parseFloat(coords[0])]);
            tm.ttmap.setZoom(10);
    });

    $('.tteventfilter').on('click',function(e){
        tm.usePlaceFrom(e.target.innerHTML);
    });
});
",TRUE);

$page->title("Places " . $focus->firstName() . " and " . ucfirst($focus->hisher()) . " Relatives Lived");

$page->h1("A Map of " . $focus->firstName() . " and " . ucfirst($focus->hisher()) . " Relatives");

$page->bodyright .= "<div id='tt-map'>";
$page->bodyright .= "<p>Hold tight, the map is loading!</p>";
$page->bodyright .= "<p>This is an interactive map of the places my ancestors lived.</p>";
$page->bodyright .= "<p>It requires JavaScript to work.</p>";
$page->bodyright .= "</div>";

$page->js("$('#places').accordion({ collapsible: true,heightStyle:'content' })",TRUE);

view('page_v_split',Array('page' => $page,'menu' => 'map'));
