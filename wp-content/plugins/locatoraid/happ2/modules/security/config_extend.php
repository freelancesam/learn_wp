<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/html/view/form'] = function( $app, $return )
{
	$security = $app->make('/security/lib');

	$csrf_name = $security->get_csrf_token_name();
	$csrf_value = $security->get_csrf_hash();

	if( strlen($csrf_name) && strlen($csrf_value) ){
		$hidden = $app->make('/form/view/hidden')
			->set_name($csrf_name)
			->set_value($csrf_value)
			;

		$return->add(
			$app->make('/html/view/element')->tag('div')
				->add_attr('style', 'display:none')
				->add( $hidden )
			);
	}

	return $return;
};