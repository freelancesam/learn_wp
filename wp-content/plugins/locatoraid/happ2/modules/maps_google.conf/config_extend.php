<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/conf/view/layout->tabs'] = function( $app, $return )
{
	$return['maps-google'] = array( 'maps-google.conf', HCM::__('Google Maps') );
	return $return;
};