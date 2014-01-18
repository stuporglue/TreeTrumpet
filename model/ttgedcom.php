<?php

class ttgedcom {
    var $gedcom;
    var $_individualCache = Array();
    var $_familyCache = Array();

    function __construct($gedcomFile){
        if(is_string($gedcomFile)){
            $parser = model('PhpGedcom\ParserSqlite',Array(NULL,__DIR__ . '/../cache/family.ged.sqlite3'));
            $this->gedcom = $parser->parse(__DIR__. '/../family.ged');
        }else if(is_object($gedcomFile)){
            $this->gedcom = $gedcomFile;
        }
    }

    function updated(){
        return filemtime(__DIR__ . '/../family.ged');
    }

    function getFocusId(){
        global $_CONFIG;

        if($_CONFIG['focus_person_id'] && $this->getIndividual($_CONFIG['focus_person_id'])){
            return $_CONFIG['focus_person_id'];
        }

        foreach($this->gedcom->getIndi() as $individual){
            return $individual->getId();
        }
    }

    function getIndividual($id){
        $indis = $this->gedcom->getIndi();
        if(isset($id,$indis)){
            return model('individual',Array($indis[$id],$this->gedcom));
        }
        return FALSE;
    }

    /**
        @brief Keep returning individual objects until we've visited them all
     */
    function nextIndividual(){
        if(!isset($this->_indiIDs)){
            $this->_indiIDs = $this->gedcom->getIndi(); 
        }
        $cur = current($this->_indiIDs);
        if($cur === FALSE){
            unset($this->_indiIDs);
            return FALSE;
        }
        next($this->_indiIDs);
        return $this->getIndividual($cur->getId());
    }

    /**
        @brief Keep returning family objects until we've visited them all
     */
    function nextFamily(){
        if(!isset($this->_familyIDs)){
            $this->_familyIDs = $this->gedcom->getFam(); 
        }
        $cur = current($this->_familyIDs);
        if($cur === FALSE){
            unset($this->_familyIDs);
            return FALSE;
        }
        next($this->_familyIDs);
        return $this->getFamily($cur->getId());
    }

    /**
        @brief Keep returning objects until we've visited them all
     */
    function nextObje(){
        if(!isset($This->_objectIDs)){
            $this->_objectIDs = $this->gedcom->getObje();
        }
        $cur = current($this->_objectIDs);
        if($cur === FALSE){
            unset($this->_objectIDs);
        }
        next($this->_familyIDs);
        return $this->getObject($cur->getId());
    }

    function getFamily($id){
        $fams = $this->gedcom->getFam();
        if(isset($fams[$id])){
            return model('family',Array($fams[$id],$this->gedcom));
        }
        return FALSE;
    }

    static function getStaticFamily($id,$gedcom){
        $fams = $gedcom->getFam();
        if(isset($fams[$id])){
            return model('family',Array($fams[$id],$gedcom));
        }
        return FALSE;
    }

    function getSource($id){
        $sources = $this->gedcom->getSour(); 
    }

    function getObject($id,$gedcom = NULL){
        if(is_null($gedcom)){
            $gedcom = $this->gedcom;
        }
        foreach($gedcom->getObject() as $obje){
            if($obje->getId() == $id){
                return model('obje',Array($obje,$gedcom));
            }
        }
        return FALSE;
    }

    function lastUpdated(){
        $updatedAt;
        $head = $this->gedcom->getHead();
        if($head){
            $date = $head->getDate();
            if($date){
                $updatedAt = $date->getDate(); 
            }
        }
        if(!isset($updatedAt)){
            $updatedAt = date('Y-m-d',filemtime(__DIR__ . '/../family.ged'));
        }
        return $updatedAt;
    }

    function createdBy(){
        $createdBy;
        foreach($parsedgedcom->getSubm() as $subm){
            $createdBy = $subm->getName();
        }
        if(!isset($createdBy)){
            global $_CONFIG;
            if(array_key_exists('email_address',$_CONFIG)){
                $createdBy = $_CONFIG['email_address'];
            }
        }
        return $createdBy;
    }

    function alphabeticByName(){
        $ancestors = Array();
        $indis = $this->gedcom->getIndi();

        foreach($indis as $individual){
            if($names = $individual->getName()){
                $name = $names[0]->getName();
                $ancestors[$individual->getId()] = trim(preg_replace('/[^a-zA-Z ]/','',$name));
            }else{
                $ancestors[$individual->getId()] = '';
            }
        }

        asort($ancestors);
        return $ancestors;
    }

    function getSubmitter(){
        if($submitter = $this->gedcom->getSubm()){
            foreach($submitter as $subm){
                return model('submitter',Array($subm,$this->gedcom));
            }
        }
        return model('submitter');
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

    public function json($id){
        $indi = $this->getIndividual($id);

        $json = Array();
        $json['id'] = $indi->getId();
        if($names = $indi->getName()){
            $name = $names[0];
            $json['name'] = $name->getName();
            foreach($names as $name){
                $json['names'][] = $name->getName();
            }
        }

        // Set gender (M|F) or default to U
        $sex = $indi->getSex();
        if(is_null($sex)){
            $sex = 'U';
        }
        $json['s'] = $sex;

        //  Loop through person events
        foreach($indi->getEven() as $event){
            $even = Array();
            $even['type'] = $event->getType();
            if($d = $event->getDate()){
                $even['date']['raw'] = $d;
            }
            if($ep = $event->getPlac()){
                if($place = $ep->getPlac()){
                    $even['place']['raw'] = $place;
                }
            }
            $json['events'][] = $even;
        }

        // Find my children and spouse
        foreach($indi->getFams() as $fams){
            $family = $this->getFamily($fams->getFams());

            $wifeId = $family->getWife();
            $husbId = $family->getHusb();
            if($wifeId && $husbId){
                if($wifeId != $indi->getId()){
                    $json['wife'] = $wifeId;
                }else if($husbId != $indi->getId()){
                    $json['husb'] = $husbId;
                }
            }

            foreach($family->getChil() as $childId){
                $json['children'][$childId] = $husbId;
            }

            foreach($family->getEven() as $event){
                $parsedEvent = $this->parseEvent($event);
                $json['events'][] = $parsedEvent;
            }
        }

        // Find my parents
        foreach($indi->getFamc() as $famc){
            $family = $this->getFamily($famc->getFamc());
            if($motherId = $family->getWife()){
                $json['mothers'][] = $motherId;
            }
            if($fatherId = $family->getHusb()){
                $json['fathers'][] = $fatherId;
            }
        }


        foreach($json['events'] as $k => $even){
            if(array_key_exists('date',$even)){
                $pd = $this->parseDateString($d);
                if($pd !== FALSE){
                    $even['date']['y'] = date('Y',$pd);
                    $month = date('m',$pd) - 1;
                    $month = ($month < 0 ? 12 : $month);
                    $even['date']['m'] = $month; 
                    $even['date']['d'] = date('d',$pd); 
                    if(!array_key_exists('refdate',$json)){
                        $json['refdate'] = $pd;
                    }
                }
            }

            if(!array_key_exists('refPlace',$json) && 
                array_key_exists('place',$even)){
                $even['refplace'] = $even['place'];
            }
        }

        usort($json['events'],Array('ged2json','eventUsort'));

        return $json;
    }

    function allEvents(){
        $events = Array();
        foreach($this->gedcom->getFam() as $id => $fam){
            if($evens = $fam->getEven()){
                foreach($evens as $even){
                    if($string = $even->getDate()){
                        if($date = $this->parseDateString($string)){
                            $events[$date][$even->getType()]['fam'][] = $id;
                        }
                    }
                }
            }
        }
        foreach($this->gedcom->getIndi() as $id => $indi){
            if($evens = $indi->getEven()){
                foreach($evens as $even){
                    if($string = $even->getDate()){
                        if($date = $this->parseDateString($string)){
                            $events[$date][$even->getType()]['indi'][] = $id;
                        }
                    }
                }
            }
        }

        ksort($events);

        return $events;
    }

    /**
     * @brief callback function for sorting an array of events
     *
     * @return 0,1,-1
     */
    protected function eventUsort($refa,$refb){
            foreach(Array('y','m','d') as $sortKey){
                if(!array_key_exists($sortKey,$refa) && !array_key_exists($sortKey,$refb)){
                    return 0;
                }

                if(!array_key_exists($sortKey,$refa)){
                    return -1;
                }

                if(!array_key_exists($sortKey,$refb)){
                    return 1;
                }

                $diff = $refa[$sortKey] - $refb[$sortKey];

                if($diff !== 0){
                    return $diff;
                }
            }

            return 0;
    }

    function __call($func,$args){
        return call_user_func_array(Array($this->gedcom,$func),$args);
    }
}
