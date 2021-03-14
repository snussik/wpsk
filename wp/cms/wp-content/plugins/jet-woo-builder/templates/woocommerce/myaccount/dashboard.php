<?php
/**
 * My Account Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="jet-woo-account-dashboard-content">
	<?php
	$template = apply_filters( 'jet-woo-builder/current-template/template-id', jet_woo_builder_integration_woocommerce()->get_current_myaccount_dashboard_template() );

	echo jet_woo_builder()->parser->get_template_content( $template );
	?>
</div>

<?php do_action( 'woocommerce_account_dashboard' );
