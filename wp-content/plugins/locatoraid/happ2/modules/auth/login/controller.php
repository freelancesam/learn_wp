<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Auth_Login_Controller_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$form = $this->app->make('/auth/login/form/login');
	
		$view = $this->make('login/view')
			->run('render', $form)
			;
		$view = $this->make('login/view/layout')
			->run('render', $view)
			;
		$view = $this->make('/layout/view/body')
			->set_content($view)
			;
		return $this->make('/http/view/response')
			->set_view($view)
			;
	}
}