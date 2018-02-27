<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/layout/top-menu'][] = function( $app, $return )
{
	$link = $app->make('/html/ahref')
		->to('/conf')
		->add( HCM::__('Configuration') )
		;
	$return['conf'] = array($link, 100);

	return $return;
};