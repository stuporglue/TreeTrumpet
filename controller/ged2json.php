<?php

$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));
$ged2json = model('ged2json',Array($gedcom));

$json = $ged2json->toJsonArray();

view('json',Array('json' => $json));
