<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Setup_Wordpress_Bootstrap_HC_MVC extends _HC_MVC
{
	public function run()
	{
		$slug = $this->make('/http/lib/uri')->slug();
		if( in_array($slug, array('setup')) ){
			return;
		}

		$slug = explode('/', $slug);
		$module = array_shift($slug);
		if( in_array($module, array('setup', 'demo')) ){
			return;
		}

		$is_setup = $this->app->make('/setup/lib')
			->is_setup()
			;

		if( ! $is_setup ){
			$view = $this->make('/http/view/response')
				->set_redirect('setup') 
				;
			echo $view;
			exit;
		}
	}
}