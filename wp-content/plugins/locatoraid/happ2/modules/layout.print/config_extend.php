<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/layout/view/body->top_header'] = function( $app, $return )
{
	$is_print_view = $app->make('/print/controller')->is_print_view();
	if( $is_print_view ){
		$return = $app->make('/html/view/container');
	}
	return $return;
};