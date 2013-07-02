<?php require_once(__DIR__ . '/lib/setup.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
            <title>TreeTrumpet Ancestors Map</title>

            <!-- Leaflet map -->
            <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.css" />
            <!--[if lte IE 8]>
                <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.ie.css" />
            <![endif]-->

            <link rel="stylesheet" href="css/3rdparty/MarkerCluster.css"/>
            <link rel="stylesheet" href="css/3rdparty/MarkerCluster.Default.css"/>
            <!--[if lte IE 8]>
            <link rel="stylesheet" href="css/3rdparty/MarkerCluster.Default.ie.css"/>
            <![endif]-->
            <link type='text/css' rel='stylesheet' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/ui-lightness/jquery-ui.css'/>
            <link type='text/css' rel="stylesheet" href="css/3rdparty/iThing.css"/>

            <link href="css/pv.css" rel="stylesheet" media="all"/>
            <link href="css/map.css" rel="stylesheet" media="all"/>
        </head>
        <body>
        <div id='pv-content'>
            <div id='pv-left-content' class='pv-content'>
                <h1>A Map Of My Ancestors</h1>
                    <?php 
                    require_once('lib/header.php'); 
                        ?>
                <h2>Important Places and My People Who Lived there</h2>
                <p>This map and list show the places my ancestors lived and died. On the map you can filter by the year events occurred, and in the list you can see a list of places. 
                <p>Clicking on the blue place names will zoom there on the map. </p>

                <?php
                    // Print a table for non-JS users and web crawlers
                    require_once('lib/3rdparty/ged2json.php');
                    require_once('lib/3rdparty/ged2geojson.php');
                    $gedcom = new ged2geojson('family.ged');
                    $ancestors = $gedcom->toJsonArray(FALSE);

                    // Now get all the places by popularity
                    $eventTypesWithPlaces = Array();
                    $popularPlaces = Array();
                    foreach($ancestors['features'] as $ancestorId => $ancestorInfo){
                        $ancestor = $ancestorInfo['properties'];
                        if(array_key_exists('events',$ancestor)){
                            foreach($ancestor['events'] as $event){
                                if(array_key_exists('place',$event) && array_key_exists('raw',$event['place'])){
                                    $popularPlaces[$event['place']['raw']][] = $ancestor['name'];  
                                    if(array_key_exists('geo',$event['place'])){
                                        $popularPlaces[$event['place']['raw']]['geo'] = $event['place']['geo'];
                                        $eventTypesWithPlaces[] = $event['type'];
                                    }
                                }
                            }
                        }
                    }

                    foreach($popularPlaces as $place => $people){
                        $popularPlaces[$place] = array_unique($people);
                    }

                    uasort($popularPlaces,function($a,$b){
                        return count($b) - count($a);
                    });

                    foreach($popularPlaces as $place => $people){
                        if(array_key_exists('geo',$people)){
                            print "<h3 data-geo='{$people['geo']['geometry']['coordinates'][0]},{$people['geo']['geometry']['coordinates'][1]}' class='pvhasgeo pvfakelink'>$place</h3>";
                            unset($people['geo']);
                        }else{
                            print "<h3>$place</h3>";
                        }

                        sort($people);
                        $boldLastName = Array();
                        foreach($people as $person){
                            $boldLastName[] = preg_replace('|/(.*)/|',"<span class='pvln'>$1</span>",$person);
                        }
                        print "<ul class='pvnamelist'><li>";
                        print implode('</li><li>',$boldLastName);
                        print "</li></ul>";
                    }

                ?>
            </div>
            <div id='pv-right-content' class='pv-content'>
                <div id='pv-map'>
                    Hang on! The map is loading!
                </div>
                <!div>
                    <h2>Map Events of Type...</h2>
                    <ul>
                        <li class='pvfakelink' onclick='pm.usePlaceFrom("Any","first")'>Any</li>
                    <?php
                    $eventTypesWithPlaces = array_unique($eventTypesWithPlaces);
                    foreach($eventTypesWithPlaces as $type){
                        print "<li class='pveventfilter pvfakelink'>$type</li>";
                    }
                    ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php require_once('lib/footer.php'); ?>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
<script type='text/javascript' src="http://cdn.leafletjs.com/leaflet-0.5/leaflet-src.js"></script>
<script type='text/javascript' src="js/3rdparty/leaflet.markercluster.js"></script>
<script type='text/javascript' src="js/3rdparty/jquery.mousewheel.min.js"></script>
<script type='text/javascript' src="js/3rdparty/jQEditRangeSlider-min.js"></script>

<script type='text/javascript' src='js/map.js'></script>
<script type='text/javascript'>
$(document).ready(function(){
    pm = $('#pv-map').pvMap('lib/ged2geojson.php','family.ged');
    $('.pvhasgeo').on('click',function(e){
        var coords = e.target.getAttribute('data-geo').split(',');
        pm.pvmap.panTo([parseFloat(coords[1]),parseFloat(coords[0])]);
        pm.pvmap.setZoom(10);
        document.location.hash="pv-map";
    });
    $('.pveventfilter').on('click',function(e){
        pm.usePlaceFrom(e.target.innerHTML);
    });
});
</script>
    </body>
</html>
