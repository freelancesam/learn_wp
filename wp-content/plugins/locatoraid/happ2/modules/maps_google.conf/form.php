<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Maps_Google_Conf_Form_HC_MVC extends _HC_Form
{
	public function conf()
	{
		$app_settings = $this->app->make('/app/settings');
		$api_key = $app_settings->get('maps_google:api_key');

		$api_key_help = $this->make('/html/view/element')->tag('div')
			->add(
				$this->make('/html/view/list-div')
					->add(
						HCM::__('Usage of the Google Maps APIs now requires an API key which you can get from the Google Maps developers website.')
						)
					->add(
						'<a href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend&keyType=CLIENT_SIDE&reusekey=true" target="_blank">' .
						HCM::__('Get Google Maps API key') .
						'</a>'
						)
				)
			->add_attr('class', 'hc-p3')
			->add_attr('class', 'hc-mb2')
			->add_attr('class', 'hc-border')
			->add_attr('class', 'hc-border-olive')
			->add_attr('class', 'hc-rounded')
			->add_attr('class', 'hc-fs4')
			;


		$label = HCM::__('Google Maps Browser API Key') . '<br>' . HCM::__('Or enter "none" to skip it');
		if( ! strlen($api_key) ){
			$label = $api_key_help . $label;
		}

		$return = array(
			'maps_google:api_key'	=>
				$this->app->make('/form/view/text')
					->set_label( $label )
					->add_attr('size', 48)
					->add_validator( $this->make('/validate/required') )
				,
			);

	// if no api key is set then don't show other inputs
		if( strlen($api_key) ){
			$return['maps_google:scrollwheel'] =
				$this->make('/form/view/checkbox')
					->set_label( HCM::__('Enable Scroll Wheel Zoom') )
				;

			$style_help = 'Get your map style code from websites like <a target="_blank" href="http://www.snazzymaps.com/">Snazzy Maps</a> or <a target="_blank" href="http://www.mapstylr.com/">Map Stylr</a> and paste it in the textarea below.';
			$label = $this->make('/html/view/list-div')
				->add( HCM::__('Custom Map Style') )
				->add( $style_help )
				;

			$return['maps_google:map_style'] =
				$this->make('input-map-style')
					->set_label( $label )
				;
		}

		$return = $this->app
			->after( $this, $return )
			;

		return $return;
	}
}