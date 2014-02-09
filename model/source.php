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

    function getName(){
        if($title = $this->source->getTitl()){
            return $title;
        }
        return "Source " . $this->source->getSour();
    }

    function overview(){
        $overview = "";

        if($auth = $this->source->getAuth()){
            $overview .= "<h3>Author</h3>";
            $overview .= "<ul><li>$auth</li></ul>";
        }

        if($publ = $this->source->getPubl()){
            $overview .= "<h3>Publisher</h3>";
            $overview .= "<p>$publ</p>";
        }

        if($text = $this->source->getText()){
            $overview .= "<h3>Text</h3><p>$text<p>";
        }

        if($abbr = $this->source->getAbbr()){
            $overview .= "<h3>Short Name</h3>";
            $overview .= "<ul><li>$abbr</li></ul>";
        }
    

        if($overview != ''){
            $overview = "<h2 class='blocktitle'>Overview</h2><div id='overview' class='block'>$overview</div>";
        }
        return $overview;
    }

    function data(){
        $data = "TODO: Handle Data";

        $this->source->getData();

        return $data;
    }

    // Notes block
    function notes(){
        $snotes = '';
        if($notes = $this->source->getNote()){
            $snotes .= "<div id='notes' class='block'>";
            foreach($notes as $note){
                $snotes .= $this->pretty_gedcom->printNoteRef($note);
            }
            $snotes .= "</div>";
        }
        if($snotes != ''){
            $snotes = "<h2 class='blocktitle'>Notes</h2>$snotes";
        }
        return $snotes;
    }

    // Multimedia block
    function multimedia()
    {
        $mm = '';
        if($objes = $this->source->getObje()){
            $mm .= "<div id='multimedia'class='block'>";
            foreach($objes as $obje){
                $mm .= $this->pretty_gedcom->printObje($obje);
            }
            $mm .= "</div>";
        }
        if($mm != ''){
            $mm = "<h2 class='blocktitle'>Multimedia</h2>$mm";
        }

        return $mm;
    }

    function metadata(){
        $meta = '';

        if($sour = $this->source->getSour()){
            $meta .= "<dt>ID</dt>";
            $meta .= "<dd>$sour</dd>";
        }

        if($chan = $this->source->getChan()){
            $meta .= "<dt>Last Updated</dt>";
            $meta .= "<dd>$chan</li></dd>";
        }

        if($repo = $this->source->getRepo()){
            $meta .= "<dt>Repository</dt><dd><dl>";
            if($repoid = $repo->getRepo()){
                $fullRepo = $this->pretty_gedcom->findRepo($repoid);
                $meta .= "<dt>Details</dt><dd>" . $this->pretty_gedcom->printRepo($fullRepo) . "</dd>";
            }
            if($calns = $repo->getCaln()){
                $meta .= "<dt>Call Number(s)</dt>";
                foreach($calns as $caln){
                    $meta .= "<dd>$caln</dd>";
                }
            }
            if($notes = $repo->getNote()){
                $meta .= "<dt>Notes</dt>";
                foreach($notes as $note){
                    $meta .= "<dd>" . $this->pretty_gedcom->printNoteRef($note) . "</dd>";
                }
            }
            $meta .= "</dl></dd>";
        }

        if($rin = $this->source->getRin()){
            $meta .= "<dt>RIN</dt>";
            $meta .= "<dd>$rin</dd>";
        }

        if($refns = $this->source->getRefn()){
            $meta .= "<dt>Reference Numbers</dt>";
            foreach($refns as $refn){
                $refline = Array();
                if($refn = $refn->getRefn()){
                    $refline[] = $refn;
                }
                if($type = $refn->getType()){
                    $refline[] = $type;
                }
                $meta .= "<dd>" . implode(" : ",$refline) . "</dd>";
            }
        }

        if($meta != ''){
            $meta = "<h2 class='blocktitle'>Metadata</h2>" . "<div id='metadata' class='block'><dl>$meta</dl></div>";
        }
        return $meta;
    }


    function getIsReference(){
        if(method_exists($this->source,'getIsReference')){
            return $this->source->getIsReference();
        }
        return FALSE;
    }

    function updated(){
        if($chan = $this->source->getChan()){
            return $this->parseDateString($chan);
        }
        return FALSE;
    }

    private function parseDateString($string){
        // might be a year!
        $ts = date_create_from_format('y',$string);
        if($ts === false){
            $ts = strtotime($string);
        }else{
            $ts = $ts->gettimestamp();
        }
        if((int)$ts == 0){ return false; }
        return $ts;
    }

    function __call($func,$args){
        return call_user_func_array(Array($this->source,$func),$args);
    }

    function __get($param){
        return $this->source->$param;
    }
}
