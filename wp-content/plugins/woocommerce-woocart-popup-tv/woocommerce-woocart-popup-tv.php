<?php
/*
 * Plugin Name: Woocart Popup TV for WooCommerce
 * Author: Trong Thang
 * Description:  Use this plugin to show lightbox popup cart for add to cart action.
 * Version: 1.0
 * Author URI: 
 * Text Domain: woocommerce-woocart-popup-tv
 */

/*
 * Created on : Jun 22, 2016, 5:31:39 PM
 * Author: Tran Trong Thang
 * Email: trantrongthang1207@gmail.com
 * Skype: trantrongthang1207
 */
if (!function_exists('tv_frontend_head')) {

    function tv_frontend_head() {
        wp_register_style('jquery.fancybox.css', plugin_dir_url(__FILE__) . 'assets/js/source/jquery.fancybox.css');
        wp_enqueue_style('jquery.fancybox.css');
        wp_register_style('tvstyle.css', plugin_dir_url(__FILE__) . 'assets/css/tvstyle.css');
        wp_enqueue_style('tvstyle.css');
        wp_register_script('jquery.fancybox.js', plugin_dir_url(__FILE__) . 'assets/js/source/jquery.fancybox.js');
        wp_enqueue_script('jquery.fancybox.js');
        wp_register_script('tvscript.js', plugin_dir_url(__FILE__) . 'assets/js/tvscript.js');
        wp_enqueue_script('tvscript.js');
    }

}
add_action('wp_enqueue_scripts', 'tv_frontend_head');

add_filter('add_to_cart_fragments', 'woocommerce_popupcart_fragment');

function woocommerce_popupcart_fragment($fragments) {

    $selectorName = 'div.tv-cart-popup-products-content';

    ob_start();

    include 'popup_content.phtml';

    $content = ob_get_clean();

    $cssSelectors[$selectorName] = $content;

    return $cssSelectors;
}

if (!is_admin()) {
    add_action('wp_footer', 'wp_head_custom_cart');
}

function wp_head_custom_cart() {
    ?>
    <div style="display: none">
        <div id="tvcart_popup">
            <div class="tv-cart-popup-products-content">
                <?php
                include 'popup_content.phtml';
                ?>
            </div>
        </div>
    </div>
    <?php
}

add_filter('woocommerce_after_add_to_cart_button', 'addfieldproductid');

function addfieldproductid() {
    global $product;
    ?>
    <input type="hidden" name="product_id" value="<?php echo absint($product->id); ?>" />
    <?php
}
