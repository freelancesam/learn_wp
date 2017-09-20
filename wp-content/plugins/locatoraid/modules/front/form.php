<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Form_LC_HC_MVC extends _HC_Form
{
	public function conf()
	{
		$return = array();

		$return['search'] = $this->app->make('/form/view/text')
			->add_attr('placeholder', HCM::__('Address or Zip Code'))
			->add_attr('class', 'hc-block')
			;

		$return = $this->app
			->after( $this, $return )
			;

		return $return;
	}
}