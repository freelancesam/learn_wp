<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Maps_Google_Conf_Controller_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$view = $this->app->make('/maps-google.conf/view')
			->render()
			;
		$view = $this->app->make('/conf/view/layout')
			->render( $view, 'maps-google.conf' )
			;
		$view = $this->make('/layout/view/body')
			->set_content($view)
			;
		return $this->make('/http/view/response')
			->set_view($view)
			;
	}
}