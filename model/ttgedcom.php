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

    function __call($func,$args){
        return call_user_func_array(Array($this->gedcom,$func),$args);
    }
}
