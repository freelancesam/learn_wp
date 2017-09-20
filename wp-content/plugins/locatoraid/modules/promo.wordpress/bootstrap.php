<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Promo_Wordpress_Bootstrap_LC_HC_MVC extends _HC_MVC
{
	public function run()
	{
		add_action( 'admin_notices', array($this, 'notices') );
	}

	public function notices()
	{
		$is_me = $this->make('/app/lib')
			->isme()
			;

		if( ! $is_me ){
			return;
		}

		$out = $this->make('/html/view/element')->tag('div')
			->add_attr('class', 'notice' )
			// ->add_attr('class', 'notice-success' )
			->add_attr('class', 'hc-p2')
			;

		ob_start();
		require( dirname(__FILE__) . '/view.html.php' );
		$view = ob_get_contents();
		ob_end_clean();

		$out->add( $view );

		echo $out;
	}
}