<?php

class family {
    var $family;
    var $pretty_gedcom;

    function __construct($family,$gedcom){
        $this->family =  $family;
        $this->pretty_gedcom = model('pretty_gedcom',Array($gedcom));
    }

    function familyName(){
        $familyName = Array();
        if($husbId = $this->family->getHusb()){
            $husb = $this->pretty_gedcom->findIndi($husbId);
            if($husbName = $husb->firstName()){
                $familyName[] = $husbName;
            }
        }

        if($wifeId = $this->family->getWife()){
            $wife = $this->pretty_gedcom->findIndi($wifeId);
            if($wifeName = $wife->firstName()){
                $familyName[] = $wifeName;
            }
        }
        $familyName = implode(' and ',$familyName);
        $familyName = str_replace('/','',$familyName);
        return $familyName;
    }

    function link(){
        global $_BASEURL;
        $url = $_BASEURL . "/family.php/".$this->family->getId()."/";
        $familyName = $this->familyName();
        $familyName = trim(preg_replace("/-+/",'-',preg_replace("/[^a-zA-Z0-9\.]/",'-',$familyName)),'-');
        $familyName = htmlentities($familyName);
        $url .= $familyName;
        return linky(htmlentities($url));
    }

    function parents(){
        $parents = "";

        if($husbId = $this->family->getHusb()){
            if($husb = $this->pretty_gedcom->findIndi($husbId)){
                $parents .= "<dt>Husband</dt><dd>";
                $parents .= "<a href='".$husb->link()."'>".$husb->firstBold()."</a>";
                $parents .= "</dd>";
            }
        }

        if($wifeId = $this->family->getWife()){
            if($wife = $this->pretty_gedcom->findIndi($wifeId)){
                $parents .= "<dt>Wife</dt><dd>";
                $parents .= "<a href='".$wife->link()."'>".$wife->firstBold()."</a>";
                $parents .= "</dd>";
            }
        }

        if($parents != ''){
            $parents = "<h2 class='blocktitle'>Couples</h2><div id='couples' class='block'><dl>" .  $parents . "</dl></div>";
        }

        return $parents;
    }

    function children(){
        $children = "";
        if($chils = $this->family->getChil()){
            if($nchi = $this->family->getNchi()){
                $children .= "<p>$nchi total children in this family</p>";
            }

            $children .= "<ol>";
            foreach($chils as $chilId){
                $chil = $this->pretty_gedcom->findIndi($chilId);
                $name = "Child $chilId";
                if($realName = $chil->firstName()){
                    $name = $realName;
                }
                $children .= "<li><a href='".$chil->link()."'>".$chil->firstBold()."</a></li>";
            }
            $children .= "</ol>";
        }

        if($children != ''){
            $children = "<h2 class='blocktitle'>Children</h2><div id='children' class='block'>" . $children . "</div>";
        }

        return $children;
    }

    function events(){
        $events = "";

        if($evens = $this->family->getEven()){
            foreach($evens as $even){
                $events .= $this->pretty_gedcom->printEven($even);   
            }
        }

        if($events != ''){
            $events = "<h2 class='blocktitle'>Events</h2><div id='events' class='block'>$events</div>";
        }

        return $events;
    }

    function references(){
        $refs = '';
        if($refns = $this->family->getRefn()){
            $refs .= "<h3>References</h3>";
            foreach($refns as $refn){
                $refs .= $this->pretty_gedcom->printRefn($refn);
            }
        }

        // Sources
        if($sours = $this->family->getSour()){
            $refs .= "<h3>Sources</h3>";
            foreach($sours as $sour){
                $refs .= $this->pretty_gedcom->printSour($sour);
            }
        }

        if($refs != ''){
            $refs = "<h2 class='blocktitle'>References and Sources</h2><div id='references' class='block'>$refs</div>";
        }

        return $refs;
    }

    function notes(){
        $noteTxt = '';
        if($notes = $this->family->getNote()){
            foreach($notes as $note){
                $noteTxt .= $this->pretty_gedcom->printNote($note);
            }
        }

        if($noteTxt != ''){
            $noteTxt = "<h2 class='blocktitle'>Notes</h2><div id='notes' class='block'>$noteTxt</div>";
        }

        return $noteTxt;
    }

    function multimedia(){
        $mm = '';
        if($objes = $this->family->getObje()){
            foreach($objes as $obje){
                $mm .= $this->pretty_gedcom->printObje($obje);
            }
        }

        if($mm != ''){
            $mm = "<h2 class='blocktitle'>Multimedia</h2><div id='multimedia' class='block'>$mm</div>";
        }

        return $mm;
    }

    function metadata(){
        $md = '';
        $md .= "<h2 class='blocktitle'>Family Metadata</h2>";
        $md .= "<div id='metadata' class='block'>";

        $md .= "<h3>GEDCOM ID</h3>";
        $md .= "<ul><li>{$this->family->getId()}</li></ul>";

        // Chan
        if($chan = $this->family->getChan()){
            $md .= "<h3>Last Changed</h3>";
            $md .= $this->pretty_gedcom->printChan($chan);
        }

        // Subm
        if($subms = $this->family->getSubm()){
            $md .= "<h3>Who Submitted This Name</h3>";
            $md .= "<ul>";
            foreach($subms as $subm){
                $md .= "<li>$subm</li>";
            }
            $md .= "</ul>";
        } 
        // Rin
        if($rin = $this->family->getRin()){
            $md .= "<h3>RIN</h3>";
            $md .= "<dl><dt>RIN</dt><dd>$rin</dd></dl>";
        }

        $md .= "</div>";

        return $md;
    }

    function ordinances(){
        $ord = "";

        if($slgs = $this->family->getSlgs()){
            $ord .= "<h3>LDS Sealing to Spouse</h3>";
            $ord .= $this->pretty_gedcom->printOrdinance($slgs);
        }

        if($ord != ''){
            $ord = "<h2 class='blocktitle'>LDS Ordinances</h2><div id='ordinances' class='block'>$ord</div>";
        }

        return $ord;
    }

    function __call($func,$args){
        call_user_func_array(Array($this->family,$func),$args);
    }

    function eventsList(){
        $events = Array();

        if($evens = $this->family->getEven()){
            foreach($evens as $even){
                $events[] = $even;
            }
        }
        return $events;
    }
}
