<?php require_once(__DIR__ . '/lib/setup.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
            <title>TreeTrumpet Ancestors Table</title>
            <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">
            <link href="css/pv.css" rel="stylesheet" media="all"/>
            <link href="css/table.css" rel="stylesheet" media="all"/>
    </head>
    <body>
        <div class='pv-content'>
            <div>
                <h1>Table View</h1>
                <?php require_once('lib/header.php'); ?>
                <p>
This page contains a sortable, filterable table of ancestors, relatives and events in the gedcom file.
                </p>
            </div>
        </div>
        <div id='pv-table'>

        <?php 
            require_once('lib/table_noscript.php');
            print $noscript;
        ?>

        </div>
        <?php require_once('lib/footer.php'); ?>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.js"></script>
        <script type='text/javascript' src='js/table.js'></script>
        <script type='text/javascript'>
        $(document).ready(function(){
            pt = $('#pv-table').pvTable('lib/ged2json.php','family.ged');
        });
        </script>
    </body>
</html>
