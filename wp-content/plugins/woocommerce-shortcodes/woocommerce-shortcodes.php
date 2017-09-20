<?php
/*
  Plugin Name: WooCommerce Short codes
  Description: A simple WooCommerce Short codes plugin.
  Author: trantrongthang
  Version: 1.3
  Plugin URI:
  Author URI:
  Donate link:
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
/*
 * Created on : Sep 18, 2017, 3:25:11 PM
 * Author: Tran Trong Thang
 * Email: trantrongthang1207@gmail.com
 * Skype: trantrongthang1207
 */

add_shortcode('woo_product_subcategories', 'woo_product_subcategories');

function woo_product_subcategories($args = array()) {

    // Find the category + category parent, if applicable
    $term = get_queried_object();
    $parent_id = empty($term->term_id) ? 0 : $term->term_id;

    $product_categories = get_categories(apply_filters('woocommerce_product_subcategories_args', array(
        'parent' => $parent_id,
        'menu_order' => 'ASC',
        'hide_empty' => 0,
        'hierarchical' => 1,
        'taxonomy' => 'product_cat',
        'pad_counts' => 1,
    )));

    if (apply_filters('woocommerce_product_subcategories_hide_empty', true)) {
        $product_categories = wp_list_filter($product_categories, array('count' => 0), 'NOT');
    }

    if ($product_categories) {
        ob_start();
        ?>
        <ul class="products">
            <?php
            foreach ($product_categories as $category) {
                wc_get_template('content-product_cat.php', array(
                    'category' => $category,
                ));
            }
            ?>
        </ul>
        <script>
            jQuery(document).ready(function ($) {
                $('body').addClass('woocommerce woocommerce-page archive');
            })
        </script>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}

add_shortcode('woo_product_categories', 'woo_product_categories');

function woo_product_categories($param) {
    if (!empty($param['term_taxonomy_id'])) {
        $term_taxonomy_id = explode(',', $param['term_taxonomy_id']);
    } else {
        $term_taxonomy_id = array(170, 174, 169);
    }
    // Find the category + category parent, if applicable
    $term = get_queried_object();
    $parent_id = empty($term->term_id) ? 0 : $term->term_id;

    $product_categories = get_categories(apply_filters('woocommerce_product_subcategories_args', array(
        'term_taxonomy_id' => $term_taxonomy_id,
        'menu_order' => 'ASC',
        'hide_empty' => 0,
        'hierarchical' => 1,
        'taxonomy' => 'product_cat',
        'pad_counts' => 1,
    )));

    if (apply_filters('woocommerce_product_subcategories_hide_empty', true)) {
        $product_categories = wp_list_filter($product_categories, array('count' => 0), 'NOT');
    }

    if ($product_categories) {
        ob_start();
        ?>
        <ul class="products">
            <?php
            foreach ($product_categories as $category) {
                wc_get_template('content-product_cat.php', array(
                    'category' => $category,
                ));
            }
            ?>
        </ul>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}

add_filter('body_class', 'woo_body_classes');

function woo_body_classes($classes) {

    $classes[] = ' woocommerce woocommerce-page archive ';

    return $classes;
}

function crl_admin__menu() {
    global $menu;
    $main_menu_exists = false;
    foreach ($menu as $key => $value) {
        if ($value[2] == 'woocommerce-shortcodes') {
            $main_menu_exists = true;
        }
    }
    if (!$main_menu_exists) {
        $wooshortcodes_menu_icon = plugin_dir_url(__FILE__) . 'assets/img/wooshortcodes.png';
        add_object_page(null, 'Woocommerce shortcodes', null, 'woocommerce-shortcodes', 'woocommerce-shortcodes', $wooshortcodes_menu_icon);
    }
    add_submenu_page('woocommerce-shortcodes', 'Shortcode category page', 'Shortcode category page', 1, 'shortcode-category-page', 'wooshortcodes');
}

function crl_admin_init() {
    // Create admin menu and page.
    add_action('admin_menu', 'crl_admin__menu');
    // Enable admin scripts and styles
    if (function_exists('wooshortcodes_admin__head')) {
        add_action('admin_enqueue_scripts', 'wooshortcodes_admin__head');
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function wooshortcodes() {
    echo '<div class="wrap"><h2>Shortcode category page</h2>';
    if (isset($_REQUEST['save'])) {

        $options['url_cate'] = $_REQUEST['url_cate'];
        $options['cateId'] = implode(',', $_REQUEST['cateId']);

        update_option('wooshortcodes', $options);
        // Show a message to say we've done something
        echo '<div class="updated wooshortcodes-success-messages"><p><strong>' . __("Settings saved.", "Wooshortcodes") . '</strong></p></div>';
    } else {
        $options = get_option('wooshortcodes');
    }
    require ('admin_menu.php');
}

add_action('init', 'crl_admin_init');
