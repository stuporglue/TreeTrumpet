#!/usr/bin/env php
<?php

# This is the unbuild script for TreeTrumpet
# It is meant to be run from the command line, but should work from a browser 
# if the server has write permissions to the current folder

function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst,'01777',TRUE); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                @copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
}

// Destination directories
$destdir = 'treetrumpet';
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
    "$destdir/cache",
    "$destdir/media"
);

// @copy files
{
    // @copy base TreeTrumpet files
    $base = glob("$destdir/*.php");
    foreach($base as $i => $file){
        if($file == 'build.php'){
            continue;
        }else if(is_file($file)){
            @copy($file,"$file");
        }
    }

    recurse_copy("$destdir/model","model");
    recurse_copy("$destdir/view","view");
    recurse_copy("$destdir/controller","controller");
    recurse_copy("$destdir/img","img");

    // Other root-dir files
    @copy("$destdir/php.ini",'php.ini');
    @copy("$destdir/.htaccess",'htaccess');
    @copy("$destdir/favicon.ico",'favicon.ico');
    @copy("$destdir/config.php.example",'config.php.example');
    @copy("$destdir/family.ged.sample",'lib/ged2json/examples/moore.ged');

    $base_dirs = Array('./img/','./lib/','./css/','./js/');
    foreach($base_dirs as $src){
        foreach(glob("$destdir/$src/*") as $file){
            if(is_file($file)){
                @copy($file,"$src/" . basename($file));
            }
        }
    }

    // } jQRangeSlider files
    @copy("$destdir/js/3rdparty/jQEditRangeSlider-min.js","js/jQRangeSlider/dest/jQEditRangeSlider-min.js");
    @copy("$destdir/js/3rdparty/jquery.mousewheel.min.js","js/jQRangeSlider/lib/jquery.mousewheel.min.js");
    @copy("$destdir/lib/licenses/jquery.mousewheel.txt","js/jQRangeSlider/lib/jquery.mousewheel.license.txt");
    @copy("$destdir/css/3rdparty/iThing.css","js/jQRangeSlider/css/iThing.css");

    // Leaflet.markercluster
    @copy("$destdir/js/3rdparty/leaflet.markercluster.js","js/Leaflet.markercluster/dist/leaflet.markercluster.js");
    @copy("$destdir/css/3rdparty/MarkerCluster.css","js/Leaflet.markercluster/dist/MarkerCluster.css");
    @copy("$destdir/css/3rdparty/MarkerCluster.Default.css","js/Leaflet.markercluster/dist/MarkerCluster.Default.css");
    @copy("$destdir/lib/licenses/Leaflet.markercluster.txt","js/Leaflet.markercluster/MIT-LICENCE.txt");

    // ged2json
    foreach(glob("lib/ged2json/examples/php/lib/*") as $file){
        if(is_file($file)){
            $destfile = basename($file);
            @copy("$destdir/lib/3rdparty/$destfile",$file);
        }
    }
    
    @copy("$destdir/lib/3rdparty/ssgeocoder.php","lib/ged2json/examples/php/lib/ssgeocoder/ssgeocoder.php");
    recurse_copy("$destdir/lib/3rdparty/php-gedcom/library","lib/ged2json/examples/php/lib/php-gedcom/library");

    // These don't be long there
    @rename("lib/ged2json/examples/php/lib/php-gedcom/library/PhpGedcom/GedCache.php","lib/php-gedcom-sqlite/GedCache.php");
    @rename("lib/ged2json/examples/php/lib/php-gedcom/library/PhpGedcom/GedcomSqlite.php","lib/php-gedcom-sqlite/GedcomSqlite.php");
    @rename("lib/ged2json/examples/php/lib/php-gedcom/library/PhpGedcom/GedcomSqliteArray.php","lib/php-gedcom-sqlite/GedcomSqliteArray.php");
    @rename("lib/ged2json/examples/php/lib/php-gedcom/library/PhpGedcom/ParserSqlite.php","lib/php-gedcom-sqlite/ParserSqlite.php");

    foreach(glob("lib/php-gedcom-sqlite/*.php") as $file){
        if(is_file($file)){
            @copy("$destdir/lib/3rdparty/php-gedcom/library/PhpGedcom/" . basename($file),$file);
        }
    }


    // Pedigree Viewer 
    foreach(glob("js/Pedigree-Viewer/js/*") as $file){
        if(is_file($file)){
            @copy("$destdir/js/3rdparty/" . basename($file),$file);
        }
    }
    foreach(glob("js/Pedigree-Viewer/css/*") as $file){
        if(is_file($file)){
            @copy("$destdir/css/3rdparty/" . basename($file),$file);
        }
    }
    foreach(glob("js/Pedigree-Viewer/css/ui/*") as $file){
        if(is_file($file)){
            @copy("$destdir/css/3rdparty/ui/" . basename($file),$file);
        }
    } 

    // PHPMailer
    foreach(glob("lib/phpmailer/*.php") as $file){
        if(is_file($file)){
            @copy("$destdir/lib/3rdparty/phpmailer/" . basename($file),$file);
        }
    }

    // version file
    @unlink("lib/version.txt");
}
