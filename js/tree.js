$(document).ready(function(){
    pt = $('#tt-tree').pvTree('lib/ged2json.php?focus=' + focus_person_id,'family.ged',{
        focusPerson : focus_person_id,
        personClick : function(e){
            var id = e.target.id.replace('person_','');

            $('#refocuslink')[0].className = id;
            $('#gotopage')[0].href = linky(ttbaseurl + '/individual.php/' + id + '/' + pt.people[id].name.replace(/[^a-zA-Z0-9\.]+/g,'-').replace(/-$/,'').replace(/^-/,''));

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
            $('#details').dialog({modal:true,width: 500});
        }
    });


    var autocompleteNames = $('.ancestorlist li a').map(function(i,e){return $(e).text();});
    var filter = function(e){
             var matcher = new RegExp(e.target.value, "i" ); 
             var foundone = false;
             var matches = [];
             for(var i = 0;i<autocompleteNames.length;i++){
                if(matcher.test(autocompleteNames[i])){
                    matches.push(autocompleteNames[i]);
                    $($('.ancestorlist li a')[i].parentNode).show();
                }else{
                    $($('.ancestorlist li a')[i].parentNode).hide();
                }
             }
             return matches;
        };
    $('#autocomplete').on('mousedown',filter);
    $('#autocomplete').on('keyup',filter);
    $('#autocomplete').parent().show();
    $('#autocomplete').val('');
    $('#searchhelp').on('click',function(e){
        $('#help').dialog({width: 500});
    });
    $('a.refocusTree').on('click',function(e){
        // climb up until we find the refocusTree class
        while(e.target.parentNode !== null && !e.target.className.match(/refocusTree/)){
            e.target = e.target.parentNode;
        }       
        if(e.target.parentNode !== null){
            refocusTree(e.target);
        }
        return false;
    });
});

// refocus the tree on someone and move the page so that the tree is in view
// return false so that the link isn't followed. The link is there for 
// javascriptless users and brings them to the individual page
function refocusTree(node){
    var id = $(node).attr('data-id');
    refocusTree.focused = refocusTree.focused || [];

    if(refocusTree.focused.indexOf(id) === -1){
        $.getJSON('lib/ged2json.php?focus=' + id,function(json){
            for(var i = 0;i<json.length;i++){
                pt.people[json[i].id] = json[i];
            } 
            pt.refocus(id);
        });

    }else{
        pt.refocus(id);
    }

    // Don't follow link...
    return false;
}
