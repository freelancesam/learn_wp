<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/layout/top-menu'][] = function( $app, $return )
{
	$label = 'Locatoraid Pro';

	$link = $app->make('/html/ahref')
		->to( 'http://www.locatoraid.com/order/' )
		->set_outside( TRUE )
		->add( $app->make('/html/icon')->icon('star') )
		->add( $label )
		->add_attr( 'target', '_blank' )
		;
	$return['promo'] = array( $link, 1000 );

	return $return;
};
