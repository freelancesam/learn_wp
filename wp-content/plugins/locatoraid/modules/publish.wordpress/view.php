<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Publish_Wordpress_View_LC_HC_MVC
{
	public function render()
	{
		ob_start();
		require( dirname(__FILE__) . '/view.html.php' );
		$out = ob_get_contents();
		ob_end_clean();

		$pageIds = hc2_wp_get_id_by_shortcode('locatoraid');
		foreach( $pageIds as $pid ){
			$link = get_permalink( $pid );
			$label = get_the_title( $pid );
			$page = $this->app->make('/html/ahref')
				->to( $link )
				->add_attr('target', '_blank')
				->add( $label )
				;
			$pages[] = $page;
		}

		$pagesView = $this->app->make('/html/list')
			->set_gutter(2)
			;
		$pagesView
			->add( $this->app->make('/html/element')->tag('h2')->add(HCM::__('Pages with shortcode')) )
			;

		$addNewLink = $this->app->make('/html/ahref')
			->to( admin_url('post-new.php') )
			->add( HCM::__('Add New') )
			->add_attr('class', 'hc-theme-btn-submit')
			->add_attr('class', 'hc-theme-btn-secondary')
			->add_attr('class', 'hc-xs-block')
			;

		if( $pages ){
			foreach( $pages as $p ){
				$pagesView->add( $p );
			}
		}
		else {
			$pagesView
				->add( HCM::__('None') )
				;
		}

		$pagesView->add( $addNewLink );

		$out = $this->app->make('/html/grid')
			->add( $out, 8, 12 )
			->add( $pagesView, 4, 12 )
			;

		return $out;
	}
}