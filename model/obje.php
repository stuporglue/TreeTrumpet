<?php

class obje {
    var $obje;
    var $pretty_gedcom;

    const THUMB_WIDTH = 250;
    const THUMB_HEIGHT = 250;

    function __construct($obje,$gedcom){
        $this->pretty_gedcom = model('pretty_gedcom',Array($gedcom));

        if($obje->getIsReference()){
            $obje = $this->findObje($obje->getObje());
        }
        $this->obje = $obje;
    }

    function mime($file = NULL){
        $fsfile = obje::fsPath($file);

        // File type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if(file_exists($fsfile) && $mimeType = finfo_file($finfo, $fsfile)){
            return $mimeType;
        }
        return FALSE;
    }

    function embedHtml(){
            $ret = "";

            // File type
            // $mime = `file -bi $file  | sed 's/;.*//'`;
            if($mimeType = $this->mime()){

                $browserImages = Array('image/jpeg','image/gif','image/png');
                $browserVideos = Array('video/mp4','video/webm','video/ogg');
                $browserAudio = Array('audio/mpeg','audio/ogg','audio/wav','audio/x-wav');

                $ret .= $this->link();
                if(in_array($mimeType,$browserImages)){
                    $ret .= $this->halfLink() . "<img class='ttthumbnail' width='".obje::THUMB_WIDTH."px' alt='".$this->title()."' src='".$this->thumbnail()."'/></a>";
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
            return FALSE; 
        }

        // Link to link
        if(filter_var($file,FILTER_VALIDATE_URL)){
            return $file;
        }
    
        $file = $this->webPath();

        if(file_exists(__DIR__ . '/../' . $file)){
            return $_BASEURL . '/' . $file;
        }

        return FALSE;
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
        $file = $this->webPath();

        $qs = Array(
            'h' => obje::THUMB_HEIGHT,
            'w' => obje::THUMB_WIDTH
        );

        $file .= "?" . http_build_query($qs);

        return $file;
    }

    function webPath($file = NULL){
        global $_BASEURL;

        $file = obje::relPath($file);
        $file = linky($_BASEURL . "/multimedia.php/$file");

        return $file;
    }

    function relPath($file = NULL){
        global $_CONFIG;

        if(is_null($file)){
            $file = $this->obje->getFile();
        }

        if(!$_CONFIG['media_dir']){
            return $file;
        }

        if(strpos($file,$_CONFIG['media_dir']) === 0){
            $file = str_replace($_CONFIG['media_dir'],'',$file);
        }

        return $file;
    }

    function fsPath($file = NULL){
        $file = obje::relPath($file);
        return __DIR__ . '/../media/' . $file;
    }

    function attachmentName(){
        return basename($this->webPath());
    }

    function readfile($basefile = NULL,$h = NULL,$w = NULL,$attachment = FALSE){
        $mime = obje::mime($basefile);
        $file = obje::fsPath($basefile);
        $attachmentName = basename($file);

        if(!file_exists($file)){
            return controller('_404');
        }

        if(!is_null($h) || !is_null($w)){
            switch($mime){
                case 'image/jpeg':
                    $img = imagecreatefromjpeg($file);
                    $outfunc = 'imagejpeg';
                    break;
                case 'image/png':
                    $img = imagecreatefrompng($file);
                    $outfunc = 'imagepng';
                    break;
                case 'image/gif':
                    $img = imagecreatefromgif($file);
                    $outfunc = 'imagegif';
                    break;
            }

            $origwidth = imagesx( $img );
            $origheight = imagesy( $img );

            if(!is_null($h)){
                $newheight = $h;
            }else{
                $newheight = $origheight;
            }

            if(!is_null($w)){
                $newwidth = $w;
            }else{
                $newwidth = $origheight;
            }

            $cachePath = __DIR__ . "/../cache/{$newheight}x{$newwidth}/" . obje::relPath($basefile);

            if(file_exists($cachePath)){
                $file = $cachePath;
            }else{
                $tmpimg = imagecreatetruecolor($newwidth,$newheight);
                imagecopyresampled($tmpimg,$img,0,0,0,0,$newwidth,$newheight,$origwidth,$origheight);

                $dir = dirname($cachePath);
                @umask(0022);
                if(!is_dir($dir)){
                    @mkdir($dir,0775,TRUE);
                }
                if(is_dir($dir)){
                    $outfunc($tmpimg,$cachePath);
                }

                if(file_exists($cachePath)){
                    $file = $cachePath;
                }
            }
        }

        header("Content-Description: File Transfer");
        header("Content-type: $mime");
        header("Content-Length: " . filesize($file));
        if($attachment){
            header("Content-disposition: attachment; filename=$attachmentName");
        }

        return readfile($file);
    }


    function __call($func,$args){
        return call_user_func_array(Array($this->obje,$func),$args);
    }

    function __get($param){
        return $this->obje->$param;
    }
}
