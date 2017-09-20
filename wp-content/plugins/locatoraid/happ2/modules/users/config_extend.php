<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/html/view/top-menu'] = function( $app, $return )
{
	$link = $app->make('/html/view/link')
		->to('/users')
		->add( $app->make('/html/view/icon')->icon('user') )
		->add( HCM::__('Users') )
		;
	$return['users'] = array( $link, 90 );

	return $return;
};
