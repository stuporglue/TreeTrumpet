Developers
==========

This project uses several other projects and needs to be built to run. It will 
not run directly from checked-out code!!!


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
    npm install -g grunt-cli
    npm install
    grunt
    cd ..

    cd Leaflet.markercluster
    npm install
    npm install -g jake
    jake
    cd ../../


Build TreeTrumpet
-----------------
    ./build.php
    or something like...
    "C:\Program Files (x86)\PHP\v5.3\php.exe" build.php

Deploy and Test
---------------
* Copy the contents of treetrumpet or treetrumpet.zip to your web server
* Add a gedcom named family.ged
* Ensure that permissions are correct (write permissions for the sqlite file)

Outside Dependencies
--------------------
PHP 5.3+ with Pear's Mail and Net_SMTP modules installed
