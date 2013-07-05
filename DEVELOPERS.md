Developers
==========

Clone TreeTrumpet
-----------------
    git clone https://github.com/stuporglue/TreeTrumpet.git

Clone Submodules
----------------
    cd TreeTrumpet
    git submodule init
    git submodule update

Same for Sub-Submodules
-----------------------
    cd lib/ged2json
    git submodule init
    git submodule update
    cd ../../

Install npm If Needed
---------------------

http://nodejs.org/download/


Build JavaScript Projects
-------------------------
    cd js/jQRangeSlider
    sudo npm install
    sudo npm install -g grunt-cli
    grunt
    cd ..

    cd Leaflet.markercluster
    sudo npm install
    jake


Build TreeTrumpet
-----------------
    ./build.sh

Deploy and Test
---------------
* Copy the contents of treetrumpet or treetrumpet.zip to your web server
* Add a gedcom named family.ged
* Ensure that permissions are correct (write permissions for the sqlite file)

Outside Dependencies
--------------------
PHP 5.3+ with Pear's Mail and Net_SMTP modules installed
