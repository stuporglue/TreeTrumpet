<?php

$page = model('page');

global $_BASEURL;
$page->canonical(linky($_BASEURL . '/contact.php'));

$page->title("Contact Me");
$page->css("css/contact.css");
$page->h1("Contact Me");
$page->body .= "<h2>Contact Information</h2>";
$page->body .= "<p>
I am interested in collaborating on my genealogy! If we're related, or you think 
we may be, please contact me so we can collaborate.
</p>";

$contacts = Array(); 
$gedcom = model('ttgedcom',Array(__DIR__ . '/../family.ged'));
$submitter = $gedcom->getSubmitter();

if($form = $submitter->emailForm()){
    if(count($_POST) > 0){
        if($sentMail = $form->sendMail()){
            $page->bodyright .= "<p class='sentstatus success'>Your message has been sent.</p>";
        }else{
            $page->bodyright .= "<p class='sentstatus failure'>There was an error sending your email message!</p>";
        }
    }
    if($formhtml = $form->form()){
         $page->bodyright .= "<h3>Email Form</h3>";
         $page->bodyright .= $formhtml;
    }
}

if($email = $submitter->emailAddress()){
    $contacts['Email Address'] = $email;
}

if($addr = $submitter->contactInfo()){
    $contacts['Traditional Contact Methods'] = $addr;
}

foreach($contacts as $title => $method){
    $page->body .= "<h3>$title</h3>";
    $page->body .= $method;
}



view('page_v_split',Array('page' => $page,'menu' => 'contact'));
