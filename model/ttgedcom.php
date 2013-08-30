<?php

class ttgedcom {
    var $gedcom;
    var $_individualCache = Array();
    var $_familyCache = Array();



    function __construct($gedcomFile){
        $parser = model('PhpGedcom\Parser');
        $this->gedcom = $parser->parse(__DIR__. '/../family.ged');
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
        if(!array_key_exists($id,$this->_individualCache)){
            foreach($this->gedcom->getIndi() as $individual){
                if($individual->getId() == $id){
                    $indi = model('individual',Array($individual,$this->gedcom));
                    $this->_individualCache[$id] = $indi;
                }
            }
        }
        return $this->_individualCache[$id];
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

    function getFamily($id,$gedcom = NULL){
        if(is_null($gedcom)){
            $gedcom = $this->gedcom;
        }
        foreach($gedcom->getFam() as $family){
            if($family->getId() == $id){
                return model('family',Array($family,$gedcom));
            }
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
        foreach($this->gedcom->getIndi() as $individual){
            $ancestors[$individual->getId()] = model('individual',Array($individual,$this->gedcom)); 
        }

        uasort($ancestors,function($a,$b){
            return $a->alphaName() > $b->alphaName();
        });

        return $ancestors;
    }

    function getSubmitter(){
        if($submitter = $this->gedcom->getSubm()){
            foreach($submitter as $subm){
                return model('submitter',Array($subm,$this->gedcom));
            }
        }
        return FALSE;
    }

    function __call($func,$args){
        return call_user_func_array(Array($this->gedcom,$func),$args);
    }
}
