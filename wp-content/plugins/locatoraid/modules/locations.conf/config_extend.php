<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/app.conf/form'] = function( $app, $return )
{
	$return['locations_address:format'] = 
		$app->make('/form/view/textarea')
			->set_label( HCM::__('Address Format') )
			->add_attr('rows', 4)
			->add_attr('cols', 32)
			->add_validator( $app->make('/validate/required') )

			->add(
				$app->make('/html/view/element')->tag('div')
					->add_attr('class', 'hc-ml3')
					->add( 
						$app->make('/html/view/label-input')
							->set_label( HCM::__('Default Setting') )
							->set_content( 
								nl2br(
									'{STREET}
									{CITY} {STATE} {ZIP}
									{COUNTRY}'
									)
								)
					)
				)
		;
	return $return;
};