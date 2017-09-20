<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$before['/html/view/form'] = function( $app, $form )
{
	if( ! $form ){
		return;
	}

	$session = $app->make('/session/lib');
	$form_errors = $session->flashdata('form_errors');
	$form_values = $session->flashdata('form_values');
	if( ! ($form_errors OR $form_values) ){
		return;
	}

	$slug = $form->slug();

	if( ! (isset($form_errors[$slug]) OR isset($form_values[$slug])) ){
		return;
	}

	if( isset($form_errors[$slug]) ){
		$form->set_errors( $form_errors[$slug] );
	}
	if( isset($form_values[$slug]) ){
		$form->set_values( $form_values[$slug] );
	}
	return;
};