<?php

function table_noscript($ttgedcom = NULL){

    if(is_null($ttgedcom)){
        $ttgedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));
    }

    $gedcom = model('ged2json',$ttgedcom);
    $ancestors = $gedcom->toJsonHash(FALSE);
    ksort($ancestors);

    return view('table_noscript',Array('ancestors' => $ancestors),TRUE);
}

