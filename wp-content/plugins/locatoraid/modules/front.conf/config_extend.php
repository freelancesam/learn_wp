<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/app.conf/form'] = function( $app, $return )
{
	$return['core:measure'] = 
		$app->make('/form/view/radio')
			->set_label( HCM::__('Measure Units') )
			->set_options( 
				array(
					'mi'	=> HCM::__('Miles'),
					'km'	=> HCM::__('Km'),
					)
			)
			->set_inline( TRUE )
		;
	return $return;
};

$after['/conf/view/layout->tabs'] = function( $app, $return )
{
	$return['fields'] = array( 'front.conf/fields', HCM::__('Locations Details') );
	$return['front-map'] = array( 'front.conf/map', HCM::__('Details On Map') );
	$return['front-list'] = array( 'front.conf/list', HCM::__('Details In List') );
	return $return;
};

$after['/app/settings->get'] = function( $app, $return, $pname )
{
	switch( $pname ){
		case 'front_list:template':
			$app_settings = $app->make('/app/settings');
			$advanced = $app_settings->get('front_list:advanced');
			if( (! $advanced) OR (! strlen($return)) ){
				$return = $app->make('/front/view/list/template')
					->render()
					;
			}
			break;

		case 'front_map:template':
			$app_settings = $app->make('/app/settings');
			$advanced = $app_settings->get('front_map:advanced');
			if( (! $advanced) OR (! strlen($return)) ){
				$return = $app->make('/front/view/map/template')
					->render()
					;
			}
			break;
	}

	return $return;
};