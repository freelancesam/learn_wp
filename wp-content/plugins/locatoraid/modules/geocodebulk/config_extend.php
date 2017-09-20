<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/html/view/top-menu'] = function( $app, $return )
{
	$is_setup = $app->make('/setup/lib')
		->is_setup()
		;
	if( ! $is_setup ){
		return $return;
	}

	$api = $app->make('/http/lib/api')
		->request('/api/locations')
		;
	$api
		->add_param('custom', 'notgeocodedcount')
		;

	$count = $api
		->get()
		->response()
		;
// echo "COUNT = '$count'<br>";
// exit;
	if( ! $count ){
		return $return;
	}

	$label = HCM::__('Geocode');
	$label .= ' (' . $count . ')';

	$link = $app->make('/html/view/link')
		->to('/geocodebulk')
		->add( $app->make('/html/view/icon')->icon('exclamation') )
		->add( $label )
		;

	$return['geocodebulk'] = $link;

	return $return;
};
