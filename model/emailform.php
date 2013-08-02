<?php

class emailform {

    function __construct(){
        if(count($_POST) && $this->enabled()){
            $this->sendMail();
        }
    }

    function enabled(){
        global $_CONFIG;
        return $_CONFIG['email_address'] != 'example@example.com' && $_CONFIG['show_email_form'] && @include('Mail.php');
    }

    function form(){
        if(!$this->enabled()){
            return FALSE;
        }

        return "<form method='post'>
            Your Email Address: <input name='from_email' type='email'/><br>
            Subject: <input name='subject' type='text'/><br>
            <textarea name='message'>Enter your message here...</textarea>
            <input type='submit' value='Send me an email!'/>
            </form>";
    }

    function sendMail(){
        if(!$this->enabled()){
            return FALSE;
        }

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
}
