<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/html/view/top-menu'] = function( $app, $return )
{
	$link = $app->make('/html/view/link')
		->to('/locations')
		->add( $app->make('/html/view/icon')->icon('home') )
		->add( HCM::__('Locations') )
		;
	$return['location'] = $link;

	return $return;
};
