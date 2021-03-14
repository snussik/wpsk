<?php
/**
 * Render check-in checkout fields for bookin form
 */

$placeholder = ! empty( $args['first_field_placeholder'] ) ? esc_attr( $args['first_field_placeholder'] ) : '';
$default     = ! empty( $args['default'] ) ? esc_attr( $args['default'] ) : '';

if ( jet_abaf()->engine_plugin->default ) {
	$date_format = jet_abaf()->engine_plugin->default['date_format'];

	$checkin = isset( jet_abaf()->engine_plugin->default['checkin'] ) ? jet_abaf()->engine_plugin->default['checkin'] : date( $date_format ) ;
	$checkout = isset( jet_abaf()->engine_plugin->default['checkout'] ) ? jet_abaf()->engine_plugin->default['checkout'] : date( $date_format ) ;

	$default = sprintf( '%1$s - %2$s', $checkin, $checkout );
}

$field_format    = ! empty( $args['cio_fields_format'] ) ? esc_attr( $args['cio_fields_format'] ) : 'YYYY-MM-DD';
$field_separator = ! empty( $args['cio_fields_separator'] ) ? esc_attr( $args['cio_fields_separator'] ) : '';

if ( $field_separator ) {

	if ( 'space' === $field_separator ) {
		$field_separator = ' ';
	}

	$field_format = str_replace( '-', $field_separator, $field_format );

}

?>
<div class="jet-abaf-field">
	<input
		type="text"
		id="jet_abaf_field"
		class="jet-abaf-field__input jet-form__field"
		placeholder="<?php echo $placeholder; ?>"
		autocomplete="off"
		data-field="checkin-checkout"
		data-format="<?php echo $field_format; ?>"
		name="<?php echo $args['name']; ?>"
		<?php if ( ! empty( $args['required'] ) ) {
			echo 'required';
		} ?>
		value="<?php echo $default; ?>"
		readonly
	>
</div>
<?php jet_abaf()->engine_plugin->ensure_ajax_js(); ?>
