<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$before['/locations/model@fetch-many'] = 'locations/model@before-fetch-many';
$before['/locations/model@count'] = 'locations/model@before-count';

$before['/locations/edit/controller'] = function( $app, $e )
{
	if( ! ($e['latitude'] && $e['longitude']) ){
		return;
	}

	if( ! ( ($e['latitude'] == -1) && ($e['longitude'] == -1) ) ){
	// add javascript
		$app->make('/app/enqueuer')
			->register_script( 'lc-locations-coordinates', 'modules/locations.coordinates/assets/js/map.js' )
			->enqueue_script( 'lc-locations-coordinates' )
			;
	}
};

$after['/locations/edit/view'] = function( $app, $return, $location )
{
	if( ! ($location['latitude'] && $location['longitude']) ){
		return $return;
	}

	$edit = 0;
	$coordinates_view = $app->make('/locations.coordinates/index/view')
		->run('render', $location, $edit)
		;

	$out = $app->make('/html/view/list')
		->set_gutter( 2 )
		;

	$out
		->add( 'coordinates', $coordinates_view )
		->add( 'content', $return )
		;
	return $out;
};

$after['/locations/edit/view/layout->menubar'] = function( $app, $return, $e )
{
// coordinates
	$return['coordinates'] = 
		$app->make('/html/view/link')
			->to('/locations.coordinates', array('id' => $e['id']))
			->add( $app->make('/html/view/icon')->icon('location') )
			->add( HCM::__('Edit Coordinates') )
		;

	return $return;
};

$after['/locations/index/view->header'] = function( $app, $return )
{
	$return['coordinates'] = HCM::__('Coordinates');
	return $return;
};

$after['/locations/index/view->row'] = function( $app, $return, $e )
{
	$p = $app->make('/locations.coordinates/presenter')
		->set_data( $e )
		;

	$coordinates_view = $p->run('present-coordinates');
	$geocoding_status = $p->run('geocoding-status');
	if( ! $geocoding_status ){
		$coordinates_view = $app->make('/html/view/link')
			->to( '/geocode', array('id' => $e['id']) )
			->add( $coordinates_view )
			;
	}
	else {
		$coordinates_view = $app->make('/html/view/link')
			->to('/locations.coordinates', array('id' => $e['id']))
			->add( $coordinates_view )
			;
	}

	$return['coordinates'] = $coordinates_view;
	return $return;
};