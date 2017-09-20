<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$before['/locations/model@fetch-many'] = 'locations/model@before-fetch-many';

$after['/locations/presenter->present_front'] = function( $app, $return )
{
	if( isset($return['priority']) && (! $return['priority']) ){
		unset($return['priority']);
	}
	return $return;
};

$after['/locations/presenter->fields'] = function( $app, $return )
{
	$return['priority'] = HCM::__('Priority');
	return $return;
};

$after['/locations/form'] = function( $app, $return )
{
	$p = $app->make('/priority/presenter');
	$options = $p->run('present-options');

	$return['priority'] = 
		$app->make('/form/view/radio')
			->set_label( HCM::__('Priority') )
			->set_options( $options )
			->set_inline()
		;
	return $return;
};

$after['/locations/index/view->header'] = function( $app, $return )
{
	$app_settings = $app->make('/app/settings');
	$this_field_pname = 'fields:' . 'priority'  . ':use';
	$this_field_conf = $app_settings->get($this_field_pname);
	if( ! $this_field_conf ){
		return $return;
	}

	$return['priority'] = HCM::__('Priority');
	return $return;
};

$after['/locations/index/view->row'] = function( $app, $return, $e )
{
	$app_settings = $app->make('/app/settings');
	$this_field_pname = 'fields:' . 'priority'  . ':use';
	$this_field_conf = $app_settings->get($this_field_pname);
	if( ! $this_field_conf ){
		return $return;
	}

	$p = $app->make('/priority/presenter');
	$options = $p->run('present-options');

	if( isset($options[$e['priority']]) ){
		$this_view = $options[$e['priority']];
	}
	else {
		$this_view = $options[1];
	}

	$return['priority'] = $this_view;
	return $return;
};