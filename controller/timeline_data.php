<?php

ob_end_flush();

$gedcom = model('ttgedcom',__DIR__ . '/../family.ged');
$events = $gedcom->allEvents();

print_r($events);

// print json_encode($events);
