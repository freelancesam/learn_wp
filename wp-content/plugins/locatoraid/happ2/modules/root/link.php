<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Root_Link_HC_MVC extends _HC_MVC
{
	public function execute( $slug )
	{
		if( is_array($slug) ){
			$slug = array_shift( $slug );
		}
		$slug = trim($slug, '/');

		$return = $this->app
			->after( $this, $slug )
			;

		return $return;
	}
}