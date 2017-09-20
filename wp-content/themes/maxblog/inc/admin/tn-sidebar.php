<?php
//register sidebar widget
function tn_widgets_init()
{
    //Home sidebar
    register_sidebar(array(
        'name' => __('Top Full Width', 'tn'),
        'id' => 'full_top',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="widget-title"><h3>',
        'after_title' => '</h3></div>',
        'description' => __('Display full width content without sidebar at top of site', 'tn'),
    ));

    register_sidebar(array(
        'name' => __('Primary Content', 'tn'),
        'id' => 'content',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="widget-title"><h3>',
        'after_title' => '</h3></div>',
        'description' => __('Display content with sidebar', 'tn'),
    ));

    register_sidebar(array(
        'name' => __('Bottom Full Width', 'tn'),
        'id' => 'full_bottom',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="widget-title"><h3>',
        'after_title' => '</h3></div>',
        'description' => __('Display full width content at bottom of site', 'tn'),
    ));

    //sidebar
    register_sidebar(array(
        'name' => __('Home Sidebar', 'tn'),
        'id' => 'sidebar_home',
        'before_widget' => '<aside id="%1$s" class="sidebar-widget widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="widget-title"><h3>',
        'after_title' => '</h3></div>',
        'description' => __('This sidebar used for Front Page', 'tn'),
    ));

    register_sidebar(array(
        'name' => __('Blog Sidebar', 'tn'),
        'id' => 'sidebar_blog',
        'before_widget' => '<aside id="%1$s" class="sidebar-widget widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="widget-title"><h3>',
        'after_title' => '</h3></div>',
        'description' => __('This sidebar used for Singe Page, Blog Page, Category and Archive Page...', 'tn'),
    ));

    //footer
    register_sidebar(array(
        'name' => __('Footer Sidebar 1', 'tn'),
        'id' => 'footer_sidebar_1',
        'before_widget' => '<aside id="%1$s" class="footer-widget widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="widget-title"><h3>',
        'after_title' => '</h3></div>',
        'description' => __('Left footer sidebar', 'tn'),
    ));

    register_sidebar(array(
        'name' => __('Footer Sidebar 2', 'tn'),
        'id' => 'footer_sidebar_2',
        'before_widget' => '<aside id="%1$s" class="footer-widget widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="widget-title"><h3>',
        'after_title' => '</h3></div>',
        'description' => __('Middle footer sidebar', 'tn'),
    ));

    register_sidebar(array(
        'name' => __('Footer Sidebar 3', 'tn'),
        'id' => 'footer_sidebar_3',
        'before_widget' => '<aside id="%1$s" class="footer-widget widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="widget-title"><h3>',
        'after_title' => '</h3></div>',
        'description' => __('Right footer sidebar', 'tn'),
    ));
}

add_action('widgets_init', 'tn_widgets_init');

