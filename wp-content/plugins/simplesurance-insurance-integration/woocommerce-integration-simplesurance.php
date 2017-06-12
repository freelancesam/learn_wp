<?php

/**
 * Plugin Name: Simplesurance plugin
 * Plugin URI:
 * Description: Simplesurance plugin for woocommerce
 * Version: 1.0.1
 * Developer: KRITEK, s.r.o.
 * Developer URI: http://www.kritek.eu
 * Author: simplesurance GmbH
 * Author URI:  http://www.simplesurance-group.com/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


if (!class_exists('WC_Integration_Simplesurance')) :

    class WC_Integration_Simplesurance {

        /**
         * Construct the plugin.
         */
        public function __construct() {

            $this->current_link = $this->get_current_url();


            add_action('plugins_loaded', array($this, 'init'));

            add_action('rest_api_init', array($this, 'dt_register_api_hooks'));
        }

        /**
         * Initialize the plugin.
         */
        public function init() {
            //Load languages

            $path = dirname(plugin_basename(__FILE__)) . '/lang/';
            load_plugin_textdomain('simplesurance', false, $path);



            add_action('plugins_loaded', array($this, 'init'));
            add_action('sisu_export', array($this, 'studio_xml_sitemap'));


            // Checks if WooCommerce is installed.
            if (class_exists('WC_Integration')) {
                // Include our integration class.
                include_once 'includes/class-wc-integration-simplesurance-integration.php';

                // Register the integration.
                add_filter('woocommerce_integrations', array($this, 'add_integration'));
                // For export categories
                add_filter('query_vars', array($this, 'add_query_vars'), 0);
                add_action('parse_request', array($this, 'sniff_requests'), 0);
                add_action('init', array($this, 'add_endpoint'), 0);
                // Set the current touch point
                add_action('wp', array($this, 'set_current_touch_point'), 200);
                // Add the sisu tag
                add_action('wp_footer', array($this, 'add_sisu_tag'));


                add_action('wp_ajax_nopriv_sisu_cart_change', 'prefix_sisu_cart_change');

                add_action('wp_enqueue_scripts', array($this, 'init_plugin'));
                add_action('wp_ajax_update_cart', array($this, 'update_cart'));
                add_action('wp_ajax_nopriv_update_cart', array($this, 'update_cart'));
                // Set the default country code
                add_filter('default_checkout_country', array($this, 'change_default_checkout_country'));
                add_filter('default_checkout_state', array($this, 'change_default_checkout_state'));
            } else {
                echo "SISU ERROR!!";
                die();
            }
        }

        /**
         * init the ajax plugin for sisu
         */
        public function init_plugin() {
            wp_enqueue_script(
                    'ajax_script', plugins_url('/js/sisu.js', __FILE__), array('jquery'), TRUE
            );
            wp_localize_script(
                    'ajax_script', 'myAjax', array(
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce("update_cart_nonce"),
                    )
            );
        }

        /**
         * Change the country code in the checkout page
         * @return string
         */
        function change_default_checkout_country() {
            return $this->get_country_base_location(); // country code
        }

        /**
         * Change the state code in the checkout page
         * @return string
         */
        function change_default_checkout_state() {
            return $this->get_country_base_location(); // country code
        }

        /**
         * Update the cart in the cart and checkout with ajax refresh
         */
        public function update_cart() {
            check_ajax_referer('update_cart_nonce', 'nonce');
            $country_code = sanitize_text_field($_POST['country_code']);
            if (true)
                wp_send_json_success($this->get_cart_change($country_code));
            else
                wp_send_json_error(array('error' => $custom_error));
        }

        /**
         * Get the updated cart and checkout script
         * @return string
         */
        private function get_cart_change($country_code = null) {
            if (!$country_code) {
                $cart = $this->get_cart_script();
            } else {
                $cart = $this->get_order_script();
            }

            return $this->get_start_change_script() .
                    $this->get_delete_products_script() .
                    $this->get_head_script($country_code) .
                    $this->get_cart().
                    $this->get_debug_script() .
                    $cart .
                    $this->get_footer_script() .
                    $this->get_end_change_script();
        }

        /**
         * Add a new integration to WooCommerce.
         */
        public function add_integration($integrations) {
            $integrations[] = 'WC_Integration_Simplesurance_Integration';
            return $integrations;
        }

        /**
         * Add the sisu tag
         */
        public function add_sisu_tag() {
            $this->fire_susi_js();
            $this->get_cart_update_script();
            $this->get_customer_update_script();
        }

        /**
         * Get the cart update script 
         * return string
         */
        private function get_cart_update_script() {
            $sc = "<script type=\"text/javascript\">jQuery( document.body ).on( 'updated_cart_totals', function() {" . PHP_EOL;
            $sc = $sc . "//console.log( 'KRITEK AJAX PLUGIN JS cart updated' ); " . PHP_EOL;
            $sc = $sc . "// e.preventDefault();" . PHP_EOL;
            $sc = $sc . "var data = {" . PHP_EOL;
            $sc = $sc . "    action: 'update_cart'," . PHP_EOL;
            $sc = $sc . "    nonce: myAjax.nonce" . PHP_EOL;
            $sc = $sc . "};" . PHP_EOL;
            $sc = $sc . PHP_EOL;
            $sc = $sc . "  jQuery.post( myAjax.url, data, function( response ) " . PHP_EOL;
            $sc = $sc . "  {" . PHP_EOL;
            $sc = $sc . "      jQuery('#sisuwpjs').html( response.data );" . PHP_EOL;
            $sc = $sc . "  });" . PHP_EOL;
            $sc = $sc . PHP_EOL;
            $sc = $sc . "} );</script>" . PHP_EOL;

            echo $sc;
        }

        /**
         * Get the cart update script 
         * return string
         */
        private function get_customer_update_script() {
            $sc = "<script type=\"text/javascript\">jQuery(document.body).on('change', '.country_to_state', function(){" . PHP_EOL;

            $sc = $sc . " var country_code = jQuery(this).val();" . PHP_EOL;
            $sc = $sc . "//console.log( country_code );" . PHP_EOL;
            $sc = $sc . "//console.log( jQuery( \"#sisu_container\" ).length  );" . PHP_EOL;
            // $sc = $sc . "     if ( jQuery( \"#sisu_container\" ).length ) {" . PHP_EOL;
            $sc = $sc . "     if ( (jQuery( \"#sisu_container\" ).length) || (sessionStorage._susi_country_changed==1)  ) {" . PHP_EOL;

            $sc = $sc . "//console.log( jQuery( \"#sisu_container\" ).length );" . PHP_EOL;

            $sc = $sc . " sessionStorage._susi_country_changed = '1';" . PHP_EOL;

            $sc = $sc . "// e.preventDefault();" . PHP_EOL;
            $sc = $sc . "var data = {" . PHP_EOL;
            $sc = $sc . "    action: 'update_cart'," . PHP_EOL;
            $sc = $sc . "    nonce: myAjax.nonce," . PHP_EOL;
            $sc = $sc . "    country_code: country_code" . PHP_EOL;
            $sc = $sc . "};" . PHP_EOL;
            $sc = $sc . PHP_EOL;
            $sc = $sc . "  jQuery.post( myAjax.url, data, function( response ) " . PHP_EOL;
            $sc = $sc . "  {" . PHP_EOL;
            $sc = $sc . "      jQuery('#sisuwpjs').html( response.data );" . PHP_EOL;
            $sc = $sc . "  });" . PHP_EOL;
            $sc = $sc . PHP_EOL;
            $sc = $sc . "}" . PHP_EOL;
            $sc = $sc . "} );" . PHP_EOL;
            $sc = $sc . "jQuery(window).bind('beforeunload',function(){" . PHP_EOL;
            $sc = $sc . "sessionStorage._susi_country_changed = '0';" . PHP_EOL;
            $sc = $sc . "});" . PHP_EOL;
            $sc = $sc . "</script>" . PHP_EOL;

            echo $sc;
        }

        /**
         * Create dinamically the js sisu script
         * @param type $objectToDelete
         */
        public function fire_susi_js($objectToDelete = null) {
//            global $woocommerce;

            if (is_cart() && (WC()->cart->get_cart_contents_count() > 0) && !is_checkout()) {

                echo $this->get_start_script();
                if ($objectToDelete == "_sisu_products_") {
                    echo $this->get_delete_products_script();
                }
                echo $this->get_head_script();
                echo $this->get_cart();
                echo $this->get_debug_script();
                echo $this->get_cart_script();
                echo $this->get_footer_script();
                echo $this->get_end_script();
            }

            if (is_checkout() && !is_wc_endpoint_url()) {

                echo $this->get_start_script();

                echo $this->get_head_script();
                echo $this->get_cart();
                echo $this->get_debug_script();
                echo $this->get_order_script();
                echo $this->get_footer_script();
                echo $this->get_end_script();
            }

            if (is_wc_endpoint_url('order-received')) {

                echo $this->get_start_script();
                echo $this->get_head_script();
                echo $this->get_customer();
                echo $this->get_debug_script();
                echo $this->get_success_script();
                echo $this->get_footer_script();
                echo $this->get_end_script();
            }
        }

        /**
         * Get the cart script
         * @return string
         */
        private function get_cart_script() {
            $cart = PHP_EOL;
            $cart = $cart . "// This indicates which page you are initializing the plugin. Use \"cart\" if it's shopping cart page, or \"checkout\" for checkout page" . PHP_EOL;
            $cart = $cart . "_sdbag.push(['page', 'cart']);" . PHP_EOL;
            $cart = $cart . "_sdbag.push(['init', 'checkout']); // When the \"page\" parameter set to \"cart\" or \"checkout\", \"init\" parameter has to have \"checkout\" value." . PHP_EOL;

            return $cart;
        }

        /**
         * Get the order script
         * @return string
         */
        private function get_order_script() {
            $cart = PHP_EOL;
            $cart = $cart . "// This indicates which page you are initializing the plugin. Use \"cart\" if it's shopping cart page, or \"checkout\" for checkout page" . PHP_EOL;
            $cart = $cart . "_sdbag.push(['page', 'checkout']);" . PHP_EOL;
            $cart = $cart . "_sdbag.push(['init', 'checkout']); // When the \"page\" parameter set to \"cart\" or \"checkout\", \"init\" parameter has to have \"checkout\" value." . PHP_EOL;

            return $cart;
        }

        /**
         * Get the product remove script
         * @return string
         */
        private function get_delete_products_script() {
            $del = "jQuery('#sisu_container').remove();" . PHP_EOL;
            $del = $del . "localStorage.removeItem('_sisu_products_');";
            return $del;
        }

        /**
         * Get the customer remove cart script
         * @return string
         */
        private function get_delete_customer_script() {
            $del = "jQuery('#sisu_container').remove();" . PHP_EOL;
            $del = $del . "localStorage.removeItem('_sisu_products_');";
            return $del;
        }

        /**
         * Get the success script
         * @return string
         */
        private function get_success_script() {
            $cart = PHP_EOL;
            $cart = $cart . "// This indicates which page you are initializing the plugin. Use \"cart\" if it's shopping cart page, or \"checkout\" for checkout page" . PHP_EOL;
            $cart = $cart . "_sdbag.push(['page', 'success']);" . PHP_EOL;
            $cart = $cart . "_sdbag.push(['init', 'success']); // When the \"page\" parameter set to \"cart\" or \"checkout\", \"init\" parameter has to have \"checkout\" value." . PHP_EOL;

            return $cart;
        }

        /**
         * Get the start script
         * @return string
         */
        private function get_start_script() {
            $startScript = PHP_EOL;
            $startScript = $startScript . "<div id=\"sisuwpjs\" >" . PHP_EOL;
            $startScript = $startScript . "<!-- SISU START -->" . PHP_EOL;
            $startScript = $startScript . PHP_EOL;
            $startScript = $startScript . "<script type=\"text/javascript\">" . PHP_EOL;
            return $startScript;
        }

        private function get_start_change_script() {
            $startScript = PHP_EOL;
            $startScript = $startScript . "<!-- SISU UPDATED -->" . PHP_EOL;
            $startScript = $startScript . PHP_EOL;
            $startScript = $startScript . "<script type=\"text/javascript\">" . PHP_EOL;
            return $startScript;
        }

        /**
         * Get the head of the script
         * @return string
         */
        private function get_head_script($country_code = null) {
            $head="";
            $head = $head . "var _sdbag = _sdbag || [];" . PHP_EOL;
            $head = $head . PHP_EOL;
            $head = $head . "_sdbag.push(['partnerId', " . $this->get_partner_id() . "]); //replace it with your partner id" . PHP_EOL;
            $head = $head . "_sdbag.push(['shopId', " . $this->get_shop_id() . "]); //replace it with your shop id" . PHP_EOL;
            $head = $head . "_sdbag.push(['country', '" . $this->get_country_base_location($country_code) . "']); //replace it with your customer's country code" . PHP_EOL;

            return $head;
        }

        /**
         * Get the footer of the script
         * @return string
         */
        private function get_footer_script() {
            $footer = PHP_EOL;
            $footer = $footer . "(function() {" . PHP_EOL;
            $footer = $footer . "var ss = document.createElement('script'); ss.type = 'text/javascript'; ss.async = true;" . PHP_EOL;
            $footer = $footer . " ss.src = ('https:' == document.location.protocol ? 'https://' : 'http://')  + '" . $this->get_jsapi_url() . "/jsapi/sisu-checkout-2.x.min.js';" . PHP_EOL;
            $footer = $footer . " var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ss, s);" . PHP_EOL;
            $footer = $footer . "})();";
            $footer = $footer . PHP_EOL;
            $footer = $footer . PHP_EOL;


            return $footer;
        }

        /**
         * Get the end of the script
         * @return string
         */
        private function get_end_script() {
            $endScript = "</script > " . PHP_EOL;
            $endScript = $endScript . PHP_EOL;
            $endScript = $endScript . "<!-- SISU STOP -->" . PHP_EOL;
            $endScript = $endScript . "</div>" . PHP_EOL;
            $endScript = $endScript . PHP_EOL;
            $endScript = $endScript . PHP_EOL;

            return $endScript;
        }

        private function get_end_change_script() {
            $endScript = "</script > " . PHP_EOL;
            $endScript = $endScript . PHP_EOL;
            $endScript = $endScript . "<!-- SISU UPDATE STOP -->" . PHP_EOL;
            $endScript = $endScript . PHP_EOL;
            $endScript = $endScript . PHP_EOL;

            return $endScript;
        }

        /**
         * Get if tebug option is true or false
         * @return boolean
         */
        private function get_debug_option() {
            if (WC()->integrations->integrations['integration-simplesurance']->get_option('debug') == 'yes') {
                $debug = true;
            } else {
                $debug = false;
            }
            return $debug;
        }

        /**
         * Get the debug script
         * @return type
         */
        private function get_debug_script() {
            $script1Debug = "";
            $scriptDebugLog = "";
            if ($this->get_debug_option()) {

                $script1Debug = "// debug and SANDBOX" . PHP_EOL;
                $script1Debug = $script1Debug . "_sdbag.push(['sandbox', true]);" . PHP_EOL;
                $scriptDebugLog = "// debug LOG ACTIVE" . PHP_EOL;
                $scriptDebugLog = $scriptDebugLog . "_sdbag.push(['debug', true]);" . PHP_EOL;
                return $script1Debug . $scriptDebugLog;
            } else {
                $script1Debug = "// debug and SANDBOX" . PHP_EOL;
                $script1Debug = $script1Debug . "_sdbag.push(['sandbox', false]);" . PHP_EOL;
                $scriptDebugLog = "// debug LOG ACTIVE" . PHP_EOL;
                $scriptDebugLog = $scriptDebugLog . "_sdbag.push(['debug', false]);" . PHP_EOL;
                return $script1Debug . $scriptDebugLog;
            }
        }

        /**
         * Get the cart script
         * @return string
         */
        private function get_cart() {
            $items = WC()->cart->get_cart();

            $cartJs = PHP_EOL;
            $commaProd = "";
            $endProd = count($items);
            $countProd = 1;
            $cartJs = $cartJs . "_sdbag.push(['products', [" . PHP_EOL;

            foreach ($items as $item => $values) {
                $_product = $values['data']->post;

                $catStr = '';
                $terms = wp_get_post_terms($values['product_id'], 'product_cat');

                $end = count($terms) - 1;
                $commaTerm = "";
                foreach ($terms as $term_id => $term) {
                    if ($end == $term_id) {
                        $commaTerm = "";
                    } else {
                        $commaTerm = ",";
                    }

                    $catStr = $catStr . "{" . $term->term_id . ":" . " \"" . $this->sanitaze_js($term->name) . "\"} $commaTerm";
                }

                $cartJs = $cartJs . " {" . PHP_EOL;
                $cartJs = $cartJs . "id: \"" . $values['product_id'] . "\"," . PHP_EOL;
                $cartJs = $cartJs . "categories: [$catStr]," . PHP_EOL;
                $cartJs = $cartJs . "name: \"" . $this->sanitaze_js($_product->post_title) . "\"," . PHP_EOL;
                $cartJs = $cartJs . "price: \"" . get_post_meta($values['product_id'], '_price', true) . "\"," . PHP_EOL;
                $cartJs = $cartJs . "currency: \"" . get_woocommerce_currency() . "\"," . PHP_EOL;
                $cartJs = $cartJs . "sku: \"" . get_post_meta($values['product_id'], '_sku', true) . "\"," . PHP_EOL;
                $cartJs = $cartJs . "qty: " . $values['quantity'] . PHP_EOL;

                if ($endProd == $countProd) {
                    $commaProd = "";
                } else {
                    $commaProd = ",";
                }
                $cartJs = $cartJs . " } $commaProd " . PHP_EOL;

                $cartJs = $cartJs . PHP_EOL;

                $catStr = "";
                $countProd++;
            }
            $cartJs = $cartJs . "]]);" . PHP_EOL;
            $cartJs = $cartJs . PHP_EOL;

            return $cartJs;
        }

        /**
         * Get the partner id
         * @return type
         */
        private function get_partner_id() {
            return WC()->integrations->integrations['integration-simplesurance']->get_option('partnerId');
        }

        /**
         * Get the shop id
         * @return type
         */
        private function get_shop_id() {
            return WC()->integrations->integrations['integration-simplesurance']->get_option('shopId');
        }

        /**
         * Get the order
         * @return type
         */
        private function get_order() {
            $order = '';
            $orderKey = filter_input(INPUT_GET, 'key');

            if (isset($orderKey)) {
                $order_id = wc_get_order_id_by_order_key($orderKey);
                $order = new WC_Order($order_id);
            } else {
                $order = -1;
            }

            return $order;
        }

        /**
         * Get the customer
         * @return string
         */
        private function get_customer() {

            $order = $this->get_order();
            $pushJsCustomer = PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . "_sdbag.push(['customer', {" . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . "firstname: \"" . $this->sanitaze_js($order->billing_first_name) . "\", " . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . "lastname: \"" . $this->sanitaze_js($order->billing_last_name) . "\", " . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . "email: \"" . $order->billing_email . "\", " . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . "phone: \"" . $order->billing_phone . "\", " . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . "street: \"" . $this->sanitaze_js($order->billing_address_1) . "\", " . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . "street_number: \"" . $this->sanitaze_js($order->billing_address_2) . "\", " . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . "zip: \"" . $order->billing_postcode . "\", " . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . "city: \"" . $order->billing_city . "\", " . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . "country: \"" . $order->billing_country . "\"" . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . " }]);" . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . "_sdbag.push(['orderId'," . $this->get_order_id($order) . "]);" . PHP_EOL;
            $pushJsCustomer = $pushJsCustomer . PHP_EOL;

            return $pushJsCustomer;
        }

        /**
         * Get the country location
         * @return type
         */
        private function get_country_base_location($country_code = null) {
            if ($country_code != '') {
                return $country_code;
            }
            $order = $this->get_order();
            if (!isset($order->billing_country)) {
                $baseLocation = wc_get_base_location();
                $location = $baseLocation['country'];
            } else {
                $location = $order->billing_country;
            }

            return $location;
        }

        /**
         * Get the jsapi url
         * @return string
         */
        private function get_jsapi_url() {
            if ($this->get_debug_option()) {
                $jsapiUrl = "www-staging.schutzklick.de";
            } else {
                $jsapiUrl = "www.schutzklick.de";
            }
            return $jsapiUrl;
        }

        /**
         * Sanitaze tool
         * @param type $string
         * @return type
         */
        private function sanitaze_js($string) {
            $result = str_replace('"', '\"', $string);
            $result = str_replace("'", "\'", $result);
            return $result;
        }

        /**
         * Get the order option
         * @return type
         */
        private function get_order_option() {

            return WC()->integrations->integrations['integration-simplesurance']->get_option('typeOrder');
        }

        /**
         * Get the order id
         * @param type $order
         * @return string
         */
        private function get_order_id($order) {

            if ($this->get_order_option() == '1') {
                $order_id = $order->id;
            } else {
                $orderKey = filter_input(INPUT_GET, 'key');
                if (isset($orderKey)) {
                    $order_id = "\"" . $orderKey . "\"";
                } else {
                    $order_id = 'undefined!';
                }
            }
            return $order_id;
        }

        //*********************************
        /**
         * Add the query vars 
         * @param type $vars
         * @return string
         */
        public function add_query_vars($vars) {
            $vars[] = '__api';
            $vars[] = 'pugs';
            return $vars;
        }

        /** Add API Endpoint
         * 	This is where the magic happens - brush up on your regex skillz
         * 	@return void
         */
        public function add_endpoint() {
            //ready for other parameters to be implemented. see sniff_requests
            add_rewrite_rule('^schutzklickCategoryExport/?([0-9]+)?/?', 'index.php?__api=1&pugs=$matches[1]', 'top');
        }

        /** 	Sniff Requests
         * 	This is where we hijack all API requests
         * 	If $_GET['__api'] is set, we kill WP and serve up pug bomb awesomeness
         * 	@return die if API request
         */
        public function sniff_requests() {
            global $wp;
            if (isset($wp->query_vars['__api'])) {
                $this->handle_request();
                exit;
            }
        }

        /** Handle Requests
         * 	This is where we send off for an intense pug bomb package
         * 	@return void 
         */
        protected function handle_request() {

            $this->send_response($this->get_product_categories());
        }

        /** Response Handler
         * 	This sends a JSON response to the browser
         */
        protected function send_response($msg) {
            header('content-type: application/json; charset=utf-8');
            echo json_encode($msg) . "\n";
            exit;
        }

        /**
         * Get the product categories
         * @return type
         */
        private function get_product_categories() {
            $product_categories = get_terms('product_cat');
            foreach ($product_categories as $key => $cat) {
                $categories[] = array('id' => $cat->term_id, 'name' => $this->sanitaze_js($cat->name), 'parent_id' => $cat->parent);
            }
            return $categories;
        }

        /**
         * Get the current url
         * @return type
         */
        private function get_current_url() {
            if (isset($_SERVER['HTTPS'])) {
                $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
            } else {
                $protocol = 'http';
            }
            return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        /**
         * Set the current touch point
         */
        public function set_current_touch_point() {


            setcookie('susi_touch_point', 'nosusi', 0, '/');
            if (is_cart()) {

                setcookie('susi_touch_point', 'cart', 0, '/');
            }
            if (is_checkout() && !is_wc_endpoint_url()) {

                setcookie('susi_touch_point', 'checkout', 0, '/');
            }
            if (is_wc_endpoint_url('order-received')) {


                setcookie('susi_touch_point', 'success', 0, '/');
            }
        }

        /**
         * Get the current touch point
         * @return type
         */
        private function get_current_touch_point() {
            return $_COOKIE['susi_touch_point'];
        }

    }

    $WC_Integration_Simplesurance = new WC_Integration_Simplesurance(__FILE__);

endif;
