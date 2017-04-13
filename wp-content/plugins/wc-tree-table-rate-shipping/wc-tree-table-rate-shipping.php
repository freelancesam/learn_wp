<?php
/**
 * Plugin Name: WooCommerce Tree Table Rate Shipping
 * Description: Ultimate shipping plugin for WooCommerce
 * Version: 1.13.0
 * Author: tablerateshipping.com
 * Plugin URI: http://tablerateshipping.com
 * Author URI: http://tablerateshipping.com
 */

require_once(dirname(__FILE__).'/src/PrerequisitesChecker.php');
if (TrsPrerequisitesChecker::check()) {
    define('TRS_ENTRY_FILE', __FILE__);
    include(dirname(__FILE__).'/bootstrap.php');
}