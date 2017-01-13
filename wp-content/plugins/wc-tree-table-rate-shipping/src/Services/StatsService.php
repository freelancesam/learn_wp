<?php
namespace Trs\Services;

use Trs\PluginMeta;
use WooCommerce;


class StatsService implements IService
{
    const SCHEDULE_HOOK_NAME = 'trs_stats_schedule_hook';


    public function __construct(PluginMeta $pluginMeta)
    {
        $this->pluginMeta = $pluginMeta;
    }

    public function install(ServiceRegistry $registry)
    {
        $registry->register($this);

        $hook = self::SCHEDULE_HOOK_NAME;

        add_action($hook, array($this, 'send'));

        if (!wp_next_scheduled($hook)) {
            wp_schedule_event(time(), 'daily', $hook);
        }

        $self = $this;
        register_deactivation_hook($this->pluginMeta->getEntryFile(), function() use($self, $hook) {
            wp_clear_scheduled_hook($hook);
            $self->send();
        });
    }

    public function send()
    {
        $data = array();

        $data['siteurl'] = site_url();

        $data['license'] = $this->pluginMeta->getLicense();

        $data['env'] = array(
            'php' => PHP_VERSION,
            'wp' => $GLOBALS['wp_version'],
            'wc' => class_exists('WooCommerce') ? WooCommerce::instance()->version : null,
        );

        $data['version'] = $this->pluginMeta->getVersion();

        $data['config'] = get_option('woocommerce_tree_table_rate_settings', null);

        $data['plugins'] = null; {

            require_once(ABSPATH.'wp-admin/includes/plugin.php');

            $plugins = get_plugins();

            $activePluginBasenames = apply_filters('active_plugins', get_option('active_plugins'));
            foreach ($plugins as $basename => &$plugin) {
                $plugin['Active'] = array_search($basename, $activePluginBasenames) !== false;
            }

            $data['plugins'] = $plugins;
        }

        wp_remote_post($this->pluginMeta->getApiStatsEndpoint(), array(
            'blocking' => false,
            'body' => json_encode($data)
        ));
    }

    private $pluginMeta;
}