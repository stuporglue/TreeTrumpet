<?php

global $_CONFIG;

$page = model('page');

$page->css('css/firstrun.css');

$page->title("Welcome To TreeTrumpet");

$page->h1("Welcome To TreeTrumpet");

if(!file_exists(__DIR__ . '/../family.ged')){
    $page->body .= view('firstrun_gedcom',Array(),TRUE);
}else{
    $page->body .= view('firstrun_required_done',Array(),TRUE);
}


$otherSteps = Array();

if(!@file_put_contents(__DIR__ . '/../cache/write_test','Caching enabled!')){
    $otherSteps[] = 'firstrun_sqlite';
}

if(!file_exists(__DIR__ . '/config.php')){
   $otherSteps[] = 'firstrun_customize'; 
}else{
    if((!$_CONFIG['show_email_address'] && !$_CONFIG['show_email_form']) || $_CONFIG['email_address'] == 'example@example.com'){
        $otherSteps[] = 'firstrun_email';
    }
    if($_CONFIG['show_email_form'] && !@include('Mail.php')){
        $otherSteps[] = 'firstrun_email_pear';
    }
    if($_CONFIG['show_email_form'] && ($_CONFIG['smtp_username'] == 'example@example.com' || $_CONFIG['smtp_password'] == 'your_secret_password')){
        $otherSteps[] = 'firstrun_email_form';
    }
}


if(!array_key_exists('ruri',$_GET)){
    $otherSteps[] = 'firstrun_htaccess';
}


if(count($otherSteps) > 0){
    $page->bodyright .= view('firstrun_other',Array(),TRUE);
    foreach($otherSteps as $step){
        $page->bodyright .= view($step,Array(),TRUE);
    }
}

view('page_v_split',Array('page' => $page,'menu' => 'tree'));
