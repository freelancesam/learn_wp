<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/locations/commands/create'][] = function( $app )
{
	$msg_key = 'locations-create';
	$msgbus = $app->make('/msgbus/lib');

	$msg = HCM::__('Location Added');
	$msgbus->add('message', $msg, $msg_key, TRUE);
};

$config['after']['/locations/commands/update'][] = function( $app )
{
	$msg_key = 'locations-update';
	$msgbus = $app->make('/msgbus/lib');

	$msg = HCM::__('Location Updated');
	$msgbus->add('message', $msg, $msg_key, TRUE);
};

$config['after']['/locations/commands/delete'][] = function( $app )
{
	$msg_key = 'locations-delete';
	$msgbus = $app->make('/msgbus/lib');

	$msg = HCM::__('Location Deleted');
	$msgbus->add('message', $msg, $msg_key, TRUE);
};
