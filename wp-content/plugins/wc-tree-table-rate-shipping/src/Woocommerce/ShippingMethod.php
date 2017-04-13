<?php
namespace Trs\Woocommerce;

use Exception;
use Trs\Core\Interfaces\IRule;
use Trs\Core\PlatformSettings;
use Trs\Factory\Registries\GlobalRegistry;
use Trs\Mapping\Interfaces\IReader;
use Trs\Woocommerce\Converters\PackageConverter;
use Trs\Woocommerce\Converters\RateConverter;
use WC_Admin_Settings;
use WC_Product;
use WC_Shipping_Method;


class ShippingMethod extends WC_Shipping_Method
{
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct($instance_id = 0)
    {
        $this->id = 'tree_table_rate';
        $this->title = $this->method_title = 'Tree Table Rate';
        $this->instance_id = $instance_id;

        $this->supports = array(
            'settings',
            'shipping-zones',
            'instance-settings',
            'global-instance',
        );

        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));

        $this->init();
    }

    public function calculate_shipping($packageData = array())
    {
        $globals = self::initGlobalRegistry(true);

        $rule = $this->loadRule($globals->reader);
        if (!isset($rule)) {
            return;
        }

        $packageData = self::removeNonShippables($packageData);

        $package = PackageConverter::fromWoocommerceToCore($packageData);

        $rates = $globals->processor->process(array($rule), $package);

        $rateIdx = 1;
        foreach ($rates as $rate) {
            $this->add_rate(RateConverter::fromCoreToWoocommerce(
                $rate,
                join('_', array_filter(array($this->id, @$this->instance_id, $rateIdx++))),
                $this->title
            ));
        }
    }

    public function init()
    {
        $this->init_form_fields();
        $this->init_settings();

        $this->enabled = $this->get_option('enabled');
        $this->tax_status = $this->get_option('tax_status');
        $this->title = $this->get_option('label') ?: 'Tree Table Rate';
    }

    public function init_form_fields()
    {
        $meta = array(
            'enabled'    => array(
                'title'   => 'Enable/Disable',
                'type'    => 'checkbox',
                'label'   => 'Enable this shipping method',
                'default' => 'yes',
            ),
            'tax_status' => array(
                'title' 		=> 'Tax Status',
                'type' 			=> 'select',
                'class'         => 'wc-enhanced-select',
                'default' 		=> 'taxable',
                'options'		=> array(
                    'taxable' 	=> 'Taxable',
                    'none' 		=> 'Not taxable',
                ),
            ),
        );

        $rules = array(
            'rule' => array(
                'type' => 'rule',
                'default' => null,
            ),
        );

        $this->form_fields = $meta + $rules;

        $this->instance_form_fields =
            $meta +
            array(
                'label' => array(
                    'title' => 'Label',
                    'type' => 'text',
                    'default' => '',
                    'placeholder' => 'Label in shipping zone table',
                    'css' => 'width: 15.7em',
                ),
            ) +
            $rules;

        unset($this->instance_form_fields['enabled']);
    }

    public function generate_rule_html()
    {
        ob_start();
        ?>
                <?php echo $this->generate_hidden_html('rule', array()) ?>
            </table>

            <?php include(__DIR__.'/../../tpl.php'); ?>

            <table>
        <?php
        return ob_get_clean();
    }

    public function generate_hidden_html($field, $definition)
    {
        $definition['type'] = 'hidden';
        $html = $this->generate_text_html($field, $definition);
        $html = preg_replace('/'.preg_quote('<tr', '/').'/', '<tr style="display:none"', $html, 1);
        return $html;
    }

    public function validate_rule_field($key)
    {
        $newRule = @$_POST[$this->plugin_id.$this->id.'_'.$key];
        if (isset($newRule)) {
            $newRule = trim(stripslashes($newRule));

            try {
                $globals = self::initGlobalRegistry(false);
                $this->loadRule($globals->reader, $newRule);
            }
            catch (Exception $e) {
                $this->errors[] = $e->getMessage();
                unset($newRule);
            }
        }

        if (!isset($newRule)) {
            $newRule = $this->get_option('rule');
        }

        return $newRule;
    }

    public function admin_options()
    {
        $methodTitleBkp = $this->method_title;
        $this->method_title .= ' Shipping';

        try {

            parent::admin_options();

        } catch (Exception $e){
            $this->method_title = $methodTitleBkp;
            throw $e;
        }

        $this->method_title = $methodTitleBkp;
    }

    public function process_admin_options()
    {
        $saved = parent::process_admin_options();

        if ($saved && version_compare(WC()->version, '2.3.0', '<')) {
            WcTools::purgeWoocommerceShippingCache();
        }

        return $saved;
    }

    public function display_errors()
    {
        foreach ($this->errors as $error) {
            WC_Admin_Settings::add_error($error);
        }
    }

    public function get_option($key, $empty_value = null) {

        $result = $empty_value;

        /** @noinspection PhpUndefinedConstantInspection */
        if (version_compare(WC_VERSION, '2.6', '>=') && empty($this->instance_id)) {

            add_filter(
                $filter = "woocommerce_shipping_instance_form_fields_{$this->id}",
                $stub = function() { return array(); }
            );

            $exception = null;
            try {
                $result = parent::get_option($key, $empty_value);
            }
            catch (Exception $e) {
                $exception = $e;
            }

            remove_filter($filter, $stub);

            if (isset($exception)) {
                throw $exception;
            }
        } else {
            $result = parent::get_option($key, $empty_value);
        }

        return $result;
    }

    public function get_instance_id()
    {
        // A hack to prevent Woocommerce 2.6 from skipping global method instance
        // rates in WC_Shipping::calculate_shipping_for_package()
        return (method_exists('parent', 'get_instance_id') ? parent::get_instance_id() : $this->instance_id) ?: -1;
    }

    private function loadRule(IReader $reader, $ruleData = null)
    {
        if (!isset($ruleData)) {
            $ruleData = $this->get_option('rule');
        }

        if (!$ruleData || !($ruleData = json_decode($ruleData, true))) {
            $ruleData = array();
        }

        /** @var IRule $rule */
        $rule = $reader->read('rule', $ruleData);

        return $rule;
    }

    static private function removeNonShippables($packageData)
    {
        foreach ((array)@$packageData['contents'] as $key => $itemData) {

            /** @var WC_Product $product */
            $product = $itemData['data'];

            if (!$product->needs_shipping()) {
                unset($packageData['contents'][$key]);
            }
        }

        return $packageData;
    }

    static private function initGlobalRegistry($lazy = true)
    {
        $settings = new PlatformSettings(
            wc_get_weight(1, 'g'), 
            wc_get_dimension(1, 'mm'), 
            pow(10, wc_get_price_decimals())
        );
        
        $globalRegistry = new GlobalRegistry($settings, $lazy);

        $globalRegistry->mappers->register('shipping_method_calculator', function() {
            return new ShippingMethodCalculatorMapper(new ShippingMethodLoader());
        });

        return $globalRegistry;
    }
}