<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Edit_View_Layout_LC_HC_MVC extends _HC_MVC
{
	public function header( $model )
	{
		$return = HCM::__('Edit Location');
		return $return;
	}

	public function menubar( $model )
	{
		$return = array();

	// LIST
		$return['list'] = 
			$this->make('/html/view/link')
				->to('/locations')
				->add( $this->make('/html/view/icon')->icon('arrow-left') )
				->add( HCM::__('Locations') )
			;

		$return = $this->app
			->after( array($this, __FUNCTION__), $return, $model )
			;

		return $return;
	}

	public function render( $content, $model )
	{
		$menubar = $this->menubar( $model);
		$header = $this->header( $model );

		$out = $this->make('/layout/view/content-header-menubar')
			->set_content( $content )
			->set_header( $header )
			->set_menubar( $menubar )
			;

		return $out;
	}
}