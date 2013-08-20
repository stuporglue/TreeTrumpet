$(document).ready(function(){
    pt = $('#tt-tree').pvTree('lib/ged2json.php','family.ged',{
        personClick : function(e){
            var id = e.target.id.replace('person_','');

            $('#refocuslink')[0].className = id;
            $('#gotopage')[0].href = linky(ttbaseurl + '/individual.php/' + id + '/' + pt.people[id].name);

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
