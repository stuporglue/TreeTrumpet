<?php 
    require_once(__DIR__ .'/config.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
            <title>Welcome to TreeTrumpet!</title>
            <link href="<?php print $_BASEURL;?>/css/pv.css" rel="stylesheet" media="all"/>
        </head>
        <body>
            <div id='pv-content'>
                <div id='pv-left-content' class='pv-content'>
                    <h1>TreeTrumpet</h1>
                    <?php require_once(__DIR__ . '/header.php'); ?>
                    <p>Thanks for installing TreeTrumpet!</p>
                    <p>To get started all you need to do is upload a GEDCOM file named '<strong>family.ged</strong>'</p>
                    <p>Bookmark <a href='<?php print $_BASEURL;?>/lib/firstrun.php'>This link</a> so you can always come back to this page if you want help making other changes to your site!</p>
<?php

ob_start();

// 1) Is the sqlite directory writeable
if(!touch(__DIR__ . '/3rdparty/ssgeocoder.sqlite3')){
?>
<h3>Enable The Placename Cache</h3>
    <p>
    If you change the permissions of this directory to be writable by the web server we can use a cache to speed up the map place lookups after the first time.
    </p>
<?php
}


$email = FALSE;
if(!file_exists(__DIR__ . '/config.php')){
?>
    <h3>Customize Your Site</h3>
    <p>By creating a config.php file (copy the example file) you can diable pages you don't want and change which contact info people can see</p>
    <p>Most important is probably the email settings since that will determine if people can see your email address, see an email form, or don't see any email options at all</p>
<?php
}else{
    if(!$_CONFIG['show_email'] && !$_CONFIG['show_email_form'] || $_CONFIG['email_address'] == 'example@example.com'){
?>

    <h3>Enable E-Mail Contact</h3>
    <p>Edit your config.php file to allow users to email you. You need to either:
<ol><li>Set the email_address setting <strong>AND</strong></li>
<li>Set show_email to TRUE</li></ol>
or
<ol><li>Set the email_address setting <strong>AND</strong></li>
<li>Set the show_email_form to TRUE <strong>AND</strong></li>
<li>Configure the smtp_* settings for your mail provider (settings for Gmail are included)</li>
</ol>
    </p>

<?php
    }
    if($_CONFIG['show_email_form'] && !@include('Mail.php')){
?>
        <h3>Finish Setting Up Email</h3>
        <p>Please ask your system administrator to install the PHP Pear Mail package. 
        The Pear Mail package is required to allow visitors to your site to email you with the email form, without showing your email address to them.
        </p>
<?php
    }

    if($_CONFIG['show_email_form'] && ($_CONFIG['smtp_username'] == 'example@example.com' || $_CONFIG['smtp_password'] == 'your_secret_password')){
?>
        <h3>Finish Setting Up Email</h3>
        <p>Make sure you set the smtp_username to your username and the smtp_password to your password or users will not be able to contact you through the web form.</p>
<?php
    }

}

$other = ob_get_clean();
ob_end_clean();

if(strlen($other) > 0){
    print "<h2>Other Stuff You Might <em>Want</em> To Do</h2>
    <p>
    None of these things are required, but may make your website more useful to visitors.
    </p>";
    print $other;
}
?>

    </div>
    </div>
    <?php require_once(__DIR__ . '/footer.php');?>
</body>
    </html>

