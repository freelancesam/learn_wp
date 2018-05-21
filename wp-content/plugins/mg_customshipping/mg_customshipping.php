<?php

/**
 * Plugin Name: MG Shipping
 * Description: Custom Shipping Method for WooCommerce
 * Version: 1.0.0
 * Author: Kemly<kemly.vn@gmail.com>
 */


if (!defined('WPINC')) {

    die;
}

/*
 * Check if WooCommerce is active
 */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    function mg_shipping_method() {
        if (!class_exists('MG_Shipping_Method')) {

            class MG_Shipping_Method extends WC_Shipping_Method {

                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct() {
                    $this->id = 'mg';
                    $this->method_title = __('MG Shipping', 'mg');
                    $this->method_description = __('Custom Shipping Method for MG', 'mg');

                    // Availability & Countries
                    //$this->availability = 'including';

                    $this->init();

                    $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset($this->settings['title']) ? $this->settings['title'] : __('MG Shipping', 'mg');
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init() {
                    // Load the settings API
                    $this->init_form_fields();
                    $this->init_settings();

                    // Save settings in admin if you have any defined
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                /**
                 * Define settings field for this shipping
                 * @return void 
                 */
                function init_form_fields() {

                    $this->form_fields = array(
                        'enabled' => array(
                            'title' => __('Enable', 'mg'),
                            'type' => 'checkbox',
                            'description' => __('Enable this shipping.', 'mg'),
                            'default' => 'yes'
                        ),
                        'title' => array(
                            'title' => __('Title', 'mg'),
                            'type' => 'text',
                            'description' => __('Title to be display on site', 'mg'),
                            'default' => __('MG Shipping', 'mg')
                        ),
                    );
                }

                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping($package = array()) {

                    $weight = 0;
                    $cost = 0;
                    $country = $package["destination"]["country"];
                    $state = $package["destination"]["state"];
                
                    global $woocommerce;
                    $title = '';
                    $cart_total = $woocommerce->cart->cart_contents_total;
                    if ($country != 'US' && $country !='UM' && $country !='VI') {
                        $cost = 25;
                        $title = 'Add $25 if the order is International';
                    } elseif ($state != 'CA') {
                        $cost = $cart_total * 0.08;
                        $title = 'Add 8% tax if the order is based out of California';
                    } elseif ($cart_total < 125) {
                        $cost = 7.95;
                        $title = 'Add $7.95 if the order is under $125';
                    } elseif ($cart_total >= 125) {
                        $cost = 0;
                        $title = 'Free Shipping';
                    }

                    $rate = array(
                        'id' => $this->id,
                        'label' => $title,
                        'cost' => $cost
                    );

                    $this->add_rate($rate);
                }

            }

        }
    }

    add_action('woocommerce_shipping_init', 'mg_shipping_method');

    function add_mg_shipping_method($methods) {
        $methods[] = 'MG_Shipping_Method';
        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'add_mg_shipping_method');

//    function my_hide_shipping_when_free_is_available($rates) {
//        $free = array();
//        foreach ($rates as $rate_id => $rate) {
//            if ('free_shipping' === $rate->method_id) {
//                $free[$rate_id] = $rate;
//                break;
//            }
//        }
//        return !empty($free) ? $free : $rates;
//    }
//
//    add_filter('woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 100);
}