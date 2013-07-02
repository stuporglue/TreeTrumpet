(function($){

    /*
    * The TreeTrumpet Table Object
    *
    * Creates a jQuery DataTables table with ancestor information in it
    */

    function TreeTrumpetTable(target,gedcomparserurl,gedcom,options){

        // Start by initializing the objects we'll need
        this.gedcom = gedcom;               // gedcom filename to parse
        this.parserurl = gedcomparserurl;   // gedcom parser URL
        var self = this;                    // Ourself (needed for use inside asyncronous callbacks)
        // this._fetchData = false;

        // this._tableStructure = "<table class='tttable'><thead><tr><th>ID</th><th>Name</th><th>Gender</th><th>Parents</th><th>Children</th><th>Events</th></tr></thead><tbody></tbody></table>";

        target = $(target)[0];   
        $(target).addClass('tttablecontainer');


        // Check if the target div has a noscript tag and remove it
        // $(target).find('noscript').remove();

        // Now see if we already have the needed divs, otherwise wipe it out
        //if($(target).find('.tttablectrl').length === 0){
        //    $(target).append("<div class='tttablectrl'></div>");
        //}
        //if($(target).find('.tttablediv').length === 0){
        //    this._fetchData = true;
        //    $(target).find('.tttablectrl').append("<div class='tttablediv'>" + this._tableStructure + "</div>");
        //}
        //if($(target).find('.tttablediv').find('.tttable').length === 0){
        //    this._fetchData = true;
        //    $(target).find('.tttablediv').append(this._tableStructure);
        //}


        this.options = options || {};

        this._tttablectrl = $(target).find('.tttablectrl');
        this._tttablediv = $(target).find('.tttablediv');
        this._tttable = $(target).find('.tttable');
        this._target = target;


        /* Public functions */

        this.addAncestorsToTable = function(){
            var params = {
                g:this.gedcom
            };

            $.getJSON(this.parserurl,params,self.formatGedJson);
        };

        this.formatGedJson = function(json){
            self.json = json;
            $(self._tttable).dataTable().fnAddData(self.json);
        };

        /* Private Functions */

        this._formatParents = function(source,type,val){
            if(type == 'set'){ return source; }

            var i;
            var cell = '';
            if(typeof source.fathers != 'undefined'){
                for(i = 0;i<source.fathers.length;i++){
                    cell += "<span class='ttname'><a href='"+source.fathers[i]+"'>" +  self.json[source.fathers[i]].name.replace(/\/(.*)\//,"<span class='ttln'>$1</span>") + "</a></span>";
                }
            }

            if(typeof source.mothers != 'undefined'){
                for(i = 0;i<source.mothers.length;i++){
                    cell += "<span class='ttname'><a href='"+source.mothers[i]+"'>" +  self.json[source.mothers[i]].name.replace(/\/(.*)\//,"<span class='ttln'>$1</span>") + "</a></span>";
                }
            }
            return cell;
        };

        this._formatChildren = function(source,type,val){
            if(type == 'set'){ return val; }
            if(typeof source.children == 'undefined'){
                return '';
            }
            var i;
            var cell = '';
            for(i = 0;i<source.children.length;i++){
                cell += "<span class='ttname'><a href='"+source.children[i]+"'>" +  self.json[source.children[i]].name.replace(/\/(.*)\//,"<span class='ttln'>$1</span>") + "</a></span>";
            }
            return cell;
        };

        this._formatEvents = function(source,type,val){
            if(type == 'set'){ return val; }

            if(typeof source.events == 'undefined'){
                return '';
            }

            var i;
            var cell = '';
            for(i = 0;i<source.events.length;i++){
                cell += "<span class='ttevent'>"; 
                if(typeof source.events[i].date != 'undefined' && typeof source.events[i].date.raw){
                    cell += source.events[i].date.raw;
                }
                if(typeof source.events[i].place!= 'undefined' && typeof source.events[i].place.raw){
                    cell += source.events[i].place.raw;
                }
                cell += "</span>";
            }
            return cell;
        };

        this._init = function(){

            $(this._tttable).dataTable();
            // {
            //    'aoColumnDefs' : [
            //        { "sType": "html", "aTargets": ['_all'] }
            //    ],
            //    'aoColumns' : [
            //        {'mData' : 'id' }, // id 
            //        {'mData' : 'name'}, // name
            //        {'mData' : 'gender'}, // gender
            //        {'mData' : self._formatParents }, // parents
            //        {'mData' : self._formatChildren }, // children
            //        {'mData' : self._formatEvents } // event
            //    ]
            //}
            // );


            //if(this._fetchData){
            //    this.addAncestorsToTable();
            //}

            $(this._target).on('click','.ttclose',function(t){
                $(self._tttable).dataTable().fnFilter('',0);
                $(self._tttable).dataTable().fnFilter('');
                $(self._tttablectrl).html("");
            });

            $(this._tttable).on('click','a.ttpersonlink',function(t){
                if(typeof t.target.hash == 'undefined'){
                    t.target = t.target.parentNode;
                }
                var target = t.target.hash.replace('#','');
                $(self._tttable).dataTable().fnFilter(target,0);
                $(self._tttablectrl).html("Filtering on: " + target + " <span class='ttclose'>(X) Clear</span>");
            });
        };



        /* Start it up */
        this._init();
    }

    $.fn.ttTable = function(gedcomparserurl,gedcom,options){
        return new TreeTrumpetTable(this,gedcomparserurl,gedcom,options);
    };
})(jQuery);
