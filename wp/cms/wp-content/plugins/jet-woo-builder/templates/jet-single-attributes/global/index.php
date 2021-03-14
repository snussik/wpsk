<?php
/**
 * Product attributes tab
 */
global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
	return;
}

ob_start();
wc_display_product_attributes( $product );

$content = ob_get_clean();

if ( empty( $content ) ) {
	return;
}

$settings = $this->get_settings();

if ( ! empty( $settings['block_title'] ) ) {
	printf(
		'<%1$s class="jet-single-attrs__title">%2$s</%1$s>',
		$settings['block_title_tag'],
		esc_html__( $settings['block_title'], 'jet-woo-builder' )
	);
}

echo $content;
