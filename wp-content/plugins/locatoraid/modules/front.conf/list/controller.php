<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_List_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$view = $this->app->make('/front.conf/list/view')
			->render()
			;
		$view = $this->app->make('/conf/view/layout')
			->render( $view, 'front.conf/list' )
			;
		$view = $this->make('/layout/view/body')
			->set_content($view)
			;
		return $this->make('/http/view/response')
			->set_view($view)
			;
	}
}