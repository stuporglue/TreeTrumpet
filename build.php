#!/usr/bin/env php
<?php

# This is the build script for TreeTrumpet
# It is meant to be run from the command line, but should work from a browser 
# if the server has write permissions to the current folder

$destdir = 'treetrumpet';

$exclude_from_zip = Array(
    './cache/geocding.sqlite3',
    './family.ged',
    './robots.txt',
    './config.php'
);

chdir(__DIR__);
umask(0);

function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst,'01777',TRUE); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 


// Create directories and set version info
{
    $directories = Array(
        "$destdir/model",
        "$destdir/view",
        "$destdir/controller",
        "$destdir/css/3rdparty/ui",
        "$destdir/js/3rdparty/ui",
        "$destdir/img",
        "$destdir/lib/3rdparty",
        "$destdir/lib/3rdparty/phpmailer",
        "$destdir/lib/licenses",
        "$destdir/lib/php-gedcom-custom/PhpGedcom",
        "$destdir/cache",
        "$destdir/media"
    );

    foreach($directories as $dir){
        @mkdir($dir,'01777',TRUE);
        if(!is_dir($dir)){
            print "ERROR: Couldn't create $dir\n";
            exit();
        }
    }

    // Set the version info
    $revision = "TreeTrumpet build from " . date('Y-m-d_H:i') . "\n";
    $gitinfo = explode("\n",`git rev-list HEAD`);
    $revision .= array_shift($gitinfo);
    file_put_contents("$destdir/lib/version.txt",$revision);
}



// copy files
{
    // Copy base TreeTrumpet files
    $base = glob("*.php");
    foreach($base as $i => $file){
        if($file == 'build.php' || $file == 'unbuild.php'){
            continue;
        }else if(is_file($file)){
            copy($file,"$destdir/$file");
        }
    }

    recurse_copy("model","$destdir/model");
    recurse_copy("view","$destdir/view");
    recurse_copy("controller","$destdir/controller");
    recurse_copy("img","$destdir/img");

    // Other root-dir files
    copy('php.ini',"$destdir/php.ini");
    copy('htaccess',"$destdir/.htaccess");
    copy('favicon.ico',"$destdir/favicon.ico");
    copy('config.php.example',"$destdir/config.php.example");
    copy('lib/ged2json/examples/moore.ged', "$destdir/family.ged.sample");

    $base_dirs = Array('./img/','./lib/','./css/','./js/');
    foreach($base_dirs as $src){
        foreach(glob("$src/*") as $file){
            if(is_file($file)){
                copy($file,"$destdir/$file");
            }
        }
    }

    // } jQRangeSlider files
    copy("js/jQRangeSlider/dest/jQEditRangeSlider-min.js","$destdir/js/3rdparty/jQEditRangeSlider-min.js");
    copy("js/jQRangeSlider/lib/jquery.mousewheel.min.js","$destdir/js/3rdparty/jquery.mousewheel.min.js");
    copy("js/jQRangeSlider/lib/jquery.mousewheel.license.txt","$destdir/lib/licenses/jquery.mousewheel.txt");
    copy("js/jQRangeSlider/css/iThing.css","$destdir/css/3rdparty/iThing.css");

    // Leaflet.markercluster
    copy("js/Leaflet.markercluster/dist/leaflet.markercluster.js", "$destdir/js/3rdparty/leaflet.markercluster.js");
    copy("js/Leaflet.markercluster/dist/MarkerCluster.css", "$destdir/css/3rdparty/MarkerCluster.css");
    copy("js/Leaflet.markercluster/dist/MarkerCluster.Default.css","$destdir/css/3rdparty/MarkerCluster.Default.css");
    copy("js/Leaflet.markercluster/MIT-LICENCE.txt","$destdir/lib/licenses/Leaflet.markercluster.txt");

    // ged2json
    foreach(glob("lib/ged2json/examples/php/lib/*") as $file){
        if(is_file($file)){
            $destfile = basename($file);
            copy($file,"$destdir/lib/3rdparty/$destfile");
        }
    }
    copy("lib/ged2json/examples/php/lib/ssgeocoder/ssgeocoder.php","$destdir/lib/3rdparty/ssgeocoder.php");
    recurse_copy("lib/ged2json/examples/php/lib/php-gedcom/library","$destdir/lib/3rdparty/php-gedcom/library");

    recurse_copy("lib/php-gedcom-sqlite","$destdir/lib/php-gedcom-custom");


    // Pedigree Viewer 
    foreach(glob("js/Pedigree-Viewer/js/*") as $file){
        if(is_file($file)){
            copy($file,"$destdir/js/3rdparty/" . basename($file));
        }
    }
    foreach(glob("js/Pedigree-Viewer/css/*") as $file){
        if(is_file($file)){
            copy($file,"$destdir/css/3rdparty/" . basename($file));
        }
    }
    foreach(glob("js/Pedigree-Viewer/css/ui/*") as $file){
        if(is_file($file)){
            copy($file,"$destdir/css/3rdparty/ui/" . basename($file));
        }
    } 

    // TagCanvas
    copy("js/TagCanvas/jquery.tagcavas.min.js","$destdir/js/3rdparty/jquery.tagcanvas.min.js");

    // PHPMailer
    foreach(glob("lib/phpmailer/*.php") as $file){
        if(is_file($file)){
            copy($file,"$destdir/lib/3rdparty/phpmailer/" . basename($file));
        }
    }
    copy("lib/phpmailer/LICENSE","$destdir/lib/3rdparty/phpmailer/LICENSE");
}

// New zip file!
{
    @unlink("$destdir.zip");
    chmod($destdir,01777);
    chdir($destdir);

    $directories = Array('.');

    $zip = new ZipArchive();
    if($zip->open("./../$destdir.zip",ZipArchive::CREATE) !== TRUE){
        exit("Cannot open $destdir.zip for writing!");
    }

    while(count($directories) > 0){
        $cur_dir = array_shift($directories);
        foreach(glob("$cur_dir/*") as $file_or_dir){
            @chmod($file_or_dir,01755);
            if(is_dir($file_or_dir)){
                $directories[] = $file_or_dir;
            }else if(is_file($file_or_dir) && !in_array($file_or_dir,$exclude_from_zip)){
                $res = $zip->addFile(preg_replace('|^[./]*|','',$file_or_dir));
            }
        }
        $res = $zip->addFile('.htaccess');
        $res = $zip->addEmptyDir('media');
        $res = $zip->addEmptyDir('cache');
    }

    $zip->close();

    chdir('./..');
    $md5 = md5("$destdir.zip");
    file_put_contents("$destdir.zip.md5",$md5);
}
