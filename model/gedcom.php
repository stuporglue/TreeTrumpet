<?php

class gedcom {
    var $gedcom;

    function __construct($gedcomFile){
        $parser = model('PhpGedcom\Parser');
        $this->gedcom = $parser->parse(__DIR__. '/../family.ged');
    }

    function getIndividual($id){
        foreach($this->gedcom->getIndi() as $individual){
            if($individual->getId() == $id){
                return model('individual',Array($individual,$this->gedcom));
            }
        }
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
}
