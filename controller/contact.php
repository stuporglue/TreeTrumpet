<?php

$page = model('page');
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
    if($formhtml = $form->form()){
        $contacts['Email Form'] = $formhtml;
    }
    if(count($_POST) > 0){
        if($sentMail = $form->sendMail()){
            $page->body .= "<p class='sentstatus success'>Your message has been sent.</p>";
        }else{
            $page->body .= "<p class='sentstatus failure'>There was an error sending your email message!</p>";
        }
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



view('page',Array('page' => $page,'menu' => 'contact'));
