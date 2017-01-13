<?php
namespace Trs\Woocommerce;

use WC_Shipping_Method;


class WcTools
{
    public static function getGlobalShippingMethods()
    {
        /** @var \WooCommerce $wc */
        $wc = WC();
        $shipping = $wc->shipping();
        $methods = array_filter(
            $shipping->shipping_methods ?: $shipping->load_shipping_methods(),
            function(WC_Shipping_Method $method) {
                return $method->supports('settings') || empty($method->supports);
            }
        );

        /** @var WC_Shipping_Method[] $methods */
        return $methods;
    }

    public static function purgeWoocommerceShippingCache()
    {
        if (!class_exists('WC_Cache_Helper') || !method_exists('WC_Cache_Helper', 'get_transient_version')) {

            global $wpdb;

            /** @noinspection SqlDialectInspection */
            /** @noinspection SqlNoDataSourceInspection */
            $transients = $wpdb->get_col("
                SELECT SUBSTR(option_name, LENGTH('_transient_') + 1)
                FROM `{$wpdb->options}`
                WHERE option_name LIKE '_transient_wc_ship_%'
            ");

            foreach ($transients as $transient) {
                delete_transient($transient);
            }

            return;
        }

        \WC_Cache_Helper::get_transient_version('shipping', true);
    }
}