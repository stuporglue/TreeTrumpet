<?php

$gedfile = __DIR__ . '/../' . $_GET['g'];
$ged2json = model('ged2json',Array($gedfile));

$json = $ged2json->toJsonArray();

view('json',Array('json' => $json));
