#!/usr/bin/env bash

# Temporary build file until I figure out if I want Grunt.js or something else

# Assemble the pieces I need into treetrumpet 

cd $(dirname $0)

mkdir -p treetrumpet
mkdir -p treetrumpet/css/3rdparty/ui
mkdir -p treetrumpet/img/3rdparty/
mkdir -p treetrumpet/js/3rdparty/ui
mkdir -p treetrumpet/lib/3rdparty/php-gedcom/library/

# Version info
echo "TreeTrumpet build from $(date +%Y-%m-%d_%H:%M)" > treetrumpet/lib/version.txt
echo -n "Git revision: " >> treetrumpet/lib/version.txt
git rev-list HEAD | head -1 >> treetrumpet/lib/version.txt

# Copy sample gedcom
cp lib/ged2json/examples/moore.ged treetrumpet/family.ged.sample

# Base PHP files
cp *.php treetrumpet/

# Non-code files
cp LICENSE.TXT README.md config.php.example treetrumpet/ 

# Base CSS
cp css/* treetrumpet/css/

# Base images
cp img/*.png treetrumpet/img/

# Base JavaScript
cp js/*.js treetrumpet/js/

# jQRangeSlider
cp js/jQRangeSlider/dest/jQEditRangeSlider-min.js treetrumpet/js/3rdparty/
cp js/jQRangeSlider/lib/jquery.mousewheel.* treetrumpet/js/3rdparty/
cp js/jQRangeSlider/css/iThing.css treetrumpet/css/3rdparty/

# Leaflet and marker cluster
cp js/Leaflet.markercluster/dist/leaflet.markercluster.js treetrumpet/js/3rdparty/
cp js/Leaflet.markercluster/dist/MarkerCluster.css treetrumpet/css/3rdparty/
cp js/Leaflet.markercluster/dist/MarkerCluster.Default.css treetrumpet/css/3rdparty/
cp js/Leaflet.markercluster/dist/MarkerCluster.Default.ie.css treetrumpet/css/3rdparty/

# PHP Libraries
cp lib/* treetrumpet/lib/
cp lib/ged2json/examples/php/lib/* treetrumpet/lib/3rdparty/
cp lib/ged2json/examples/php/lib/ssgeocoder/ssgeocoder.php treetrumpet/lib/3rdparty/
cp -r lib/ged2json/examples/php/lib/php-gedcom/library treetrumpet/lib/3rdparty/php-gedcom/

# Pedigree-Viewer
cp js/Pedigree-Viewer/js/* treetrumpet/js/3rdparty
cp js/Pedigree-Viewer/css/* treetrumpet/css/3rdparty/
cp js/Pedigree-Viewer/css/ui/* treetrumpet/css/3rdparty/ui/

#sudo chgrp -R www-data treetrumpet
#sudo chmod -R 775 treetrumpet

rm -f treetrumpet.zip
zip -r treetrumpet treetrumpet/ -x treetrumpet/lib/3rdparty/ssgeocoder.sqlite3 -x treetrumpet/family.ged -x treetrumpet/robots.txt -x treetrumpet/config.php

md5sum treetrumpet.zip > treetrumpet.zip.md5
