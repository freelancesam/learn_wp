<?php
namespace Trs;

use tree_table_rate;
use Trs\Services\ServiceRegistry;
use Trs\Services\StatsService;
use Trs\Services\UpdateService;
use Trs\Services\UpgradeService;


class Loader
{
    public function __construct(PluginMeta $pluginMeta)
    {
        $this->pluginMeta = $pluginMeta;
    }
    
    public function bootstrap()
    {
        $services = new ServiceRegistry();

        $services->install(new UpdateService($this->pluginMeta));
        $services->install(new StatsService($this->pluginMeta));

        $upgrades = new UpgradeService($this->pluginMeta);
        if ($upgrades->ready()) {
            $services->install($upgrades);
        }

        add_filter('woocommerce_shipping_methods', array($this, '_registerShippingMethods'));

        add_action('init', array($this, '_init'), PHP_INT_MAX);
    }

    public function _init()
    {
        add_filter("plugin_action_links_{$this->pluginMeta->getPluginBasename()}", array($this, '_injectSettingsLink'));
        $this->fixIncorrectHidingOfShippingSectionWhenNoShippingZoneMethodsDefined();

        // Our settings page only
        if (
            @$_GET['section'] === tree_table_rate::className()
            ||
            (
                ($instanceId = @$_REQUEST['instance_id']) !== null &&
                class_exists('\\WC_Shipping_Zones') &&
                \WC_Shipping_Zones::get_shipping_method($instanceId) instanceof tree_table_rate
            )) {

            $enqueueAssets = new EnqueueAssets($this->pluginMeta);
            add_action('admin_enqueue_scripts', $enqueueAssets, PHP_INT_MAX);

            // Compatibility with Virtue theme 3.2.2 (https://wordpress.org/themes/virtue/)
            remove_action('admin_enqueue_scripts', 'kadence_admin_scripts');

            // Compatibility with Woocommerce Product Tab Pro 1.8.0 (http://codecanyon.net/item/woocommerce-tabs-pro-extra-tabs-for-product-page/8218941)
            remove_action('admin_print_footer_scripts', '_hc_tinymce_footer_scripts');
        }
    }

    public function _registerShippingMethods($shippingMethods)
    {
        static $shippingMethod;

        if (!isset($shippingMethod)) {
            $shippingMethod = new tree_table_rate();
        }

        $shippingMethods[tree_table_rate::className()] = $shippingMethod;

        return $shippingMethods;
    }

    public function _injectSettingsLink($links)
    {
        $settingsUrl = admin_url('admin.php?page=wc-settings&tab=shipping&section='.urlencode(tree_table_rate::className()));
        array_unshift($links, '<a href="'.esc_html($settingsUrl).'">'.__('Settings').'</a>');

        return $links;
    }

    public function _fixShippingMethodCount($count)
    {
        if ($count == 0) {
            $count = 1;
        }

        return $count;
    }


    private $pluginMeta;

    private function fixIncorrectHidingOfShippingSectionWhenNoShippingZoneMethodsDefined()
    {
        add_filter(
            'transient_wc_shipping_method_count_1_' .
            \WC_Cache_Helper::get_transient_version('shipping'),
            array($this, '_fixShippingMethodCount'),
            10, 2
        );
    }
}