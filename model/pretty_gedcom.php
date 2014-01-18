<?php

class pretty_gedcom {
    var $parsedgedcom;

    function __construct($parsedgedcom){
        $this->parsedgedcom = $parsedgedcom;
    }

    function findObje($id){
        foreach($this->parsedgedcom->getObje() as $obje){
            if($obje->hasAttribute('id') && $obje->getId() == $id){
                return $obje;
            }
        }
        return FALSE;
    }

    function findFam($id){
        foreach($this->parsedgedcom->getFam() as $fam){
            if($fam->hasAttribute('id') && $fam->getId() == $id){
                return model('family',Array($fam,$this->parsedgedcom));
            }
        }
        return FALSE;
    }

    function findIndi($id){
        foreach($this->parsedgedcom->getIndi() as $indi){
            if($indi->hasAttribute('id') && $indi->getId() == $id){
                return model('individual',Array($indi,$this->parsedgedcom));
            }
        }
        return FALSE;
    }

    function printOrdinance($ord){
        $ret = '';
        $ret .= "<dl>";

        if($stat = $ord->getStat()){
            $ret .= "<dt>Status</dt><dd>$stat</dd>";
        }
        if($date = $ord->getdate()){
            $ret .= "<dt>Date</dt><dd>$date</dd>";
        }
        if($plac = $ord->getPlac()){
            $ret .= "<dt>Place</dt><dd>$plac</dd>";
        }
        if($temp = $ord->getTemp()){
            $ret .= "<dt>Temple</dt><dd>$temp</dd>";
        }
        if($sours = $ord->getSour()){
            $ret .= "<dt>Sources</dt><dd>";
            foreach($sours as $sour){
                $ret .= $this->printSour($sour);
            }
            $ret .= "</dd>";
        }

        if($notes = $ord->getNote()){
            $ret .= "<dt>Note</dt><dd>";
            foreach($notes as $note){
                $ret .= $this->printNote($note);
            }
            $ret .= "</dd>";
        }

        $ret .= "</dl>";
        return $ret;
    }

    function printChan($chan){
        $ret = '';
        $ret .= "<dl>";
        if($date = $chan->getDate()){
            $ret .= "<dt>Date</dt><dd>$date</dd>";
        }
        if($time = $chan->getTime()){
            $ret .= "<dt>Time</dt><dd>$time</dd>";
        }
        if($notes = $chan->getNote()){
            $ret .= "<dt>Notes</dt><dd>";
            foreach($notes as $note){
                $ret .= $this->printNote($note);
            }
            $ret .= "</dd>";
        }
        $ret .= "</dl>";
        return $ret;
    }

    function printFamc($fam,$selfId){
        $ret = '';

        $ret .= "<ul>";

        if($fam->hasAttribute('famc') && $famc = $fam->getFamc()){
            $family = $this->findFam($famc);
            $ret .= "<li><a href='" . $family->link() . "'>" . $family->familyName() . " (Family $famc)</a></li>";
            $cfam = $this->findFam($famc);

            if($cfam && $wife = $cfam->getWife()){ 
                $wife = $this->findIndi($wife);
                $name = "Wife {$wife->getId()}";
                if($realName = $wife->firstBold()){
                    $name = $realName;
                }
                $ret .= "<dt>Mother</dt><dd><a href='" . $wife->link() . "'>$name</a></dd>";
            }

            if($cfam && $husb = $cfam->getHusb()){ 
                $husb = $this->findIndi($husb);
                $name = "Husb {$husb->getId()}";
                if($realName = $husb->firstName()){
                    $name = $realName;
                }
                $ret .= "<dt>Father</dt><dd><a href='" . $husb->link() . "'>$name</a></dd>";
            }

            if($cfam && $chils = $cfam->getChil()){
                $ret .= "<dt>Children</dt><dd><ol>";
                foreach($chils as $chil){
                    $chil = $this->findIndi($chil);
                    $name = "Child {$chil->getId()}";
                    if($realName = $chil->firstName()){
                        $name = $realName;
                    }
                    if($chil->getId() == $selfId){
                        $name .= " (self) ";
                    }
                    $ret .= "<li><a href='" . $chil->link() . "'>$name</a></li>";

                }
                $ret .= "</ol></dd>";
            }
        }

        if($fam->hasAttribute('pedi') && $pedi = $fam->getPedi()){
            $ret .= "HANDLE PEDI";
        }

        if($notes = $fam->getNote()){
            $ret .= "<li><h3>Notes</h3><li>";
            foreach($notes as $note){
                $ret .= $this->printNote($note);
            }
            $ret .= "</li>";
        }

        $ret .= "</ul>";
        return $ret;
    }

    function printFams($fam,$selfId){
        $ret = '';

        $ret .= "<ul>";

        // Turn a family reference into a de-referenced family
        if($fam->hasAttribute('fams') && $fams = $fam->getFams()){
            $family = $this->findFam($fams);
            $ret .= "<li><a href='" . $family->link() . "'>" . $family->familyName() . " (Family $fams)</a><ul>";
            $sfam = $this->findFam($fams);

            if($sfam && $wife = $sfam->getWife()){ 
                $wife = $this->findIndi($wife);
                $name = "Wife {$wife->getId()}";
                if($realName = $wife->firstName()){
                    $name = $realName;
                }
                if($wife->getId() == $selfId){
                    $name .= " (self) ";
                }
                $ret .= "<li><a href='" . $wife->link() . "'>$name</a></li>";
            }

            if($sfam && $husb = $sfam->getHusb()){ 
                $husb = $this->findIndi($husb);
                $name = "Husb {$husb->getId()}";
                if($realName = $husb->firstName()){
                    $name = $realName;
                }
                if($husb->getId() == $selfId){
                    $name .= " (self) ";
                }
                $ret .= "<li><a href='" . $husb->link() . "'>$name</a></li>";
            }

            if($sfam && $chils = $sfam->getChil()){
                $ret .= "<li>Children<ol>";
                foreach($chils as $chil){
                    $chil = $this->findIndi($chil);
                    $name = "Child {$chil->getId()}";
                    if($realName = $chil->firstName()){
                        $name = $realName;
                    }
                    if($chil->getId() == $selfId){
                        $name .= " (self) ";
                    }
                    $ret .= "<li><a href='" . $chil->link() . "'>$name</a></li>";

                }
                $ret .= "</ol></li>";
            }

            $ret .= "</ul></li>";
        }

        if($fam->hasAttribute('pedi') && $pedi = $fam->getPedi()){
            $ret .= "HANDLE PEDI";
        }

        if($notes = $fam->getNote()){
            $ret .= "<li><h3>Notes</h3><li>";
            foreach($notes as $note){
                $ret .= $this->printNote($note);
            }
            $ret .= "</li>";
        }

        $ret .= "</ul>";
        return $ret;
    }

    function printObje($obje){

        if($obje->getIsReference()){
            $obje = $this->pretty_gedcom->findObje($obje->getObje());
        }

        if(get_class($obje) != 'obje'){ 
            $obje = model('obje',Array($obje,$this->parsedgedcom));
        }

        $ret = '';
        $ret .= "<ul>";


        if($obje->hasAttribute('file')){
            $ret .= "<li>";
            $ret .= $obje->embedHtml();
            $ret .= "</li>";
        }else if($obje->hasAttribute('blob') && $blob = $obje->getBlob()){
            $ret .= "<li>Please ask for support for embedded images and in the mean time re-export your GEDCOM with linked images instead.</li>";
        }

        if($notes = $obje->getNote()){
            $ret .= "<li><h3>Notes</h3>";
            foreach($notes as $note){
                $ret .=  $this->printNote($note);
            }
            $ret .= "</li>";
        }

        if($obje->hasAttribute('chan') &&  $chan = $obje->getChan()){
            $ret .= "<li><h3>Changes</h3>";
            $ret .=  $ret .= $this->printChan($chan);
        }

        if($obje->hasAttribute('refn') && $refns = $obje->getRefn()){
            $ret .= "<li><h3>References</h3>";
            foreach($refns as $refn){
                $ret .=  $ret .= $this->printRefn($refn);
            }
            $ret .= "</li>";
        }

        if($obje->hasAttribute('rin') && $rin = $obje->getRin()){
            $ret .= "<li><h3>RIN</h3>$rin</li>";
        }

        $ret .= "</ul>";
        return $ret;
    }

    function printRefn($refn){
        $ret = '';
        $ret .= "<dl>";
        if($refnum = $refn->getRefn()){
            $ret .= "<dt>Reference Number</dt><dd>$refnum</dd>";
        }
        if($type = $refn->getType()){
            $ret .= "<dt>Type</dt><dd>$type</dd>";
        }
        $ret .= "</dl>";
        return $ret;
    }

    // Every function should $ret .= a set of closed and valid nodes
    function printAsso($asso){
        $ret = '';
        $ret .= "<dl>";
        if($id = $asso->getIndi()){
            $ret .= "<dt>Associate ID</dt><dd>$id</dd>";
        }
        if($rela = $asso->getRela()){
            $ret .= "<dt>Relationship</dt><dd>$rela</dd>";
        }
        if($notes = $asso->getNote()){
            $ret .= "<dt>Notes</dt><dd>";
            foreach($notes as $note){
                $ret .= $this->printNote($note);
            }
            $ret .= "</dd>";
        }
        if($sours = $asso->getSour()){
            $ret .= "<dt>Sources</dt><dd>";
            foreach($sours as $sour){
                $ret .=  $ret .= $this->printSour($sour); 
            }
            $ret .= "</dd>";
        }

        $ret .= "</dl>";
        return $ret;
    }

    function printSubm($subm){
        $ret = '';
        if($name = $subm->getName()){
            $ret .= "<div>$name</div><br>";
        }

        if($addr = $subm->getAddr()){ 
            $ret .= $this->printAddr($addr);
        }

        if($phons = $subm->getPhon()){
            foreach($phons as $phon){
                $ret .= $this->printPhon($phon);
            }
        }
        return $ret;
    }

    function printEven($even){
        $ret = '';
        $type = preg_replace("|.*\\\|",'',get_class($even));
        $ret .= "<h3>Type: $type</h3>";
        $ret .= "<div class='event'>";
        $ret .= "<dl>";
        if($even->hasAttribute('famc') && $famc = $even->getFamc()){
            $ret .= "<dt>Family ID</dt><dd>$famc</dd>";
        }
        if($even->hasAttribute('type') && $type = $even->getType()){
            $ret .= "<dt>Type</dt><dd>$type</dd>";
        }
        if($date = $even->getDate()){
            $ret .= "<dt>Date</dt><dd>$date</dd>";
        }
        if($plac = $even->getPlac()){
            $ret .= $this->printPlac($plac);
        }
        if($caus = $even->getCaus()){
            $ret .= "<dt>Cause</dt><dd>$caus</dd>";
        }
        if($age = $even->getAge()){
            $ret .= "<dt>Age</dt><dd>$age</dd>";
        }
        if($addr = $even->getAddr()){
            $ret .= $this->printAddr($addr);
        }
        if($sours = $even->getSour()){
            foreach($sours as $sour){
                $ret .= $this->printSour($sour);
            }
        }
        if($note = $even->getNote()){
            $ret .= $this->printNote($note);
        }
        if($obje = $even->getObje()){
            $ret .= $this->printObje($obje);
        }
        $ret .= "</dl>";
        $ret .= "</div>";
        return $ret;
    }

    function printAddr($addr){
        $ret = '';

        $address = Array();
        if($adr1 = $addr->getAdr1()){ 
            $address[] = $adr1; 
        }
        if($adr2 = $addr->getAdr2()){ 
            $address[] = $adr2; 
        }

        $stateLine = "";
        if($city= $addr->getCity()){ 
            $stateLine .= $city; 
        }

        if($stae = $addr->getStae()){ 
            if($stateLine == ""){
                $stateLine .= $stae;
            }else{
                $stateLine .= ", $stae";
            }
        }
        if($post = $addr->getPost()){ 
            if($stateLine == ""){
                $stateLine .= $post;
            }else{
                $stateLine .= " $post";
            }
        }
        if($stateLine != ""){
            $address[] = $stateLine;
        }

        if($ctry= $addr->getCtry()){ 
            $address[] = $ctry; 
        }

        if(count($address) > 0){
            $ret .= "<address>" . implode("<br>",$address) . "</address>";
        }
        return $ret;
    }

    function printPhon($phon){
        $ret = '';
        if($ph = $phon->getPhon()){
            if(preg_match('/^[0-9+()[]#-\s-]+$/',$ph)){
                $ret .= "<div><a href='tel:$ph'>$ph</a></div>";
            }else{
                $ret .= "<div>$ph</div>";
            }
        }
        return $ret;
    }

    function printPlac($plac){
        $ret = '';
        $ret .= "<dt>Place</dt><dd><p>";
        if($name = $plac->getPlac()){
            $ret .= "$name<br>";
        }
        if($form = $plac->getForm()){
            $ret .= "HANDLE FORM";
        }
        if($notes = $plac->getNote()){
            foreach($notes as $note){
                $ret .= $this->printNote($note);
            }
        }
        if($sours = $plac->getSour()){
            foreach($sours as $sour){
                $ret .= $this->printSour($sour);
            }
        }
        $ret .= "</dd>";
        return $ret;
    }

    function printAttr($attr){
        $ret = '';
        $ret .= "<h3>Type: " . $attr->getType() . "</h3>";

        $ret .= "<dl>";
        if($attrText = $attr->getAttr()){
            $ret .= "<dt>Info</dt><dd>$attrText</dd>";
        }

        if($date = $attr->getDate()){
            $ret .= "<dt>Date</dt><dd>$date</dd>";
        }

        if($plac = $attr->getPlac()){
            $ret .= "<dt>Place</dt><dd>Place</dd>";
        }

        if($caus = $attr->getCaus()){
            $ret .= "<dt>Cause</dt><dd>$caus</dd>";
        }

        if($age = $attr->getAge()){
            $ret .= "<dt>Age</dt><dd>$age</dd>";
        }

        if($addr = $attr->getAddr()){
            $ret .= "<dt>Address</dt><dd>" .  $ret .= $this->printAddr($addr) . "</dd>";
        }

        if($phones = $attr->getPhon()){
            $ret .= "<dt>Phone Number</dt><dd>";
            foreach($phones as $phone){
                $ret .=  $ret .= $this->printPhon($phone);
            }
            $ret .= "</dd>";
        }

        if($agnc = $attr->getAgnc()){
            $ret .= "<dt>Agency</dt><dd>$agnc</dd>";
        }

        if($notes = $attr->getNote()){
            foreach($notes as $note){
                $ret .=  $ret .= $this->printNote($note);
            }
        }
        if($sours = $attr->getSour()){
            foreach($sours as $sour){
                $ret .=  $ret .= $this->printSour($sour);
            }
        }
        if($objes = $attr->getObje()){
            foreach($objes as $obje){
                $ret .=  $ret .= $this->printObje($obje);
            }
        }
        $ret .= "</dl>";
        return $ret;
    }

    function printNote($note){
        $ret = '';
        $ret .= "<div class='note'>";
        //if($ref = $note->getIsRef()){
        //    $ret .= "Reference";
        //}
        if($text = $note->getNote()){
            $ret .= "<p>$text</p>";
        }
        if($sours = $note->getSour()){
            $ret .= "<h4>Sources</h4>";
            foreach($sours as $sour){
                $ret .= $this->printSour($sour);
            }
        }
        $ret .= "</div>";
        return $ret;
    }

    function printSour($sour){
        $ret = '';
        $ret .= "<dl>";
        if($sourid = $sour->getSour()){
            $ret .= "<dt>Source ID</dt><dd>$sourid</dd>";
        }
        if($page = $sour->getPage()){
            $ret .= "<dt>Page</dt><dd>$page</dd>";
        }
        if($even = $sour->getEven()){
            $ret .= "Handle even:";
        }
        if($data = $sour->getData()){
            $ret .= "<dt>Data<dl>";

            if($agnc = $data->getAgnc()){
                $ret .= "Handle AGNC";
            }
            if($date = $data->getDate()){
                $ret .= "<dt>Date</dt><dd>$date</dd>";
            }
            if($text = $data->getText()){
                $ret .= "<dt>Text</dt><dd>$text</dd>";
            }
            if($note = $data->getNote()){
                $ret .= "<dt>Note</dt><dd>$note</dd>";
            }
            $ret .= "</dl></dt>";
        }
        if($quay = $sour->getQuay()){
            $ret .= "<dt>Quay</dt><dd>$quay</dd>";
        }
        if($text = $sour->getText()){
            $ret .= "<dt>Text</dt><dd>$text</dd>";
        }
        if($obje = $sour->getObje()){
            $ret .= $this->printObje($obje);
        }
        if($notes = $sour->getNote()){
            $ret .= "<dt>Notes</dt><dd>";
            foreach($notes as $note){
                $ret .= $this->printNote($note);
            }
            $ret .= "</dd>";
        }
        $ret .= "</dl>";
        return $ret;
    }

    static function parseDateString($string){
        // might be a year!
        $ts = date_create_from_format('Y',$string);
        if($ts === FALSE){
            $ts = strtotime($string);
        }else{
            $ts = $ts->getTimestamp();
        }
        if((int)$ts == 0){ 
            return FALSE; 
        }
        return $ts;
    }
    
    static function parseTimeString($string){
        return strtotime($string);
    }

    static function parseDateTimeString($date,$time){
        $date = pretty_gedcom::parseDateString($date);
        if($date === FALSE){
            return FALSE;
        }
        $time = pretty_gedcom::parseTimeString($time);
        if($time === FALSE){
            return FALSE;
        }

        return mktime(
            date('H',$time),
            date('i',$time),
            date('s',$time),
            date('n',$date),
            date('j',$date),
            date('Y',$date)
        );
    }

    function __call($func,$args){
        return call_user_func_array(Array($this->parsedgedcom,$func),$args);
    }
}
