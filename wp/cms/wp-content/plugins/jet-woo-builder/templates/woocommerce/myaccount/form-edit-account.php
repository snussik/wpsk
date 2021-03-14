<?php
/**
 * Edit account form
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<div class="jet-woo-account-account-content">
	<?php
	$template = apply_filters( 'jet-woo-builder/current-template/template-id', jet_woo_builder_integration_woocommerce()->get_current_myaccount_account_template() );

	echo jet_woo_builder()->parser->get_template_content( $template );
	?>
</div>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
