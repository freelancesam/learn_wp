<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/app/enqueuer'] = function( $app, $enqueuer )
{
	static $done = FALSE;
	if( $done ){
		return;
	}
	$done = TRUE;

	$enqueuer
		->register_script( 'gmaps', 'happ2/modules/maps_google/assets/js/gmaps.js' )
		;

	$app_settings = $app->make('/app/settings');
	$api_key = $app_settings->get('maps_google:api_key');
	if( $api_key == 'none' ){
		$api_key = '';
	}

	$map_style = $app_settings->get('maps_google:map_style');
	$scrollwheel = $app_settings->get('maps_google:scrollwheel');
	$scrollwheel = $scrollwheel ? TRUE : FALSE;

	$params = array(
		'api_key'		=> $api_key,
		'map_style'		=> $map_style,
		'scrollwheel'	=> $scrollwheel,
		);

	$enqueuer
		->localize_script( 'gmaps', $params )
		;

	$enqueuer
		->enqueue_script( 'gmaps' )
		;
};