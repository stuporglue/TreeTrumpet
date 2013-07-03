<?php 
require_once(__DIR__ . '/lib/setup.php'); 
require_once('lib/get_all_events.php');
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8"/>
        <title>HTML5 TreeTrumpet Demo</title>
        <link href="css/tt.css" rel="stylesheet" media="all"/>
        <link href="css/3rdparty/ui/ui.slider.css" rel="stylesheet" media="all" /> 
        <link href="css/3rdparty/tree.css" rel="stylesheet" media="all"/>
        <link href="css/tree.css" rel="stylesheet" media="all"/>
        </head>
    <body>
    <div id="tt-content">
        <div id='tt-left-content' class='tt-content'>
            <h1>HTML5 TreeTrumpet Demo</h1>
            <?php require_once('lib/header.php'); ?>
            <h2>A TreeTrumpet Demo</h2>
            <p>
                This is a bowtie view of part of my family tree. Other possible views include ancestors only and descendants only. 
            </p>
        </div>


        <div id='tt-right-content' class='tt-content'>
            <!-- This is the box the actual tree goes in -->
                <div id="tt-timeline">
<?php
    print_r(makeGedcomEventsArray());
?>
                </div> 
            </div>
        </div>
    </div>
</body>
</html>
