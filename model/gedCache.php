<?php

class gedCache {
    function __construct($gedcom,$cacheFile){
        $parser = new PhpGedcom\Parser();
        if(filemtime($cacheFile) > filemtime($gedcom)){
        }else{
            $parser->parse($gedcom);
        }
    }


    function __get($prop){
        return $this->gedcom;
    }
}
