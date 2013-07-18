<?php require_once(__DIR__ . '/lib/setup.php'); ?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8"/>
        <title>HTML5 TreeTrumpet Demo</title>
        <link href="css/tt.css" rel="stylesheet" media="all"/>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
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
                <?php
                    require_once(__DIR__ . '/lib/3rdparty/ged2json.php');
                    $ged2json = new ged2json(__DIR__ . '/family.ged');
                    $ancestors = $ged2json->toJsonArray(TRUE);
                    usort($ancestors,function($a,$b){
                        return str_replace('/','',$a['name']) > str_replace('/','',$b['name']);
                    });
                    $ids = Array();
                    foreach($ancestors as $ancestor){
                        $ids[$ancestor['id']] = $ancestor['name'];
                    }
                    ksort($ids);

                    foreach($ids as $id => $name){
                        print "<p>Back to the home person: <a href='individual.php?id={$id}' onclick=\"return refocusTree('{$id}');\">" . preg_replace('|/(.*)/|',"<span class='ttln'>$1</span>",$name) . "</a></p>";
                        break;
                    }

                    print "<ul class='shortlist'>";
                    foreach($ancestors as $ancestor){
                        print "<li><a href='individual.php?id={$ancestor['id']}' onclick=\"return refocusTree('{$ancestor['id']}');\">" . preg_replace('|/(.*)/|',"<span class='ttln'>$1</span>",$ancestor['name']) . "</a></li>";
                    }
                    print "</ul>";
                ?>
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

                        $('#refocuslink')[0].className = id;
                        $('#gotopage')[0].className = id;

                        $('#details .name').html(pt.people[id].name);
                        $('#details .gender').html(pt.people[id].gender);
                        var table = '<table>';
                        var ev;
                        for(var i = 0;i < pt.people[id].events.length;i++){
                            ev = pt.people[id].events[i];
                            table += '<tr><td>';
                            if(typeof ev.type != 'undefined'){
                                table += ev.type;
                            }
                            table += '</td><td>';
                            if(typeof ev.date != 'undefined' && typeof ev.date.raw != 'undefined'){
                                table += ev.date.raw;
                            }
                            table += '</td><td>';
                            if(typeof ev.place != 'undefined' && typeof ev.place.raw != 'undefined'){
                                table += ev.place.raw;
                            }
                            table += '</td></tr>';
                        }
                        $('#details .events').html(table);

                        $('#details').dialog({modal:true});
                    }
                });
            });

            // refocus the tree on someone and move the page so that the tree is in view
            // return false so that the link isn't followed. The link is there for 
            // javascriptless users and brings them to the individual page
            function refocusTree(id){
                pt.refocus(id);
                //window.location.hash='tree';
                //window.location=window.location;
                return false;
            }
        </script> 	
<div id='details' style='display:none'>
    <h3>All about <span class='name'></span></h3>
    <span id='refocuslink' onclick='pt.refocus(this.className);' class=''>Focus Tree on Me</span><br>
    <span id='gotopage' onclick='document.location="individual.php?id=" + this.className;' class=''>See all details</span>
    <h4>Gender</h3>
    <div class='gender'></div>
    <h4>Events</h4>
    <div class='events'></div>
</div>

</body>
</html>
