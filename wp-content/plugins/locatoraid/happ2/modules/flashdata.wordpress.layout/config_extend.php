<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$before['/layout/view/body'] = function( $app, $src )
{
	// in admin show by admin notices
	if( is_admin() ){
		return;
	}

	$flash_out = $app->make('/flashdata.layout/view')
		->run('render')
		;

	if( ! $flash_out ){
		return;
	}

	$return = $app->make('/html/view/container')
		->add( $flash_out )
		->add( $return )
		;

	$src
		->set_content($return)
		;
};