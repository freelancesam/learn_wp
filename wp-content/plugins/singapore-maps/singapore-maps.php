<?php
/*
  Plugin Name: Singapore Maps
  Description: A regional map of Singapore which opens a Google map of listings.
  Version: 1.6.0
  Author: sydcode
  Author URI: http://www.freelancer.com.au/u/sydcode.html

  Instructions:
  Copy the "singapore-maps" folder to the "wp-content/plugins" folder.
  Login and activate the plugin in the dashboard plugins panel.
  Edit the settings using the link in the dashboard settings menu.

  Regions:
  The region for a listing may be selected in the metabox, or the listing
  may be assigned to one of the following categories (taxonomies):
  "Central", "East", "North", "North-East", "West"

  Support:
  sydcode@gmail.com
  http://www.freelancer.com.au/u/sydcode.html
 */

define('SM_VERSION', '1.6.0');
define('SM_DEFAULT_HEIGHT', 362);
define('SM_DEFAULT_WIDTH', 583);
define('SM_DEFAULT_INFOBOX_WIDTH', 400);

//add by Ho Ngoc Hang
define('SM_GMAP_DEFAULT_HEIGHT', 550);
define('SM_GMAP_DEFAULT_WIDTH', 950);

define('SM_NORTH_ICON', 'http://represent.la/images/icons/startup.png');
define('SM_NORTH_EAST_ICON', 'http://represent.la/images/icons/accelerator.png');
define('SM_WEST_ICON', 'http://represent.la/images/icons/incubator.png');
define('SM_EAST_ICON', 'http://represent.la/images/icons/investor.png');
define('SM_CENTRAL_ICON', 'http://represent.la/images/icons/event.png');

/**
 * Enqueue scripts and stylesheets for settings panel
 */
function sm_enqueue_admin_scripts() {
    wp_enqueue_style('jquery-minicolours-style', plugins_url('minicolors/jquery.minicolors.css', __FILE__), array(), '2.0');
    wp_enqueue_script('jquery-minicolours-script', plugins_url('minicolors/jquery.minicolors.js', __FILE__), array('jquery'), '2.0');
    wp_enqueue_script('sm-admin-script', plugins_url('js/admin.js', __FILE__), array('jquery', 'jquery-minicolours-script'), SM_VERSION);
}

/**
 * Enqueue scripts and stylesheets then localize data for scripts
 */
function sm_enqueue_scripts() {
    $settings = get_option('sm_settings');

    // Load styles
    wp_enqueue_style('sm-style', plugins_url('style.css', __FILE__), array(), SM_VERSION);


    $styles = '';
    if (!empty($settings['normal-color'])) {
        $styles .= '#singapore_maps .google_readmore a { background-color: ' . $settings['normal-color'] . '; }' . PHP_EOL;
    }
    if (!empty($settings['font-color'])) {
        $styles .= '#singapore_maps .google_readmore a { color: ' . $settings['font-color'] . '; }' . PHP_EOL;
    }
    if (!empty($settings['hover-color'])) {
        $styles .= '#singapore_maps .google_readmore a:hover { background-color: ' . $settings['hover-color'] . '; }' . PHP_EOL;
    }
    if (!empty($settings['infobox-width'])) {
        $styles .= '#singapore_maps .google_infobox { width: ' . $settings['infobox-width'] . 'px; }';
    }
    if (!empty($styles)) {
        wp_add_inline_style('sm-style', $styles);
    }

    // Load scripts
    wp_enqueue_script('modernizr', plugins_url('js/modernizr.custom.62242.js', __FILE__), array(), '2.6.2');
    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array(), '3');
    wp_enqueue_script('google-infobox', plugins_url('js/infobox_packed.js', __FILE__), array('google-maps'), '1.1.12');
    wp_enqueue_script('jquery-address', plugins_url('js/jquery.address.min.js', __FILE__), array('jquery'), '1.5');
    wp_enqueue_script('jquery-imagemapster', plugins_url('js/jquery.imagemapster.min.js', __FILE__), array('jquery'), '1.2.10');
    $deps = array('modernizr', 'google-maps', 'google-infobox', 'jquery', 'jquery-ui-tabs', 'jquery-address', 'jquery-imagemapster');
    wp_enqueue_script('sm-script', plugins_url('js/script.js', __FILE__), $deps, SM_VERSION);

    /*
     * Changed date: 30 July 2014
     * Tran Trong Thang <trantrongthang1207@gmail.com>
     */
    // wp_enqueue_script('sm-rental-script', plugins_url('js/rentalscript.js', __FILE__), $deps, SM_VERSION);
    // Set icon for Google Maps
    if (empty($settings['icontype'])) {
        $url = '';
    } else {
        switch ($settings['icontype']) {
            case 'building':
                $url = plugins_url('images/building.png', __FILE__);
                break;
            case 'custom':
                $url = $settings['iconurl'];
                break;
            default:
                $url = '';
        }
    }
    wp_localize_script('sm-script', 'mapIcon', $url);

    // Set markers for Google Maps
    $listings = sm_get_listings1();
    //var_dump($listings);
    //echo json_encode($listings);
    wp_localize_script('sm-script', 'mapListings', json_encode($listings));

    //$listings1 = sm_get_rental_listings1();
    //var_dump($listings);
    //echo json_encode($listings);
    // wp_localize_script('sm-rental-script', 'rentalmapListings', json_encode($listings1));
    // Set tooltips for regional map
    if (empty($settings['tooltips'])) {
        $tooltips = array('north' => '', 'northEast' => '', 'east' => '', 'west' => '', 'central' => '');
    } else {
        // Count listings
        $north = empty($listings['North']) ? '0' : count($listings['North']);
        $northeast = empty($listings['North-East']) ? '0' : count($listings['North-East']);
        $east = empty($listings['East']) ? '0' : count($listings['East']);
        $west = empty($listings['West']) ? '0' : count($listings['West']);
        $central = empty($listings['Central']) ? '0' : count($listings['Central']);
        // Create tooltips
        $tooltips = array(
            'north' => '<div style="text-align: center;"><strong>North Region</strong><br />' . $north . ' Properties</div>',
            'northEast' => '<div style="text-align: center;"><strong>North-East Region</strong><br />' . $northeast . ' Properties</div>',
            'east' => '<div style="text-align: center;"><strong>East Region</strong><br />' . $east . ' Properties</div>',
            'west' => '<div style="text-align: center;"><strong>West Region</strong><br />' . $west . ' Properties</div>',
            'central' => '<div style="text-align: center;"><strong>Central Region</strong><br />' . $central . ' Properties</div>'
        );
    }
    wp_localize_script('sm-script', 'mapToolTips', $tooltips);

    // Set values for Google infoboxes
    $url = plugins_url('images/cross.png', __FILE__);
    wp_localize_script('sm-script', 'mapCloseIcon', $url);
    $details = empty($settings['details']) ? '' : $settings['details'];
    wp_localize_script('sm-script', 'mapDetails', $details);
    $url = plugins_url('images/camera.png', __FILE__);
    wp_localize_script('sm-script', 'mapNoPhoto', $url);
    $text = empty($settings['button-text']) ? 'More infomation' : $settings['button-text'];
    wp_localize_script('sm-script', 'mapButtonText', $text);

    // Set width and height of maps
    $height = $width = '';
    if (!empty($settings['mapsize'])) {
        $mapsize = intval($settings['mapsize']);
        if ($mapsize > 0) {
            $height = round($mapsize / 100 * SM_DEFAULT_HEIGHT);
            $width = round($mapsize / 100 * SM_DEFAULT_WIDTH);
        }
    }
    wp_localize_script('sm-script', 'mapScaledHeight', strval($height));
    wp_localize_script('sm-script', 'mapScaledWidth', strval($width));

    /*
     * Trong Thang
     */

    wp_localize_script('sm-script', 'mapScaledHeightimg', strval(450));
    wp_localize_script('sm-script', 'mapScaledWidthimg', strval(730));
    //icon region
    wp_localize_script('sm-script', 'mapIconNorth', $settings['north_icon']);
    wp_localize_script('sm-script', 'mapIconNorthEast', $settings['north_east_icon']);
    wp_localize_script('sm-script', 'mapIconEast', $settings['east_icon']);
    wp_localize_script('sm-script', 'mapIconWest', $settings['west_icon']);
    wp_localize_script('sm-script', 'mapIconCentral', $settings['central_icon']);
}

function sm_enqueue_scripts_rental() {
    $settings = get_option('sm_settings');

    // Load styles
    wp_enqueue_style('sm-style', plugins_url('style.css', __FILE__), array(), SM_VERSION);


    $styles = '';
    if (!empty($settings['normal-color'])) {
        $styles .= '#singapore_maps .google_readmore a { background-color: ' . $settings['normal-color'] . '; }' . PHP_EOL;
    }
    if (!empty($settings['font-color'])) {
        $styles .= '#singapore_maps .google_readmore a { color: ' . $settings['font-color'] . '; }' . PHP_EOL;
    }
    if (!empty($settings['hover-color'])) {
        $styles .= '#singapore_maps .google_readmore a:hover { background-color: ' . $settings['hover-color'] . '; }' . PHP_EOL;
    }
    if (!empty($settings['infobox-width'])) {
        $styles .= '#singapore_maps .google_infobox { width: ' . $settings['infobox-width'] . 'px; }';
    }
    if (!empty($styles)) {
        wp_add_inline_style('sm-style', $styles);
    }

    // Load scripts
    wp_enqueue_script('modernizr', plugins_url('js/modernizr.custom.62242.js', __FILE__), array(), '2.6.2');
    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array(), '3');
    wp_enqueue_script('google-infobox', plugins_url('js/infobox_packed.js', __FILE__), array('google-maps'), '1.1.12');
    wp_enqueue_script('jquery-address', plugins_url('js/jquery.address.min.js', __FILE__), array('jquery'), '1.5');
    wp_enqueue_script('jquery-imagemapster', plugins_url('js/jquery.imagemapster.min.js', __FILE__), array('jquery'), '1.2.10');
    $deps = array('modernizr', 'google-maps', 'google-infobox', 'jquery', 'jquery-ui-tabs', 'jquery-address', 'jquery-imagemapster');
    // wp_enqueue_script('sm-script', plugins_url('js/script.js', __FILE__), $deps, SM_VERSION);

    /*
     * Changed date: 30 July 2014
     * Tran Trong Thang <trantrongthang1207@gmail.com>
     */
    wp_enqueue_script('sm-rental-script', plugins_url('js/rentalscript.js', __FILE__), $deps, SM_VERSION);

    // Set icon for Google Maps
    if (empty($settings['icontype'])) {
        $url = '';
    } else {
        switch ($settings['icontype']) {
            case 'building':
                $url = plugins_url('images/building.png', __FILE__);
                break;
            case 'custom':
                $url = $settings['iconurl'];
                break;
            default:
                $url = '';
        }
    }
    wp_localize_script('sm-script', 'mapIcon', $url);

    // Set markers for Google Maps
    //$listings = sm_get_listings1();
    //var_dump($listings);
    //echo json_encode($listings);
    // wp_localize_script('sm-script', 'mapListings', json_encode($listings));

    $listings = sm_get_rental_listings1();
    //var_dump($listings);
    //echo json_encode($listings);
    wp_localize_script('sm-rental-script', 'rentalmapListings', json_encode($listings));

    // Set tooltips for regional map
    if (empty($settings['tooltips'])) {
        $tooltips = array('north' => '', 'northEast' => '', 'east' => '', 'west' => '', 'central' => '');
    } else {
        // Count listings
        $north = empty($listings['North']) ? '0' : count($listings['North']);
        $northeast = empty($listings['North-East']) ? '0' : count($listings['North-East']);
        $east = empty($listings['East']) ? '0' : count($listings['East']);
        $west = empty($listings['West']) ? '0' : count($listings['West']);
        $central = empty($listings['Central']) ? '0' : count($listings['Central']);
        // Create tooltips
        $tooltips = array(
            'north' => '<div style="text-align: center;"><strong>North Region</strong><br />' . $north . ' Properties</div>',
            'northEast' => '<div style="text-align: center;"><strong>North-East Region</strong><br />' . $northeast . ' Properties</div>',
            'east' => '<div style="text-align: center;"><strong>East Region</strong><br />' . $east . ' Properties</div>',
            'west' => '<div style="text-align: center;"><strong>West Region</strong><br />' . $west . ' Properties</div>',
            'central' => '<div style="text-align: center;"><strong>Central Region</strong><br />' . $central . ' Properties</div>'
        );
    }
    wp_localize_script('sm-rental-script', 'rentalmapToolTips', $tooltips);

    // Set values for Google infoboxes
    $url = plugins_url('images/cross.png', __FILE__);
    wp_localize_script('sm-script', 'mapCloseIcon', $url);
    $details = empty($settings['details']) ? '' : $settings['details'];
    wp_localize_script('sm-script', 'mapDetails', $details);
    $url = plugins_url('images/camera.png', __FILE__);
    wp_localize_script('sm-script', 'mapNoPhoto', $url);
    $text = empty($settings['button-text']) ? 'More infomation' : $settings['button-text'];
    wp_localize_script('sm-script', 'mapButtonText', $text);

    // Set width and height of maps
    $height = $width = '';
    if (!empty($settings['mapsize'])) {
        $mapsize = intval($settings['mapsize']);
        if ($mapsize > 0) {
            $height = round($mapsize / 100 * SM_DEFAULT_HEIGHT);
            $width = round($mapsize / 100 * SM_DEFAULT_WIDTH);
        }
    }
    wp_localize_script('sm-script', 'mapScaledHeight', strval($height));
    wp_localize_script('sm-script', 'mapScaledWidth', strval($width));

    /*
     * Trong Thang
     */

    wp_localize_script('sm-script', 'mapScaledHeightimg', strval(450));
    wp_localize_script('sm-script', 'mapScaledWidthimg', strval(730));
    //icon region
    wp_localize_script('sm-script', 'mapIconNorth', $settings['north_icon']);
    wp_localize_script('sm-script', 'mapIconNorthEast', $settings['north_east_icon']);
    wp_localize_script('sm-script', 'mapIconEast', $settings['east_icon']);
    wp_localize_script('sm-script', 'mapIconWest', $settings['west_icon']);
    wp_localize_script('sm-script', 'mapIconCentral', $settings['central_icon']);
}

function sm_enqueue_scripts_resale() {
    $settings = get_option('sm_settings');

    // Load styles
    wp_enqueue_style('sm-style', plugins_url('style.css', __FILE__), array(), SM_VERSION);

    $styles = '';
    if (!empty($settings['normal-color'])) {
        $styles .= '#singapore_maps .google_readmore a { background-color: ' . $settings['normal-color'] . '; }' . PHP_EOL;
    }
    if (!empty($settings['font-color'])) {
        $styles .= '#singapore_maps .google_readmore a { color: ' . $settings['font-color'] . '; }' . PHP_EOL;
    }
    if (!empty($settings['hover-color'])) {
        $styles .= '#singapore_maps .google_readmore a:hover { background-color: ' . $settings['hover-color'] . '; }' . PHP_EOL;
    }
    if (!empty($settings['infobox-width'])) {
        $styles .= '#singapore_maps .google_infobox { width: ' . $settings['infobox-width'] . 'px; }';
    }
    if (!empty($styles)) {
        wp_add_inline_style('sm-style', $styles);
    }

    // Load scripts
    wp_enqueue_script('modernizr', plugins_url('js/modernizr.custom.62242.js', __FILE__), array(), '2.6.2');
    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array(), '3');
    wp_enqueue_script('google-infobox', plugins_url('js/infobox_packed.js', __FILE__), array('google-maps'), '1.1.12');
    wp_enqueue_script('jquery-address', plugins_url('js/jquery.address.min.js', __FILE__), array('jquery'), '1.5');
    wp_enqueue_script('jquery-imagemapster', plugins_url('js/jquery.imagemapster.min.js', __FILE__), array('jquery'), '1.2.10');
    $deps = array('modernizr', 'google-maps', 'google-infobox', 'jquery', 'jquery-ui-tabs', 'jquery-address', 'jquery-imagemapster');
    // wp_enqueue_script('sm-script', plugins_url('js/script.js', __FILE__), $deps, SM_VERSION);

    /*
     * changed date: 30 july 2014
     * Tran Trong Thang <trantrongthang1207@gmail.com>
     */

    wp_enqueue_script('sm-resale-script', plugins_url('js/resalescript.js', __FILE__), $deps, SM_VERSION);

    // Set icon for Google Maps
    if (empty($settings['icontype'])) {
        $url = '';
    } else {
        switch ($settings['icontype']) {
            case 'building':
                $url = plugins_url('images/building.png', __FILE__);
                break;
            case 'custom':
                $url = $settings['iconurl'];
                break;
            default:
                $url = '';
        }
    }
    wp_localize_script('sm-script', 'mapIcon', $url);

    // Set markers for Google Maps
    $listings = sm_get_resale_listings1();


    //var_dump($listings);
    //echo json_encode($listings);
    wp_localize_script('sm-resale-script', 'resalemapListings', json_encode($listings));

    // Set tooltips for regional map
    if (empty($settings['tooltips'])) {
        $tooltips = array('north' => '', 'northEast' => '', 'east' => '', 'west' => '', 'central' => '');
    } else {
        // Count listings
        $north = empty($listings['North']) ? '0' : count($listings['North']);
        $northeast = empty($listings['North East']) ? '0' : count($listings['North East']);
        $east = empty($listings['East']) ? '0' : count($listings['East']);
        $west = empty($listings['West']) ? '0' : count($listings['West']);
        $central = empty($listings['Central']) ? '0' : count($listings['Central']);
        // Create tooltips
        $tooltips = array(
            'north' => '<div style="text-align: center;"><strong>North Region</strong><br />' . $north . ' Properties</div>',
            'northEast' => '<div style="text-align: center;"><strong>North-East Region</strong><br />' . $northeast . ' Properties</div>',
            'east' => '<div style="text-align: center;"><strong>East Region</strong><br />' . $east . ' Properties</div>',
            'west' => '<div style="text-align: center;"><strong>West Region</strong><br />' . $west . ' Properties</div>',
            'central' => '<div style="text-align: center;"><strong>Central Region</strong><br />' . $central . ' Properties</div>'
        );
    }
    wp_localize_script('sm-resale-script', 'resalemapToolTips', $tooltips);

    // Set values for Google infoboxes
    $url = plugins_url('images/cross.png', __FILE__);
    wp_localize_script('sm-script', 'mapCloseIcon', $url);
    $details = empty($settings['details']) ? '' : $settings['details'];
    wp_localize_script('sm-script', 'mapDetails', $details);
    $url = plugins_url('images/camera.png', __FILE__);
    wp_localize_script('sm-script', 'mapNoPhoto', $url);
    $text = empty($settings['button-text']) ? 'More infomation' : $settings['button-text'];
    wp_localize_script('sm-script', 'mapButtonText', $text);

    // Set width and height of maps
    $height = $width = '';
    if (!empty($settings['mapsize'])) {
        $mapsize = intval($settings['mapsize']);
        if ($mapsize > 0) {
            $height = round($mapsize / 100 * SM_DEFAULT_HEIGHT);
            $width = round($mapsize / 100 * SM_DEFAULT_WIDTH);
        }
    }
    wp_localize_script('sm-script', 'mapScaledHeight', strval($height));
    wp_localize_script('sm-script', 'mapScaledWidth', strval($width));

    /*
     * Trong Thang
     */

    wp_localize_script('sm-script', 'mapScaledHeightimg', strval(450));
    wp_localize_script('sm-script', 'mapScaledWidthimg', strval(730));
    //icon region
    wp_localize_script('sm-script', 'mapIconNorth', $settings['north_icon']);
    wp_localize_script('sm-script', 'mapIconNorthEast', $settings['north_east_icon']);
    wp_localize_script('sm-script', 'mapIconEast', $settings['east_icon']);
    wp_localize_script('sm-script', 'mapIconWest', $settings['west_icon']);
    wp_localize_script('sm-script', 'mapIconCentral', $settings['central_icon']);
}

/**
 * Get listings for all regions
 */
function sm_get_rental_listings() {
    global $wpdb, $post;
    // Get post type
    $settings = get_option('sm_settings');
    $post_type = 'rental';
    $taxonomy = 'rental-regions';

    // Get posts
    $map_regions = array('North', 'North East', 'East', 'West', 'Central');
    $map_listings = array();
    foreach ($map_regions as $region) {
        // Get posts for region
        $sql = "
            SELECT * FROM $wpdb->posts P
            WHERE P.post_type = '%s'
            AND P.post_status = 'publish'   
            AND (
                P.ID IN (       
                    SELECT ID FROM $wpdb->posts P
                    LEFT JOIN $wpdb->term_relationships R
                    ON (P.ID = R.object_id)
                    LEFT JOIN $wpdb->term_taxonomy X
                    ON (R.term_taxonomy_id = X.term_taxonomy_id)
                    LEFT JOIN $wpdb->terms T
                    ON (X.term_id = T.term_id)
                    WHERE X.taxonomy = '%s' 
                    AND T.name = '%s'
                ) 
                OR P.ID IN (
                    SELECT post_id FROM $wpdb->postmeta 
                    WHERE meta_key = 'listing_region' 
                    AND meta_value = '%s'
                )
            )
        ";
        $results = null;
        $results = $wpdb->get_results($wpdb->prepare($sql, $post_type, $taxonomy, $region, $region));
        $settings = get_option('sm_settings');
        // Process posts for region
        if ($results) {
            $region_listings = array();
            foreach ($results as $post) {
                setup_postdata($post);
                $listing = array();
                // Get title, permalink, thumbnail, address and description
                $listing['title'] = get_the_title();
                $listing['link'] = get_permalink();
                $id = get_the_ID();

                $listing['total'] = count($results);

                switch ($region) {
                    case 'North':
                        $listing['type'] = 'north_map';
                        $listing['icon'] = $settings['north_icon'];
                        break;
                    case 'North-East':
                        $listing['type'] = 'northeast_map';
                        $listing['icon'] = $settings['north_east_icon'];
                        break;
                    case 'East':
                        $listing['type'] = 'east_map';
                        $listing['icon'] = $settings['east_icon'];
                        break;
                    case 'West':
                        $listing['type'] = 'west_map';
                        $listing['icon'] = $settings['west_icon'];
                        break;
                    case 'Central':
                        $listing['type'] = 'central_map';
                        $listing['icon'] = $settings['central_icon'];
                        break;
                }
                $region_listings[] = $listing;
            }
            $map_listings[$region] = $region_listings;
        }
    }
    wp_reset_postdata();
    //print_r($map_listings);
    return $map_listings;
}

/**

 * Lay Rental listing
 * Chang  by Hoang Bien <hoangbien264@gmail.com>
 * @global type $wpdb
 * @global type $post
 * @return type /
 */
function sm_get_rental_listings1() {
    global $wpdb, $post;
    // Get post type
    $settings = get_option('sm_settings');
    $post_type = 'rental';
    $taxonomy = 'rental-regions';

    // Get posts
    $map_regions = array('North', 'North East', 'East', 'West', 'Central');
    $map_listings = array();
    $url_site = get_bloginfo('url');
    $pathImage = $url_site . "/wp-content/uploads";
    foreach ($map_regions as $region) {
        // Get posts for region
        $sql = "
            SELECT * FROM $wpdb->posts P
            WHERE P.post_type = '%s'
            AND P.post_status = 'publish'   
            AND (
                P.ID IN (       
                    SELECT ID FROM $wpdb->posts P
                    LEFT JOIN $wpdb->term_relationships R
                    ON (P.ID = R.object_id)
                    LEFT JOIN $wpdb->term_taxonomy X
                    ON (R.term_taxonomy_id = X.term_taxonomy_id)
                    LEFT JOIN $wpdb->terms T
                    ON (X.term_id = T.term_id)
                    WHERE X.taxonomy = '%s' 
                    AND T.name = '%s'
                ) 
                OR P.ID IN (
                    SELECT post_id FROM $wpdb->postmeta 
                    WHERE meta_key = 'listing_region' 
                    AND meta_value = '%s'
                )
            )
        ";
        $results = null;
        $results = $wpdb->get_results($wpdb->prepare($sql, $post_type, $taxonomy, $region, $region));
        $settings = get_option('sm_settings');
        // Process posts for region
        if ($results) {
            $region_listings = array();
            foreach ($results as $post) {
                setup_postdata($post);
                $listing = array();
                // Get title, permalink, thumbnail, address and description
                $listing['title'] = get_the_title();
                $listing['link'] = get_permalink();
                $id = get_the_ID();
                // $thumb_id = get_post_thumbnail_id($id);
                //$thumb_url = wp_get_attachment_image_src($thumb_id);
                $thumb_url = ms_get_image_by_object($id);
                if ($thumb_url) {
                    $thumb_url = $pathImage . $thumb_url;
                } else {
                    $thumb_url = get_post_meta($id, 'wpcf-featured-image', true);
                }
                $listing['thumbURL'] = ($thumb_url != "") ? $thumb_url : '';
                //$listing['thumbURL'] = empty($thumb_url) ? '' : $thumb_url[0];
                $descriptions = get_post_meta($id, 'property-description', true);
                $listing['description'] = wordLimit($descriptions, 6);
//                $address = get_post_meta($id, 'street_address', true);
//                $address = empty($address) ? sm_get_address() : $address;
//                // Restrict results on Google Maps to Singapore 
//                if (stripos($address, 'singapore') > 0) {
//                    $listing['address'] = trim($address);
//                } else {
//                    $listing['address'] = trim($address) . ', Singapore';
//                }

                $listing['total'] = count($results);

                switch ($region) {
                    case 'North':
                        $listing['type'] = 'north_map';
                        $listing['icon'] = $settings['north_icon'];
                        break;
                    case 'North-East':
                        $listing['type'] = 'northeast_map';
                        $listing['icon'] = $settings['north_east_icon'];
                        break;
                    case 'East':
                        $listing['type'] = 'east_map';
                        $listing['icon'] = $settings['east_icon'];
                        break;
                    case 'West':
                        $listing['type'] = 'west_map';
                        $listing['icon'] = $settings['west_icon'];
                        break;
                    case 'Central':
                        $listing['type'] = 'central_map';
                        $listing['icon'] = $settings['central_icon'];
                        break;
                }
                //$thumb_url = get_post_meta($id, 'wpcf-featured-image', true);
                $listing['random'] = rand();

                $address = get_post_meta($id, 'property-location', true);
                if (!$address) {
                    $address = get_post_meta($id, 'property-district', true);
                }
                if ($address) {
                    if (stripos($address, 'singapore') > 0) {
                        $address = trim($address);
                    } else {
                        $address = trim($address) . ', Singapore';
                    }
                    $listing['address'] = trim($address);
                    $arrayLongLast = sm_get_lat_long($address);
                    //$lat_long = unserialize($ltlng);

                    if (!empty($arrayLongLast)) {
                        $listing['lat'] = $arrayLongLast['lat'];
                        $listing['lng'] = $arrayLongLast['lng'];
                    }
                } else {
                    $listing['lat'] = ''; //$arrayLongLast['lat'];
                    $listing['lng'] = ''; //$arrayLongLast['lng']; 
                }
                $price = get_post_meta($id, 'property-price', true);
                if (!$price) {
                    $price = 0; //get_post_meta($id, 'property-district', true);
                }
                $listing['price'] = number_format($price,2);
                $region_listings[] = $listing;
            }
            $map_listings = array_merge($map_listings, $region_listings);
            //$map_listings[$region] = $region_listings;
        }
    }
    wp_reset_postdata();
    //var_dump($map_listings);
    //print_r($map_listings);
    return $map_listings;
}

function sm_get_resale_listings() {
    global $wpdb, $post;
    // Get post type
    $settings = get_option('sm_settings');
    $post_type = 'resale';
    $taxonomy = 'resale-regions';

    // Get posts
    $map_regions = array('North', 'North East', 'East', 'West', 'Central');
    $map_listings = array();
    foreach ($map_regions as $region) {
        // Get posts for region
        $sql = "
            SELECT * FROM $wpdb->posts P
            WHERE P.post_type = '%s'
            AND P.post_status = 'publish'   
            AND (
                P.ID IN (       
                    SELECT ID FROM $wpdb->posts P
                    LEFT JOIN $wpdb->term_relationships R
                    ON (P.ID = R.object_id)
                    LEFT JOIN $wpdb->term_taxonomy X
                    ON (R.term_taxonomy_id = X.term_taxonomy_id)
                    LEFT JOIN $wpdb->terms T
                    ON (X.term_id = T.term_id)
                    WHERE X.taxonomy = '%s' 
                    AND T.name = '%s'
                ) 
                OR P.ID IN (
                    SELECT post_id FROM $wpdb->postmeta 
                    WHERE meta_key = 'listing_region' 
                    AND meta_value = '%s'
                )
            )
        ";
        $results = null;
        $results = $wpdb->get_results($wpdb->prepare($sql, $post_type, $taxonomy, $region, $region));
        $settings = get_option('sm_settings');
        // Process posts for region
        if ($results) {
            $region_listings = array();
            //var_dump($result)
            foreach ($results as $post) {
                setup_postdata($post);
                $listing = array();
                // Get title, permalink, thumbnail, address and description
                $listing['title'] = get_the_title();
                $listing['link'] = get_permalink();
                $id = get_the_ID();

                $listing['total'] = count($results);

                switch ($region) {
                    case 'North':
                        $listing['type'] = 'north_map';
                        $listing['icon'] = $settings['north_icon'];
                        break;
                    case 'North East':
                        $listing['type'] = 'northeast_map';
                        $listing['icon'] = $settings['north_east_icon'];
                        break;
                    case 'East':
                        $listing['type'] = 'east_map';
                        $listing['icon'] = $settings['east_icon'];
                        break;
                    case 'West':
                        $listing['type'] = 'west_map';
                        $listing['icon'] = $settings['west_icon'];
                        break;
                    case 'Central':
                        $listing['type'] = 'central_map';
                        $listing['icon'] = $settings['central_icon'];
                        break;
                }
                $region_listings[] = $listing;
            }
            $map_listings[$region] = $region_listings;
        }
    }
    wp_reset_postdata();
    //print_r($map_listings);
    return $map_listings;
}

function sm_get_lat_long($address) {
    if (!is_string($address))
        die("All Addresses must be passed as a string");
//    $_url = sprintf('http://maps.google.com/maps?output=js&q=%s', rawurlencode($address));
//   // echo $_url;
//    $_result = false;
//    $_coords['lat'] = '';
//    $_coords['lng'] = '';
//    if ($_result = file_get_contents($_url)) {
//        echo $_result;
//        if (strpos($_result, 'errortips') > 1 || strpos($_result, 'Did you mean:') !== false)
//            return false;
//        preg_match('!center:\s*{lat:\s*(-?\d+\.\d+),lng:\s*(-?\d+\.\d+)}!U', $_result, $_match);
//        $_coords['lat'] = $_match[1];
//        $_coords['lng'] = $_match[2];
//    }
//    return $_coords;
    //$address = $dlocation; // Google HQ
    $prepAddr = str_replace(' ', '+', $address);
    $geocode = ms_getContentFromApi('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
    $output = json_decode($geocode);
    $latitude = isset($output->results[0]->geometry->location->lat)?$output->results[0]->geometry->location->lat:'';
    $longitude = isset($output->results[0]->geometry->location->lng)?$output->results[0]->geometry->location->lng:'';
    $_coords['lat'] = $latitude;
    $_coords['lng'] = $longitude;
    return $_coords;
}

function ms_getContentFromApi($url, $use_include_path = false, $stream_context = null, $curl_timeout = 20) {
    if ($stream_context == null && preg_match('/^https?:\/\//', $url))
        $stream_context = @stream_context_create(array('http' => array('timeout' => $curl_timeout)));
    if (function_exists('curl_init')) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($curl, CURLOPT_TIMEOUT, $curl_timeout);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        if ($stream_context != null) {
            $opts = stream_context_get_options($stream_context);
            if (isset($opts['http']['method']) && strtolower($opts['http']['method']) == 'post') {
                curl_setopt($curl, CURLOPT_POST, true);
                if (isset($opts['http']['content'])) {
                    parse_str($opts['http']['content'], $datas);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $datas);
                }
            }
        }
        $content = curl_exec($curl);
        curl_close($curl);
        return $content;
    } else {
        if (in_array(ini_get('allow_url_fopen'), array('On', 'on', '1')) || !preg_match('/^https?:\/\//', $url)) {
            return @file_get_contents($url, $use_include_path, $stream_context);
        } else
            return false;
    }
}

/**
 * Get resale listing 
 * Chang by Hoang Bien <hoangbien264@gmail.com>
 * @global type $wpdb
 * @global type $post
 * @return type
 */
function sm_get_resale_listings1() {
    global $wpdb, $post;
    // Get post type
    $settings = get_option('sm_settings');
    $post_type = 'resale';
    $taxonomy = 'resale-regions';

    // Get posts
    $map_regions = array('North', 'North East', 'East', 'West', 'Central');
    $map_listings = array();
    $url_site = get_bloginfo('url');
    $pathImage = $url_site . "/wp-content/uploads";
    //wp-content/uploads
    foreach ($map_regions as $region) {
        // Get posts for region
        $sql = "
            SELECT * FROM $wpdb->posts P
            WHERE P.post_type = '%s'
            AND P.post_status = 'publish'   
            AND (
                P.ID IN (       
                    SELECT ID FROM $wpdb->posts P
                    LEFT JOIN $wpdb->term_relationships R
                    ON (P.ID = R.object_id)
                    LEFT JOIN $wpdb->term_taxonomy X
                    ON (R.term_taxonomy_id = X.term_taxonomy_id)
                    LEFT JOIN $wpdb->terms T
                    ON (X.term_id = T.term_id)
                    WHERE X.taxonomy = '%s' 
                    AND T.name = '%s'
                ) 
                OR P.ID IN (
                    SELECT post_id FROM $wpdb->postmeta 
                    WHERE meta_key = 'listing_region' 
                    AND meta_value = '%s'
                )
            )
        ";
        $results = null;
        $results = $wpdb->get_results($wpdb->prepare($sql, $post_type, $taxonomy, $region, $region));
        $settings = get_option('sm_settings');
        // Process posts for region
        if ($results) {
            $region_listings = array();
            foreach ($results as $post) {
                setup_postdata($post);
                $listing = array();
                // Get title, permalink, thumbnail, address and description
                $listing['title'] = get_the_title();
                $listing['link'] = get_permalink();
                $id = get_the_ID();
                // $thumb_id = get_post_thumbnail_id($id);
                //$thumb_url = wp_get_attachment_image_src($thumb_id);
                $thumb_url = ms_get_image_by_object($id);
                if ($thumb_url) {
                    $thumb_url = $pathImage . $thumb_url;
                } else {
                    $thumb_url = get_post_meta($id, 'wpcf-featured-image', true);
                }
                $listing['thumbURL'] = ($thumb_url != "") ? $thumb_url : '';
                //$listing['thumbURL'] = empty($thumb_url) ? '' : $thumb_url[0];
                $descriptions = get_post_meta($id, 'property-description', true);
                $listing['description'] = wordLimit($descriptions, 6);
                // $address = get_post_meta($id, 'street_address', true);
//                $address = empty($address) ? sm_get_address() : $address;
//                // Restrict results on Google Maps to Singapore 
//                if (stripos($address, 'singapore') > 0) {
//                    $listing['address'] = trim($address);
//                } else {
//                    $listing['address'] = trim($address) . ', Singapore';
//                }

                $listing['total'] = count($results);

                switch ($region) {
                    case 'North':
                        $listing['type'] = 'north_map';
                        $listing['icon'] = $settings['north_icon'];
                        break;
                    case 'North East':
                        $listing['type'] = 'northeast_map';
                        $listing['icon'] = $settings['north_east_icon'];
                        break;
                    case 'East':
                        $listing['type'] = 'east_map';
                        $listing['icon'] = $settings['east_icon'];
                        break;
                    case 'West':
                        $listing['type'] = 'west_map';
                        $listing['icon'] = $settings['west_icon'];
                        break;
                    case 'Central':
                        $listing['type'] = 'central_map';
                        $listing['icon'] = $settings['central_icon'];
                        break;
                }
                $listing['random'] = rand();

                $address = get_post_meta($id, 'property-location', true);
                if (!$address) {
                    $address = get_post_meta($id, 'property-district', true);
                }
                if ($address) {
                    if (stripos($address, 'singapore') > 0) {
                        $address = trim($address);
                    } else {
                        $address = trim($address) . ', Singapore';
                    }
                    $listing['address'] = trim($address);
                    $arrayLongLast = sm_get_lat_long($address);
                    //$lat_long = unserialize($ltlng);

                    if (!empty($arrayLongLast)) {
                        $listing['lat'] = $arrayLongLast['lat'];
                        $listing['lng'] = $arrayLongLast['lng'];
                    }
                } else {
                    $listing['lat'] = ''; //$arrayLongLast['lat'];
                    $listing['lng'] = ''; //$arrayLongLast['lng']; 
                }

                $price = get_post_meta($id, 'property-price', true);
                if (!$price) {
                    $price = 0; //get_post_meta($id, 'property-district', true);
                }
                $listing['price'] = number_format($price,2);
                $region_listings[] = $listing;
            }
            // $map_listings[$region] = $region_listings;
            $map_listings = array_merge($map_listings, $region_listings);
        }
    }
    wp_reset_postdata();
    // var_dump($map_listings);
    return $map_listings;
}

function sm_get_listings() {
    global $wpdb, $post;
    // Get post type
    $settings = get_option('sm_settings');
    $post_type = empty($settings['posttype']) ? 'post' : $settings['posttype'];
    $taxonomy = empty($settings['taxonomy']) ? 'category' : $settings['taxonomy'];

    // Get posts
    $map_regions = array('North', 'North-East', 'East', 'West', 'Central');
    $map_listings = array();
    foreach ($map_regions as $region) {
        // Get posts for region
        $sql = "
			SELECT * FROM $wpdb->posts P
			WHERE P.post_type = '%s'
			AND P.post_status = 'publish'	
			AND (
				P.ID IN (		
					SELECT ID FROM $wpdb->posts P
					LEFT JOIN $wpdb->term_relationships R
					ON (P.ID = R.object_id)
					LEFT JOIN $wpdb->term_taxonomy X
					ON (R.term_taxonomy_id = X.term_taxonomy_id)
					LEFT JOIN $wpdb->terms T
					ON (X.term_id = T.term_id)
					WHERE X.taxonomy = '%s' 
					AND T.name = '%s'
				) 
				OR P.ID IN (
					SELECT post_id FROM $wpdb->postmeta 
					WHERE meta_key = 'listing_region' 
					AND meta_value = '%s'
				)
			)
		";
        $results = null;
        $results = $wpdb->get_results($wpdb->prepare($sql, $post_type, $taxonomy, $region, $region));
        $settings = get_option('sm_settings');
        // Process posts for region
        if ($results) {
            $region_listings = array();
            foreach ($results as $post) {
                setup_postdata($post);
                $listing = array();
                // Get title, permalink, thumbnail, address and description
                $listing['title'] = get_the_title();
                $listing['link'] = get_permalink();
                $id = get_the_ID();

                $listing['total'] = count($results);

                switch ($region) {
                    case 'North':
                        $listing['type'] = 'north_map';
                        $listing['icon'] = $settings['north_icon'];
                        break;
                    case 'North-East':
                        $listing['type'] = 'northeast_map';
                        $listing['icon'] = $settings['north_east_icon'];
                        break;
                    case 'East':
                        $listing['type'] = 'east_map';
                        $listing['icon'] = $settings['east_icon'];
                        break;
                    case 'West':
                        $listing['type'] = 'west_map';
                        $listing['icon'] = $settings['west_icon'];
                        break;
                    case 'Central':
                        $listing['type'] = 'central_map';
                        $listing['icon'] = $settings['central_icon'];
                        break;
                }
                $region_listings[] = $listing;
            }
            $map_listings[$region] = $region_listings;
        }
    }
    wp_reset_postdata();
    //print_r($map_listings);
    return $map_listings;
}

function sm_get_listings1_resale() {
    global $wpdb, $post;
    // Get post type
    $settings = get_option('sm_settings');
    $post_type = 'resale';
    $taxonomy = 'resale-categories';

    // Get posts
    $map_regions = array('North', 'North East', 'East', 'West', 'Central');
    $map_listings = array();
    foreach ($map_regions as $region) {
        // Get posts for region
        $sql = "
            SELECT * FROM $wpdb->posts P
            WHERE P.post_type = '%s'
            AND P.post_status = 'publish'   
            AND (
                P.ID IN (       
                    SELECT ID FROM $wpdb->posts P
                    LEFT JOIN $wpdb->term_relationships R
                    ON (P.ID = R.object_id)
                    LEFT JOIN $wpdb->term_taxonomy X
                    ON (R.term_taxonomy_id = X.term_taxonomy_id)
                    LEFT JOIN $wpdb->terms T
                    ON (X.term_id = T.term_id)
                    WHERE X.taxonomy = '%s' 
                    AND T.name = '%s'
                ) 
                OR P.ID IN (
                    SELECT post_id FROM $wpdb->postmeta 
                    WHERE meta_key = 'listing_region' 
                    AND meta_value = '%s'
                )
            )
        ";
        $results = null;
        $results = $wpdb->get_results($wpdb->prepare($sql, $post_type, $taxonomy, $region, $region));
        $settings = get_option('sm_settings');
        // Process posts for region
        if ($results) {
            $region_listings = array();
            foreach ($results as $post) {
                setup_postdata($post);
                $listing = array();
                // Get title, permalink, thumbnail, address and description
                $listing['title'] = get_the_title();
                $listing['link'] = get_permalink();
                $id = get_the_ID();
                // $thumb_id = get_post_thumbnail_id($id);
                //$thumb_url = wp_get_attachment_image_src($thumb_id);
                $thumb_url = get_post_meta($id, 'wpcf-featured-image', true);
                $listing['thumbURL'] = ($thumb_url != "") ? $thumb_url : '';
                //$listing['thumbURL'] = empty($thumb_url) ? '' : $thumb_url[0];
                $listing['description'] = get_post_meta($id, 'listing_desc', true);
                $address = get_post_meta($id, 'street_address', true);
                $address = empty($address) ? sm_get_address() : $address;
                // Restrict results on Google Maps to Singapore 
                if (stripos($address, 'singapore') > 0) {
                    $listing['address'] = trim($address);
                } else {
                    $listing['address'] = trim($address) . ', Singapore';
                }

                $listing['total'] = count($results);

                switch ($region) {
                    case 'North':
                        $listing['type'] = 'north_map';
                        $listing['icon'] = $settings['north_icon'];
                        break;
                    case 'North East':
                        $listing['type'] = 'northeast_map';
                        $listing['icon'] = $settings['north_east_icon'];
                        break;
                    case 'East':
                        $listing['type'] = 'east_map';
                        $listing['icon'] = $settings['east_icon'];
                        break;
                    case 'West':
                        $listing['type'] = 'west_map';
                        $listing['icon'] = $settings['west_icon'];
                        break;
                    case 'Central':
                        $listing['type'] = 'central_map';
                        $listing['icon'] = $settings['central_icon'];
                        break;
                }

                $listing['random'] = rand();

                $ltlng = get_post_meta($id, 'kp-lat-long', true);
                $lat_long = unserialize($ltlng);

                if (is_object($lat_long) && $lat_long->lat && $lat_long->lng) {
                    $listing['lat'] = $lat_long->lat;
                    $listing['lng'] = $lat_long->lng;
                }
                //$region_listings[] = $listing;
                $map_listings[] = $listing;
            }
            //$map_listings[$region] = $region_listings;
        }
    }
    wp_reset_postdata();
    return $map_listings;
}

function sm_get_listings1() {
    global $wpdb, $post;
    // Get post type
    $settings = get_option('sm_settings');
    $post_type = empty($settings['posttype']) ? 'post' : $settings['posttype'];
    $taxonomy = empty($settings['taxonomy']) ? 'category' : $settings['taxonomy'];

    // Get posts
    $map_regions = array('North', 'North-East', 'East', 'West', 'Central');
    $map_listings = array();
    foreach ($map_regions as $region) {
        // Get posts for region
        $sql = "
			SELECT * FROM $wpdb->posts P
			WHERE P.post_type = '%s'
			AND P.post_status = 'publish'	
			AND (
				P.ID IN (		
					SELECT ID FROM $wpdb->posts P
					LEFT JOIN $wpdb->term_relationships R
					ON (P.ID = R.object_id)
					LEFT JOIN $wpdb->term_taxonomy X
					ON (R.term_taxonomy_id = X.term_taxonomy_id)
					LEFT JOIN $wpdb->terms T
					ON (X.term_id = T.term_id)
					WHERE X.taxonomy = '%s' 
					AND T.name = '%s'
				) 
				OR P.ID IN (
					SELECT post_id FROM $wpdb->postmeta 
					WHERE meta_key = 'listing_region' 
					AND meta_value = '%s'
				)
			)
		";
        $results = null;
        $results = $wpdb->get_results($wpdb->prepare($sql, $post_type, $taxonomy, $region, $region));
        $settings = get_option('sm_settings');
        // Process posts for region
        if ($results) {
            $region_listings = array();
            foreach ($results as $post) {
                setup_postdata($post);
                $listing = array();
                // Get title, permalink, thumbnail, address and description
                $listing['title'] = get_the_title();
                $listing['link'] = get_permalink();
                $id = get_the_ID();
                // $thumb_id = get_post_thumbnail_id($id);
                //$thumb_url = wp_get_attachment_image_src($thumb_id);
                $thumb_url = get_post_meta($id, 'wpcf-featured-image', true);
                $listing['thumbURL'] = ($thumb_url != "") ? $thumb_url : '';
                //$listing['thumbURL'] = empty($thumb_url) ? '' : $thumb_url[0];
                $listing['description'] = get_post_meta($id, 'listing_desc', true);
                $address = get_post_meta($id, 'street_address', true);
                $address = empty($address) ? sm_get_address() : $address;
                // Restrict results on Google Maps to Singapore 
                if (stripos($address, 'singapore') > 0) {
                    $listing['address'] = trim($address);
                } else {
                    $listing['address'] = trim($address) . ', Singapore';
                }

                $listing['total'] = count($results);

                switch ($region) {
                    case 'North':
                        $listing['type'] = 'north_map';
                        $listing['icon'] = $settings['north_icon'];
                        break;
                    case 'North-East':
                        $listing['type'] = 'northeast_map';
                        $listing['icon'] = $settings['north_east_icon'];
                        break;
                    case 'East':
                        $listing['type'] = 'east_map';
                        $listing['icon'] = $settings['east_icon'];
                        break;
                    case 'West':
                        $listing['type'] = 'west_map';
                        $listing['icon'] = $settings['west_icon'];
                        break;
                    case 'Central':
                        $listing['type'] = 'central_map';
                        $listing['icon'] = $settings['central_icon'];
                        break;
                }

                $listing['random'] = rand();

                $ltlng = get_post_meta($id, 'kp-lat-long', true);
                $lat_long = unserialize($ltlng);

                if (is_object($lat_long) && $lat_long->lat && $lat_long->lng) {
                    $listing['lat'] = $lat_long->lat;
                    $listing['lng'] = $lat_long->lng;
                }
                //$region_listings[] = $listing;
                $map_listings[] = $listing;
            }
            //$map_listings[$region] = $region_listings;
        }
    }
    wp_reset_postdata();
    return $map_listings;
}

//use for map - Ho Ngoc Hang
function sm_get_listings2() {
    global $wpdb, $post;
    // Get post type
    $settings = get_option('sm_settings');
    $post_type = empty($settings['posttype']) ? 'post' : $settings['posttype'];
    $taxonomy = empty($settings['taxonomy']) ? 'category' : $settings['taxonomy'];

    // Get posts
    $map_regions = array('North', 'North-East', 'East', 'West', 'Central');
    $map_listings = array();
    //foreach ($map_regions as $region) {
    // Get posts for region
    $sql = "
			SELECT * FROM $wpdb->posts P
			WHERE P.post_type = '%s'
			AND P.post_status = 'publish'	
			AND (
				P.ID IN (		
					SELECT ID FROM $wpdb->posts P
					LEFT JOIN $wpdb->term_relationships R
					ON (P.ID = R.object_id)
					LEFT JOIN $wpdb->term_taxonomy X
					ON (R.term_taxonomy_id = X.term_taxonomy_id)
					LEFT JOIN $wpdb->terms T
					ON (X.term_id = T.term_id)
					WHERE X.taxonomy = '%s' 
					
				) 
				
			)
		";
    $results = null;
    $results = $wpdb->get_results($wpdb->prepare($sql, $post_type, $taxonomy));
    $settings = get_option('sm_settings');
    // Process posts for region
    if ($results) {
        $region_listings = array();
        foreach ($results as $post) {
            setup_postdata($post);
            $listing = array();
            // Get title, permalink, thumbnail, address and description
            $listing['title'] = get_the_title();
            $listing['link'] = get_permalink();
            $id = get_the_ID();
            $thumb_url = get_post_meta($id, 'wpcf-featured-image', true);
            $listing['thumbURL'] = ($thumb_url != "") ? $thumb_url : '';
            $listing['description'] = get_post_meta($id, 'listing_desc', true);
            $address = get_post_meta($id, 'street_address', true);
            $address = empty($address) ? sm_get_address() : $address;
            // Restrict results on Google Maps to Singapore 
            if (stripos($address, 'singapore') > 0) {
                $listing['address'] = trim($address);
            } else {
                $listing['address'] = trim($address) . ', Singapore';
            }

            $listing['total'] = count($results);

            switch ($region) {
                case 'North':
                    $listing['type'] = 'north_map';
                    $listing['icon'] = $settings['north_icon'];
                    break;
                case 'North-East':
                    $listing['type'] = 'northeast_map';
                    $listing['icon'] = $settings['north_east_icon'];
                    break;
                case 'East':
                    $listing['type'] = 'east_map';
                    $listing['icon'] = $settings['east_icon'];
                    break;
                case 'West':
                    $listing['type'] = 'west_map';
                    $listing['icon'] = $settings['west_icon'];
                    break;
                case 'Central':
                    $listing['type'] = 'central_map';
                    $listing['icon'] = $settings['central_icon'];
                    break;
            }
            $region_listings[] = $listing;
        }
        //$map_listings[] = $region_listings;
    }
    //}
    wp_reset_postdata();
    //  print_r($map_listings);
    return $region_listings;
}

function sm_check_mobile() {
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|meego.+mobile|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Get listing address from post content
 */
function sm_get_address() {
    $html = get_the_content();
    $dom = new DOMDocument;
    // Load content while suppressing errors 
    $previous = libxml_use_internal_errors(true);
    //change by Ho Ngoc Hang
    if ($html != '') {
        $dom->loadHTML($html);
    }
    //END  change by Ho Ngoc Hang
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
    $dom->preserveWhiteSpace = false;
    // Find table cell for address
    $cells = $dom->getElementsByTagName('td');
    $found = false;
    if (!empty($cells)) {
        foreach ($cells as $cell) {
            if ($found) {
                return $cell->nodeValue;
            } else if ($cell->nodeValue == 'Address') {
                $found = true;
            }
        }
    }
    return '';
}

/**
 * Handle shortcode
 */
function sm_shortcode() {
    ob_start();
    include('template.php');
    sm_enqueue_scripts();
    return ob_get_clean();
}

function sm_shortcode_rental() {
    ob_start();
    include('template-rental.php');
    //sm_enqueue_scripts();
    sm_enqueue_scripts_rental();
    return ob_get_clean();
}

function sm_shortcode_resale() {
    ob_start();
    include('template-resale.php');
    sm_enqueue_scripts_resale();
    return ob_get_clean();
}

/**
 * Add settings link in plugin panel
 */
function sm_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=singapore_maps">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

/**
 * Register settings, stylesheet and script
 */
function sm_settings_init() {
    register_setting('sm_settings_group', 'sm_settings');
    add_settings_section('sm_settings_section', '', 'sm_settings_section', 'sm_settings_page');
}

/**
 * Create settings menu
 */
function sm_settings_menu() {
    $page_hook_suffix = add_options_page('Singapore Maps', 'Singapore Maps', 'manage_options', 'singapore_maps', 'sm_settings_page');
    add_action('admin_print_scripts-' . $page_hook_suffix, 'sm_enqueue_admin_scripts');
}

/**
 * Load settings template
 */
function sm_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    } else {
        sm_settings_template();
    }
}

/**
 * Register settings fields 
 */
function sm_settings_section() {
    add_settings_field('sm_settings_posttype', 'Custom Post Type', 'sm_settings_posttype', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_taxonomy', 'Custom Taxonomy', 'sm_settings_taxonomy', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_metabox', 'Show Meta Box', 'sm_settings_metabox', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_mapsize', 'Google Map Size', 'sm_settings_mapsize', 'sm_settings_page', 'sm_settings_section');
    //add_settings_field('sm_settings_mapsize_smap', 'Singapore map Size', 'sm_settings_mapsize_smap', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_alignment', 'Map Alignment', 'sm_settings_alignment', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_tooltips', 'Show Tooltips', 'sm_settings_tooltips', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_details', 'Show Details', 'sm_settings_details', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_icontype', 'Marker Icon', 'sm_settings_icontype', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_iconurl', 'Custom Icon URL', 'sm_settings_iconurl', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_infobox', 'Google Infobox', 'sm_settings_infobox', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_button_colors', 'Link Button Colors', 'sm_settings_button_colors', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_button_text', 'Link Button Text', 'sm_settings_button_text', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_css', 'Custom CSS', 'sm_settings_css', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_hidden_map', 'Hide map filters', 'sm_settings_hidden_map', 'sm_settings_page', 'sm_settings_section');
    //add_settings_field('sm_settings_gsize', 'Size of Singapore map', 'sm_settings_gsize', 'sm_settings_page', 'sm_settings_section');
    add_settings_field('sm_settings_category_icon', 'Category Marker Icon URL', 'sm_settings_category_icon', 'sm_settings_page', 'sm_settings_section');
}

/**
 * Setting field for custom post type
 */
function sm_settings_posttype() {
    $settings = get_option('sm_settings');
    if (!isset($settings['posttype'])) {
        $settings['posttype'] = false;
    }
    $args = array('_builtin' => false);
    $post_types = get_post_types($args, 'objects');
    if (empty($post_types)) {
        echo "No custom post types available." . PHP_EOL;
    } else {
        echo "<select name='sm_settings[posttype]' style='width:300px;'>" . PHP_EOL;
        echo "<option value=''></option>" . PHP_EOL;
        foreach ($post_types as $post_type) {
            $selected = selected($settings['posttype'], $post_type->name, false);
            printf("<option value='%s'%s>%s&nbsp;&nbsp;</option>", $post_type->name, $selected, $post_type->labels->singular_name);
        }
        echo "</select>" . PHP_EOL;
        echo "<p class='description'>Select the custom post type of the listings.</p>" . PHP_EOL;
    }
}

/**
 * Setting field for custom taxonomy
 */
function sm_settings_taxonomy() {
    $settings = get_option('sm_settings');
    if (!isset($settings['taxonomy'])) {
        $settings['taxonomy'] = false;
    }
    $args = array('_builtin' => false);
    $taxonomies = get_taxonomies($args, 'objects');
    if (empty($taxonomies)) {
        echo "No custom taxonomies available." . PHP_EOL;
    } else {
        echo "<select name='sm_settings[taxonomy]' style='width:300px;'>" . PHP_EOL;
        echo "<option value=''></option>" . PHP_EOL;
        foreach ($taxonomies as $taxonomy) {
            $selected = selected($settings['taxonomy'], $taxonomy->name, false);
            printf("<option value='%s'%s>%s&nbsp;&nbsp;</option>", $taxonomy->name, $selected, $taxonomy->labels->singular_name);
        }
        echo "</select>" . PHP_EOL;
        echo "<p class='description'>Select a custom taxonomy for the regions.</p>" . PHP_EOL;
    }
}

/**
 * Setting field for showing meta box
 */
function sm_settings_metabox() {
    $settings = get_option('sm_settings');
    $checked = empty($settings['metabox']) ? '' : checked($settings['metabox'], 1, false);
    echo "<label><input type='checkbox' name='sm_settings[metabox]' value='1' " . $checked . " />&nbsp;&nbsp;Check to show meta box on the edit page.</label>" . PHP_EOL;
    echo "<p class='description'>Adds fields for the street address and description.</p>" . PHP_EOL;
}

/**
 * Setting field for map size
 */
function sm_settings_mapsize() {
    $settings = get_option('sm_settings');
    $value = empty($settings['mapsize']) ? '100' : $settings['mapsize'];
    echo "<input class='small-text' type='number' name='sm_settings[mapsize]' value='" . $value . "'/>&nbsp;%" . PHP_EOL;
    echo "<p class='description'>Enter a percentage of the default Google map size.</p>" . PHP_EOL;
}

/**
 * Setting field for alignment type
 */
function sm_settings_alignment() {
    $settings = get_option('sm_settings');
    if (empty($settings['alignment'])) {
        $left = checked(true, true, false);
        $center = '';
        $right = '';
    } else {
        $left = checked($settings['alignment'], 'left', false);
        $center = checked($settings['alignment'], 'center', false);
        $right = checked($settings['alignment'], 'right', false);
    }
    echo "<label><input name='sm_settings[alignment]' type='radio' value='left' " . $left . "/>&nbsp;Left</label><br />" . PHP_EOL;
    echo "<label><input name='sm_settings[alignment]' type='radio' value='center' " . $center . "/>&nbsp;Center</label><br />" . PHP_EOL;
    echo "<label><input name='sm_settings[alignment]' type='radio' value='right' " . $right . "/>&nbsp;Right</label><br />" . PHP_EOL;
    echo "<p class='description'>Select the alignment of the maps within the content.</p>" . PHP_EOL;
}

/**
 * Setting field for showing tooltips
 */
function sm_settings_tooltips() {
    $settings = get_option('sm_settings');
    $checked = empty($settings['tooltips']) ? '' : checked($settings['tooltips'], 1, false);
    echo "<label><input type='checkbox' name='sm_settings[tooltips]' value='1' " . $checked . " />&nbsp;&nbsp;Check to show tooltips on the regional map.</label>" . PHP_EOL;
    echo "<p class='description'>Tooltips show the region name and number of listings.</p>" . PHP_EOL;
}

/**
 * Setting field for showing listing details
 */
function sm_settings_details() {
    $settings = get_option('sm_settings');
    $checked = empty($settings['details']) ? '' : checked($settings['details'], 1, false);
    echo "<label><input type='checkbox' name='sm_settings[details]' value='1' " . $checked . " />&nbsp;&nbsp;Check to show listing details on Google Maps.</label>" . PHP_EOL;
    echo "<p class='description'>Show details box when the map marker is clicked.</p>" . PHP_EOL;
}

/**
 * Setting field for marker icon type
 */
function sm_settings_icontype() {
    $settings = get_option('sm_settings');
    if (empty($settings['icontype'])) {
        $pin = checked(true, true, false);
        $building = '';
        $custom = '';
    } else {
        $pin = checked($settings['icontype'], 'pin', false);
        $building = checked($settings['icontype'], 'building', false);
        $custom = checked($settings['icontype'], 'custom', false);
    }
    echo "<label><input name='sm_settings[icontype]' type='radio' value='pin' " . $pin . "/>&nbsp;Red Pin</label><br />" . PHP_EOL;
    echo "<label><input name='sm_settings[icontype]' type='radio' value='building' " . $building . "/>&nbsp;Building</label><br />" . PHP_EOL;
    echo "<label><input name='sm_settings[icontype]' type='radio' value='custom' " . $custom . "/>&nbsp;Custom</label><br />" . PHP_EOL;
    echo "<p class='description'>Select the type of marker icon for Google Maps.</p>" . PHP_EOL;
}

/**
 * Setting field for marker icon URL
 */
function sm_settings_iconurl() {
    $settings = get_option('sm_settings');
    $value = empty($settings['iconurl']) ? '' : $settings['iconurl'];
    echo "<input class='regular-text' type='text' name='sm_settings[iconurl]' value='" . $value . "'/>" . PHP_EOL;
    echo "<p class='description'>Enter the URL of an image to use for marker icon.</p>" . PHP_EOL;
}

/**
 * Setting field for size of Google infobox
 */
function sm_settings_infobox() {
    $settings = get_option('sm_settings');
    $value = empty($settings['infobox-width']) ? SM_DEFAULT_INFOBOX_WIDTH : $settings['infobox-width'];
    echo "<input class='small-text' type='number' name='sm_settings[infobox-width]' value='" . $value . "'/>&nbsp;&nbsp;Width (pixels)" . PHP_EOL;
    echo "<p class='description'>Enter the width of the infobox for Google Maps.</p>" . PHP_EOL;
}

/**
 * Setting field for colors of link button
 */
function sm_settings_button_colors() {
    $settings = get_option('sm_settings');
    $value = empty($settings['normal-color']) ? '' : $settings['normal-color'];
    echo "<p><input id='minicolors_normal' class='regular-text' type='text' name='sm_settings[normal-color]' value='" . $value . "' style='height: auto;' />&nbsp;&nbsp;Normal</p>" . PHP_EOL;
    $value = empty($settings['hover-color']) ? '' : $settings['hover-color'];
    echo "<p><input id='minicolors_hover' class='regular-text' type='text' name='sm_settings[hover-color]' value='" . $value . "' style='height: auto;' />&nbsp;&nbsp;Hover</p>" . PHP_EOL;
    $value = empty($settings['font-color']) ? '' : $settings['font-color'];
    echo "<p><input id='minicolors_font' class='regular-text' type='text' name='sm_settings[font-color]' value='" . $value . "' style='height: auto;' />&nbsp;&nbsp;Font</p>" . PHP_EOL;
    echo "<p class='description'>Select colors for the link button in the details box.</p>" . PHP_EOL;
}

/**
 * Setting field for text shown on link button
 */
function sm_settings_button_text() {
    $settings = get_option('sm_settings');
    $value = empty($settings['button-text']) ? 'More information' : $settings['button-text'];
    echo "<input class='regular-text' type='text' name='sm_settings[button-text]' value='" . $value . "'/>" . PHP_EOL;
    echo "<p class='description'>Enter text for the link button in the details box.</p>" . PHP_EOL;
}

/**
 * Setting field for custom CSS
 */
function sm_settings_css() {
    $settings = get_option('sm_settings');
    $value = empty($settings['css']) ? '' : $settings['css'];
    echo "<textarea class='regular-text' name='sm_settings[css]' style='height: 90px; width: 300px;'>" . $value . "</textarea>" . PHP_EOL;
    echo "<p class='description'>Enter any custom CSS styles for the maps.</p>" . PHP_EOL;
}

/**
 * Create settings template
 */
function sm_settings_template() {
    ?>
    <div class='wrap'>
        <div class='icon32' id='icon-options-general'><br></div>
        <h2>Singapore Maps Settings</h2>
        <form method='post' action='options.php'>
            <?php settings_fields('sm_settings_group'); ?>
            <?php do_settings_sections('sm_settings_page'); ?>
            <p class='submit'>
                <input id='submit' type='submit' name='submit' class='button-primary' value='<?php esc_attr_e("Save Changes"); ?>' />
            </p>
        </form>
    </div>
    <?php
}

/**
 * set display map ?
 * @author: Ho Ngoc Hang
 */
function sm_settings_hidden_map() {
    $settings = get_option('sm_settings');
    $checked = empty($settings['hiddenmap']) ? '' : checked($settings['hiddenmap'], 1, false);
    echo "<label><input type='checkbox' name='sm_settings[hiddenmap]' value='1' " . $checked . " />&nbsp;&nbsp;Check to hide map filter.</label>" . PHP_EOL;
    echo "<p class='description'>Hide map filters.</p>" . PHP_EOL;
}

function sm_settings_mapsize_smap() {
    $settings = get_option('sm_settings');
    $value = empty($settings['mapsize_smap']) ? '100' : $settings['mapsize_smap'];
    echo "<input class='small-text' type='number' name='sm_settings[mapsize_smap]' value='" . $value . "'/>&nbsp;%" . PHP_EOL;
    echo "<p class='description'>Enter a percentage of the default Singapore map size.</p>" . PHP_EOL;
}

/*
 * set width google map
 * @author: Ho Ngoc Hang
 */

function sm_settings_gsize() {
    $settings = get_option('sm_settings');
    $value_w = empty($settings['gsize-width']) ? SM_GMAP_DEFAULT_WIDTH : $settings['gsize-width'];
    $value_h = empty($settings['gsize-height']) ? SM_GMAP_DEFAULT_HEIGHT : $settings['gsize-height'];

    echo "<input class='small-text' type='number' name='sm_settings[gsize-width]' value='" . $value_w . "'/>&nbsp;&nbsp;Width (pixels)" . PHP_EOL;
    echo "<p class='description'>Enter the width of Singapore Maps.</p>" . PHP_EOL;

    echo "<input class='small-text' type='number' name='sm_settings[gsize-height]' value='" . $value_h . "'/>&nbsp;&nbsp;Height (pixels)" . PHP_EOL;
    echo "<p class='description'>Enter the height of Singapore Maps.</p>" . PHP_EOL;
}

function sm_settings_category_icon() {
    $settings = get_option('sm_settings');
    $north_icon = empty($settings['north_icon']) ? SM_NORTH_ICON : $settings['north_icon'];
    $north_east_icon = empty($settings['north_east_icon']) ? SM_NORTH_EAST_ICON : $settings['north_east_icon'];
    $west_icon = empty($settings['west_icon']) ? SM_WEST_ICON : $settings['west_icon'];
    $central_icon = empty($settings['central_icon']) ? SM_CENTRAL_ICON : $settings['central_icon'];
    $east_icon = empty($settings['east_icon']) ? SM_EAST_ICON : $settings['east_icon'];

    echo "<input class='regular-text' type='text' name='sm_settings[north_icon]' value='" . $north_icon . "'/>" . PHP_EOL;
    echo "<p class='description'>Enter the URL of marker icon to use for North region.</p>" . PHP_EOL;

    echo "<input class='regular-text' type='text' name='sm_settings[north_east_icon]' value='" . $north_east_icon . "'/>" . PHP_EOL;
    echo "<p class='description'>Enter the URL of marker icon to use for North East region.</p>" . PHP_EOL;

    echo "<input class='regular-text' type='text' name='sm_settings[west_icon]' value='" . $west_icon . "'/>" . PHP_EOL;
    echo "<p class='description'>Enter the URL of marker icon to use for West region.</p>" . PHP_EOL;

    echo "<input class='regular-text' type='text' name='sm_settings[central_icon]' value='" . $central_icon . "'/>" . PHP_EOL;
    echo "<p class='description'>Enter the URL of marker icon to use for Central region.</p>" . PHP_EOL;

    echo "<input class='regular-text' type='text' name='sm_settings[east_icon]' value='" . $east_icon . "'/>" . PHP_EOL;
    echo "<p class='description'>Enter the URL of marker icon to use for East region.</p>" . PHP_EOL;
}

/**
 * Create address meta box for Google Maps
 */
function sm_add_metabox() {
    $settings = get_option('sm_settings');
    if (!empty($settings['metabox'])) {
        $posttype = $settings['posttype'];
        if (!empty($posttype)) {
            add_meta_box(
                    'sm_metabox', 'Singapore Maps', 'sm_metabox_template', $posttype, 'normal', 'low'
            );
        }
    }
}

/**
 * Create template for address meta box
 */
function sm_metabox_template($post) {
    wp_nonce_field(plugin_basename(__FILE__), 'sm_nonce');
    $street_address = get_post_meta($post->ID, 'street_address', true);
    $listing_desc = get_post_meta($post->ID, 'listing_desc', true);
    $listing_region = get_post_meta($post->ID, 'listing_region', true);

    include('metabox.php');
}

/**
 * Save street address for Google Maps
 */
function sm_save_metadata($post_id) {
    // Perform checks
    $settings = get_option('sm_settings');
    if (empty($settings['metabox'])) {
        return $post_id;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    if (empty($_POST['sm_nonce']) || !wp_verify_nonce($_POST['sm_nonce'], plugin_basename(__FILE__))) {
        return $post_id;
    }
    if (empty($settings['posttype']) || $settings['posttype'] != $_POST['post_type']) {
        return $post_id;
    }
    if (!current_user_can('edit_posts', $post_id)) {
        return $post_id;
    }
    // Update street address
    $new_address = sanitize_text_field($_POST['street_address']);
    $old_address = get_post_meta($post_id, 'street_address', true);
    if ($new_address && $new_address != $old_address) {
        update_post_meta($post_id, 'street_address', $new_address);
    } elseif ('' == $new_address && $old_address) {
        delete_post_meta($post_id, 'street_address', $old_address);
    }

    $location = $new_address;

    if (!stristr($location, 'Singapore'))
        $location .= ', Singapore';

    $maps_uri = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=true&address=' . urlencode($location);
    $maps_json = file_get_contents($maps_uri);
    $maps_return = json_decode($maps_json);
    $lat_long = @$maps_return->results[0]->geometry->location;

    $latitude_longitude = serialize($lat_long);
    update_post_meta($post_id, 'kp-lat-long', $latitude_longitude);


    // Update description
    $new_desc = sanitize_text_field($_POST['listing_desc']);
    $old_desc = get_post_meta($post_id, 'listing_desc', true);
    if ($new_desc && $new_desc != $old_desc) {
        update_post_meta($post_id, 'listing_desc', $new_desc);
    } elseif ('' == $new_desc && $old_desc) {
        delete_post_meta($post_id, 'listing_desc', $old_desc);
    }
    // Update region
    $new_region = sanitize_text_field($_POST['listing_region']);
    $old_region = get_post_meta($post_id, 'listing_region', true);
    if ($new_region && $new_region != $old_region) {
        update_post_meta($post_id, 'listing_region', $new_region);
    } elseif ('' == $new_region && $old_region) {
        delete_post_meta($post_id, 'listing_region', $old_region);
    }
}

/**
 * Hooks and Filters
 */
add_action('admin_init', 'sm_settings_init');
add_action('admin_menu', 'sm_settings_menu');
add_action('add_meta_boxes', 'sm_add_metabox');
add_action('save_post', 'sm_save_metadata');
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'sm_settings_link');
add_shortcode('singapore-maps', 'sm_shortcode');
add_shortcode('resale-singapore-maps', 'sm_shortcode_resale');
add_shortcode('rental-singapore-maps', 'sm_shortcode_rental');

function ms_gallery_images($post_id) {

    $args = array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'post_parent' => $post_id,
        'post_mime_type' => 'image',
        'posts_per_page' => 1,
        'order' => 'ASC',
        'orderby' => 'menu_order',
    );

    $images = get_posts($args);

    return $images;
}

/**
 * 
 * @param type $post_id
 * @return boolean
 */
function ms_get_image_by_object($post_id) {

    $post = ms_gallery_images($post_id);
    if (empty($post))
        return false;
    $post = $post[0];
    $arrayImage = get_post_meta($post->ID, '_wp_attachment_metadata', true);
    $file_root_image = isset($arrayImage['file']) ? $arrayImage['file'] : '';
    if (!$file_root_image) {
        $file_root_image = get_post_meta($post->ID, '_wp_attached_file', true);
    }
    if ($file_root_image) {
        $pathImage = explode("/", $file_root_image);
        array_pop($pathImage);
        $stringPath = implode("/", $pathImage);

        if (isset($arrayImage['sizes']['kfs_main_image'])) {
            $imageName = $arrayImage['sizes']['kfs_main_image']['file'];
        }
        if (!$imageName) {
            $imageName = isset($arrayImage['sizes']['medium']['file']) ? $arrayImage['sizes']['medium']['file'] : '';
        }
        if (!$imageName) {
            $imageName = isset($arrayImage['sizes']['thumbnail']['file']) ? $arrayImage['sizes']['thumbnail']['file'] : '';
        }
        if ($imageName) {
            $imageName = "/" . $stringPath . "/$imageName";
        }
        if (!$imageName) {
            $imageName = $file_root_image;
        }
        return $imageName;
    } else {
        return false;
    }
}

function wordLimit($str, $limit = 100, $end_char = '&#8230;') {
    if (trim($str) == '')
        return $str;
// always strip tags for text
    $str = strip_tags($str);

    preg_match('/\s*(?:\S*\s*){' . (int) $limit . '}/', $str, $matches);
    if (strlen($matches[0]) == strlen($str))
        $end_char = '';
    return rtrim($matches[0]) . $end_char;
}

// END
