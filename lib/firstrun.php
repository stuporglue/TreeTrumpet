<?php 
require_once(__DIR__ .'/config.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
            <title>Welcome to TreeTrumpet!</title>
            <link href="<?php print $_BASEURL;?>/css/tt.css" rel="stylesheet" media="all"/>
        </head>
        <body>
            <div id='tt-content'>
                <div id='tt-left-content' class='tt-content'>
                    <h1>TreeTrumpet</h1>
                    <?php require_once(__DIR__ . '/header.php'); ?>
                    <p>Thanks for installing TreeTrumpet!</p>
<?php

if(!file_exists(__DIR__ . '/../family.ged')){
?>
        <h2>Required Setup Steps</h2>
        <p>
            <ol>
            <li>Upload a GEDCOM file named <strong>family.ged</strong>.</li>
            </ol>
        </p>
<?php
}else{
?>
<h2>All Required Steps are Done!</h2>
<p>Awesome. <a href='../'>Show me my site!</a></p>
<p>To update your site, upload a new <strong>family.ged</strong></p>
<p>Optionally change other settings as mentioned below.</p>
<?php
}

ob_start();

// 1) Is the sqlite directory writeable
if(!@touch(__DIR__ . '/3rdparty/ssgeocoder.sqlite3')){
?>
<h3>Enable The Placename Cache</h3>
    <p>
    If you change the permissions of <em>lib/3rdparty/</em> to be writable by the web server we can use a cache to speed up the map place lookups after the first time.
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

if(strlen($other) > 0){
    print "<h2>Other Stuff You Might <em>Want</em> To Do</h2>
        <p>
        None of these things are required, but may make your website more useful to visitors.
        </p>";
    print "<h3>Bookmark This Page</h3>";
    print "<p>Bookmark <a href='$_BASEURL/lib/firstrun.php'>This link</a> so you can always come back to this page if you want help making other changes to your site!</p>";
    print $other;
}
?>

    </div>
    </div>
    <?php require_once(__DIR__ . '/footer.php');?>
</body>
    </html>

