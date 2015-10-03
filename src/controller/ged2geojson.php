<?php

$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));
$ged2json = model( 'ged2geojson', Array($gedcom, __DIR__ . '/../cache/geocoding.sqlite3'));

$json = $ged2json->toJsonArray(FALSE);
view('json',Array('json' => $json));
