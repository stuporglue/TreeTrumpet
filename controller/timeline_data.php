<?php

ob_end_flush();

$gedcom = model('ttgedcom',__DIR__ . '/../family.ged');
$events = $gedcom->allEvents();

print json_encode($events);
