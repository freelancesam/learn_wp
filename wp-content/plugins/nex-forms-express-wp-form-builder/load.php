<?php
if ( ! defined( 'ABSPATH' ) ) exit;
error_reporting(0);
ini_set('display_errors', 0);
include_once( 'nf-admin/class.dashboard.php');
include_once( 'nf-admin/class.install.php');
include_once( 'nf-admin/class.db.php');
include_once( 'nf-admin/class.functions.php');
include_once( 'nf-admin/class.export.php');
include_once( 'nf-admin/class.preferences.php');
//include_once( 'nf-admin/class.builder.php');
include_once( 'nf-admin/class.icons.php');
include_once( 'nf-admin/class.googlefonts.php');
/*error_reporting(0);
ini_set('display_errors', 0);*/
function enqueue_nex_forms_admin_scripts($hook) {
    
	
	 if ( 'toplevel_page_nex-forms-dashboard' != $hook ) {
			return;
		}
	
	wp_enqueue_script('jquery');
	wp_enqueue_style('jquery-ui');
	
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-widget');
	wp_enqueue_script('jquery-ui-mouse');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-droppable');
	wp_enqueue_script('jquery-ui-resizable');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-ui-autocomplete');
	wp_enqueue_script('jquery-form');

	/* Custom Includes */
	wp_enqueue_script('nex_forms-materialize.min',plugins_url('/assets/materialize.min.js',__FILE__));
	wp_enqueue_script('formilise-js-init',plugins_url('/nf-admin/js/initialize.js',__FILE__));
	wp_enqueue_script('nex-forms-stats',plugins_url( 'nf-admin/js/dashboard.js',__FILE__));
	//wp_enqueue_script('nex_forms-iziModal.min',plugins_url('/assets/iziModal.min.js',__FILE__));
	
	
}

function enqueue_nex_forms_admin_styles($hook) {
	/* CSS */
	
	 if ( 'toplevel_page_nex-forms-dashboard' != $hook ) {
			return;
		}

	
	wp_enqueue_style('nex_forms-materialize.min',plugins_url('/assets/materialize.min.css',__FILE__));
	wp_enqueue_style('nex_forms-bootstrap.min',plugins_url('/nf-admin/css/bootstrap.min.css',__FILE__));
	wp_enqueue_style('nex_forms-dashboard',plugins_url('/nf-admin/css/dashboard.css',__FILE__));
	wp_enqueue_style('nex_forms-font-awesome.min',plugins_url('/assets/font-awesome.min.css',__FILE__));
	wp_enqueue_style('nex_forms-material-icons','https://fonts.googleapis.com/icon?family=Material+Icons');
}
add_action( 'admin_enqueue_scripts', 'enqueue_nex_forms_admin_scripts' );
add_action( 'admin_enqueue_scripts', 'enqueue_nex_forms_admin_styles' );

?>