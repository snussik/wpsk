<?php
/**
 * Sale badge template
 */

global $post, $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
	return;
}

$badge_text = jet_woo_builder()->macros->do_macros( $this->get_settings( 'single_badge_text' ) );

if ( $product->is_on_sale() ) {
	echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( $badge_text, 'jet-woo-builder' ) . '</span>', $post, $product );
}
