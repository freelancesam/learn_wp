<?php

function theme_enqueue_styles() {
    wp_enqueue_style('avada-parent-stylesheet', get_template_directory_uri() . '/style.css');
    wp_enqueue_script('fgc-script', get_stylesheet_directory_uri() . '/assets/js/fgcscript.js', array('jquery'));
}

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

function avada_lang_setup() {
    $lang = get_stylesheet_directory() . '/languages';
    load_child_theme_textdomain('Avada', $lang);
}

add_action('after_setup_theme', 'avada_lang_setup');

function register_my_menu() {
    register_nav_menu('header-menu', __('Header Menu'));
}

add_action('init', 'register_my_menu');

/*
 * increase_fontsize_invoicepdf
 * author: HungTT-FGC	
 * version: 1.0
 */

function increase_fontsize_invoicepdf() {
    ?>
    <style>
        #page {
            font-size: 1.1em;
        }
    </style>
    <?php

}

add_filter('woocommerce_cart_shipping_method_full_label', 'remove_free_label', 10, 2);

function remove_free_label($full_label, $method) {
    $full_label = str_replace("(Free)", "", $full_label);
    return $full_label;
}

add_action('wcdn_head', 'increase_fontsize_invoicepdf', 20);

add_action('woocommerce_after_single_product_summary', 'avada_woocommerce_after_single_product_summary', 20);

function tv_remove_product_page_skus($enabled) {
    if (!is_admin() && is_product()) {
        return false;
    }

    return $enabled;
}

add_filter('wc_product_sku_enabled', 'tv_remove_product_page_skus');


add_action('woocommerce_checkout_process', 'is_phone');

function is_phone() {
    $phone = $_REQUEST['billing_phone'];

    if (0 == strlen(trim(preg_replace('/[\s\#0-9_\-\+\(\)]/', '', $phone)))) {
        if (strlen($phone) < 8) {
            // your function's body above, and if error, call this wc_add_notice
            wc_add_notice(__('Your phone number has atleast 8 integers.'), 'error');
        }
    }
}

remove_action('woocommerce_shipping_init', 'wcso_shipping_methods_init');

function wcso_review_order_shipping_options_custom() {
    echo 'hehe';
}

add_action('init', 'custom_add_style_files', 10);

function custom_add_style_files() {

    remove_action('woocommerce_cart_totals_after_shipping', 'wcso_review_order_shipping_options', 10);
    add_action('woocommerce_cart_totals_after_shipping', 'wcso_review_order_shipping_options_custom', 10);
    
    remove_action('woocommerce_review_order_after_shipping', 'wcso_review_order_shipping_options', 10);
    add_action('woocommerce_review_order_after_shipping', 'wcso_review_order_shipping_options_custom', 10);
}
