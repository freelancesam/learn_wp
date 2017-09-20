<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Form_LC_HC_MVC extends _HC_MVC
{
	public function conf()
	{
		$app_settings = $this->app->make('/app/settings');
		$p = $this->app->make('/locations/presenter');
		$labels = $p->run('fields-labels');

		$return = array(
			'name'	=>
				$this->make('/form/view/text')
					->add_attr('class', 'hc-block')
					->add_attr('class', 'hc-fs5')
					->add_attr('style', 'height: 2em;')
					->add_attr('placeholder', HCM::__('Name'))

					->add_validator( $this->make('/validate/required') )
				,
			'street1'	=>
				$this->make('/form/view/text')
					->set_label( HCM::__('Street Address 1') )
					->add_attr('class', 'hc-block')
				,
			'street2'	=>
				$this->make('/form/view/text')
					->set_label( HCM::__('Street Address 2') )
					->add_attr('class', 'hc-block')
				,
			'city'	=>
				$this->make('/form/view/text')
					->set_label( HCM::__('City') )
					->add_attr('size', 32)
				,
			'state'	=>
				$this->make('/form/view/text')
					->set_label( HCM::__('State') )
					->add_attr('size', 16)
				,
			'zip'	=>
				$this->make('/form/view/text')
					->set_label( HCM::__('Zip Code') )
					->add_attr('size', 16)
				,
			'country'	=>
				$this->make('/form/view/text')
					->set_label( HCM::__('Country') )
					->add_attr('size', 32)
				,
			'phone'	=>
				$this->make('/form/view/text')
					->set_label( (isset($labels['phone']) && strlen($labels['phone'])) ? $labels['phone'] : HCM::__('Phone') )
					->add_attr('class', 'hc-block')
				,
			'website'	=>
				$this->make('/form/view/text')
					->set_label( (isset($labels['website']) && strlen($labels['website'])) ? $labels['website'] : HCM::__('Website') )
					->add_attr('class', 'hc-block')
				,
			);

		$return = $this->app
			->after( $this, $return )
			;

	// remove unneeded and adjust labels if needed
		$always_show = array('name', 'street1', 'street2', 'city', 'state', 'zip', 'country');
		$input_names = array_keys( $return );
		foreach( $input_names as $k ){
			if( ! in_array($k, $always_show) ){
				$this_field_pname = 'fields:' . $k  . ':use';
				$this_field_conf = $app_settings->get($this_field_pname);
				if( ! $this_field_conf ){
					unset( $return[$k] );
					continue;
				}

				$this_field_pname = 'fields:' . $k  . ':label';
				$this_label = $app_settings->get($this_field_pname);
				if( strlen($this_label) ){
					$return[$k]
						->set_label( $this_label )
						;
				}
			}
		}

		return $return;
	}
}