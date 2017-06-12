/* 
 * Front script for "Singapore Maps" Wordpress plugin
 */

/*
 * Trong Thang(trantrongthang1207@gmail.com)
 */

var gmarkers = [];
var not_done = [];
var map;

jQuery(document).ready(function($) {

    $(document).delegate(".v3-tab-hd", 'click', function() {
        var datatab = $(this).attr('data-target');
        if (datatab == 'grid-view') {
            jQuery(".singapore_maps .fgcback").hide();
            jQuery(".singapore_maps #sidebar-map-singapo").hide();
            jQuery('.google_map, .child-content').hide();
            jQuery('.singapore_maps #regional_map').show().addClass('regional_map');
            jQuery('.singapore_maps #maps_container').hide();
            location.hash = 'pmap';
        } else if (datatab == 'resale-tb') {
            jQuery(".singapore_maps_resale .fgcback").hide();
            jQuery(".singapore_maps_resale #sidebar-map-singapo").hide();
            jQuery('.google_map, .child-content').hide();
            jQuery('.singapore_maps_resale #regional_map').show().addClass('regional_map');
            jQuery('.singapore_maps_resale #maps_container').hide();
            location.hash = 'resalemap';
        } else if (datatab == 'rental-tb') {
            jQuery(".singapore_maps_rental .fgcback").hide();
            jQuery(".singapore_maps_rental #sidebar-map-singapo").hide();
            jQuery('.google_map, .child-content').hide();
            jQuery('.singapore_maps_rental #regional_map').show().addClass('regional_map');
            jQuery('.singapore_maps_rental #maps_container').hide();
            location.hash = 'rentalmap';
        }
    })
    $ = jQuery;
    //code for adding style for mozilla
    var browsers = Array();
    $.each($.browser, function(i, val) {
        browsers[i] = val;
        if (i == 'mozilla') {
        }
    });

    //code for adding browser specific classes
    if (browsers['msie'] && browsers['version'] == '7.0') {
        $("body").addClass('ie7');
        $("body").addClass('ie');
    }
    if (browsers['msie'] && browsers['version'] == '8.0') {
        $("body").addClass('ie8');
        $("body").addClass('ie');
    }
    if (browsers['msie'] && browsers['version'] == '9.0') {
        $("body").addClass('ie9');
        $("body").addClass('ie');
    }
    if (browsers['mozilla']) {
        $("body").addClass('moz');
    }
    if (browsers['webkit']) {
        $("body").addClass('chrome');
    }
    if (browsers['opera']) {
        $("body").addClass('opera');
        //alert($(".container").width())
    }

    // Check for touch screens
    if (Modernizr.touch) {
        var showToolTip = false;
    } else {
        var showToolTip = true;
    }

    // Initialize Address plugin
    jQuery.address.strict(false);
    jQuery.address.externalChange(function(event) {
        if (window.location.hash == '') {

            jQuery('.google_map').hide();
            jQuery('.singapore_maps #regional_map').fadeIn();
        } else if (window.location.hash != '#pmap') {
            var hash = window.location.hash;
            jQuery('.singapore_maps #regional_map').hide();
            jQuery('.singapore_maps #maps_container').show();
            jQuery(hash).fadeIn();
            selectMap(hash.substr(1));
            $(".singapore_maps #sidebar-map-singapo").show();
            $(".singapore_maps #filter_hackerspace[typeregion='" + hash.substr(1) + "']").addClass("inactive");
        } else if (window.location.hash == '#pmap') {
            jQuery('.google_map').hide();
            jQuery('.singapore_maps #regional_map').show();
            return;
        }
    });

    // Initialize ImageMapster plugin
    jQuery('.singapore_maps #regional_map img').mapster({
        fillColor: 'ffffff',
        fillOpacity: 0.3,
        mapKey: 'alt',
        showToolTip: showToolTip,
        onClick: function(event) {
            // console.log('CLICKED');
            jQuery('.singapore_maps #regional_map').hide().removeClass('regional_map');
            jQuery('.singapore_maps #maps_container').show();
            jQuery('.singapore_maps #' + event.key).fadeIn();
            selectMap(event.key);
            jQuery.address.value(event.key);
            jQuery(".singapore_maps .fgcback").show();
            jQuery(".singapore_maps #sidebar-map-singapo").show();
            $(".singapore_maps .category_toggle").removeClass('inactive');
            $(".singapore_maps #filter_hackerspace[typeregion='" + event.key + "']").addClass("inactive");
            $(".singapore_maps #filter_hackerspace[typeregion='" + event.key + "']").parent().next().show();
            /*$('html, body').animate({
             scrollTop: 550 + 'px'
             }, 700);*/
        },
        areas: [
            {key: 'north_map', isSelectable: false, toolTip: mapToolTips.north},
            {key: 'northeast_map', isSelectable: false, toolTip: mapToolTips.northEast},
            {key: 'east_map', isSelectable: false, toolTip: mapToolTips.east},
            {key: 'west_map', isSelectable: false, toolTip: mapToolTips.west},
            {key: 'central_map', isSelectable: false, toolTip: mapToolTips.central},
        ]
    });

    /*
     * Trong Thang(trantrongthang1207@gmail.com)
     */
    jQuery(".singapore_maps .fgctooltip .map-label").live('click', function(e) {
        e.preventDefault();
        var eventkey = jQuery(this).attr("href");

        jQuery('.singapore_maps #regional_map').hide().removeClass('regional_map');
        jQuery('.google_map').hide();
        jQuery('.singapore_maps #maps_container').show();
        jQuery('.singapore_maps #' + eventkey).fadeIn();
        selectMap(eventkey);
        jQuery.address.value(eventkey);

        jQuery(".singapore_maps .fgcback").show();
        $(".singapore_maps #sidebar-map-singapo").show();

        $(".singapore_maps .category_toggle").removeClass('inactive');
        $(".singapore_maps #filter_hackerspace[typeregion='" + eventkey + "']").addClass("inactive");
        $(".singapore_maps #filter_hackerspace[typeregion='" + eventkey + "']").parent().next().show();
        /*$('html, body').animate({
         scrollTop: 550 + 'px'
         }, 700);*/
    })

    jQuery(".singapore_maps .fgcback").live('click', function(e) {
        e.preventDefault();

        jQuery(".singapore_maps .fgcback").hide();
        jQuery(".singapore_maps #sidebar-map-singapo").hide();
        jQuery('.google_map, .child-content').hide();
        jQuery('.singapore_maps #regional_map').show().addClass('regional_map');
        jQuery('.singapore_maps #maps_container').hide();
        location.hash = 'pmap';
        /*$('html, body').animate({
         scrollTop: 550 + 'px'
         }, 700);*/
    })


    /*
     * Hien thi cac icon cua tung khu vuc
     */
    // toggle (hide/show) markers of a given type (on the map)
    $(".singapore_maps #filter_hackerspace").live('click', function() {
        var type = $(this).attr('typeregion')
        if ($(this).is('.inactive')) {
            hide(type, $(this));
        } else {
            show(type, $(this));
        }
    })
    jQuery(".singapore_maps .category_info").live('click', function(e) {
        e.preventDefault();
        var type = $(this).attr('href')
        if ($(this).prev().is('.inactive')) {
            hide(type, $(this).prev());
        } else {
            show(type, $(this).prev());
        }
    })

// hide all markers of a given type
    function hide(type, me) {
        for (var i = 0; i < gmarkers.length; i++) {
            if (gmarkers[i].type == type) {
                gmarkers[i].setVisible(false);
            }
        }
        me.removeClass("inactive");
    }

    // show all markers of a given type
    function show(type, me) {
        //console.log(gmarkers.length)
        for (var i = 0; i < gmarkers.length; i++) {
            // console.log(gmarkers[i])
            if (gmarkers[i].type == type) {
                gmarkers[i].setVisible(true);
            }
        }
        me.addClass("inactive");
    }


    /*
     * Thay su kien click len cac icon tren map
     */

    $(".singapore_maps .child-content li a").live('click', function(e) {
        e.preventDefault();
        var marker_id = $(this).attr('indexicon');
        //console.log(marker_id, gmarkers)
        if (marker_id) {
            map.panTo(gmarkers[marker_id].getPosition());
            map.setZoom(15);
            google.maps.event.trigger(gmarkers[marker_id], 'click');
            //google.maps.event.trigger(gmarkers[marker_id], 'click');
        }
    })

    if (mapScaledHeightimg != '' && mapScaledWidthimg != '') {
        jQuery('.singapore_maps #regional_map img').mapster('resize', mapScaledWidthimg, mapScaledHeightimg, 0);
    }

    // Select Google map
    function selectMap(key) {
        //var lat = 1.364236;
        //var long = 103.836937;
        var lat = 1.365342;
        var long = 103.807411;
        switch (key) {
            case 'north_map':
                mapIcon = '';
                mapIcon = mapIconNorth;
                initGoogleMap(key, 'north_map', lat, long);
                break;
            case 'northeast_map':
                mapIcon = '';
                mapIcon = mapIconNorthEast;
                initGoogleMap(key, 'northeast_map', lat, long);
                break;
            case 'east_map':
                mapIcon = '';
                mapIcon = mapIconEast;
                initGoogleMap(key, 'east_map', lat, long);
                break;
            case 'west_map':
                mapIcon = '';
                mapIcon = mapIconWest;
                initGoogleMap(key, 'west_map', lat, long);
                break;
            case 'central_map':
                mapIcon = '';
                mapIcon = mapIconCentral;
                initGoogleMap(key, 'central_map', lat, long);
                break;
        }
    }

    // Initialize Google map
    function initGoogleMap(elementID, region, latitude, longitude) {
        var mapOptions = {
            zoom: 12, //Giam zoom de hien thi het map cua singapore
            center: new google.maps.LatLng(latitude, longitude),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        };
        map = new google.maps.Map(document.getElementById(elementID), mapOptions);
        var listings = JSON.parse(mapListings);
        //console.log(listings);
        createMapMarkers(map, listings, region);
    }

    // Add markers to Google map
    function createMapMarkers(map, listings, region) {
        // jQuery('#maps_container').show();
        $('html, body').animate({
            scrollTop: jQuery('.v3-singapore-maps').offset().top + 'px'
        }, 700);
        $(".child-content").html('');
        //console.log('listings', listings.length, listings);
        gmarkers = [];

        if (listings) {
            var geocoder = new google.maps.Geocoder();
            var infoBox = new InfoBox({
                closeBoxURL: mapCloseIcon,
                alignBottom: true
            });
            var jj = 0;
            for (var i = 0; i < listings.length; i++) {
                var title = listings[i].title;
                var address = listings[i].address;
                var link = listings[i].link;
                var thumbURL = listings[i].thumbURL;
                var description = listings[i].description;
                var typeRegion = listings[i].type;
                var iconmarker = listings[i].icon;
                (function(title, link, thumbURL, description, typeRegion, iconmarker) {
                    /* geocoder.geocode({'address': address}, function(results, status) { */
                    if (listings[i].lat && listings[i].lng) {
                        // Add marker and bind click event
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(parseFloat(listings[i].lat), parseFloat(listings[i].lng)),
                            map: map,
                            title: title
                        });

                        /*
                         * Day gia tri cua map vao mot mang va duoc danh dau 
                         */
                        marker.type = typeRegion;
                        gmarkers.push(marker);

                        /*
                         * Khong hien thi cac icon cua khu vuoc khong duoc chon
                         */
                        //console.log(typeRegion, region)
                        if (typeRegion != region)
                            marker.setVisible(false);
                        //console.log('test', i, title)
                        if (iconmarker != '') {
                            marker.setIcon(iconmarker);
                        }
                        // Bind marker to click event
                        if (mapDetails) {
                            google.maps.event.addListener(marker, 'click', function() {
                                if (infoBox)
                                    infoBox.close();
                                var content = "<div class='google_details'><div class='google_sidebar'>";
                                if (thumbURL) {
                                    content += "<img class='google_thumbnail' src='" + thumbURL + "' alt='' />";
                                } else {
                                    content += "<img class='google_thumbnail' src='" + mapNoPhoto + "' alt='' />";
                                }

                                content += "<div class='google_readmore'><a href='" + link + "' title=''>" + mapButtonText + "</a></div></div>";
                                content += "</div><a class='title' href='" + link + "' title=''>" + title + "</a>";
                                if (description) {
                                    //  content += "<div>" + description + "</div>";
                                }
                                infoBox.setContent("<div class='google_infobox'>" + content + "</div>");
                                infoBox.setPosition(marker.getPosition());
                                infoBox.open(map, marker);
                            });
                        }
                        /*
                         * Khong thuc hien chem dia chi vao menu trai
                         */
                        //$(".child-content." + typeRegion).append('<li><a indexicon="' + jj + '" href="">' + title + '</a></li>')
                        //jj++;
                        //console.log('mapped' + i);
                    } else {
                        /*the address could not be mapped*/
                        not_done[i] = [i];
                        not_done[i]['typeRegion'] = typeRegion;
                        not_done[i]['index'] = i;
                        not_done[i]['status'] = false;
                    }
                    /*  }); */
                })(title, link, thumbURL, description, typeRegion, iconmarker);
            }

            console.log(not_done);

        }
    }

    //sidebar
    jQuery(".singapore_maps #sidebar-map-singapo ul.list .category a.category_info").click(function() {

        $accordion = jQuery(this).parent().parent().find('.child-content');
        if ($accordion.is(':hidden') === true) {
            // jQuery('.category_toggle').removeClass('inactive');

            jQuery(".singapore_maps #sidebar-map-singapo ul.list .category .child-content").slideUp();
            $accordion.slideDown();
            // jQuery(this).find('.category_toggle').addClass('inactive');
        }
    });


});