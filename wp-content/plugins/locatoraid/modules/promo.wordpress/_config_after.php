<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/layout/view/content-header-menubar'][] = function( $app, $return )
{
	$promo = $app->make('/promo.wordpress/view');

	$return = $app->make('/html/list')
		->set_gutter(2)
		->add( $promo )
		->add( $return )
		;

	return $return;
};