<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$before['/http/view/response']	= function( $app, $src )
{
	$redirect = $src->redirect();
	if( ! $redirect ){
		return;
	}

	$msgbus = $app->make('/msgbus/lib');
	$session = $app->make('/session/lib');

	$msg = $msgbus->get('message');
	if( $msg ){
		$session->set_flashdata('message', $msg);
	}
	$error = $msgbus->get('error');
	if( $error ){
		$session->set_flashdata('error', $error);
	}
	$warning = $msgbus->get('warning');
	if( $warning ){
		$session->set_flashdata('warning', $warning);
	}
	$debug = $msgbus->get('debug');
	if( $debug ){
		$session->set_flashdata('debug', $debug);
	}
};