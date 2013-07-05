<?php

require_once(__DIR__ . '/lib/setup.php');

function sendMail(){
    global $_CONFIG;

    require_once("Mail.php");

    if (!filter_var($_POST['from_email'], FILTER_VALIDATE_EMAIL)) {
        return FALSE;
    }

    $from     = $_POST['from_email'];
    $to       = $_CONFIG['email_address'];
    $subject  = preg_replace("/[^a-zA-Z0-9.;:@'\"\/\!\? /",' ',$_POST['subject']);
    $body     = $_POST['message'];

    $host     = $_CONFIG['smtp_server'];
    $port     = $_CONFIG['smtp_port'];
    $username = $_CONFIG['smtp_username'];  //<> give errors
    $password = $_CONFIG['smtp_password'];

    $headers = array(
        'From'    => $from,
        'To'      => $to,
        'Subject' => $subject
    );
    $smtp = Mail::factory('smtp', array(
        'host'     => $host,
        'port'     => $port,
        'auth'     => true,
        'username' => $username,
        'password' => $password
    ));

    $mail = $smtp->send($to, $headers, $body);

    if (PEAR::isError($mail)) {
        error_log($mail->getMessage());
        return FALSE;
    } else {
        return TRUE;
    }
}


if(count($_POST) > 0){
    $email_worked = sendMail();
}

?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8"/>
        <title>Contact Me</title>
        <link href="css/tt.css" rel="stylesheet" media="all"/>
        <link href="css/contact.css" rel="stylesheet" media="all"/>
        </head>
    <body>
    <div id="tt-content">
        <div id='tt-left-content' class='tt-content'>
            <h1>Contact Me</h1>
            <?php require_once('lib/header.php'); ?>
            <h2>Contact Information</h2>
<p>
I am interested in collaborating on my genealogy! If we're related, or you think 
we may be, please contact me so we can collaborate.
</p>
<?php

$printed = FALSE;
if(file_exists('family.ged')){
    // Parse the given file
    $parser = new PhpGedcom\Parser();
    $parsedgedcom = $parser->parse('family.ged');
    require_once('lib/pretty-print_php-gedcom.php');
    if($submitter = $parsedgedcom->getSubm()){
        foreach($submitter as $subm){
            print printSubm($subm);
        }
    }

    if($_CONFIG['show_email'] && $_CONFIG['email_address'] != 'example@example.com'){
        print "<a href='mailto:{$_CONFIG['email_address']}'>{$_CONFIG['email_address']}</a>";
        $printed = TRUE;
    }
}

if(!$printed && !$_CONFIG['email_address'] == 'example@example.com' && !@include('Mail.php')){
    print "<p>There's no contact information here yet. Ask the webmaster to upload a GEDCOM file with embedded contact information in it.</p>";
}

?>
        </div>
        <div id='tt-right-content' class='tt-content'>
<?php

if($_CONFIG['email_address'] != 'example@example.com' && $_CONFIG['show_email_form']){

    if(!@include_once('Mail.php')){
        print "Ask your system administrator to install the PHP Pear Mail package";
    }else{

        if(isset($email_worked)){
            if($email_worked){
                print "<p class='sentstatus'>Your message has been sent</p>";
            }else{
                print "<p class='sentstatus'>There was an error sending your email message!</p>";
            }
        }

        print "<form method='post'>
            Your Email Address: <input name='from_email' type='email'/><br>
            Subject: <input name='subject' type='text'/><br>
            <textarea name='message'>Enter your message here...</textarea>
            <input type='submit' value='Send me an email!'/>
            </form>";
    }
}
?>
        </div>
    </div>
    <?php require_once('lib/footer.php'); ?>
</body>
</html>
