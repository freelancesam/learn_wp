<?php
function elegant_enqueue_css() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

add_action('wp_enqueue_scripts', 'elegant_enqueue_css');

include('editor/footer-editor.php');

include('editor/login-editor.php');
<<<<<<< HEAD
add_filter('pre_get_posts','lay_custom_post_type');
function lay_custom_post_type($query) {
  if (is_home() && $query->is_main_query ())
    $query->set ('post_type', array ('post','logos'));
    return $query;
}
=======
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
