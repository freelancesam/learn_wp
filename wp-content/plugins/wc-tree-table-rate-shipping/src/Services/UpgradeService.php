<?php
namespace Trs\Services;

use BadMethodCallException;
use Trs\Migration\Backup\ConfigBackupStorage;
use Trs\Migration\GeneralMigrationAdapter;
use Trs\Migration\MigrationTransaction;
use Trs\Migration\Storage\StorageBucket;
use Trs\Migration\Storage\StorageItem;
use Trs\Migration\Storage\WordpressOptionsStorage;
use Trs\PluginMeta;
use Trs\Woocommerce\WcTools;


class UpgradeService implements IService
{
    const CONFIG_OPTION_NAME = 'woocommerce_tree_table_rate_settings'; 
    const CONFIG_BACKUP_OPTIONS_PREFIX  = 'woocommerce_tree_table_rate_settings__bkp__';
    
    public function __construct(PluginMeta $pluginMeta)
    {
        $this->pluginMeta = $pluginMeta;
    }

    public function install(ServiceRegistry $registry)
    {
        $message = null;
        if (!$this->ready($message)) {
            throw new BadMethodCallException($message);
        }

        $registry->register($this);

        if (did_action('plugins_loaded')) {
            $this->maybeUpgrade();
        } else {
            add_action('plugins_loaded', array($this, 'maybeUpgrade'));
        }
    }

    public function ready(&$message = null)
    {
        if (!in_array(
            'woocommerce/woocommerce.php',
            apply_filters('active_plugins', get_option('active_plugins'))
        )) {
            $message = 'Active WooCommerce environment required in order to perform upgrades';
            return false;
        }

        return true;
    }

    public function maybeUpgrade()
    {
        $updateCurrentVersion = false;

        $currentVersion = $this->pluginMeta->getVersion();

        $previousVersion = get_option('trs_version');
        if (empty($previousVersion)) {
            $previousVersion = get_option(self::CONFIG_OPTION_NAME) ? '1.5.0' : $currentVersion;
            $updateCurrentVersion = true;
        }

        if (version_compare($previousVersion, $currentVersion, '<')) {

            $upgradeScripts = glob($this->pluginMeta->getMigrationsPath('*.php'), GLOB_NOSORT);
            natsort($upgradeScripts);
            
            $migrations = array();
            foreach ($upgradeScripts as $script) {
                $version = pathinfo($script, PATHINFO_FILENAME);
                if (version_compare($previousVersion, $version, '<=')) {
                    $migrations[] = new GeneralMigrationAdapter(include($script));
                }
            }
            
            if ($migrations) {

                $configStorageItem = new StorageItem(new WordpressOptionsStorage(), self::CONFIG_OPTION_NAME);

                $configBackups = new ConfigBackupStorage(
                    new StorageBucket(new WordpressOptionsStorage(false), self::CONFIG_BACKUP_OPTIONS_PREFIX),
                    'uniqid'
                );
                
                $migrationTransaction = new MigrationTransaction($migrations, $configStorageItem, $configBackups);

                $migrationTransaction->run();
            }
            
            // Although, in theory, we don't need to purge shipping cache since we always expect to produce
            // a similar functioning config after migrations, in practice, we'd better allow a user to test
            // a new config right after migration in case there is any issue with that, rather than showing
            // results cached from a previous config.
            WcTools::purgeWoocommerceShippingCache();
        }

        if ($updateCurrentVersion || $previousVersion !== $currentVersion) {
            update_option('trs_version', $currentVersion);
        }
    }


    private $pluginMeta;
}
?>