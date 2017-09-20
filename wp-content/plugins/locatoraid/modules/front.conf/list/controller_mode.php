<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_List_Controller_Mode_LC_HC_MVC extends _HC_Form
{
	public function execute()
	{
		$args = $this->app->make('/app/lib/args')->parse( func_get_args() );
		$to = $args->get('to');

		$app_settings = $this->app->make('/app/settings');

		if( $to == 'reset' ){
			$this_field_pname = 'front_list:template';
			$new_value = '';
		}
		else {
			$this_field_pname = 'front_list:advanced';
			$new_value = ($to == 'advanced') ? 1 : 0;
		}

		$this_field_conf = $app_settings->set( $this_field_pname, $new_value );

		$redirect_to = $this->make('/html/view/link')
			->to('/front.conf/list')
			->href()
			;

		return $this->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}
}