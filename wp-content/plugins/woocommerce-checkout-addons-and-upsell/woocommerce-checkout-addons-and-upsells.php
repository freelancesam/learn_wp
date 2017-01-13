<?php
/**
 * Plugin Name: Woocommerce Checkout Addons & Upsells
 * Description: Add additional fields and product upsells to your Woocomerce checkout.
 * Version: 1.2
 * Plugin URI: http://codemypain.com
 * Author: Isaac Oyelowo
 * Author URI: http://isaacoyelowo.com
 *
 */
ini_set("display_errors", 0);
defined('SB_DS') or define('SB_DS', DIRECTORY_SEPARATOR);

class SB_WC_UpSell {

    protected $_plugin_id = 'sb_wc_upsell';
    protected $_plugin_dir;
    protected $_plugin_url;

    public function __construct() {
        @session_start();
        $this->_plugin_dir = dirname(__FILE__);
        $this->_plugin_url = get_site_url(null, '/wp-content/plugins/') . basename($this->_plugin_dir);

        require_once $this->_plugin_dir . SB_DS . 'helper.php';
    }

    public function Init() {
        $this->AddActions();
        $this->AddFilters();
    }

    protected function AddActions() {
        add_action('init', array($this, 'action_init'));
        if (is_admin()) {
            add_action('admin_init', array($this, 'action_admin_init'));
            add_action('woocommerce_product_data_panels', array($this, 'action_woocommerce_product_data_panels'));
            add_action('save_post', array($this, 'action_save_post'));
            add_action('woocommerce_settings_upsell_settings', array($this, 'action_woocommerce_settings_upsell_settings'));
            add_action('woocommerce_settings_save_upsell_settings', array($this, 'action_save_settings'));
            add_action('admin_init', array(&$this, 'wcau_admin_style'));
        } else {
            
        }
        add_action('woocommerce_checkout_insurance', array($this, 'action_woocommerce_checkout_after_customer_details'));
        add_action('woocommerce_checkout_process', array($this, 'action_woocommerce_checkout_process'));
        add_action('woocommerce_after_checkout_validation', array($this, 'action_woocommerce_after_checkout_validation'));
        add_action('woocommerce_checkout_order_processed', array($this, 'action_woocommerce_checkout_order_processed'), 10, 2);
        add_action('woocommerce_cart_calculate_fees', array($this, 'action_woocommerce_cart_calculate_fees'));
        add_action('woocommerce_checkout_order_processed', array($this, 'action_woocommerce_checkout_order_processed'), 10, 2);
        add_action('init', array($this, 'wcau_localize'));
    }

    protected function AddFilters() {
        if (is_admin()) {
            add_filter('woocommerce_product_data_tabs', array($this, 'filter_woocommerce_product_data_tabs'));
            add_filter('woocommerce_settings_tabs_array', array($this, 'filter_woocommerce_settings_tabs_array'), 50);
        } else {
            
        }
        //add_filter('woocommerce_checkout_fields', array($this, 'filter_woocommerce_checkout_fields'));
    }

    public function action_init() {
        if (is_admin()) {
            $this->handleAdminRequests();
        } else {
            $this->handleRequests();
        }
    }

    public function wcau_localize() {
        // Localization
        load_plugin_textdomain('wupc', false, dirname(plugin_basename(__FILE__)) . "/languages");
    }

    public function wcau_admin_style() {
        wp_register_style('wcau-back-end-style', plugins_url('css/admin.css', __FILE__));
        wp_enqueue_style('wcau-back-end-style');
    }

    protected function handleAdminRequests() {
        global $wpdb;
        $task = isset($_REQUEST['task']) ? $_REQUEST['task'] : null;
        if (!$task)
            return false;
        if ($task == 'uc_prod') {
            //{ "id": "Netta rufina", "label": "Red-crested Pochard", "value": "Red-crested Pochard" }
            $term = trim($_REQUEST['term']);
            $query = "SELECT * FROM $wpdb->posts WHERE post_type = 'product' AND post_status = 'publish' AND post_title LIKE '%$term%'";
            //print $query;
            $prods = array();
            foreach ($wpdb->get_results($query) as $row) {
                //$prods[] = array('id' => $row->ID, 'label' => $row->post_title, 'value' => $row->post_title);
                $prods[] = array('id' => $row->ID, 'label' => $row->post_title, 'value' => $row->ID);
            }
            header('Content-type: application/json');
            die(json_encode($prods));
        }
    }

    public function handleRequests() {
        global $woocommerce;

        $task = isset($_REQUEST['task']) ? $_REQUEST['task'] : null;
        if (!$task)
            return false;
        if ($task == 'sb_wc_upsell-add_to_cart') {
            $product_id = (int) $_POST['product_id'];
            $item_key = WC()->cart->add_to_cart($product_id);
            $json = json_encode(array('status' => 'ok', 'item_key' => $item_key));
            header('Content-type: application/json');
            die($json);
        } elseif ($task == 'sb_wc_upsell-remove_from_cart') {
            $key = trim($_POST['item_key']);
            WC()->cart->set_quantity($key, 0);
            die('removed');
        }

        if ($task == 'sb_wc_addon_upload') {
            if (!is_dir($this->_plugin_dir . SB_DS . 'uploads'))
                mkdir($this->_plugin_dir . SB_DS . 'uploads');
            require_once $this->_plugin_dir . SB_DS . 'qqFileUploader.php';
            $uploader = new qqFileUploader();
            $uploader->allowedExtensions = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
            // Specify max file size in bytes.
            $uploader->sizeLimit = 10 * 1024 * 1024; //10MB
            // Specify the input name set in the javascript.
            $uploader->inputName = 'qqfile';
            // If you want to use resume feature for uploader, specify the folder to save parts.
            $uploader->chunksFolder = 'chunks';
            $img_filename = wp_unique_filename($this->_plugin_dir . SB_DS . 'uploads', $uploader->getName());
            // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
            $result = $uploader->handleUpload($this->_plugin_dir . SB_DS . 'uploads', $img_filename);
            // To save the upload with a specified name, set the second parameter.
            // $result = $uploader->handleUpload('uploads/', md5(mt_rand()).'_'.$uploader->getName());
            // To return a name used for uploaded file you can use the following line.
            $qquuid = $_REQUEST['qquuid'];
            $result['uploadName'] = $uploader->getUploadName();
            $result['qquuid'] = $qquuid;
            if (!is_array($_SESSION['addon_files']) || !isset($_SESSION['addon_files']))
                $_SESSION['addon_files'] = array();

            $_SESSION['addon_files'][$qquuid] = $result['uploadName'];
            header("Content-Type: text/plain");
            echo json_encode($result);
            die();
        }
        elseif ($task == 'sb_wc_addon_delete_img') {
            $qquuid = $_POST['qquuid'];
            if (!isset($_SESSION['addon_files'][$qquuid]))
                die("qquuid not found $qquuid");
            $file = $this->_plugin_dir . SB_DS . 'uploads' . SB_DS . $_SESSION['addon_files'][$qquuid];
            unlink($file);
            unset($_SESSION['addon_files'][$qquuid]);
            die('deleted ');
        }
        elseif ($task == 'sb_wc_addon_add_fee') {
            $name = trim($_REQUEST['name']);
            $amount = (float) $_REQUEST['amount'];
            header('Content-type: application/json');
            if (empty($name) || empty($amount)) {
                die(json_encode(array('status' => 'error', 'error' => __('Invalid addon data', 'wupc'))));
            }
            $fee_id = str_replace('-', '_', sanitize_title($name));
            if (!isset($_SESSION['fees']))
                $_SESSION['fees'] = array();
            $_SESSION['fees'][$fee_id] = array('name' => $name, 'amount' => $amount);
            die(json_encode(array('status' => 'ok', 'fee_id' => $fee_id)));
        }
        elseif ($task == 'sb_wc_addon_remove_fee') {
            header('Content-type: application/json');
            $fee_id = $_REQUEST['fee_id'];
            if (!isset($_SESSION['fees']) || !is_array($_SESSION['fees']))
                die();
            if (isset($_SESSION['fees'][$fee_id]))
                unset($_SESSION['fees'][$fee_id]);
            die(json_encode(array('status' => 'ok')));
        }
    }

    public function action_woocommerce_product_data_panels() {
        global $post;


        require_once $this->_plugin_dir . SB_DS . 'html' . SB_DS . 'admin' . SB_DS . 'product-panels.php';
    }

    public function action_woocommerce_settings_upsell_settings() {
        $ops = get_option('upsell_settings', array());
        if (!is_array($ops))
            $ops = array();

        require_once $this->_plugin_dir . SB_DS . 'html' . SB_DS . 'admin' . SB_DS . 'settings.php';
    }

    public function action_save_settings() {
        $task = isset($_POST['task']) ? $_POST['task'] : null;
        if ($task == 'save_addons') {
            $addons = array();
            foreach ($_POST['ao'] as $_addon) {
                $addon = array_map('trim', $_addon);
                $addon['id'] = str_replace('-', '_', sanitize_title($addon['name']));
                $addons[] = $addon;
            }
            update_option('sb_wc_addons', $addons);
            //$url = admin_url('/admin.php?page=wc-settings&tab=upsell_settings&section=addons');
            //header('Location: ' . $url);die();
        }
        $ops = array_map('trim', $_POST['ops']);
        if (!isset($ops['show_thumb']))
            $ops['show_thumb'] = 'no';
        if (!isset($ops['hide_title']))
            $ops['hide_title'] = 'no';

        update_option('upsell_settings', $ops);
    }

    public function filter_woocommerce_product_data_tabs($tabs) {
        $tabs['upsell'] = array(
            'label' => __('Upsell', 'wupc'),
            'target' => 'upsell_data',
            'class' => array('hide_if_grouped'),
        );

        return $tabs;
    }

    public function action_save_post($post_id) {
        if (!isset($_POST['post_type']))
            return false;
        if ($_POST['post_type'] != 'product')
            return false;
        $ids = trim($_POST['upsell_products']);
        update_post_meta($post_id, '_upsell_products', $ids);
    }

    public function filter_woocommerce_settings_tabs_array($tabs) {
        $tabs['upsell_settings'] = __('Addons & Upsells', 'wupc');
        return $tabs;
    }

    public function filter_woocommerce_checkout_fields($checkout_fields) {
        /*
          $addons = get_option('sb_wc_addons', array());
          if( !is_array($addons) )
          $addons = array();
          if( empty($addons) )
          return $checkout_fields;
          $required = array();

          $this->checkout_fields['addons']	= array(
          'order_comments' => array(
          'type' => 'textarea',
          'class' => array('notes'),
          'label' => __( 'Order Notes', 'woocommerce' ),
          'placeholder' => _x('Notes about your order, e.g. special notes for delivery.', 'placeholder', 'woocommerce')
          )
          );
         */
        return $checkout_fields;
    }

    public function action_woocommerce_checkout_after_customer_details() {
        $ops = get_option('upsell_settings', array());
        $ops = is_array($ops) ? $ops : array();

        require_once $this->_plugin_dir . SB_DS . 'html' . SB_DS . 'frontend' . SB_DS . 'checkout-addons.php';
        require_once $this->_plugin_dir . SB_DS . 'html' . SB_DS . 'frontend' . SB_DS . 'checkout-upsell.php';
    }

    public function action_woocommerce_checkout_process() {
        WC()->checkout()->posted['addons'] = $_POST['addon'];
        //print_r(WC()->checkout()->posted['addons']);die();
    }

    public function action_woocommerce_after_checkout_validation($posted) {
        $addons = get_option('sb_wc_addons', array());
        if (!is_array($addons))
            $addons = array();
        //print_r($addons);die();
        if (count($addons) <= 0)
            return false;
        $in_cart = SB_WC_UpSellHelper::GetCartIds();

        foreach ($addons as $addon) {
            $skip = false;
            foreach (explode('|', $addon['pids']) as $_pid) {
                $pid = trim($_pid);
                if (in_array($pid, $in_cart)) {
                    $skip = true;
                }
            }
            if ($skip)
                continue;
            if (!isset($addon['required']) || (int) $addon['required'] != 1)
                continue;
            $fid = $addon['id'];
            if ($addon['type'] == 'text') {
                if (empty($posted['addons'][$fid])) {
                    wc_add_notice('<strong>' . stripslashes($addon['name']) . '</strong> ' . __('is a required field.', 'wupc'), 'error');
                    //break;
                }
            }
            if ($addon['type'] == 'checkbox' || $addon['type'] == 'radio') {
                if (!isset($posted['addons'][$fid]) || empty($posted['addons'][$fid])) {
                    wc_add_notice('<strong>' . stripslashes($addon['name']) . '</strong> ' . __('is a required field.', 'wupc'), 'error');
                    //break;
                }
            }
        }
        //print_r($posted);
        //error_log(print_r($posted, 1));
    }

    public function action_woocommerce_checkout_order_processed($order_id, $posted) {
        $addons = get_option('sb_wc_addons', array());
        if (!is_array($addons))
            $addons = array();
        if (count($addons) <= 0)
            return false;
        foreach ($addons as $addon) {
            $aid = $addon['id'];
            add_post_meta($order_id, '_addon_' . $aid, $posted['addons'][$aid]);
        }
        //##check for uploaded images
        if (isset($posted['addons']['images'])) {
            $images = array();
            foreach (explode(',', $posted['addons']['images']) as $qquuid) {
                if (isset($_SESSION['addon_files'][$qquuid])) {
                    $images[] = $_SESSION['addon_files'][$qquuid];
                    unset($_SESSION['addon_files'][$qquuid]);
                }
            }
            add_post_meta($order_id, '_addon_images', $images);
        }
        if (isset($_SESSION['fees']))
            unset($_SESSION['fees']);
    }

    public function action_admin_init() {
        add_meta_box('wc-addons', __('Add-Ons', 'wupc'), array($this, 'metabox_addons'), 'shop_order', 'advanced', 'high');
    }

    public function metabox_addons($post) {
        $addons = get_option('sb_wc_addons', array());
        if (!is_array($addons))
            $addons = array();
        $metas = get_post_meta($post->ID);
        //print_r($addons);
        ?>
        <table>
            <?php foreach ($metas as $meta_key => $meta): ?>
                <?php
                if (!strstr($meta_key, '_addon_'))
                    continue;
                $id = str_replace('_addon_', '', $meta_key);
                if ($id != 'images'):
                    $addon = SB_WC_UpSellHelper::GetAddonById($id);
                    if (!$addon)
                        continue;
                    ?>
                    <tr>
                        <th style="text-align:left;"><?php print stripslashes($addon['name']); ?>: </th>
                        <td>
                            <?php if ($addon['type'] == 'checkbox'): ?>
                                <?php print ($meta[0] == '1') ? 'Yes' : 'No'; ?>
                            <?php elseif ($addon['type'] == 'dropdown'): list($l, $v) = strstr($meta[0], '=') ? explode('=', $meta[0]) : array($meta[0], ''); ?>
                                <?php print $l; ?>
                            <?php elseif ($addon['type'] == 'multi-checkbox'): $ops = unserialize($meta[0]); ?>
                                <?php print implode(', ', $ops); ?>
                            <?php elseif ($addon['type'] == 'multiselect'): $data = unserialize($meta[0]); ?>
                                <?php print $data['ops']; ?>
                            <?php else: ?>
                                <?php print $meta[0]; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <th style="text-align:left;"><?php _e('Images'); ?></th>
                        <td>
                            <?php
                            $images = unserialize($meta[0]);
                            foreach ($images as $img):
                                ?>
                                <img src="<?php print $this->_plugin_url ?>/uploads/<?php print $img; ?>" alt="" style="width:70px;" />
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
        <?php
    }

    public function action_woocommerce_cart_calculate_fees(WC_Cart $cart) {
        if (!isset($_SESSION['fees']))
            return false;
        foreach ($_SESSION['fees'] as $fee) {
            $cart->add_fee($fee['name'], $fee['amount']);
        }
    }

}

$sb_wc_upsell = new SB_WC_UpSell();
$sb_wc_upsell->Init();
