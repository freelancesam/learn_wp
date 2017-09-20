<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Conf_View_Layout_HC_MVC extends _HC_MVC
{
	public function tabs()
	{
		$return = array();

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}

	public function menubar( $current_tab = NULL )
	{
		$return = array();
		$tabs = $this->tabs();

		reset( $tabs );
		foreach( $tabs as $tab_key => $tab ){
			if( is_array($tab) ){
				$tab_link = array_shift( $tab );
				$tab_label = array_shift( $tab );
				if( substr($tab_link, 0, 1) != '/' ){
					$tab_link = '/' . $tab_link;
				}
			}
			else {
				$tab_link = '/conf/' . $tab_key;
				$tab_label = $tab;
			}

			$link = $this->app->make('/html/view/link')
				->to( $tab_link )
				->add( $tab_label )
				;

			if( trim($tab_link, '/') == $current_tab ){
				$link
					->add_attr('class', 'hc-theme-btn-submit')
					->add_attr('class', 'hc-theme-btn-primary')
					;
			}

			$return[ $tab_key ] = $link;
		}

		return $return;
	}

	public function render( $content, $current_tab = NULL )
	{
		$header = HCM::__('Settings');
		$menubar = $this
			->menubar( $current_tab )
			;

		$out = $this->make('/layout/view/content-header-menubar')
			->set_content( $content )
			->set_header( $header )
			->set_menubar( $menubar )
			;

		return $out;
	}
}