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
            <h1>HTML5 TreeTrumpet Timeline</h1>
            <?php require_once('lib/header.php'); ?>
            <h2>A TreeTrumpet Timeline</h2>
            <p>
This timeline is made of all the events with dates. 
            </p>
        </div>


        <div id='tt-right-content' class='tt-content'>
            <!-- This is the box the actual tree goes in -->
                <div id="tt-timeline">
                <dl>
<?php
    $events = makeGedcomEventsArray(); 
    foreach($events as $date => $eventByDate){
        print "<dt>" . date('d M Y',$date) . "</dt>";
        print "<dd><ul>";
        foreach($eventByDate as $event){
            print "<li>" . printTimelineEvent($event) . "</li>";
        }
        print "</ul></dd>";
    }
?>
</dl>
                </div> 
            </div>
        </div>
    </div>
</body>
</html>
