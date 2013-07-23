<?php

function table_noscript(){
    $gedcom = model('ged2json',Array(__DIR__ . '/../family.ged'));
    $ancestors = $gedcom->toJsonHash(FALSE);
    ksort($ancestors);


    return view('table_noscript',Array('ancestors' => $ancestors),TRUE);
}

