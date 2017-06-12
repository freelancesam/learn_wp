<?php
namespace Trs\Services;

use Trs\Services\Interfaces\IService;
use Trs\Services\ServiceRegistry;
use Trs\Woocommerce\Model\Shipping\Exceptions\MalformedPersistentId;
use Trs\Woocommerce\Model\Shipping\ShippingMethodPersistentId;
use Trs\Woocommerce\WcTools;


class AjaxService implements IService
{
    const AJAX_ACTION_SHIPPING_METHOD = 'trs_shipping_method';


    public function install()
    {
        add_action('wp_ajax_'. self::AJAX_ACTION_SHIPPING_METHOD, array($this, 'shippingMethod'));
    }

    public function shippingMethod() {

        if (!wp_verify_nonce(@$_POST['_wpnonce'], 'woocommerce-settings')) {
            self::respond(401, "Nonce validation failed. Refresh the page and try again.");
        }

        if (!current_user_can('manage_woocommerce')) {
            self::respond(403, "You have no permissions to perform the action.");
        }

        $id = @$_GET['id'];
        $enable = isset($_POST['enable']) ? (bool)(int)$_POST['enable'] : true;

        if (!isset($id)) {
            self::respond(400, 'No shipping method id provided.');
        }

        /** @var ShippingMethodPersistentId $id */
        try {
            $id = ShippingMethodPersistentId::unserialize($id);
        } catch (MalformedPersistentId $e) {
            self::respond(400, $e->getMessage());
        }


        /** @noinspection PhpUnusedLocalVariableInspection */
        $updated = false;

        if (!$id->global) {

            global $wpdb;

            $updatedRows = $wpdb->update(
                "{$wpdb->prefix}woocommerce_shipping_zone_methods",
                array('is_enabled' => (int)$enable),
                array('instance_id' => $id->id)
            );

            if ($updatedRows === false) {
                self::respond(500, "An error occurred while updating shipping method: {$wpdb->last_error}.");
            }

            $updated = $updatedRows > 0;

        } else {

            $methods = WC()->shipping()->load_shipping_methods();

            /** @var \WC_Shipping_Method $method */
            $method = @$methods[$id->id];
            if (!isset($method)) {
                self::respond(404, "A shipping method with id '{$id}' not found.");
            }

            $method->init_settings();
            if (!isset($method->settings['enabled'])) {
                self::respond(500,
                    "Unsupported shipping method settings structure. " .
                    "Try to " . ($enable ? 'enable' : 'disable') . " the method manually."
                );
            }

            $method->settings['enabled'] = WcTools::bool2YesNo($enable);

            $optionKey = (
            method_exists($method, 'get_option_key')
                ? $method->get_option_key()
                : $method->plugin_id . $method->id . '_settings'
            );

            $updated = update_option(
                $optionKey,
                apply_filters('woocommerce_settings_api_sanitized_fields_'.$method->id, $method->settings)
            );
        }

        if ($updated) {
            WcTools::purgeWoocommerceShippingCache();
        }

        self::respond(200, 'OK');
    }

    private static function respond($code, $message)
    {
        wp_die(
            $message,
            null, array('response' => $code)
        );
    }
}