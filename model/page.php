<?php

// Prep the page pieces for printing

// Parts:
// * HTML Head
// * Navigation Menu
// * HTML Footer

class page{
    var $title = "My Family Tree";
    var $css = Array();
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

    function css($url,$media = 'all'){
       $this->css[$url] = $media; 
    }

    function js($url,$inline = FALSE){
        if($inline){
            $this->inline[] = $url; 
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
}
