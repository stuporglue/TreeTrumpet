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
    var $footer = "Create your own personal genealogy website like this one with <a href='https://github.com/stuporglue/TreeTrumpet'>TreeTrumpet</a>.";
    var $js = Array();
    var $inlinejs = Array();
    var $h1 = '';
    var $body = '';
    var $bodyright = '';
    var $hidden = '';
    var $head = '';
    var $canonical = '';
    var $description = '';
    var $keywords = Array('genealogy');
    var $author = '';
    var $copyright = '';

    function canonical($can = FALSE){
        if($can){
            $this->canonical = $can;
            return $this->canonical;
        }else if($this->canonical != ''){
            return $this->canonical;
        }else{
            return FALSE;
        }
    }

    function description($desc = FALSE){
        if($desc){
            $this->description = $desc;
            return $this->description;
        }else if($this->description != ''){
            return $this->description;
        }else{
            return FALSE;
        }
    }

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

    function cacheLocally($url,$type){
        global $_BASEURL,$_CONFIG;

        if(strlen(filter_var($url,FILTER_VALIDATE_URL)) === 0 && strpos('//',$url) === 0){
            $url = "$_BASEURL/$url";
        } else if($_CONFIG['cache_resources']){
            $cacheDir = __DIR__ . "/../cache/$type/3rdparty/";
            @mkdir($cacheDir,0775,TRUE);
            $downloadedFile = $cacheDir . basename($url);

            if(!file_exists($downloadedFile)){
                if($urlcont = file_get_contents($url)){
                    $res = file_put_contents($downloadedFile,$urlcont);
                }
            }

            if(file_exists($downloadedFile)){
                $basename = basename($url);
                $url = "$_BASEURL/cache/$type/3rdparty/" . basename($url);
            }
        }

        return $url;
    }

    function printCss(){
        $cssstr = "";
        foreach($this->css as $css => $media){
            $css = $this->cacheLocally($css,'css');
            $cssstr .= "<link type='text/css' href='$css' rel='stylesheet' media='$media'/>";
        }
        foreach($this->conditionalCss as $css => $if){
            $css = $this->cacheLocally($css,'css');
            $cssstr .= "<!--[$if]><link type='text/css' href='$css' rel='stylesheet'/><![endif]-->";
        }
        return $cssstr;
    }

    function printJs(){
        $jsstr = "";
        foreach($this->js as $js){
            $js = $this->cacheLocally($js,'js');
            $jsstr .= "<script type='text/javascript' src='$js'></script>";
        }
        foreach($this->inlinejs as $js){
            $jsstr .= "<script type='text/javascript'><!--\n$js\n--></script>";
        }
        return $jsstr;
    }
}
