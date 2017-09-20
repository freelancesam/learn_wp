<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/app/enqueuer@get-scripts']		= 'app/enqueuer@after-get-scripts';
$after['/app/enqueuer@get-styles']		= 'app/enqueuer@after-get-styles';

$after['/layout/view/body'] = function( $app, $return )
{
	$is_print_view = $app->make('/print/controller')
		->is_print_view()
		;
	if( ! $is_print_view ){
		return $return;
	}

	$head = $app->make('/wordpress.layout.print/view/head');
	$out = $app->make('/html/view/container');
	$out
		->add('<!DOCTYPE html>' . "\n" )
		->add(
			$app->make('/html/view/element')->tag('html')
				->add_attr('xmlns', 'http://www.w3.org/1999/xhtml')
				->add("\n")
				->add(
					$app->make('/html/view/element')->tag('head')
						->add( $head )
					)
				->add(
					$app->make('/html/view/element')->tag('body')
						->add( $return )
					)
			)
		;

	return $out;
};