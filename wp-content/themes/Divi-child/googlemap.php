<?php

/*
  Created on : Nov 4, 2016, 4:07:24 PM
  Author     : Tran Trong Thang
  Email      : trantrongthang1207@gmail.com
  Skype      : trantrongthang1207
 */
/**
 * The template for displaying the Logos.
 * Template name: Google map
 * 
 */
defined('ABSPATH') or die;
get_header();
?>
<script>
    jQuery(document).ready(function ($) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {'action': 'searchmap'},
            dataType: 'html',
            success: function (data, textStatus, jqXHR) {
                //console.log(data)
            }
        })
    })
</script>
<div class="tvsearch row">
       
        <div class="tvformsearch">
            <hr/>
            <link rel='stylesheet' id='lctrStylesheet1-css'  href='http://17jason25.tv.net/shop/skin/frontend/base/default/locatoraid-pro/assets/css/lpr.css' type='text/css' media='all' />
            <link rel='stylesheet' id='lctrStylesheet2-css'  href='http://17jason25.tv.net/shop/skin/frontend/base/default/locatoraid-pro/assets/css/hitcode-wp.css' type='text/css' media='all' />

            <script type='text/javascript'>
                /* <![CDATA[ */
                var url_prefix = "/wp-admin/admin-ajax.php/action=initmap";
                /* ]]> */
            </script>
            <script src="http://wp.tv.net/wp-content/plugins/learn_wp/locatoraid-pro/assets/js/lpr.js" type="text/javascript"></script>
            <script type='text/javascript'>
                        /* <![CDATA[ */
                        var lpr_vars = {"conf_trigger_autodetect": "0", "conf_append_search": "Australia", "start_listing": "0", "map_scrollwheel": "1", "show_print_link": "Print View", "show_matched_locations": "Matched Locations", "directions_in_new_tab": "0"};
                /* ]]> */
            </script>
            <script src="http://wp.tv.net/wp-content/plugins/learn_wp/locatoraid-pro/assets/js/lpr-front.js" type="text/javascript"></script>
            <script type='text/javascript' src='//maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&language=en_US&key=AIzaSyC3si2ppsuHO-eHcMPv90Z_g527jPwG40k&ver=4.7.2'></script>
            <script type='text/javascript' src='//cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js'></script>

            <div class="hc">



                <form action="/retailers/?/search" method="post" accept-charset="utf-8" id="lpr-search-form" class="form-horizontal form-condensed"><div style="display:none">
                        <input type="hidden" name="hc_csrf_token" value="76086ac766a98408744e0be951130a45" />
                    </div>

                    <input type="hidden" name="country" value="" />
                    <input type="hidden" name="priority" value="8" />

                    <input type="hidden" name="search2" value="" />

                    <ul class="list-unstyled list-margin-v">

                        <li style="display: none; text-align: left;">
                            <a rel="nofollow" href="#" id="lpr-skip-current-location">Enter your postcode</a>
                        </li>

                        <li>



                            <div class="hc-clearfix hc-mxn2">
                                <div class="hc-sm-col hc-px2 hc-sm-col-7" style="white-space: nowrap; margin-bottom:.25em; text-align: left;">
                                    <div id="lpr-current-location" style="display: none;">Your location</div> <input type="text" name="search" value="" id="lpr-search-address" style="margin: 0 0; width: 95%;" class="" placeholder="Enter your postcode"  />	</div>
                                <div class="hc-sm-col hc-px2 hc-sm-col-2" style="white-space: nowrap; margin-bottom:.25em; text-align: left;">
                                    <select name="within" id="lpr-search-within" style="margin: 0 0; width: 100%;">
                                        <option value="25">25 km</option>
                                        <option value="50">50 km</option>
                                        <option value="100">100 km</option>
                                        <option value="200">200 km</option>
                                    </select>	</div>
                                <div class="hc-sm-col hc-px2 hc-sm-col-3" style="white-space: nowrap; margin-bottom:.25em; text-align: left;">

                                    <input type="submit" id="lpr-search-button" value="Search" style="margin: 0 0; width: 100%; background: #c0c0c0">
                                </div>
                            </div>

                        </li>
                    </ul>

                </form>
                <div id="lpr-results" class="hc-clearfix hc-mxn2">
                    <div id="lpr-map" class="hc-sm-col hc-sm-col-8 hc-px2"></div>
                    <div id="lpr-locations" class="hc-sm-col hc-sm-col-4 hc-px2"></div>

                </div>
            </div>
            
        </div>
    </div>
<?php

wp_reset_query();
get_footer();
?>