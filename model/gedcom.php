<?php

class gedcom {
    var $gedcom;

    function __construct($gedcomFile){
        $parser = model('PhpGedcom\Parser');
        $this->gedcom = $parser->parse(__DIR__. '/../family.ged');
    }

    function getFocusId(){
        $ids = Array();
        foreach($this->gedcom->getIndi() as $individual){
            $ids[] = $individual->getId();
        }
        return array_shift($ids);
    }

    function getIndividual($id){
        foreach($this->gedcom->getIndi() as $individual){
            if($individual->getId() == $id){
                return model('individual',Array($individual,$this->gedcom));
            }
        }
    }

    function getFamily($id){
        foreach($this->gedcom->getFam() as $family){
            if($family->getId() == $id){
                return model('family',Array($family,$this->gedcom));
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
}
