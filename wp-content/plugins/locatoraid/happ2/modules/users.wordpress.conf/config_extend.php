<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/conf/view/layout->tabs'] = function( $app, $return )
{
	$return['wordpress-users'] = array( 'users.wordpress.conf', HCM::__('Users') );
	return $return;
};