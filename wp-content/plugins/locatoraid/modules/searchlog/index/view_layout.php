<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Searchlog_Index_View_Layout_LC_HC_MVC
{
	public function header()
	{
		$return = HCM::__('Search Log');
		return $return;
	}

	public function menubar()
	{
		$return = array();
		return $return;
	}

	public function render( $content )
	{
		$header = $this->header();
		$menubar = $this->menubar();

		$out = $this->app->make('/layout/view/content-header-menubar')
			->set_content( $content )
			->set_header( $header )
			->set_menubar( $menubar )
			;

		return $out;
	}
}