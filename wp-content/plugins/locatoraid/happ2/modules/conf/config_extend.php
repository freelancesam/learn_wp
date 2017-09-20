<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/html/view/top-menu'] = function( $app, $return )
{
	$link = $app->make('/html/view/link')
		->to('/conf')
		->add( $app->make('/html/view/icon')->icon('cog') )
		->add( HCM::__('Settings') )
		;
	$return['conf'] = array($link, 100);

	return $return;
};
