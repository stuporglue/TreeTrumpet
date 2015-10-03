<?php

function lib($realController){
    $args = func_get_args();
    array_shift($args);
    return controller($realController,$args);
}
