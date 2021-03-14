<?php
/**
 * Astra integration
 */

add_action( 'jet-smart-filters/providers/woocommerce-archive/before-ajax-content', 'jet_woo_astra_compatibility', 1 );

if ( 'yes' === jet_woo_builder_shop_settings()->get( 'custom_checkout_page' ) ) {
	add_filter( 'astra_woo_shop_product_structure_override', 'jet_woo_astra_structure_override', 1 );
}

/**
 * Astra theme compatibility
 */
function jet_woo_astra_compatibility() {

	if ( class_exists( 'Astra_Woocommerce' ) ) {
		$astra = new Astra_Woocommerce();

		if ( ! apply_filters( 'astra_woo_shop_product_structure_override', false ) ) {
			$astra->shop_customization();
		}

		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	}

}

/**
 * Returns status of Astra theme structure override
 *
 * @param $override
 *
 * @return bool
 */
function jet_woo_astra_structure_override( $override ) {

	$override = true;

	return $override;

}