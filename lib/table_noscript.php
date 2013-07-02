<?php
require_once('lib/3rdparty/ged2json.php');
$gedcom = new ged2json('family.ged');
$ancestors = $gedcom->toJsonHash(FALSE);
ksort($ancestors);

$noscript = "";
$noscript .= "<div class='pvtablectrl'></div><div class='pvtablediv'><table class='pvtable'>
    <thead><tr><th>ID</th><th>Name</th><th>Gender</th><th>Parents</th><th>Children</th><th>Events</th></tr></thead><tbody>";
foreach($ancestors as $id => $ancestor){
    $noscript .= "<tr id={$id}>";

    // ID
    $noscript .= "<td>$id</td>";

    // names
    $noscript .= "<td><a class='pvindipage' href='individual.php?id=$id'>";
    foreach($ancestor['names'] as $name){
        $noscript .=   preg_replace('|/(.*)/|',"<span class='pvln'>$1</span>",$name) ;
    }
    $noscript .= "</a></td>";

    // Gender
    $noscript .= "<td>" . $ancestor['gender'] . "</td>";

    // Parents
    $noscript .= "<td>";
    if(array_key_exists('fathers',$ancestor)){
        $noscript .= "<ul>";
        foreach($ancestor['fathers'] as $father){
            $noscript .= "<li class='pvnowrap'><a class='pvpersonlink' href='#{$father}'>" .  preg_replace('|/(.*)/|',"<span class='pvln'>$1</span>",$ancestors[$father]['name']) . "</a></li>";
        }
        $noscript .= "</ul>";
    }
    if(array_key_exists('mothers',$ancestor)){
        $noscript .= "<ul>";
        foreach($ancestor['mothers'] as $mother){
            $noscript .= "<li class='pvnowrap'><a class='pvpersonlink' href='#{$mother}'>" .  preg_replace('|/(.*)/|',"<span class='pvln'>$1</span>",$ancestors[$mother]['name']) . "</a></li>";
        }
        $noscript .= "</ul>";
    }
    $noscript .= "</td>";

    // Children 
    $noscript .= "<td>";
    if(array_key_exists('children',$ancestor)){
        $noscript .= "<ul>";
        foreach($ancestor['children'] as $child => $spouse){
            $noscript .= "<li class='pvnowrap'><a class='pvpersonlink' href='#{$child}'>" .  preg_replace('|/(.*)/|',"<span class='pvln'>$1</span>",$ancestors[$child]['name']) . "</a></li>";
        }
        $noscript .= "</ul>";
    }
    $noscript .= "</td>";

    // Events
    $noscript .= "<td>";
    if(array_key_exists('events',$ancestor)){
        $noscript .= "<ul>";
        foreach($ancestor['events'] as $event){
            $noscript .= "<li class='pvevent'>";
            $noscript .= "{$event['type']}: ";
            if(array_key_exists('date',$event)){
                $noscript .= "{$event['date']['raw']} ";
            }
            if(array_key_exists('place',$event)){
                $noscript .= "{$event['place']['raw']}";
            }
            $noscript .= "</li>";
        }
        $noscript .= "</ul>";
    }
    $noscript .= "</td>";
    $noscript .= "</tr>";
}
$noscript .= "</tbody></table></div>";
