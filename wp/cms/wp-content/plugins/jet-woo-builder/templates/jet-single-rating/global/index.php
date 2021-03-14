<?php
/**
 * Single rating template
 */

$settings = $this->get_settings();

global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
	return;
}

if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();

if ( ! isset( $settings['rating_icon'] ) ) {
	$settings['rating_icon'] = 'jetwoo-front-icon-rating-1';
}

$empty_single_rating = isset( $settings['show_single_empty_rating'] ) && 'true' === $settings['show_single_empty_rating'];
$rating              = jet_woo_builder_template_functions()->get_product_custom_rating( $settings['rating_icon'], $empty_single_rating );

if ( $rating_count > 0 || $empty_single_rating ) : ?>
	<div class="woocommerce-product-rating">
		<?php echo $rating; ?>
		<?php if ( comments_open() ) : ?>
			<a href="#reviews" class="woocommerce-review-link" rel="nofollow">
				(<?php printf( _n( '%s customer review', '%s customer reviews', $review_count, 'jet-woo-builder' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>
				)
			</a>
		<?php endif ?>
	</div>
<?php endif; ?>