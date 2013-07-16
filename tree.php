<?php require_once(__DIR__ . '/lib/setup.php'); ?>
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
                <div id="tt-tree">
                    please wait...loading
                </div> 
            </div>

        </div>
    </div>
    <?php require_once('lib/footer.php'); ?>

    <!-- Yay jQuery! Yay jQuery-ui! -->

        <script type="text/javascript" src="js/3rdparty/excanvas.js"></script>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script type="text/javascript" src="js/3rdparty/jquery.mousewheel.js"></script>
        <script type="text/javascript" src="js/3rdparty/sharing-time.js"></script>
        <script type="text/javascript" src="js/3rdparty/sharing-time-ui.js"></script>
        <script type="text/javascript" src="js/3rdparty/sharing-time-chart.js"></script>
        <script type="text/javascript" src="js/3rdparty/jsZoom.js"></script>
        <script type="text/javascript" src="js/3rdparty/make_chart.js"></script>
        <script type="text/javascript" src="js/3rdparty/tree.js"></script>



        <!-- Kick it off once everything is ready -->
        <script type="text/javascript"> 
            $(document).ready(function(){
                pt = $('#tt-tree').pvTree('lib/ged2json.php','family.ged',{
                    personClick : function(e){
                        var id = e.target.id.replace('person_','');
                        pt.refocus(id);
                    }
                });
            });
        </script> 	
</body>
</html>
