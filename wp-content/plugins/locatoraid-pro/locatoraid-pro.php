<?php
/**
 * @package Locatoraid Pro
 * @author HitCode
 */
/*
Plugin Name: Locatoraid Pro
Plugin URI: http://www.locatoraid.com/
Description: Store locator plugin, Pro version
Author: HitCode
Version: 2.7.5
Author URI: http://www.hitcode.com/
*/
/* 
to create another instance, simply copy this file to locatoraid-pro_2.php or locatoraid-pro_another.php 
or anything else starting with locatoraid-pro_
*/

// uncomment the next line if you have other plugin or theme already loading Google Maps API that causes errors
// define('LPR_NO_LOAD_GOOGLEMAPS', 1);
// or make it reuse from another plugin
// we've encountered 'contact-details-google-maps', 'googleapis', 'gmap_form_api'
// define('LPR_GOOGLE_MAPS_HANDLE', 'gmap_form_api');

include_once( dirname(__FILE__) . '/application/libraries/locatoraid_base.php' );
include_once( dirname(__FILE__) . '/application/libraries/hcWpPremiumPlugin.php' );

if( ! class_exists('Locatoraid_Pro') )
{
register_uninstall_hook( __FILE__, array('Locatoraid_Pro', 'uninstall') );
class Locatoraid_Pro extends Locatoraid_Base
{
	public function __construct( $wpi = '', $full_path = '' )
	{
		if( ! $full_path )
			$full_path = __FILE__;
		parent::__construct( 
			$wpi,
			$full_path
			);
		$target_free = $wpi ? 'locatoraid_' . $wpi . '.php' : 'locatoraid.php';
		$this->deactivate_other( array($target_free) );

		$this->hc_product = 'lctr';
		$this->premium = new hcWpPremiumPlugin(
			$this->app,
			$this->hc_product,
			$this->slug,
			$this->full_path,
			$this->system_type
			);
		$this->premium->my_type = 'wp';
	}

	public function admin_menu( $title = '' )
	{
		$menu_title = get_site_option( $this->app . '_menu_title', ucfirst($this->app) );
		parent::admin_menu( $menu_title );
	}

	static function uninstall( $prefix = 'lctr2' )
	{
		$prefix = 'lctr2';
		Locatoraid_Base::uninstall( $prefix );
		hcWpPremiumPlugin::uninstall( 'locatoraid' );
	}
}
}

if( preg_match("/locatoraid-pro_(.+)\.php/", basename(__FILE__), $ma) )
{
	$lctr = new Locatoraid_Pro( $ma[1], __FILE__);
}
else
{
	$lctr = new Locatoraid_Pro('', __FILE__);
}
?>