<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/app/enqueuer->register_script'] = function( $app, $handle, $path )
{
	$wp_handle = 'hc2-script-' . $handle;
	$path = $app->make('/wordpress.layout/path')
		->full_path( $path )
		;
	wp_register_script( $wp_handle, $path, array('jquery') );
};

$after['/app/enqueuer->register_style'] = function( $app, $handle, $path )
{
	$skip = array('reset', 'style', 'form', 'font');
	if( in_array($handle, $skip) ){
		return;
	}

	if( $handle == 'hc' ){
		$path = str_replace('/hc.css', '/hc-wp.css', $path);
	}

	$wp_handle = 'hc2-style-' . $handle;
	$path = $app->make('/wordpress.layout/path')
		->full_path( $path )
		;
	wp_register_style( $wp_handle, $path );
};

$after['/app/enqueuer->enqueue_script'] = function( $app, $handle )
{
	$wp_handle = 'hc2-script-' . $handle;
	wp_enqueue_script( $wp_handle );
};

$after['/app/enqueuer->enqueue_style'] = function( $app, $handle )
{
	$wp_handle = 'hc2-style-' . $handle;
	wp_enqueue_style( $wp_handle );
};

$after['/app/enqueuer->localize_script'] = function( $app, $handle, $params )
{
	$wp_handle = 'hc2-script-' . $handle;
	$js_var = 'hc2_' . $handle . '_vars'; 
	wp_localize_script( $wp_handle, $js_var, $params );
};

$after['/layout/view/body'] = function( $app )
{
	$enqueuer = $app->make('/app/enqueuer');
	return;
};

$after['/layout/view/content-header-menubar'] = function( $app, $return )
{
	$header = $return->child('header');
	if( $header ){
		$wp_header_end = $app->make('/html/view/element')->tag('hr')
			->add_attr('class', 'wp-header-end')
			;
		$header
			->add( $wp_header_end )
			;
	}
	return $return;
};