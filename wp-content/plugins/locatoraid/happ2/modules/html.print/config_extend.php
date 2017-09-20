<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$before['/html/view/link'] = function( $app, $src )
{
	$is_print_view = $app->make('/print/controller')->is_print_view();
	if( $is_print_view ){
		if( $src->is_always_show() ){
			$src->set_readonly();
		}
		else {
			$src->hide();
		}
	}
};

$before['/html/view/select-links'] = function( $app, $src )
{
	$is_print_view = $app->make('/print/controller')->is_print_view();
	if( $is_print_view ){
		$src->set_readonly();
	}
};

$before['/html/view/date-nav'] = function( $app, $src )
{
	$is_print_view = $app->make('/print/controller')->is_print_view();
	if( $is_print_view ){
		$src->set_readonly();
	}
};