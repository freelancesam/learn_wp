<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/html/view/top-menu'] = function( $app, $return )
{
	$link = $app->make('/html/view/link')
		->to('/publish.wordpress')
		->add( $app->make('/html/view/icon')->icon('edit') )
		->add( 'Publish' )
		;
	$return['publish'] = $link;

	return $return;
};
