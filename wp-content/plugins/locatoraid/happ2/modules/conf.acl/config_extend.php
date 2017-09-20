<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/root/link'] = function( $app, $return )
{
return $return;
	if( ! $return ){
		return $return;
	}

	// check module
	// also check if it ends with .conf
	$module = 'conf';

	$is_me = FALSE;

	if( ($module == $return) OR (substr($return, 0, strlen($module . '/')) == $module . '/') ){
		$is_me = TRUE;
	}
	else {
		$dotmodule = '.' . $module;
		if( substr($return, -strlen($dotmodule)) == $dotmodule ){
			$is_me = TRUE;
		}
		if( strpos($return, $dotmodule . '/') !== FALSE ){
			$is_me = TRUE;
		}
	}

	if( ! $is_me ){
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