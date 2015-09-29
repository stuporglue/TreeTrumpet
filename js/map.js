(function($){
    /*
    * The TreeTrumpetMapViewer object
    *
    * Creates a Leaflet.js map and populates it with the contents of a GEDCOM file
    *
    * @param target             -- the jQuery element (a div) which will hold the map
    * @param gedcomparserurl    -- Url to a script that will take the required parsing parameters
    * @param gedcom             -- Relative path to the gedcom file we're going to use
    * @param options            -- Hash of options (none used yet!)
    *
    *
    * About gedcomparserurl
    *
    * Get Parameters are: 
    * g -- the name of the gedcom file to parse. It is up to gedcomparserurl to determine the absolute path 
    */
    function TreeTrumpetMapViewer(target,gedcomparserurl,gedcom,options){


        // Start by initializing the objects we'll need
        this.ttmap = null;                  // Leaflet map object
        this.ttslider = null;               // The time slider
        this.ttancestors = null;            // All our ancestors
        this._ttancestors = [];             // Cache of all our ancestors
        this.parserurl = gedcomparserurl;   // gedcom parser URL
        this.gedcom = gedcom;               // gedcom filename to parse
        var self = this;                    // Ourself (needed for use inside asyncronous callbacks)
        this._unmapped_ancestors = [];      

        // Initialize the map
        // Target is a div which will hold the map. We replace its contents with our divs.
        target = $(target)[0];   
        $(target).addClass('ttmapcontainer');
        $(target).html("<div class='ttmap'></div><div class='ttslider'></div>"); // clear it out
        this.options = options || {};
        this.ttmap = new L.Map(target.children[0], {
            maxZoom : 18,
            center : [0,0],
            zoom : 2
        }
        );

        // Initialize the slider object
        this.ttslider = $(target.children[1]).editRangeSlider({
            type: 'number',
            step:1,
            arrows:false,
            bounds:{min: 1900, max: new Date().getFullYear()},
            defaultValues:{min: 1900, max: new Date().getFullYear()},
            wheelMode: "scroll"
        }).bind('valuesChanged',
            function(e,data){
                self._filterOnDates(e,data);
            }
        );


        // Add a base map
        // Our base map is a free MapQuest/OSM map
        // http://developer.mapquest.com/web/products/open/map#terms
        var mapquestUrl = '//{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png';
        var subDomains;
        if(window.location.protocol === 'https:'){
            subDomains = ['otile1-s','otile2-s','otile3-s','otile4-s'];
        } else{
            subDomains = ['otile1','otile2','otile3','otile4'];
        }
        var mapquestAttrib = '<p>Tiles Courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png"></p>';
        var basemap = new L.TileLayer(mapquestUrl, {maxZoom: 18, attribution: mapquestAttrib, subdomains: subDomains}).addTo(this.ttmap);

        // This cluster layer lets our map work with tons of ancestors at once. 
        // Too many icons on a web map make it unresponsive 
        // This layer holds our ancestors markers
        this.ttancestors = new L.MarkerClusterGroup({
            zoomToBoundsOnClick: false,
            showCoverageOnHover: false,
            spiderfyOnMaxZoom: false,
            removeOutsideVisibleBounds:true,
            maxClusterRadius: 40,
            singleMarkerMode: true,

            iconCreateFunction: function(cluster) {
                var children = cluster.getAllChildMarkers();
                var gender = children[0].feature.properties.gender;
                for(var i = 0;i<children.length;i++){
                    if(gender != children[i].feature.properties.gender){
                        // we've got a mix. Run with it
                        return self._makeSingleIcon('mixed','<b>' + children.length + '</b>',children.length);
                    } 
                }
                return self._makeSingleIcon(gender,'<b>' + children.length + '</b>',children.length);
            }
        }).addTo(this.ttmap);

        // Now add an onclick handler to the clusters. 
        // If we're zoomed to the max level, show the popup no matter what
        // If we're not zoomed to max, but we only have 3 or less ancestors show the popup
        // If we're not zoomed to max and have > 3 ancestors, then zoom in
        this.ttancestors.on('clusterclick',function(cluster){
            var children = cluster.layer.getAllChildMarkers();
            if(self.ttmap.getZoom() === self.ttmap.getMaxZoom() || children.length <= 3){
                var html = "<div class='ttscrollpopup'>";
                // TODO: Sort by birthdate: children.sort(function(a,b){ });
                for(var i = 0;i<children.length;i++){
                    html += self._makePopupHTML(children[i].feature.properties);
                }
                html += "</div>";
                L.popup().setLatLng(cluster.layer._latlng).setContent(html).openOn(self.ttmap);
            }else{
                cluster.layer.zoomToBounds();
            }
        });

        /**
        * @brief Bind a single popup
        */
        this._makeSinglePopup = function(feature){
            feature.bindPopup(self._makePopupHTML(feature.feature.properties));
        };

        /**
        * @brief Create a single popup's HTML
        *
        * @param properties (required) The ancestor's properties 
        */
        this._makePopupHTML = function(properties){
            var popup = "<div class='ttsinglepopup'>";

            // Name and gender span
            popup += "<span class='";
            switch(properties.gender){
                case 'M':
                    popup += 'ttgender-male';
                    break;
                case 'F':
                    popup += 'ttgender-female';
                    break;
            }
            popup += "'>";
            popup += properties.name;
            popup += "</span>";

            // Events list
            if(properties.events.length > 0){
                popup += "<table>";
                for(var i = 0;i<properties.events.length;i++){
                    popup += "<tr>";
                    popup += "<td>" + properties.events[i].type + "</td>";
                    if(typeof properties.events[i].date != 'undefined' && typeof properties.events[i].date.raw != 'undefined'){
                        popup += "<td>" + properties.events[i].date.raw+ "</td>";
                    }else{
                        popup += "<td></td>";
                    }
                    if(typeof properties.events[i].place != 'undefined' && typeof properties.events[i].place.raw != 'undefined'){
                        popup += "<td>" + properties.events[i].place.raw + "</td>";
                    }else{
                        popup += "<td></td>";
                    }
                    popup += "</tr>";
                }
                popup += "</table>";
            }

            popup += "</div>";
            return popup;
        };

        /*
        * @brief Add new ancestors to the map
        *
        * Fetches a list of ancestors from a gedcom file using a getJSON call and callback function
        *
        * A future version might accept parametrs for lazy loading
        */
        this.addAncestorsToMap = function(){
            var params = {
                g:this.gedcom
            };

            $.getJSON(this.parserurl,params,function(json){
                var tmp;

                for(var i = 0;i<json.features.length;i++){
                    if(json.features[i].geometry === null){
                        self._unmapped_ancestors.push(json.features[i]);
                        continue;
                    }

                    tmp = new L.GeoJSON(json.features[i], { 
                        style: self._makeSingleIcon(json.features[i].properties.gender) 
                    }); 

                    tmp.eachLayer(self._makeSinglePopup);
                    tmp.eachLayer(self._ttancestorsPush);
                }

                self._resortAncestors();

                // Add ancestors
                self._filterOnDates();
            });
        };

        /**
        * @brief Change which date and place are the refdate/place
        *
        * @param eventType (required) A string of a GEDCOM event type. 
        * @param failover (optional) Set to "first" or "last" to specify which event should be used if the given event is not found.
        *
        * @note If failover is specified the date and place may come from different types of events
        */
        this.usePlaceFrom = function(eventType,failover){

            failover = failover || false;

            var refdate;
            var refplace;

            var failoverplace;
            var failoverdate;

            for(var i = 0;i<self._ttancestors.length;i++){
                self._unmapped_ancestors.push(self._ttancestors[i].feature);
            }
            self._ttancestors = [];

            refdate = undefined;
            refplace = undefined;
            failoverdate = undefined;
            failoverplace = undefined;


            // count down so as we splice people out we don't have to move the cursor
            for(i = self._unmapped_ancestors.length - 1;i>=0;i--){
                // On initial load ancestors may have a refplace that's not in the list of requested types
                // save it of so we can get back to it
                if(typeof self._unmapped_ancestors[i].properties.refplace != 'undefined'){
                    self._unmapped_ancestors[i].properties._refplace = self._unmapped_ancestors[i].properties.refplace;
                }
                if(typeof self._unmapped_ancestors[i].properties.refdate != 'undefined'){
                    self._unmapped_ancestors[i].properties._refdate = self._unmapped_ancestors[i].properties.refdate;
                }

                delete self._unmapped_ancestors[i].properties.refdate;
                delete self._unmapped_ancestors[i].properties.refplace;

                // soft failover for when we pull date/place from different events of the same type
                refdate = undefined;
                refplace = undefined;
                failoverdate = undefined;
                failoverplace = undefined;

                if(typeof self._unmapped_ancestors[i].properties.events ==  'undefined'){
                    self._unmapped_ancestors[i].properties.events = [];
                }

                for(var e = 0;e < self._unmapped_ancestors[i].properties.events.length;e++){
                    if(self._unmapped_ancestors[i].properties.events[e].type == eventType){
                        // Give priority to events that have
                        // pm._ttancestors[0].feature.properties.events[0].place.geo.geometry
                            if(
                                typeof  self._unmapped_ancestors[i].properties.events[e].place != 'undefined' && 
                                typeof  self._unmapped_ancestors[i].properties.events[e].place.geo != 'undefined' && 
                                typeof  self._unmapped_ancestors[i].properties.events[e].place.geo.geometry != 'undefined' &&
                                typeof  self._unmapped_ancestors[i].properties.events[e].date != 'undefined' &&
                                typeof  self._unmapped_ancestors[i].properties.events[e].date.y != 'undefined'
                            ){
                                self._unmapped_ancestors[i].properties.refplace = self._unmapped_ancestors[i].properties.events[e].place.geo.geometry;
                                self._unmapped_ancestors[i].properties.refdate = self._unmapped_ancestors[i].properties.events[e].date;
                                break;  // Nailed it!
                            } else if(
                                typeof  self._unmapped_ancestors[i].properties.events[e].place != 'undefined' && 
                                typeof  self._unmapped_ancestors[i].properties.events[e].place.geo != 'undefined' && 
                                typeof  self._unmapped_ancestors[i].properties.events[e].place.geo.geometry != 'undefined'
                            ){
                                // Grab the first place we find.
                                if(typeof refplace == 'undefined'){
                                    refplace = self._unmapped_ancestors[i].properties.events[e].place.geo.geometry;
                                }else if(failover == 'last'){
                                    // If failover == 'last' then keep grabing places until we run out of places
                                    refplace = self._unmapped_ancestors[i].properties.events[e].place.geo.geometry;
                                }
                            } else if(
                                typeof  self._unmapped_ancestors[i].properties.events[e].date != 'undefined' &&
                                typeof  self._unmapped_ancestors[i].properties.events[e].date.y != 'undefined' 
                            ){
                                if(typeof refdate == 'undefined'){
                                    refdate = self._unmapped_ancestors[i].properties.events[e].date;
                                }else if(failover == 'last'){
                                    refdate = self._unmapped_ancestors[i].properties.events[e].date;
                                }
                            }
                    }else if(failover !== false){
                        if(
                            typeof  self._unmapped_ancestors[i].properties.events[e].place != 'undefined' && 
                            typeof  self._unmapped_ancestors[i].properties.events[e].place.geo != 'undefined' && 
                            typeof  self._unmapped_ancestors[i].properties.events[e].place.geo.geometry != 'undefined'
                        ){
                            // Grab the first place we find.
                            if(typeof failoverplace == 'undefined'){
                                failoverplace = self._unmapped_ancestors[i].properties.events[e].place.geo.geometry;
                            }else if(failover == 'last'){
                                // If failover == 'last' then keep grabing places until we run out of places
                                failoverplace = self._unmapped_ancestors[i].properties.events[e].place.geo.geometry;
                            }
                        } 

                        if(
                            typeof  self._unmapped_ancestors[i].properties.events[e].date != 'undefined' && 
                            typeof  self._unmapped_ancestors[i].properties.events[e].date.y != 'undefined'
                        ){
                            if(typeof failoverdate == 'undefined'){
                                failoverdate = self._unmapped_ancestors[i].properties.events[e].date;
                            }else if(failover == 'last'){
                                failoverdate = self._unmapped_ancestors[i].properties.events[e].date;
                            }
                        }
                    }
                }

                // An exact match was not found. Set what we can with what we've got.
                if(typeof self._unmapped_ancestors[i].properties.refdate == 'undefined'){
                    if(typeof refdate != 'undefined'){
                        self._unmapped_ancestors[i].properties.refdate = refdate;
                    }else if(failover !== 'false' && failoverdate){
                        self._unmapped_ancestors[i].properties.refdate = failoverdate;
                    }else if(failover !== false && typeof self._unmapped_ancestors[i].properties._refdate != 'undefined'){
                        self._unmapped_ancestors[i].properties.refdate = self._unmapped_ancestors[i].properties._refdate;
                    }
                }

                if(typeof self._unmapped_ancestors[i].properties.refplace == 'undefined'){
                    if(typeof refplace != 'undefined'){
                        self._unmapped_ancestors[i].properties.refplace = refplace;
                    }else if(failover !== false && failoverplace){
                        self._unmapped_ancestors[i].properties.refplace = failoverplace;
                    }else if(failover !== false && typeof self._unmapped_ancestors[i].properties._refplace != 'undefined'){
                        self._unmapped_ancestors[i].properties.refplace = self._unmapped_ancestors[i].properties._refplace;
                    }
                }

                // Move anyone without a place to the unmapped list
                if(typeof self._unmapped_ancestors[i].properties.refplace != 'undefined'){
                    self._unmapped_ancestors[i].geometry = self._unmapped_ancestors[i].properties.refplace;
                    tmp = new L.GeoJSON(self._unmapped_ancestors[i], { 
                        style: self._makeSingleIcon(self._unmapped_ancestors[i].properties.gender) 
                    }); 
                    self._unmapped_ancestors.splice(i,1);

                    tmp.eachLayer(self._makeSinglePopup);
                    tmp.eachLayer(self._ttancestorsPush);
                }
            }

            this._resortAncestors();
            this._filterOnDates();
        };

        /**
        * @brief Resort ancestors by their refdate
        */
        this._resortAncestors = function(){
            var dateparts = ['y','m','d'];
            self._ttancestors.sort(function(a,b){
                if(typeof a.feature.properties.refdate == 'undefined' && typeof b.feature.properties.refdate == 'undefined'){
                    return 0;
                }

                if(typeof a.feature.properties.refdate == 'undefined'){
                    return -1;
                }

                if(typeof b.feature.properties.refdate == 'undefined'){
                    return -1;
                }

                var diff;
                for(var i = 0;i<dateparts.length;i++){
                    if(typeof a.feature.properties.refdate[dateparts[i]] == 'undefined' && typeof b.feature.properties.refdate[dateparts[i]] == 'undefined'){
                        return 0;
                    }

                    if(typeof a.feature.properties.refdate[dateparts[i]] == 'undefined'){
                        return -1;
                    }

                    if(typeof b.feature.properties.refdate[dateparts[i]] == 'undefined'){
                        return 1; 
                    }

                    diff = parseInt(a.feature.properties.refdate[dateparts[i]],10) - parseInt(b.feature.properties.refdate[dateparts[i]],10);

                    if(diff !== 0){
                        return diff;
                    }
                }

                return 0;
            });

            this._setSliderMinMax();
        };

        /**
        * @brief Push an ancestor into the ancestors array
        */
        this._ttancestorsPush = function(l){
            self._ttancestors.push(l);
        };

        /*
        * @brief Make a L.DivIcon marker with appropriate classes
        *
        * Markers have two classes, a gender class and a count class
        *
        * Clusters with more ancestors are bigger. Current breaks are at 1,2-10 and >10
        *
        * @param gender -- A gender (M,F,mixed) or a feature (so this can be used with map.eachLayer())
        * @param html -- The html to show inside the marker
        * @param count -- The number of ancestors in this marker
        */
        this._makeSingleIcon = function(gender,html,count) {

            html = html || ' ';
            count = count || 1;

            if(typeof gender.properties != 'undefined'){
                gender = gender.properties.gender;
            }

            var className = 'ttgender-icon ';
            switch(gender){
                case 'M':
                    className += 'ttgender-male ';
                    break;
                case 'F':
                    className += 'ttgender-female ';
                    break;
                case 'mixed':
                    className += 'ttgender-mixed ';
                    break;
                default:
                    className += 'ttgender-unknown ';
                    break;
            }

            if(count == 1){
                className += 'one';
            }else if(count <= 10){
                className += 'some';
            }else{
                className += 'many';
            }

            return new L.DivIcon({ className : className ,html: html });

        };

        /**
        * @brief Redraw the clusters based on a date range
        *
        * @param e (optional) The event which triggered this redraw
        *
        * @param data (optiona) The values to use for filtering
        *
        * @note This is a callback for ttslider
        */
        this._filterOnDates = function(e,data){
            e = e || null;
            data = data || {values:{min: self.ttslider.minDate, max: self.ttslider.maxDate}};

            var wereFiltered = [];
            if(data.values.min == self.ttslider.minDate && data.values.max == self.ttslider.maxDate){
                wereFiltered = self._ttancestors;
            }else{
                // Assuming points are sorted...
                for(var i  = 0;i<self._ttancestors.length;i++){
                    if(typeof self._ttancestors[i].feature.properties.refdate == 'undefined' || typeof self._ttancestors[i].feature.properties.refdate.y == 'undefined'){
                        continue;
                    }
                    if(self._ttancestors[i].feature.properties.refdate.y < data.values.min){
                        continue;
                    }
                    if(self._ttancestors[i].feature.properties.refdate.y > data.values.max){
                        break;
                    }
                    wereFiltered.push(self._ttancestors[i]);
                }
            }
            self.ttancestors.clearLayers();
            self.ttancestors.addLayers(wereFiltered);
        };

        this._setSliderMinMax = function(){

            // Set new min/max dates for the slider
            for(i = 0;i<self._ttancestors.length;i++){
                if(
                    typeof self._ttancestors[i].feature.properties.refdate != 'undefined' &&
                    typeof self._ttancestors[i].feature.properties.refdate.y != 'undefined'
                ){
                    self.ttslider.minDate = parseInt(self._ttancestors[i].feature.properties.refdate.y,10); 
                    break;
                }
            }

            for(i = self._ttancestors.length - 1;i > 0;i--){
                if(
                    typeof self._ttancestors[i].feature.properties.refdate != 'undefined' &&
                    typeof self._ttancestors[i].feature.properties.refdate.y != 'undefined'
                ){
                    self.ttslider.maxDate = parseInt(self._ttancestors[i].feature.properties.refdate.y,10); 
                    break;
                }
            }
            self.ttslider.editRangeSlider('bounds',self.ttslider.minDate,self.ttslider.maxDate);
            self.ttslider.editRangeSlider('min',self.ttslider.minDate);
            self.ttslider.editRangeSlider('max',self.ttslider.maxDate);
        };


        // At this point everything is initialized and we can actually 
        // load the ancestors onto the map
        this.addAncestorsToMap();
    }

    $.fn.ttMap = function(gedcomparserurl,gedcom,options){
        return new TreeTrumpetMapViewer(this,gedcomparserurl,gedcom,options);
    };
})(jQuery);
