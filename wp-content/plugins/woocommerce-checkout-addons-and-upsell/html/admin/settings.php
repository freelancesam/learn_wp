<?php
	$def_ops = array(
		'header_title' => __('You might also be interested in.'),
		'num_prods'		=> 4,
		'show_thumb'	=> 'yes',
		'thumb_width'	=> 150,
		'thumb_height'	=> 150,
		'hide_title'	=> 'no'
	);
$ops = array_merge($def_ops, $ops);
$section = isset($_GET['section']) ? $_GET['section'] : 'settings';
?>
<ul class="subsubsub">
	<li>
		<a class="<?php print $section == 'settings' ? 'current' : ''; ?>" href="<?php print admin_url('/admin.php?page=wc-settings&tab=upsell_settings&section=settings'); ?>"><?php _e('Upsell Settings' , 'wupc'); ?></a>
		|
	</li>
	<li>
		<a class="<?php print $section == 'addons' ? 'current' : ''; ?>" href="<?php print admin_url('/admin.php?page=wc-settings&tab=upsell_settings&section=addons'); ?>"><?php _e('Add-Ons' , 'wupc'); ?></a>
	</li>
</p>

<?php if( $section == 'settings' ): ?>
<div style="float:left;width:65%;">
	<div>
		<label><?php _e('Header Title' , 'wupc'); ?></label><br/>
		<input type="text" style="width:100%;" name="ops[header_title]" value="<?php print @$ops['header_title']; ?>" />
	</div>
	<div>
		<label><?php _e('Num. of products to show:' , 'wupc'); ?></label>
		<input type="number" name="ops[num_prods]" value="<?php print $ops['num_prods']; ?>" />
	</div>
	<div>
		<label><?php _e('Show thumbnail:' , 'wupc'); ?></label>
		<input type="checkbox" name="ops[show_thumb]" value="yes" <?php print $ops['show_thumb'] == 'yes' ? 'checked' : ''; ?> />
	</div>
	<div>
		<label><?php _e('Thumb size in px:'); ?></label>
		<input type="text" name="ops[thumb_width]" value="<?php print $ops['thumb_width']; ?>" /> x
		<input type="text" name="ops[thumb_height]" value="<?php print $ops['thumb_height']; ?>" /> 
	</div>
	<div>
		<label><?php _e('Hide product title:' , 'wupc'); ?></label>
		<input type="checkbox" name="ops[hide_title]" value="yes" <?php print $ops['hide_title'] == 'yes' ? 'checked' : ''; ?> />
	</div>
</div>
<div style="float:right;width:34%;">
	
</div>
<?php elseif( $section == 'addons' ): ?>
	<?php
	$addons = get_option('sb_wc_addons', array());
	if( !is_array($addons) )
		$addons = array();
	include_once $this->_plugin_dir .SB_DS . 'html' . SB_DS . 'admin' . SB_DS . 'add-ons-editor.php'; 
	?>
<?php endif; ?>
<div style="clear:both;width:100%;"></div>