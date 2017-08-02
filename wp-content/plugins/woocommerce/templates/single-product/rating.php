<?php
/**
 * Single Product Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
<<<<<<< HEAD
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
=======
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.3.2
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

<<<<<<< HEAD
if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
=======
if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();

if ( $rating_count > 0 ) : ?>

	<div class="woocommerce-product-rating">
<<<<<<< HEAD
		<?php echo wc_get_rating_html( $average, $rating_count ); ?>
=======
		<div class="star-rating">
			<span style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%">
				<?php
				/* translators: 1: average rating 2: max rating (i.e. 5) */
				printf(
					__( '%1$s out of %2$s', 'woocommerce' ),
					'<strong class="rating">' . esc_html( $average ) . '</strong>',
					'<span>5</span>'
				);
				?>
				<?php
				/* translators: %s: rating count */
				printf(
					_n( 'based on %s customer rating', 'based on %s customer ratings', $rating_count, 'woocommerce' ),
					'<span class="rating">' . esc_html( $rating_count ) . '</span>'
				);
				?>
			</span>
		</div>
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		<?php if ( comments_open() ) : ?><a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s customer review', '%s customer reviews', $review_count, 'woocommerce' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>)</a><?php endif ?>
	</div>

<?php endif; ?>
