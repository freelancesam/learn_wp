<?php
class TrsPrerequisitesChecker
{
    static public function check()
    {
        self::$errors = array();

        if (version_compare($phpv = PHP_VERSION, '5.3', '<')) {
            self::$errors[] =
                "You are running an outdated PHP version {$phpv}. 
                 {pluginName} requires PHP 5.3+. 
                 Contact your hosting support to switch to a newer PHP version.";
        }
        
        global $wp_version;
        if (isset($wp_version) && version_compare($wp_version, '4.0', '<')) {
            self::$errors[] =
                "You are running an outdated WordPress version {$wp_version}.
                 {pluginName} is tested with WordPress 4.0+.
                 Consider updating to a modern WordPress version.";
        }

        if (!self::isWoocommerceActive()) {
            self::$errors[] =
                "WooCommerce is not active. 
                 {pluginName} requires WooCommerce to be installed and activated.";
        } else {
            if (defined('WC_VERSION') || did_action('plugins_loaded')) {
                self::_checkWoocoomerceVersion();
            } else {
                add_action('plugins_loaded', array(get_class(), '_checkWoocoomerceVersion'));
            }
        }

        // Hook admin_notices always since errors can be added lately
        add_action('admin_notices', array(get_class(), '_showErrors'));

        return !self::$errors;
    }

    static public function _showErrors()
    {
        if (!self::$errors) {
            return;
        }

        ?>
        <div class="notice notice-error">
            <?php foreach (self::$errors as $error): ?>
                <?php $error = str_replace('{pluginName}', 'Tree Table Rate Shipping', $error) ?>
                <p><?php echo esc_html($error) ?></p>
            <?php endforeach; ?>
        </div>
        <?php
    }

    static public function _checkWoocoomerceVersion()
    {
        $wcVersion = defined('WC_VERSION') ? WC_VERSION : null;

        if (!isset($wcVersion) || version_compare($wcVersion, '2.3', '<')) {
            self::$errors[] =
                "You are running an outdated WooCommerce version".(isset($wcVersion) ? " ".$wcVersion : null).".
                 {pluginName} requires WooCommerce 2.3+.
                 Consider updating to a modern WooCommerce version.";
        }
    }

    static private $errors;

    static private function isWoocommerceActive()
    {
        static $active_plugins;

        if (!isset($active_plugins)) {
            $active_plugins = (array)get_option('active_plugins', array());
            if (is_multisite()) {
                $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
            }
        }

        return
            in_array('woocommerce/woocommerce.php', $active_plugins) ||
            array_key_exists('woocommerce/woocommerce.php', $active_plugins);
    }
}