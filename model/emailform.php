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
        require_once(__DIR__ . "/../lib/3rdparty/phpmailer/class.phpmailer.php");
        
        if(count($_POST) > 0  && !$this->enabled()){
            return FALSE;
        }

        if(!filter_var($_POST['from_email'], FILTER_VALIDATE_EMAIL)) {
            return FALSE;
        }

        $mail = new PHPMailer();

	if(array_key_exists('smtp_server',$_CONFIG)){
	    $mail->IsSMTP();

	    // $mail->SMTPDebug = 2;
	    // $mail->Debugoutput = 'html';

	    $mail->Host = $_CONFIG['smtp_server'];
	    $mail->Port = $_CONFIG['smtp_port'];
	    $mail->SMTPAuth = TRUE;
	    $mail->SMTPSecure = $_CONFIG['smtp_security'];
	    $mail->Username = $_CONFIG['smtp_username'];
	    $mail->Password = $_CONFIG['smtp_password'];
	}else{
	    $mail->IsSendmail();
	}


        $mail->setFrom($_POST['from_email']);
        $mail->AddAddress($_CONFIG['email_address']);
        $mail->Subject = preg_replace("|[^a-zA-Z0-9.;:@'\"/\!\? ]|",' ',$_POST['subject']);
        $mail->Body = $_POST['message'];

        if(!$mail->Send()){
            error_log("Mailer Error: " . $mail->ErrorInfo);
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
