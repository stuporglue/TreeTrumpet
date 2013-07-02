<?php 
    require_once(__DIR__ . '/lib/config.php'); 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
            <title>TreeTrumpet</title>
            <link href="css/pv.css" rel="stylesheet" media="all"/>
    </head>
    <body>
        <div id='pv-content'>
            <div id='pv-left-content' class='pv-content'>
                <h1>TreeTrumpet</h1>
                <?php require_once('lib/header.php'); ?>

    <h2>About</h2>
<p>
TreeTrumpet is a very easy way to get your family history online. It is simple to install,
configuration is optional, and all you need to do is upload a GEDCOM file to update it. 
</p>

<p>
TreeTrumpet was created to make your genealogy look good to visitors, but also to attract
visitors. Every time you upload a new GEDCOM file TreeTrumpet notifies Google, Bing and 
Yahoo that your site has been updated. The search engines can then send visitors to your site. 
</p>

<h2>Installing</h2>
<?php require_once('lib/header.php'); ?>
<p>
TreeTrumpet needs to be uploaded to a web server which supports PHP 5.3 or higher. I personally
use <a href='https://www.bluehost.com'>https://www.bluehost.com/</a>, but any web hosting which supports PHP should work just as well. 
Your hosting provider will have instructions for uploading files, either through the browser, 
or through FTP.
</p>

<p>
Unzip treetrumpet.zip and upload everything inside up to your server. That's all there is 
to the install. 
</p>


<h2>Adding Your Family</h2>
<p>
Export a GEDCOM file from your genealogy program and name it _family.ged_. Upload it to the 
webserver. To update the site, simply upload a new file named _family.ged_.
</p>



<h2>Configuration (Optional)</h2>
<p>
If you want people to be able contact you by email, or if you want to disable certain pages, 
copy config.php.example to be named config.php and edit the values inside. Upload it to the
web server. 
</p>

<h2>Help!</h2>
<p>
For help, to report bugs in the software, or to request new features please use the issue tracker 
<a href='https://github.com/stuporglue/TreeTrumpet/issues/'>on GitHub</a>.
</p>
<p>
If you do not have a GitHub account, please email me at <a href='mailto:stuporglue@gmail.com'>stuporglue@gmail.com</a>. Do not email 
or post any login or account information for you web hosting!
</p>
    
            <div id='pv-right-content' class='pv-content'>
                <div class='pv-preview'>
                    <img src='img/tree.png' alt='TreeTrumpet Tree View'/>
                    <a href='tree.php'>TreeTrumpet Tree View</a>
                </div>
                <div class='pv-preview'>
                    <img src='img/map_preview.png' alt='TreeTrumpet Map View'/>
                    <a href='map.php'>TreeTrumpet Map View</a>
                </div>
                <div class='pv-preview'>
                    <img src='img/table.png' alt='TreeTrumpet Table View'/>
                    <a href='table.php'>TreeTrumpet Table View</a>
                </div>
            </div>
        </div>
            <?php require_once('lib/footer.php');?>
    </body>
</html>
