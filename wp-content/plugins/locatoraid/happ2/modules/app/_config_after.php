<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/app/enqueuer'][] = function( $app, $enqueuer )
{
	$enqueuer
		->register_script( 'hc', 'happ2/assets/js/hc2.js' )

		->register_style( 'hc-start', 'happ2/assets/css/hc-start.css' )
		->register_style( 'hc', 'happ2/assets/css/hc.css' )
		->register_style( 'font', 'https://fonts.googleapis.com/css?family=PT+Sans' )
		;

// enqueue
	$enqueuer
		->enqueue_script( 'hc' )
		;
};