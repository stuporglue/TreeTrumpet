<?php
/*
 * WARNING: This is the master config file. Do not change it!
 *
 * Instead, copy config.php.defaults to config.php and make changes there!
 *
 * This file will be overwritten whenever you upgrade TreeTrumpet!
 */

global $_BASEURL,$_CONFIG;

$_CONFIG = Array(
    /*
     * focus_person_id
     *
     * User can set the focus person. It defaults to the first person in the file
     */
    'focus_person_id' => FALSE,

    /*
     *   media_dir
     *
     *   media_dir lets GEDCOMs with absolute media paths work
     *
     *   If your gedcom file uses relative URLs, you can just upload everything to media
     *   with nothing listed in this config file
     *
     *   If your media paths have standard prefix you can list it here. The 
     *   prefix will be stripped off of the front of any attachment file
     *   paths in the Gedcom. 
     *      
     *   If you keep all your media in a single directory, then you can just
     *   upload that directory to the media directory, set this prefix and
     *   everything should just-work(TM).
     *
     *   Examples:
     *
     *  * Windows
     *  media_dir=C:\Users\Michael\Genealogy\Files
     *  Path to file shown in GEDCOOM: C:\Users\Michael\Genealogy\Files\mcginnis\patrick_marriage_cert.jpg
     *  Path to file on server: media/mcginnis/patrick_marriage_cert.jpg
     *
     *  * OSX
     *  media_dir=/Volumes/External Hard Drive/Genealogy/Files
     *  Path to file shown in GEDCOM: /Volumes/External Hard Drive/Genealogy/Files/mcginnis/patrick_marriage_cert.jpg
     *  Path to file on server: media/mcginnis/patrick_marriage_cert.jpg
     *
     *  * Linux
     *  media_dir=/home/michael/genealogy/files
     *  Path to file shown in GEDCOM: /home/michael/genealogy/files/mcginnis/patrick_marriage_cert.jpg
     *  Path to file on server: media/mcginnis/patrick_marriage_cert.jpg
     * 
     */

    'media_dir' => '/media/',


    /*
     * A list of modules which are enabled (of course)
     *
     * You can disable them by setting the value to FALSE
     */


    'tree'      =>  TRUE, 
    'map'       =>  TRUE, 
    'people'    =>  TRUE, 
    'contact'   =>  TRUE, 
    'gedcom'    =>  TRUE, 


    /* $contact_settings define additional settings for the contact page
     *
     * You can use this to allow people to send you emails. 
     */

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

    // For debugging. Caches js and css locally then uses it instead
    'cache_resources'   => FALSE,
);

// If config exists merge it into the real settings
$inifile = __DIR__ . '/../config.php';
if(file_exists($inifile)){

    $ini_config = Array();
    // Can't use parse_ini_file because it fails if the file doesn't end with .ini
    $inifile = explode("\n",file_get_contents($inifile));
    foreach($inifile as $kvpair){
        $kvpair = preg_replace('/;.*/','',$kvpair);
        preg_match('/([^=]+)=(.*)/',$kvpair,$matches);
        if(count($matches) > 0){
            $ini_config[trim($matches[1])] = trim($matches[2]);
        }
    }

    foreach($ini_config as $k => $val){
        if($val === '1'){
            $ini_config[$k] = TRUE;
        }
    }

    $_CONFIG = array_merge($_CONFIG,$ini_config);
}


// Get the base URL
$_BASEURL = '';
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off'){
    $_BASEURL .= 'http://';
}else{
    $_BASEURL .= 'https://';
} 
$_BASEURL .= $_SERVER['SERVER_NAME']; 
$scriptdir = dirname($_SERVER['SCRIPT_NAME']);

if(basename($scriptdir) == 'lib'){
    $scriptdir = dirname($scriptdir);
}

$_BASEURL .= $scriptdir;

// Hey! New content! -- Auto submit sitemap to these URLs: 
// http://www.google.com/webmasters/tools/ping?sitemap=http://www.example.com/sitemap.gz -- 200
// http://www.bing.com/ping?sitemap=http%3A%2F%2Fwww.example.com/sitemap.xml -- 200
// Others???
