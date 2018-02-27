<?php
/*
  Plugin Name: Tripadvisor Short codes
  Description: A simple Tripadvisor Short codes plugin.
  Author: Tran Trong Thang
  Version: 1.0
  Plugin URI:
  Author URI:
  Donate link:
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
/*
 * Created on : Jan 17, 2018, 11:29:16 AM
 * Author: Tran Trong Thang
 * Email: trantrongthang1207@gmail.com
 * Skype: trantrongthang1207
 */

add_shortcode('woo_product_subcategories', 'woo_product_subcategories');

add_action('wp_footer', 'my_footer_scripts');

function my_footer_scripts() {
    global $options;
    $options = get_option('tripadshortcodes');
    $tripadvisor_active = $options['tripadvisor_active'];
    $tripadvisor_js = $options['tripadvisor_js'];
    if ($tripadvisor_active) {
        //echo $options['tripadvisor_code'];
        //echo str_replace(array('<', '>', '\"', '"'), array('<', '>', '"', '"'), htmlspecialchars($options['tripadvisor_code']));
        ob_start();
        ?>
        <link href="http://vietnam-easy-rider.com/wp-content/themes/gotravel/assets/css/ion-icons/css/ionicons.min.css?ver=4.9.2" rel="stylesheet" type="text/css"/>
        <div class="advisor-pop">
            <div style="position:relative">
                <div class="close2"><i aria-hidden="true" class="ion-close-circled"></i></div>
                <div id="TA_selfserveprop524" class="TA_selfserveprop">
                    <ul id="cPj95yu" class="TA_links otY8x3zV">
                        <li id="dxiPev0Zsqa" class="dv1jjdIy">
                            <a target="_blank" href="https://www.tripadvisor.com/"><img src="https://www.tripadvisor.com/img/cdsi/img2/branding/150_logo-11900-2.png" alt="TripAdvisor"/></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="advisor-open" style="">
            <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/img/advisor-open.png' ?>">
        </div>
        <script src="<?php echo $tripadvisor_js ?>"></script>
        <script>
            jQuery(document).ready(function ($) {

                $('.close2').click(function () {
                    $('.advisor-pop').animate({
                        'right': '-340px'
                    }, 1000);
                    setTimeout(function () {
                        $('.advisor-open').animate({
                            'right': '0px'
                        }, 500)
                    }, 1050);
                });
                $('.advisor-open').click(function () {
                    setTimeout(function () {
                        $('.advisor-pop').animate({
                            'right': '0'
                        }, 1000)
                    }, 550);

                    $('.advisor-open').animate({
                        'right': '-50px'
                    }, 500);

                });
            })
            jQuery(window).load(function (){
                jQuery('.advisor-pop').fadeIn(800);
            })
        </script>
        <style>
            .advisor-pop {
                position: fixed;
                right: 0;
                top: 40%;
                z-index: 9999;
                display: none;
            }
            .close2{
                font-size: 30px;
                position: absolute;
                right: 10px;
                top: 35px;
                z-index: 9999;
            }
            .close2:hover{
                cursor: pointer;
            }
            .advisor-open {
                cursor: pointer;
                position: fixed;
                right: -50px;
                top: 40%;
                z-index: 99999;
            }     
            .advisor-open img {
                height: 50px;
                width: 50px;
            }
        </style>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        echo $html;
    }
}

function crl_admin__menu() {
    global $menu;
    $main_menu_exists = false;
    foreach ($menu as $key => $value) {
        if ($value[2] == 'tripadvisor-shortcodes') {
            $main_menu_exists = true;
        }
    }
    if (!$main_menu_exists) {
        $tripadshortcodes_menu_icon = plugin_dir_url(__FILE__) . 'assets/img/tripadshortcodes.png';
        add_object_page(null, 'Tripadvisor shortcodes', null, 'tripadvisor-shortcodes', 'tripadvisor-shortcodes', $tripadshortcodes_menu_icon);
    }
    add_submenu_page('tripadvisor-shortcodes', 'Tripadvisor category page', 'Tripadvisor category page', 1, 'shortcode-category-page', 'tripadshortcodes');
}

function crl_admin_init() {
    // Create admin menu and page.
    add_action('admin_menu', 'crl_admin__menu');
    // Enable admin scripts and styles
    if (function_exists('tripadshortcodes_admin__head')) {
        add_action('admin_enqueue_scripts', 'tripadshortcodes_admin__head');
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function tripadshortcodes() {
    echo '<div class="wrap"><h2>Tripadvisor category page</h2>';
    if (isset($_REQUEST['save'])) {

        $options['tripadvisor_active'] = ($_REQUEST['tripadvisor_active']);
        $options['tripadvisor_js'] = ($_REQUEST['tripadvisor_js']);
        //$options['tripadvisor_code'] = ($_REQUEST['tripadvisor_code']);

        update_option('tripadshortcodes', $options);
        // Show a message to say we've done something
        echo '<div class="updated tripadshortcodes-success-messages"><p><strong>' . __("Settings saved.", "Tripadshortcodes") . '</strong></p></div>';
    } else {
        $options = get_option('tripadshortcodes');
    }
    require ('admin_menu.php');
}

add_action('init', 'crl_admin_init');
