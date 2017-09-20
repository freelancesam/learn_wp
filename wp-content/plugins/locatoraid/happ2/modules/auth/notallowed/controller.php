<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Auth_Notallowed_Controller_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$view = $this->make('view/notallowed');
		return $this->make('/http/view/response')
			->set_view($view)
			;
	}
}