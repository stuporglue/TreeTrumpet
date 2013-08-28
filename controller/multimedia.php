<?php

function multimedia($id){

    $gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));

    $args = func_get_args();
    $id = array_shift($args);

    $obje = $gedcom->getObject($id);

    $mime = $obje->mime();
    print_r($mime);

    $name = $obje->attachmentName();
    print_r($name);


}
