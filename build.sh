#!/usr/bin/env bash

# Temporary build file until I figure out if I want Grunt.js or something else

# Assemble the pieces I need into pedigree-viewer

cd $(dirname $0)

mkdir -p pedigree-viewer
mkdir -p pedigree-viewer/css/3rdparty/ui
mkdir -p pedigree-viewer/img/3rdparty/
mkdir -p pedigree-viewer/js/3rdparty/ui
mkdir -p pedigree-viewer/lib/3rdparty/php-gedcom/library/

# Version info
echo "TreeTrumpet build from $(date +%Y-%m-%d_%H:%M)" > pedigree-viewer/lib/version.txt
echo -n "Git revision: " >> pedigree-viewer/lib/version.txt
git rev-list HEAD | head -1 >> pedigree-viewer/lib/version.txt

# Copy sample gedcom
cp lib/ged2json/examples/moore.ged pedigree-viewer/family.ged.sample

# Base PHP files
cp *.php pedigree-viewer/

# Non-code files
cp LICENSE.TXT README.md config.php.example pedigree-viewer/ 

# Base CSS
cp css/* pedigree-viewer/css/

# Base images
cp img/*.png pedigree-viewer/img/

# Base JavaScript
cp js/*.js pedigree-viewer/js/

# jQRangeSlider
cp js/jQRangeSlider/dest/jQEditRangeSlider-min.js pedigree-viewer/js/3rdparty/
cp js/jQRangeSlider/lib/jquery.mousewheel.* pedigree-viewer/js/3rdparty/
cp js/jQRangeSlider/css/iThing.css pedigree-viewer/css/3rdparty/

# Leaflet and marker cluster
cp js/Leaflet.markercluster/dist/leaflet.markercluster.js pedigree-viewer/js/3rdparty/
cp js/Leaflet.markercluster/dist/MarkerCluster.css pedigree-viewer/css/3rdparty/
cp js/Leaflet.markercluster/dist/MarkerCluster.Default.css pedigree-viewer/css/3rdparty/
cp js/Leaflet.markercluster/dist/MarkerCluster.Default.ie.css pedigree-viewer/css/3rdparty/

# PHP Libraries
cp lib/* pedigree-viewer/lib/
cp lib/ged2json/examples/php/lib/* pedigree-viewer/lib/3rdparty/
cp lib/ged2json/examples/php/lib/ssgeocoder/ssgeocoder.php pedigree-viewer/lib/3rdparty/
cp -r lib/ged2json/examples/php/lib/php-gedcom/library pedigree-viewer/lib/3rdparty/php-gedcom/

# Pedigree-Viewer
cp js/Pedigree-Viewer/js/* pedigree-viewer/js/3rdparty
cp js/Pedigree-Viewer/css/* pedigree-viewer/css/3rdparty/
cp js/Pedigree-Viewer/css/ui/* pedigree-viewer/css/3rdparty/ui/

#sudo chgrp -R www-data pedigree-viewer
#sudo chmod -R 775 pedigree-viewer

rm -f pedigree-viewer.zip
zip -r pedigree-viewer pedigree-viewer/ -x pedigree-viewer/family.ged -x pedigree-viewer/robots.txt -x pedigree-viewer/config.ini
md5sum pedigree-viewer.zip > pedigree-viewer.zip.md5
