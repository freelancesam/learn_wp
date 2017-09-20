<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/html/view/icon'] = function( $app, $return, $src )
{
	$convert = array(
		'networking'	=> 'networking',
		'star-o'	=> 'star-empty',
		// 'plus'		=> 'plus-alt', // simple plus appears off center vertically
		'cog'		=> 'admin-generic',
		'user'		=> 'admin-users',
		'group'		=> 'groups',
		'times'		=> 'dismiss',
		'check'		=> 'yes',
		'status'	=> 'post-status',
		'list'		=> 'editor-ul',
		'history'	=> 'book',
		'exclamation'	=> 'warning',
		'printer'		=> 'media-text',
		'home'			=> 'admin-home',
		'star'			=> 'star-filled',

		'purchase'		=> 'products',
		'sale'			=> 'cart',
		'inventory'		=> 'admin-page',
		'copy'			=> 'admin-page',
		'chart'			=> 'chart-bar',
		'message'		=> 'email',
		'holidays'		=> 'palmtree',
		'connection'	=> 'admin-links',
		'view'			=> 'visibility',
	);

	$return = isset($convert[$return]) ? $convert[$return] : $return;

	if( $return && strlen($return) ){
		if( substr($return, 0, 1) == '&' ){
			$return = $app->make('/html/view/element')->tag('span')
				->add( $return )
				->add_attr('class', 'hc-mr1')
				->add_attr('class', 'hc-ml1')
				->add_attr('class', 'hc-char')
				;
		}
		else {
			$return = $app->make('/html/view/element')->tag('i')
				->add_attr('class', 'dashicons')
				->add_attr('class', 'dashicons-' . $return)
				->add_attr('class', 'hc-dashicons')
				;
		}
	}

	$attr = $src->attr();
	foreach( $attr as $k => $v ){
		$return->add_attr( $k, $v );
	}

	return $return;
};
