TreeTrumpet 
==============

About
-----
TreeTrumpet is a very easy way to get your family history online. It is simple to install,
configuration is optional, and all you need to do is upload a GEDCOM file to update it. 

TreeTrumpet was created to make your genealogy look good to visitors, but also to attract
visitors. Every time you upload a new GEDCOM file TreeTrumpet notifies Google, Bing and 
Yahoo that your site has been updated. The search engines can then send visitors to your site. 


Installing
----------
TreeTrumpet needs to be uploaded to a web server which supports PHP 5.3 or higher. I personally
use https://www.bluehost.com/, but any web hosting which supports PHP should work just as well. 
Your hosting provider will have instructions for uploading files, either through the browser, 
or through FTP.

Unzip pedigree-viewer.zip and upload everything inside up to your server. That's all there is 
to the install. 

Installing From Source
----------------------


    git clone https://github.com/stuporglue/TreeTrumpet.git tree
    cd tree
    git submodule init
    git submodule update --init --recursive


Next you'll need to build jQRangeSlider

Install grunt if you don't have it: 


    cd js/jQRangeSlider
    npm install
    npm install -g grunt-cli
    grunt
    cd ../..

Now you can build TreeTrumpet

    php ./build.php

The built project will be in the treetrumpet directory. You can now copy the treetrumpet directory to your web server.



Adding Your Family
------------------
Export a GEDCOM file from your genealogy program and name it family.ged. Upload it to the 
webserver. To update the site, simply upload a new file named family.ged.


Configuration (Optional)
------------------------
If you want people to be able contact you by email, or if you want to disable certain pages, 
copy config.php.example to be named config.php and edit the values inside. Upload it to the
web server. 


Help!
-----
For help, to report bugs in the software, or to request new features please use the issue tracker 
on GitHub: https://github.com/stuporglue/TreeTrumpet/issues/

If you do not have a GitHub account, please email me at stuporglue@gmail.com. Do not email 
or post any login or account information for you web hosting!
