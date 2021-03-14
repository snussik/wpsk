<?php
/**
 * My Account page
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="jet-woo-builder-my-account-content">
	<?php
	$endpoint_enable = 'yes' === jet_woo_builder_shop_settings()->get( 'custom_myaccount_page_endpoints' );
	$template        = apply_filters( 'jet-woo-builder/current-template/template-id', jet_woo_builder_integration_woocommerce()->get_current_myaccount_template() );

	if ( ! $endpoint_enable ) {
		remove_action( 'woocommerce_account_content', 'woocommerce_account_content' );
		do_action( 'woocommerce_account_content' );
	}

	echo jet_woo_builder()->parser->get_template_content( $template );
	?>
</div>
