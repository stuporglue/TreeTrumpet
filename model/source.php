<?php

class source {
    var $source;
    var $gedcom;

    function __construct($source,$gedcom,$pretty_gedcom = NULL){
        $this->source = $source;
        $this->gedcom = $gedcom;
        if(!is_null($pretty_gedcom)){
            $this->pretty_gedcom = $pretty_gedcom;
        }else{
            $this->pretty_gedcom = model('pretty_gedcom',$gedcom);
        }
    }

    function link(){
        global $_BASEURL;
        $url = $_BASEURL . "/source.php/".$this->source->getSour()."/";
        $url .= linkPath($this->source->getTitl());
        return linky(htmlentities($url));
    }

    function __call($func,$args){
        return call_user_func_array(Array($this->source,$func),$args);
    }

    function __get($param){
        return $this->source->$param;
    }
}
