<?php

class submitter {
    var $submitter;
    var $pretty_gedcom;

    function __construct($submitter = NULL,$gedcom = NULL){
        if(!is_null($submitter)){
            $this->submitter = $submitter;
        }
        if(!is_null($gedcom)){
            $this->pretty_gedcom = model('pretty_gedcom',$gedcom);
        }
    }

    function contactInfo(){
        if(!isset($this->pretty_gedcom) || !isset($this->submitter)){
            return FALSE;
        }
        return $this->pretty_gedcom->printSubm($this->submitter);
    } 

    function name(){
        if(!isset($this->pretty_gedcom) || !isset($this->submitter)){
            return FALSE;
        }

        if($name = $this->submitter->getName()){
            return $name;
        }
        return FALSE;
    }

    function emailForm(){
        global $_CONFIG;
        $form = model('emailform');
        if($form && $form->enabled()){
            return $form;
        }
        return FALSE;
    }

    function emailAddress(){
        global $_CONFIG;
        if($_CONFIG['show_email_address'] && 
            $_CONFIG['email_address'] != 'example@example.com'
        ){
            return "<a href='mailto:{$_CONFIG['email_address']}'>{$_CONFIG['email_address']}</a>";
        }
        return FALSE;
    }

    function __call($func,$args){
        if(!isset($this->submitter)){
            return FALSE;
        }
        return call_user_func_array(Array($this->submitter,$func),$args);
    }
}
