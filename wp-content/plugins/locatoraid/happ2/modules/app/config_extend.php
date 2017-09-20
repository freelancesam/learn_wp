<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/app/enqueuer'] = function( $app, $enqueuer )
{
	$enqueuer
		->register_script( 'hc', 'happ2/assets/js/hc2.js' )
		;

	if( defined('NTS_DEVELOPMENT2') ){
		$enqueuer
			->register_style( 'reset',		'happ2/assets/css/hc-1-reset.css' )
			->register_style( 'utilities',	'happ2/assets/css/hc-2-utilities.css' )
			->register_style( 'basstheme',	'happ2/assets/css/hc-3-bass-theme.css' )
			->register_style( 'bass',		'happ2/assets/css/hc-3-bass.css' )
			->register_style( 'style',		'happ2/assets/css/hc-4-style.css' )
			->register_style( 'form',		'happ2/assets/css/hc-5-form.css' )
			->register_style( 'grid',		'happ2/assets/css/hc-6-grid.css' )
			->register_style( 'javascript',	'happ2/assets/css/hc-7-javascript.css' )
			->register_style( 'schecal',	'happ2/assets/css/hc-9-schecal.css' )
			->register_style( 'animate',	'happ2/assets/css/hc-10-animate.css' )
			;
	}
	else {
		$enqueuer
			->register_style( 'hc', 'happ2/assets/css/hc.css' )
			;
	}

	$enqueuer
		->register_style( 'font', 'https://fonts.googleapis.com/css?family=PT+Sans' )
		;

// enqueue
	$enqueuer
		->enqueue_script( 'hc' )
		;

	if( defined('NTS_DEVELOPMENT2') ){
		$enqueuer
			->enqueue_style( 'reset' )
			->enqueue_style( 'utilities' )
			->enqueue_style( 'basstheme' )
			->enqueue_style( 'bass' )
			->enqueue_style( 'style' )
			->enqueue_style( 'form' )
			->enqueue_style( 'grid' )
			->enqueue_style( 'javascript' )
			// ->enqueue_style( 'schecal' )
			->enqueue_style( 'animate' )
			;
	}
	else {
		$enqueuer
			->enqueue_style( 'hc' )
			;
	}

	$enqueuer
		->enqueue_style( 'font' )
		;
};