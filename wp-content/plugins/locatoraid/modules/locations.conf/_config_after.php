<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/app.conf/form'][] = function( $app, $return )
{
	$return['locations_address:format'] = array(
		'input'	=> $app->make('/form/textarea')
			->set_rows(4)
			,
		'label'	=> HCM::__('Address Format'),
		'help'	=> $app->make('/html/list')
			->set_gutter(1)
			->add( HCM::__('Default Setting') )
			->add( 
				nl2br(
					'{STREET}
					{CITY} {STATE} {ZIP}
					{COUNTRY}'
					)
				),
		'validators' => array(
			$app->make('/validate/required')
			)
		);
	return $return;
};
