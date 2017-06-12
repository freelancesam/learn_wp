<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post,$product;
$id = $post->ID;
$queried_post = get_page_by_path('the-fix',OBJECT,'page');

?>
	<div class="counter-wrapper">
        <div class="conter-border">
        <div class="discount-box-clock"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/clock_offer_new_pink.png"></div>
        <div class="thefix-heading"><h4>Closing In</h4></div>
        <div id="countdowntimer" class="counter-content"><div id="future_date"></div></div>
        </div>
    </div>
    <?php 
	$endtimestamp = get_post_meta($product->id, '_sale_price_dates_to', true);
	$end_timestamp_offset = $endtimestamp + 13*60*60;// new york 8:00 am +1*60*60 ???
	$enddate = date("Y/m/d H:i:s", $end_timestamp_offset); 
	?>
    <script type="text/javascript">
jQuery(function(){
	jQuery("#future_date").countdowntimer({
        dateAndTime : "<?php echo $enddate; ?>",
		size: "lg",
		regexpMatchFormat : "([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
     // 	regexpReplaceWith : "$1  <span></span>, $2:$3:$4",
      	regexpReplaceWith : function($1,$2,$3,$4){
            var cl;
            var s = $1.split(':');
            if(s[0] == '01'){
                cl =s[0]+"<span> day</span>, "+ s[1]+':'+s[2]+':'+s[3];
            }else{
                cl = s[0]+"<span> days</span>, "+ s[1]+':'+s[2]+':'+s[3];
            }
            return cl;
        },
		expiryUrl : "<?php echo get_permalink($queried_post->ID);?>"
	});
});
</script>
<h1 itemprop="name" class="No27-32-700-gray product-page-title"><?php echo $product->post->post_title; ?> </h1>
<?php echo apply_filters( 'woocommerce_short_description', $product->post->post_excerpt ) ?>
