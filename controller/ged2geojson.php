<?php

$ged2json = model('ged2geojson',Array(__DIR__ . '/../family.ged'));

$json = $ged2json->toJson(FALSE);
view('json',Array('json' => $json));
