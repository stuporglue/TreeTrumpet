<?php

$ged2json = model('ged2json',Array(__DIR__ . '/../' . $_GET['g']));

$json = (string)$ged2json;
view('json',Array('json' => $json));
