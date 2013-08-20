<?php

$ged2json = model( 'ged2geojson', Array( __DIR__ . '/../family.ged', __DIR__ . '/../cache/geocoding.sqlite3'));

$json = $ged2json->toJsonArray(FALSE);
view('json',Array('json' => $json));
