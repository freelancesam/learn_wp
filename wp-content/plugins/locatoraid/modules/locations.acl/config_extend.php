<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/root/link'] = function( $app, $return )
{
	if( ! $return ){
		return $return;
	}

	// check module
	$module = 'locations';
	if( ($module != $return) && (substr($return, 0, strlen($module . '/')) != $module . '/') ){
		return $return;
	}

	// check admin
	$user = $app->make('/auth/model/user')->get();
	if( $user->is_admin() ){
		return $return;
	}

	$return = '';
	return $return;
};