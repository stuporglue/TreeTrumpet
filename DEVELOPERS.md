Developers
==========

This project uses several other projects and needs to be built to run. It will 
not run directly from checked-out code!!!

These instructions work on Debian Linux. Please let me know what changes you have to make to build on other platforms. 


Clone TreeTrumpet
-----------------
    git clone https://github.com/stuporglue/TreeTrumpet.git

Clone and Init Submodules (and Sub-Submodules)
----------------
    cd TreeTrumpet
    git submodule update --init --recursive
    
Install npm If Needed
---------------------

http://nodejs.org/download/


Build JavaScript Projects
-------------------------
    cd js/jQRangeSlider
    sudo npm install -g grunt-cli
    npm install grunt-contrib-mincss@0.4.0-rc7
    npm install
    grunt
    cd ..

    cd Leaflet.markercluster
    npm install
    sudo npm install -g jake
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
PHP 5.3+ with SQLite support
