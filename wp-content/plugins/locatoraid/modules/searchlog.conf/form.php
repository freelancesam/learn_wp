<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Searchlog_Conf_Form_HC_MVC
{
	public function inputs()
	{
		$return = array();

		$options = array(
			7*24*60*60	=> HCM::__('1 Week'),
			2*7*24*60*60	=> HCM::__('2 Weeks'),
			4*7*24*60*60	=> HCM::__('4 Weeks'),
			8*7*24*60*60	=> HCM::__('8 Weeks'),
			);

		$return['searchlog:period'] = array(
			'input'	=> $this->app->make('/form/select')
				->set_options( $options )
				,
			'label'	=> HCM::__('Log Searches'),
			);

		return $return;
	}
}