<?php

class geocoder {
    var $ssgeocoder;

    function __construct(){
        $this->ssgeocoder = new ssgeocoder(__DIR__ . '/../cache/geocoding.sqlite3');
    }

    function __call($func,$args = Array()){
        return call_user_func_array(Array($this->ssgeocoder,$func),$args);
    }
}
