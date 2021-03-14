<?php
/**
 * My Addresses
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="jet-woo-account-address-content">
	<?php
	$template = apply_filters( 'jet-woo-builder/current-template/template-id', jet_woo_builder_integration_woocommerce()->get_current_myaccount_address_template() );

	echo jet_woo_builder()->parser->get_template_content( $template );
	?>
</div>
