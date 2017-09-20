<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/locations/commands/create'] = function( $app, $return )
{
	$msg_key = 'locations-create';
	$msgbus = $app->make('/msgbus/lib');

	if( $return && isset($return['errors']) ){
		$msg = $return['errors'];
		// $msgbus->add('error', $msg, $msg_key);
	}
	else {
		$msg = HCM::__('Location Added');
		$msgbus->add('message', $msg, $msg_key, TRUE);
	}

	return $return;
};

$after['/locations/commands/update'] = function( $app, $return )
{
	$msg_key = 'locations-update';
	$msgbus = $app->make('/msgbus/lib');

	if( $return && isset($return['errors']) ){
		$msg = $return['errors'];
		// $msgbus->add('error', $msg, $msg_key);
	}
	else {
		$msg = HCM::__('Location Updated');
		$msgbus->add('message', $msg, $msg_key, TRUE);
	}

	return $return;
};

$after['/locations/commands/delete'] = function( $app, $return )
{
	$msg_key = 'locations-delete';
	$msgbus = $app->make('/msgbus/lib');

	if( $return && isset($return['errors']) ){
		$msg = $return['errors'];
		$msgbus->add('error', $msg, $msg_key);
	}
	else {
		$msg = HCM::__('Location Deleted');
		$msgbus->add('message', $msg, $msg_key, TRUE);
	}

	return $return;
};
