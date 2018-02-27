<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/layout/top-menu'][] = function( $app, $return )
{
	$link = $app->make('/html/ahref')
		->to('/searchlog')
		->add( HCM::__('Search Log') )
		;

	$return['searchlog'] = array( $link, 300 );
	return $return;
};
