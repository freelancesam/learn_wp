<?php
$sym = get_woocommerce_currency_symbol();
?>
<div id="upsell_data" class="panel woocommerce_options_panel sb-wc-cp-panel">
	<h2><?php _e('Checkout Upsell Products' , 'wupc'); ?></h2>
	<div class="options_group">
		<div class="form-field">
			<label><?php _e('Products:' , 'wupc'); ?></label>
			<input type="text" name="upsell_products" value="<?php print get_post_meta($post->ID, '_upsell_products', 1); ?>" class="input-tags" />
		</div>
	</div>
</div>
<script src="<?php print $this->_plugin_url; ?>/js/jquery.tagsinput.js"></script>
<script>
//<![CDATA[
var uc_url = '<?php print admin_url('/index.php?task=uc_prod');?>';
if( !document.getElementById('tags-input-css') )
{
	var css = document.createElement('link');
	css.id = 'tags-input-css';
	css.rel = 'stylesheet';
	css.href = '<?php print $this->_plugin_url; ?>/css/jquery.tagsinput.css';
	document.head.appendChild(css);
}
jQuery(function()
{
	jQuery('.input-tags').tagsInput({'defaultText': '<?php print _e(''); ?>', 'autocomplete_url': uc_url});
});
//]]>
</script>