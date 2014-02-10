$(document).ready(function(){

    dontChangeTabs = false;

    // Initialize the tabs interface. 
    $('h2.blocktitle').hide(); $('.tt-content').tabs({
        activate : function(event,ui){
            // Set the page hash for deeplinking
            dontChangeTabs = true;
            window.location.hash = ui.newTab.children()[0].hash;
        }
    });

    // Make a map between tab IDs and their index
    var tabIdx = {};
    var tabLinks = $('.tt-content > ul li a');
    for(var i = 0;i<tabLinks.length;i++){
        tabIdx[tabLinks[i].getAttribute('href')] = i;
    }


    // Switch tabs to the current hash
    var goToHash = function(){
        if(!dontChangeTabs){
            if(typeof tabIdx[window.location.hash] != 'undefined'){
                $('.tt-content').tabs('option','active',tabIdx[window.location.href]);
            }
        }else{
            dontChangeTabs = false;
        }
    };

    // When the hash changes, trigger tab switch
    window.onhashchange = goToHash;

    // Do initial tab switch
    goToHash();
});
