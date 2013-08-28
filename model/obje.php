<?php

class obje {
    var $obje;
    var $pretty_gedcom;

    function __construct($obje,$gedcom){
        $this->pretty_gedcom = model('pretty_gedcom',Array($gedcom));

        if($obje->getIsReference()){
            $obje = $this->findObje($obje->getObje());
        }
        $this->obje = $obje;
    }

    function mime(){
        $mediafile = $this->webPath();
        $fsfile = __DIR__ . '/../' . $mediafile;

        // File type
        // $mime = `file -bi $file  | sed 's/;.*//'`;
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if(file_exists($fsfile) && $mimeType = finfo_file($finfo, $fsfile)){
            return $mimeType;
        }
        return FALSE;
    }

    function embedHtml(){
            $ret = "";

            $file = $this->obje->getFile();
            $mediafile = $this->webPath();
            $fsfile = __DIR__ . '/../' . $mediafile;

            // File type
            // $mime = `file -bi $file  | sed 's/;.*//'`;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if(file_exists($fsfile) && $mimeType = finfo_file($finfo, $fsfile)){
            if($mimeType = $this->mime()){

                $browserImages = Array('image/jpeg','image/gif','image/png');
                $browserVideos = Array('video/mp4','video/webm','video/ogg');
                $browserAudio = Array('audio/mpeg','audio/ogg','audio/wav','audio/x-wav');

                $ret .= $this->link();
                if(in_array($mimeType,$browserImages)){
                    $ret .= $this->halfLink() . "<img alt='".$this->title()."' src='".$this->thumbnail()."'/></a>";
                }else if(in_array($mimeType,$browserAudio)){
                    $ret .= "<audio controls><source src='$file' type='$mimeType'>Your browser does not support the HTML5 audio element.</audio>";
                }else if(in_array($mimeType,$browserVideos)){
                    $ret .= "<video height='400' controls><source src='".$this->link()."' type='$mimeType'>Your browser does not support the HTML5 video tag.</video>";
                } else {
                    $ret .= $this->title() . "</a>";
                }


                // URL type
            }else if(filter_var($file,FILTER_VALIDATE_URL)){
                $ret .= $this->link();
                // Unknown
            }else{
                $form = $this->obje->getForm();

                if(!$form){
                    $form = 'file';
                }
                if($titl = $this->obje->getTitl()){
                    $titl = ", titled <em>$titl</em>";
                }
                $ret .= "Oh no! This $form$titl can't be found. Please ask the owner of this website to upload it or fix the link!";
            }

        return $ret;
    }

    function href(){
        global $_BASEURL;

        $file = $this->obje->getFile();

        // link to nowhere
        if(!$file){
            return '#'; 
        }

        // Link to link
        if(filter_var($file,FILTER_VALIDATE_URL)){
            return $file;
        }
    
        $file = $this->webPath();

        if(file_exists(__DIR__ . '/../' . $file)){
            return $_BASEURL . '/' . $file;
        }

        return '#';
    }

    function link(){
        return $this->halfLink() . $this->title() . "</a>";
    }

    // Return the opening <a> tag
    function halfLink(){
        return "<a alt='" . $this->title() . "' title='" . $this->title() . "' href='" . $this->href() . "'>";
    }

    // Return a suitable title for link text or an alt-tag
    function title(){
        if($titl = $this->obje->getTitl()){
            return htmlentities($titl);
        }else{
            return $this->webPath();
        }
    }

    function updated($fallback = TRUE){
        // Do objects have modified times?

        // Default to filemtime
        if($fallback){
            return filemtime(__DIR__ . '/../family.ged');
        }else{
            return FALSE;
        }
    }

    function thumbnail(){
        return $this->href();
    }

    function webPath(){
        global $_CONFIG;

        if(!$_CONFIG['media_dir']){
            return;
        }

        $file = $this->obje->getFile();

        if(strpos($file,$_CONFIG['media_dir']) === 0){
            $file = str_replace($_CONFIG['media_dir'],'',$file);
        }

        return "/media/$file";
    }

    function attachmentName(){
        return $this->obje->getId() . '_' . basename($this->webPath());
    }

    function __call($func,$args){
        return call_user_func_array(Array($this->obje,$func),$args);
    }

    function __get($param){
        return $this->obje->$param;
    }
}
