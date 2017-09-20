<?php
define('TN_THEME_VERSION','1.0');

if (!isset($content_width)) {
    $content_width = 1090;
}

//include enqueue
require_once get_template_directory() . '/inc/tn-enqueue.php';


//add theme support
if (!function_exists('tn_setup')) {
    function tn_setup()
    {
        add_theme_support('automatic-feed-links');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
        add_theme_support('post-formats', array('image', 'gallery', 'video', 'quote', 'audio'));
        add_post_type_support('page', 'video');
    }
}
add_action('after_setup_theme', 'tn_setup');

//load translate function
if (!function_exists('tn_translate')) {
    function tn_translate()
    {
        load_theme_textdomain('tn', get_template_directory() . '/languages');
        $locale = get_locale();
        $locale_file = get_template_directory() . "/library/$locale.php";
        if (is_readable($locale_file)) {
            require($locale_file);
        }
    }
}
add_action('after_setup_theme', 'tn_translate');

//resize thumb
if (!function_exists('img_crop_setup')) {
    function img_crop_setup()
    {
        add_image_size('small_thumb', 90, 63, true);
        add_image_size('module_big_thumb', 320, 225, true);
        add_image_size('module_medium_thumb', 320, 180, true);
        add_image_size('module_5_thumb', 320, 430, true);
        add_image_size('module_square_thumb', 320, 320, true);
        add_image_size('module_slider_thumb', 355, 395, true);
        add_image_size('blog_classic_thumb', 740, 431, true);;
        add_image_size('feature_medium_thumb', 378, 283, true);
        add_image_size('feature_big_thumb', 568, 284, true);
        add_image_size('big-slider-thumb', 1170, 660, true);
        add_image_size('native-image-thumb', 990, 0, true);
    }
}
add_action('after_setup_theme', 'img_crop_setup');

//include sidebar
require_once get_template_directory() . '/inc/admin/tn-sidebar.php';

//include custom filed
require_once get_template_directory() . '/inc/admin/tn-custom-field.php';

// Re-define meta box path and URL
define('RWMB_URL', trailingslashit(get_template_directory_uri() . '/lib/meta-box'));
define('RWMB_DIR', trailingslashit(get_template_directory() . '/lib/meta-box'));

// Include the meta box
require_once RWMB_DIR . 'meta-box.php';
require_once(dirname(__FILE__) . '/lib/taxonomy-meta/taxonomy-meta.php');

//include theme option framework
if (!class_exists('ReduxFramework') && file_exists(dirname(__FILE__) . '/lib/ReduxFramework/ReduxCore/framework.php')) {
    require_once(dirname(__FILE__) . '/lib/ReduxFramework/ReduxCore/framework.php');

}

if (!isset($tn_options) && file_exists(dirname(__FILE__) . '/inc/admin/tn-redux.php')) {
    require_once(dirname(__FILE__) . '/inc/admin/tn-redux.php');
}

//include tn framework
require_once get_template_directory() . '/inc/tn-include.php';

//register menu
function register_top_menu()
{
    register_nav_menu('menu_main', __('Main Menu', 'tn'));
    register_nav_menu('menu_top', __('Top Menu', 'tn'));
    register_nav_menu('menu_footer', __('Footer Menu', 'tn'));
}

add_action('init', 'register_top_menu');
add_filter('wp_title', 'tn_wp_title', 10, 2);
