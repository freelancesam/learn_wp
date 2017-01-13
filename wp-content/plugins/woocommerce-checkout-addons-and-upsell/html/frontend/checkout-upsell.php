<?php
$upsells = array();
$ids = SB_WC_UpSellHelper::GetCartIds();
foreach($ids as $pid)
{
	$upsell = get_post_meta($pid, '_upsell_products', 1);
	if( empty($upsell) ) continue;
	$upsells = array_merge($upsells, array_map('trim', explode(',', $upsell)));
	
}
//print_r($upsells);
//get products
?>
<?php if( count($upsells) ): ?>
<h3><?php print $ops['header_title']; ?></h3>
<div id="checkout-upsells">
<?php
$args = array('posts_per_page' => $ops['num_prods'], 
				'post_type' => 'product', 
				'post__in' => $upsells, 
				'orderby' => 'rand'
);
$prods = query_posts($args);
?>

	<?php while( have_posts() ): the_post(); ?>
	<div class="upsell">
		<?php
		$cb_id = 'up-sell-'.get_the_ID();
		if( $ops['show_thumb'] == 'yes' )
		{
			$size = 'shop_catalog';
			if ( has_post_thumbnail() )
				print get_the_post_thumbnail( $post->ID, $size );
			elseif ( wc_placeholder_img_src() )
			print wc_placeholder_img( $size );
		} 
		?>
		<?php print ($ops['hide_title'] != 'yes') ? get_the_title() : ''; ?>
		<p>
			<input type="checkbox" name="<?php print $cb_id; ?>" value="<?php the_ID(); ?>" class="add-upsell-cb" />
			<span for="<?php print $cb_id; ?>"><?php _e('Add to cart'); ?></span>
		</p>
	</div>
	<?php endwhile; wp_reset_query(); ?>
	<style>
	#checkout-upsells{clear:both;overflow:hidden;}
	#checkout-upsells .upsell{float:left;width:160px;margin:0 10px 10px 0;}
	</style>
	<script>
	var sb_wc_upsell = 
	{
		add_item: function(product_id)
		{
			var cb = jQuery(this);
			var params = 'task=sb_wc_upsell-add_to_cart&product_id=' + product_id;
			jQuery.post('index.php', params, function(res)
			{
				jQuery(cb).attr('data-item_key', res.item_key);
				jQuery('body').trigger('update_checkout');
			});
		},
		remove_item: function(key)
		{
			var cb = jQuery(this); 
			var params = 'task=sb_wc_upsell-remove_from_cart&item_key=' + key;
			jQuery.post('index.php', params, function(res)
			{
				cb.attr('data-item_key', null);
				jQuery('body').trigger('update_checkout');
			});
		}
	};
	jQuery(function()
	{
		jQuery('.add-upsell-cb').click(function()
		{
			var cb = this;
			if( jQuery(cb).is(':checked') )
			{
				sb_wc_upsell.add_item.call(cb, this.value);	
			}
			else
			{
				//##remove product from cart
				sb_wc_upsell.remove_item.call(cb, jQuery(this).attr('data-item_key'));
			}
		});
	});
	</script>
</div><!-- end id="checkout-upsells" -->
<?php endif; ?>