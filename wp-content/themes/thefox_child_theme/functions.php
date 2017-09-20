<?php  

function theme_enqueue_styles() {

    global $rd_data;

    wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'style_end', get_template_directory_uri() . '/style_end.css' );
	if($rd_data['rd_responsive']== true){
	if ($rd_data['rd_nav_type'] !== 'nav_type_19' && $rd_data['rd_nav_type'] !== 'nav_type_19_f' ){	
	    wp_enqueue_style('media-queries',  get_template_directory_uri() . '/media-queries_wide.css');
	}
	else {
	    wp_enqueue_style('media-queries',  get_template_directory_uri() . '/media-queries_edge_nav.css');		
	}
	}
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'style' ));
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );


// if you want to add some custom function

?>