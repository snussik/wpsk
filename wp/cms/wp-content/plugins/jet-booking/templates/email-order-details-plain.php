<?php
/**
 * WooCommerce order details
 */
?>
<?php _e( 'Booking Details', 'jet-appointments-booking' ); ?>
<?php
	foreach ( $details as $item ) {
		?>
		- <?php

			if ( ! empty( $item['key'] ) ) {
				echo $item['key'] . ': ';
			}

			if ( ! empty( $item['is_html'] ) ) {
				echo $item['display_plain'];
			} else {
				echo $item['display'];
			}

		?>
		<?php
	}
?>
