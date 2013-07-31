<?php

// Prep the page pieces for printing

// Parts:
// * HTML Head
// * Navigation Menu
// * HTML Footer

class page{
    var $title = "My Family Tree";
    var $css = Array();
    var $conditionalCss = Array();
    var $footer = "Create your personal genealogy website like this one with <a href='http://treetrumpet.com'>TreeTrumpet</a>.";
    var $js = Array();
    var $inlinejs = Array();
    var $h1 = '';
    var $body = '';
    var $hidden = '';
    var $head = '';

    function title($title){
        $this->title = $title;
    }

    function css($url,$media = 'all',$if = NULL){
        if(is_null($if)){
            $this->css[$url] = $media; 
        }else{
            $conditionalCss[$url] = $if;
        }
    }

    function js($url,$inline = FALSE){
        if($inline){
            $this->inlinejs[] = $url; 
        }else{
            $this->js[] = $url; 
        }
    }

    function footer($footer){
        $this->footer = $footer;
    }

    function h1($h1){
        $this->h1 = $h1;
    }

    function body($body){
        $this->body = $body;
    }

    function hidden($hidden){
        $this->hidden = $hidden;
    }

    function head($head){
        $this->head = $head;
    }

    function printCss(){
        global $_BASEURL;
        $cssstr = "";
        foreach($this->css as $css => $media){

            if(preg_match('/^[a-zA-Z0-9]/',$css)){
                $css = "$_BASEURL/$css";
            }

            $cssstr .= "<link type='text/css' href='$css' rel='stylesheet' media='$media'/>";
        }
        foreach($this->conditionalCss as $css => $if){
            $cssstr .= "<!--[$if]><link type='text/css' href='$css' rel='stylesheet'/><![endif]-->";
        }
        return $cssstr;
    }

    function printJs(){
        $jsstr = "";
        foreach($this->js as $js){
            $jsstr .= "<script type='text/javascript' src='$js'></script>";
        }
        foreach($this->inlinejs as $js){
            $jsstr .= "<script type='text/javascript'><!--\n$js\n--></script>";
        }
        return $jsstr;
    }
}
