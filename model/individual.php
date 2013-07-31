<?php

// Should this be a view? I'm really not sure. It's a class that prints out nicely rendered chunks of an individual
class individual {
    var $individual;
    var $pretty_gedcom;

    function __construct($individual,$gedcom){
        $this->individual = $individual;
        $this->pretty_gedcom = model('pretty_gedcom',Array($gedcom));
    }

    function firstName(){
        $firstName;
        if($names = $this->individual->getName()){
            $firstName = $names[0]->getName();
        }
        return $firstName;
    }

    function firstBold(){
        $firstBold;
        if($names = $this->individual->getName()){
            $firstName = $names[0]->getName();
            $firstBold = preg_replace('|/(.*)/|',"<span class='ttln'>$1</span>",$firstName);
        }
        return $firstBold;
    }

    function link(){
        global $_BASEURL;
        $url = $_BASEURL . "/individual.php/".$this->individual->getId()."/";
        $firstName = $this->firstName();
        $firstName = trim(preg_replace("/-+/",'-',preg_replace("/[^a-zA-Z0-9\.]/",'-',$firstName)),'-');
        $firstName = htmlentities($firstName);
        $url .= $firstName;
        return linky(htmlentities($url));
    } 

    function posessive(){
        switch($this->individual->getSex()){
        case 'M':
            return 'his';
            break;
        case 'F':
            return 'her';
            break;
        default:
            return 'their';
            break;
        }

    }

    // Overview
    function overview() {
        $overview = "";

        if($names = $this->individual->getName()){
            $overview .= "<h3>Names</h3>";
            $overview .= "<ul>";
            foreach($names as $name){
                $overview .= "<li>" . $name->getName() . "</li>";
            }
            $overview .= "</ul>";
        }

        if($sex = $this->individual->getSex()){
            $overview .= "<h3>Gender</h3>";
            switch($sex){
            case 'M':
                $overview .= "Male";
                break;
            case 'F':
                $overview .= "Female";
                break;
            default:
                $overview .= "Unknown ($sex)";
            }
        }

        // getAlia
        if($aliases = $this->individual->getAlia()){
            $overview .= "<h3>Possible Duplicates</h3>";
            $overview .= "<ul>";
            foreach($aliases as $alias){
                $overview .= "<li><a href='individual.php?id=$alias'>$alias</a></li>";
            }
            $overview .= "</ul>";
        }

        if($overview != ''){
            $overview = "<h2 id='overview'>Overview</h2><div class='block'>$overview</div>";
        }

        return $overview;
    }

    // Attributes
    function attributes()
    {
        $attributes = "";

        if($attrs = $this->individual->getAttr()){
            foreach($attrs as $attr){
                $attributes .= "<div class='block'>";
                $attributes .= $this->pretty_gedcom->printAttr($attr);
                $attributes .= "</div>";
            }
        }

        if($attributes != ''){
            $attributes = "<h2 id='attributes'>Attributes</h2>$attributes";
        }

        return $attributes;
    }

    // Parents
    function parents()
    {
        $parents = "";

        if($fams = $this->individual->getFamc()){
            $parents .= "<div class='block'>";
            foreach($fams as $famc){
                $parents .= $this->pretty_gedcom->printFamc($famc,$this->individual->getId());
            }
            $parents .= "</div>";
        }

        if($parents .= ''){
            $parents = "<h2 id='parents'>Parents</h2>$parents";
        }
        return $parents;
    }

    // spouse and kids
    function spouseAndKids()
    {
        $spak = '';

        if($fams = $this->individual->getFams()){
            $spak .= "<div class='block'>";
            foreach($fams as $famc){
                $spak .= $this->pretty_gedcom->printFams($famc,$this->individual->getId());
            }
            $spak .= "</div>";
        }
        if($spak != ''){
            $spak = "<h2 id='spouses'>Spouses and Children</h2>$spak";
        }

        return $spak;
    }

    // Events block
    function events()
    {
        $events = '';
        if($evens = $this->individual->getEven()){
            $events .= "<div class='block'>";
            foreach($evens as $even){
                $events .= $this->pretty_gedcom->printEven($even);   
            }
            $events .= "</div>";
        }

        if($events != ''){
            $events = "<h2 id='events'>Events in the life of ".$this->firstBold()."</h2>$events";
        }
        return $events;
    }

    // Associates Block
    function associates()
    {
        $associates = '';
        if($assos = $this->individual->getAsso()){
            $associates .= "<div class='block'>";
            foreach($assos as $asso){
                $associates .= $this->pretty_gedcom->printAsso($asso);
            }
            $associates .= "</div>";
        }

        if($associates != ''){
            $associates = "<h2 id='associates'>Associates of ".$this->firstBold()."</h2>$associates";
        }

        return $associates;
    }

    // Notes block
    function notes()
    {
        $snotes = '';
        if($notes = $this->individual->getNote()){
            $snotes .= "<div class='block'>";
            foreach($notes as $note){
                $snotes .= $this->pretty_gedcom->printNote($note);
            }
            $snotes .= "</div>";
        }
        if($snotes != ''){
            $snotes = "<h2 id='notes'>Notes</h2>$snotes";
        }
        return $snotes;
    }

    // References and sources block
    function references()
    {
        $refs = '';
        if($refns = $this->individual->getRefn()){
            $refs .= "<h3>References</h3>";
            foreach($refns as $refn){
                $refs .= $this->pretty_gedcom->printRefn($refn);
            }
        }

        // Sources
        if($sours = $this->individual->getSour()){
            $refs .= "<h3>Sources</h3>";
            foreach($sours as $sour){
                $refs .= $this->pretty_gedcom->printSour($sour);
            }
        }

        if($refs != ''){
            $refs = "<h2 id='references'>References and Sources</h2><div class='block'>$refs</div>";
        }
        return $refs;
    }

    // Multimedia block
    function multimedia()
    {
        $mm = '';
        if($objes = $this->individual->getObje()){
            $mm .= "<div class='block'>";
            foreach($objes as $obje){
                $mm .= $this->pretty_gedcom->printObje($obje);
            }
            $mm .= "</div>";
        }
        if($mm != ''){
            $mm = "<h2 id='multimedia'>Multimedia</h2>$mm";
        }

        return $mm;
    }

    // Ancestor Metadata
    function metadata()
    {
        $meta = '';
        $meta .=  "<h2 id='metadata'>Metadata</h2>";
        $meta .=  "<div class='block'>";

        // GEDCOM ID
        $meta .=  "<h3>GEDCOM ID</h3>";
        $meta .=  "<ul><li>{$this->individual->getId()}</li></ul>";

        // Anci
        if($ancis = $this->individual->getAnci()){
            $meta .=  "<h3>Interest in This Ancestor</h3>";
            $meta .=  "<ul>";
            foreach($ancis as $anci){
                $meta .=  "<li>$anci</li>";
            }
            $meta .=  "</ul>";
        }

        // Desi
        if($descs = $this->individual->getDesi()){
            $meta .=  "<h3>Interest in This Descendant</h3>";
            $meta .=  "<ul>";
            foreach($descs as $desc){
                $meta .=  "<li>$desc</li>";
            }
            $meta .=  "</ul>";
        }

        // Subm
        if($subms = $this->individual->getSubm()){
            $meta .=  "<h3>Who Submitted This Name</h3>";
            $meta .=  "<ul>";
            foreach($subms as $subm){
                $meta .=  "<li>$subm</li>";
            }
            $meta .=  "</ul>";
        } 

        // Resn
        if($resns = $this->individual->getResn()){
            $meta .=  "<h3>Restricted!</h3>";
            $meta .=  "<dl><dt>Reason</dt><dd>$resns</dd></dl>";
        } 

        // Rfn
        if($rfns = $this->individual->getRfn()){
            $meta .=  "<h3>Record File Number</h3>";
            $meta .=  "<dl><dt>RFN</dt><dd>$rfns</dd></dl>";
        }

        // Afn
        if($afn = $this->individual->getAfn()){
            $meta .=  "<h3>Ancestor File Number";
            $meta .=  "<dl><dt>AFN</dt><dd>$afn</dd></dl>";
        }

        // Chan
        if($chan = $this->individual->getChan()){
            $meta .=  "<h3>Last Changed</h3>";
            $meta .= $this->pretty_gedcom->printChan($chan);
        }

        // Rin
        if($rin = $this->individual->getRin()){
            $meta .=  "<h3>RIN</h3>";
            $meta .=  "<dl><dt>RIN</dt><dd>$rin</dd></dl>";
        }
        $meta .=  "</div>";

        return $meta;
    }

    // Ordinances Block
    function ordinances()
    {
        $ord = "";
        // Bapl
        if($bapl = $this->individual->getBapl()){
            $ord .= "<h3>LDS Baptism</h3>";
            $ord .= $this->pretty_gedcom->printOrdinance($bapl);
        }

        // conl
        if($conl = $this->individual->getConl()){
            $ord .= "<h3>LDS Confirmation</h3>";
            $ord .= $this->pretty_gedcom->printOrdinance($conl);
        }

        // endl
        if($endl = $this->individual->getEndl()){
            $ord .= "<h3>LDS Endowment</h3>";
            $ord .= $this->pretty_gedcom->printOrdinance($endl);
        }

        // slgc
        if($slgc = $this->individual->getSlgc()){
            $ord .= "<h3>LDS Sealing To Parents</h3>";
            $ord .= $this->pretty_gedcom->printOrdinance($endl);
        }

        if($ord != ''){
            $ord = "<h2 id='ordinances'>LDS Ordinances</h2><div class='block'>$ord</div>";
        }
        return $ord;
    }

}
