<?php
/**
 * The template to display the reviewers star rating in reviews
 *
<<<<<<< HEAD
 * This template can be overridden by copying it to yourtheme/woocommerce/review-rating.php.
=======
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
<<<<<<< HEAD
 * @version 3.1.0
=======
 * @version 3.0.0
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $comment;
$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );

<<<<<<< HEAD
if ( $rating && 'yes' === get_option( 'woocommerce_enable_review_rating' ) ) {
	echo wc_get_rating_html( $rating );
}
=======
if ( $rating && get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) { ?>

	<div class="star-rating">
		<span style="width:<?php echo ( esc_attr( $rating ) / 5 ) * 100; ?>%"><?php
			/* translators: %s: rating */
			printf( esc_html__( '%s out of 5', 'woocommerce' ), '<strong>' . $rating . '</strong>' );
		?></span>
	</div>

<?php }
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
