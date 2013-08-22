<?php require_once(__DIR__ . '/lib/setup.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
    </head>
    <body>
        <div class='tt-content'>
            <div>
                <h1>Table View</h1>
                <?php require_once('lib/header.php'); ?>
                </div>
        </div>
        <div id='tt-table'>

        <?php 
            require_once('lib/table_noscript.php');
            print $noscript;
        ?>

        </div>
        <?php require_once('lib/footer.php'); ?>
    </body>
</html>
