<?php
/*
 * WARNING: This is the master config file. Do not change it!
 *
 * Instead, copy config.php.defaults to config.php and make changes there!
 *
 * This file will be overwritten whenever you upgrade TreeTrumpet!
 */



/*
 * This is a simple config file for TreeTrumpet
 *
 * You don't NEED to change anything but you can if you wish to change
 * the behavior of TreeTrumpet
 *
 * To use this file, copy config.php.defaults to config.php
 * and change any values you wish
 *
 */


/*
 * $enabled_modules is a list of modules which are enabled (of course)
 *
 * You can disable them by setting the value to FALSE
 */

$_CONFIG = Array(
    'tree'      =>  TRUE, 
    'map'       =>  TRUE, 
    'table'     =>  TRUE, 
    'contact'   =>  TRUE, 
    'gedcom'    =>  TRUE, 


/* $contact_settings define additional settings for the contact page
 *
 * You can use this to allow people to send you emails. 
 */

    'show_address'      => FALSE,
    'show_phone'        => FALSE,
    'email_address'     => "example@example.com", 
    'show_email'        => FALSE,
    'show_email_form'   => FALSE,

    // These are SMTP settings and you will need to check with your email
    // provider to determine the correct values. The values listed are
    // for use with Gmail.
    'smtp_server'       => 'ssl://smtp.gmail.com',
    'smtp_port'         => '465',
    'smtp_username'     => 'example@example.com', // Your gmail username
    'smtp_password'     => 'your_secret_password', // Your gmail password
);

// If config exists merge it into the real settings
if(file_exists(__DIR__ . '/../config.php')){

    $ini_config = parse_ini_file(__DIR__ . '/..config.php');
    print_r($ini_config);
    foreach($ini_config as $k => $val){
        if($val === 1){
            $ini_config[$k] = TRUE;
        }
    }

    $_CONFIG = array_merge($_CONFIG,$ini_config);
}
