<?php

$page = model('page');

$page->css("$_BASEURL/css/tt.css");
$page->title("Welcome to TreeTrumpet");

$page->h1("TreeTrumpet");

$page->body .= "<p>Thanks for installing TreeTrumpet!</p>";

if(!file_exists(__DIR__ . '/../family.ged')){
    $page->body .= view('firstrun_gedcom',Array(),TRUE);
}else{
    $page->body .= view('firstrun_required_done',Array(),TRUE);
}

$otherSteps = Array();

if(!@touch(__DIR__ . '/3rdparty/ssgeocoder.sqlite3')){
    $otherSteps[] = 'firstrun_sqlite';
}

if(!file_exists(__DIR__ . '/config.php')){
   $otherSteps[] = 'firstrun_customize'; 
}else{
    if(!$_CONFIG['show_email'] && !$_CONFIG['show_email_form'] || $_CONFIG['email_address'] == 'example@example.com'){
        $otherSteps[] = 'firstrun_email';
    }
    if($_CONFIG['show_email_form'] && !@include('Mail.php')){
        $otherSteps[] = 'firstrun_email_pear';
    }
    if($_CONFIG['show_email_form'] && ($_CONFIG['smtp_username'] == 'example@example.com' || $_CONFIG['smtp_password'] == 'your_secret_password')){
        $otherSteps[] = 'firstrun_email_form';
    }
}

if(count($otherSteps) > 0){
    $page->body .= view('firstrun_other',Array(),TRUE);
    foreach($otherSteps as $step){
        $page->body .= view($step,Array(),TRUE);
    }
}

view('page',Array('page' => $page,'menu' => 'tree'));
