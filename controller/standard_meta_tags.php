<?php

global $_BASEURL;

function standard_meta_tags(&$gedcom,&$page){
    if(isset($gedcom) && isset($page)){
        if($submitter = $gedcom->getSubmitter()){
            $page->author = $submitter->name();
        }
        if($head = $gedcom->getHead()){
            $page->copyright = $head->getCopr();
        }
    }
}

