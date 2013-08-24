<?php

class emailform {

    function enabled(){

        if(isset($this->enabled)){
            return $this->enabled;
        }

        $this->enabled = TRUE;

        global $_CONFIG;
        if(!@include_once('Mail.php')){
            $this->enabled = FALSE;
        }
        if($_CONFIG['email_address'] == 'example@example.com'){
            $this->enabled = FALSE;
        } 
        if(!$_CONFIG['show_email_form']){
            $this->enabled = FALSE;
        }
        if($_CONFIG['smtp_username'] == 'example@example.com'){
            $this->enabled = FALSE;
        }
        if($_CONFIG['smtp_password'] == 'your_secret_gmail_password'){
            $this->enabled = FALSE;
        }
        if (!filter_var($_CONFIG['email_address'], FILTER_VALIDATE_EMAIL)) {
            $this->enabled = FALSE;
        }

        return $this->enabled;
    }

    function form(){
        if(!$this->enabled()){
            return FALSE;
        }

        return "<form method='post'>
            <label for='from_email'>Your Email Address:</label><input name='from_email' id='from_email' type='email'/><br>
            <label for='subject'>Subject:</label><input id='subject' name='subject' type='text'/><br>
            <label for='message'>Message:</label>
            <textarea class='clearme' id='message' name='message'></textarea>
            <input type='submit' value='Send me an email!'/>
            </form>";
    }

    function sendMail(){
        global $_CONFIG;
        
        if(count($_POST) > 0  && !$this->enabled()){
            return FALSE;
        }

        if (!filter_var($_POST['from_email'], FILTER_VALIDATE_EMAIL)) {
            return FALSE;
        }

        $post = $_POST;

        $from     = $_POST['from_email'];
        $to       = $_CONFIG['email_address'];
        $subject  = preg_replace("|[^a-zA-Z0-9.;:@'\"/\!\? ]|",' ',$_POST['subject']);
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
