<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/conf/model->save'] = function( $app, $return )
{
	$msg = HCM::__('Settings Updated');
	$msgbus = $app->make('/msgbus/lib');
	$msgbus->add('message', $msg);
};
