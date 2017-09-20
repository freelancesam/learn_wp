<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/html/view/top-menu'] = function( $app, $return )
{
	$label = 'Locatoraid Pro';

	$link = $app->make('/html/view/link')
		->to('http://www.locatoraid.com/order/')
		->add( $app->make('/html/view/icon')->icon('star') )
		->add( $label )
		->add_attr( 'target', '_blank' )
		;
	$return['promo'] = array( $link, 200 );

	return $return;
};
