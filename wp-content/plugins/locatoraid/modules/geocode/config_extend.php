<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$before['/locations/edit/controller'] = function( $app, $e )
{
	if( $e['latitude'] && $e['longitude'] ){
		return;
	}

// add javascript
	$app->make('/app/enqueuer')
		->register_script( 'lc-geocode', 'modules/geocode/assets/js/geocode.js' )
		->enqueue_script( 'lc-geocode' )
		;
	
};

$after['/locations/edit/view'] = function( $app, $return, $location )
{
	if( $location['latitude'] && $location['longitude'] ){
		return $return;
	}
	$geocode_view = $app->make('/geocode/view')
		->render( $location )
		;

	$out = $app->make('/html/view/list')
		->set_gutter( 2 )
		;

	$out
		->add( 'geocode', $geocode_view )
		->add( 'content', $return )
		;

	return $out;
};
