#!/usr/bin/env php
<?php

# This is the build script for TreeTrumpet
# It is meant to be run from the command line, but should work from a browser 
# if the server has write permissions to the current folder

chdir(__DIR__);

$destdir = 'treetrumpet';


// Create directories and set version info
{
    $directories = Array(
        "$destdir",
        "$destdir/css/3rdparty/ui",
        "$destdir/img/3rdparty",
        "$destdir/js/3rdparty/ui",
        "$destdir/lib/3rdparty/php-gedcom/library/",
        "$destdir/lib/licenses"
    );

    foreach($directories as $dir){
        @mkdir($dir,'0755',TRUE);
        if(!is_dir($dir)){
            print "ERROR: Couldn't create $dir\n";
            exit();
        }
    }

    // Set the version info
    $revision = "TreeTrumpet build from " . date('Y-m-d_H:M') . "\n";
    $gitinfo = explode("\n",`git rev-list HEAD`);
    $revision .= array_shift($gitinfo);
}


// copy files
{
    // Copy base TreeTrumpet files
        {
            $base = glob("*.php");
            foreach($base as $i => $file){
                if($file == 'build.php'){
                    unset($base[$i]);
                }
            }

            foreach($base as $file){
                if(is_file($file)){
                    copy($file,"$destdir/$file");
                }
            }

            foreach(Array('./','./lib/','./css/','./js/') as $src){
                foreach(glob("$src/*") as $file){
                    if(is_file($file)){
                        copy($file,"$destdir/$file");
                    }
                }
            }
        }

// jQRangeSlider files
                    {
                        copy("js/jQRangeSlider/dest/jQEditRangeSlider-min.js","$destdir/js/3rdparty/jQEditRangeSlider-min.js");
                        copy("js/jQRangeSlider/lib/jquery.mousewheel.min.js","$destdir/js/3rdparty/jquery.mousewheel.min.js");
                        copy("js/jQRangeSlider/lib/jquery.mousewheel.license.txt","$destdir/lib/licenses/jquery.mousewheel.txt");
                        copy("js/jQRangeSlider/css/iThing.css","$destdir/css/3rdparty/iThing.css");
                    }

// Leaflet.markercluster
                    {
                        copy("js/Leaflet.markercluster/dist/leaflet.markercluster.js", "$destdir/js/3rdparty/leaflet.markercluster.js");
                        copy("js/Leaflet.markercluster/dist/MarkerCluster.css", "$destdir/css/3rdparty/MarkerCluster.css");
                        copy("js/Leaflet.markercluster/dist/MarkerCluster.Default.css","$destdir/css/3rdparty/MarkerCluster.Default.css");
                        copy("js/Leaflet.markercluster/dist/MarkerCluster.Default.ie.css","$destdir/css/3rdparty/MarkerCluster.Default.ie.css");
                        copy("js/Leaflet.markercluster/MIT-LICENSE.TXT","$destdir/lib/licenses/Leaflet.markercluster.txt");
                    }

// ged2json
                    {
                        foreach(glob("lib/ged2json/examples/php/lib/*") as $file){
                            if(is_file($file)){
                                $destfile = basename($file);
                                copy($file,"$destdir/lib/3rdparty/$destfile");
                            }
                        }
                        copy("lib/ged2json/examples/php/lib/ssgeocoder/ssgeocoder.php","$destdir/lib/3rdparty/ssgeocoder.php");
                        copy("lib/ged2json/examples/php/lib/php-gedcom/library","$destdir/lib/3rdparty/php-gedcom/",TRUE);
                    }

// Pedigree Viewer 
                            {
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
                            }
}

// New zip file!
{
    @unlink("$destdir.zip");
    chmod($destdir,0755);
    // make zip file

    $md5 = md5("$destdir.zip");
    file_put_contents("$destdir.zip.md5",$md5);
}
