<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class App_Bootstrap_HC_MVC extends _HC_MVC
{
	public function run()
	{
		$is_me = $this->make('/app/lib')
			->isme()
			;
		if( $is_me ){
			$enqueuer = $this->app->make('/app/enqueuer');
		}

		return $this;
	}
}