<?php
/**
 * Thank you page
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="jet-woo-builder-woocommerce-thankyou woocommerce-order">
	<?php if ( $order ) :
		do_action( 'woocommerce_before_thankyou', $order->get_id() );

		$template = apply_filters( 'jet-woo-builder/current-template/template-id', jet_woo_builder_integration_woocommerce()->get_current_thankyou_template() );

		echo jet_woo_builder()->parser->get_template_content( $template );
	else : ?>
		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<?php endif; ?>
</div>
