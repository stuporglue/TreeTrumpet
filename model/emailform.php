<?php

class emailform {

    function enabled(){

        if(isset($this->enabled)){
            return $this->enabled;
        }

        $this->enabled = TRUE;

        global $_CONFIG;
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
        require_once(__DIR__ . "/../libphpmailer/class.phpmailer.php");
        
        if(count($_POST) > 0  && !$this->enabled()){
            return FALSE;
        }

        if(!filter_var($_POST['from_email'], FILTER_VALIDATE_EMAIL)) {
            return FALSE;
        }

        $smtp = new PHPMailer();
        $smtp->IsSMTP();
        $smtp->Host = $_CONFIG['smtp_server'];
        $smtp->Port = $_CONFIG['smtp_port'];
        $smtp->SMTPAuth = TRUE;
        $smtp->SMTPSecure = 'ssl';
        $smtp->Username = $_CONFIG['smtp_username'];
        $smtp->Password = $_CONFIG['smtp_password'];
        $smtp->setFrom($_POST['from_email']);
        $smtp->Subject = preg_replace("|[^a-zA-Z0-9.;:@'\"/\!\? ]|",' ',$_POST['subject']);
        $smtp->Body = $_POST['message'];
        $smtp->AddAddress($_CONFIG['email_address']);

        if(!$smtp->Send()){
            error_log("Mailer Error: " . $smtp->ErrorInfo);
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
