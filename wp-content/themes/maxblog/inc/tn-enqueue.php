<?php
//load styles
if (!function_exists('tn_load_css')) {
    function tn_load_css()
    {
        //css
        $tn_rtl = tn_get_theme_option('tn_rtl');

        wp_enqueue_style('tn-style', get_stylesheet_uri(), array(), TN_THEME_VERSION);
        wp_enqueue_style('tn-extend-css', get_template_directory_uri() . '/lib/extend-lib/css/extend-lib.css', array('tn-style'), TN_THEME_VERSION, 'all');

        if(!empty($tn_rtl)){
            wp_enqueue_style('tn-style-css', get_template_directory_uri() . '/assets/css/tn-style-rtl.css', array('tn-style', 'tn-extend-css'), TN_THEME_VERSION, 'all');
        } else {
            wp_enqueue_style('tn-style-css', get_template_directory_uri() . '/assets/css/tn-style.css', array('tn-style', 'tn-extend-css'), TN_THEME_VERSION, 'all');
        }
    }
}
add_action('wp_enqueue_scripts', 'tn_load_css');

if (!function_exists('tn_load_scripts')) {
    function tn_load_scripts()
    {
        if (is_admin()) return false;
        $tn_smooth_scroll = tn_get_theme_option('tn_smooth_scroll');

        wp_enqueue_script('jquery');
        wp_enqueue_script('tn-extend-lib', get_template_directory_uri() . '/lib/extend-lib/js/extend-lib.js', array('jquery'), false, true);

      if (!empty($tn_smooth_scroll)) {
            wp_enqueue_script('tn-smooth-scroll', get_template_directory_uri() . '/lib/extend-lib/js/smooth-scroll.js', array('jquery'), false, true);
        }

        wp_enqueue_script('tn-scroll-up-lib', get_template_directory_uri() . '/lib/extend-lib/js/scroll-up-bar.js', array('jquery', 'tn-extend-lib'), false, true);
        wp_enqueue_script('tn-sticky-sidebar-lib', get_template_directory_uri() . '/lib/extend-lib/js/jquery.sticky-kit.min.js', array('jquery', 'tn-extend-lib', 'tn-scroll-up-lib'), false, true);
        wp_enqueue_script('tn-script', get_template_directory_uri() . '/assets/js/tn-script.js', array('jquery', 'tn-extend-lib','tn-scroll-up-lib', 'tn-sticky-sidebar-lib'), TN_THEME_VERSION, true);

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
}
add_action('wp_enqueue_scripts', 'tn_load_scripts');

//include admin script
if (!function_exists('tn_admin_script')) {
    function tn_admin_script($hook)
    {
        if (is_admin() && ($hook == 'post.php' || $hook == 'post-new.php')) {
            wp_register_script('tn-admin-script', get_template_directory_uri() . '/inc/admin/js/tn-script-admin.js', array(), false, true);
            wp_enqueue_script('tn-admin-script'); // enqueue it
        }
    }
}
add_action('admin_enqueue_scripts', 'tn_admin_script');

//Custom Theme Options
if (!function_exists('tn_redux_css')) {
    function tn_redux_css()
    {
        wp_register_style('tn-redux-css', get_template_directory_uri() . '/inc/admin/css/custom-redux.css', array('redux-css', 'admin-css'), TN_THEME_VERSION, 'all');
        wp_enqueue_style('tn-redux-css');
    }
}

add_action('redux/page/tn_options/enqueue', 'tn_redux_css');
