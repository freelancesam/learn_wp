<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/conf/view/layout->tabs'][] = function( $app, $return )
{
	$return['searchlog'] = array( 'searchlog.conf', HCM::__('Search Log') );
	return $return;
};