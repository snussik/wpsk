<?php

$f1_label = ! empty( $args['first_field_label'] ) ? wp_kses_post( $args['first_field_label'] ) : '';
$f1_placeholder = ! empty( $args['first_field_placeholder'] ) ? esc_attr( $args['first_field_placeholder'] ) : '';

$f2_label = ! empty( $args['second_field_label'] ) ? wp_kses_post( $args['second_field_label'] ) : '';
$f2_placeholder = ! empty( $args['second_field_placeholder'] ) ? esc_attr( $args['second_field_placeholder'] ) : '';

$default = ! empty( $args['default'] ) ? esc_attr( $args['default'] ) : '';

$f1_default = '';
$f2_default = '';

if ( ! empty( $default ) ) {
	$default_values = explode( ' - ', $default );
	$f1_default = isset( $default_values[0] ) ? $default_values[0] : '';
	$f2_default = isset( $default_values[1] ) ? $default_values[1] : '';
}

if ( jet_abaf()->engine_plugin->default ) {
	$date_format = jet_abaf()->engine_plugin->default['date_format'];

	$f1_default = isset( jet_abaf()->engine_plugin->default['checkin'] ) ? jet_abaf()->engine_plugin->default['checkin'] : date( $date_format ) ;
	$f2_default = isset( jet_abaf()->engine_plugin->default['checkout'] ) ? jet_abaf()->engine_plugin->default['checkout'] : date( $date_format ) ;
	$default    = $f1_default . ' - ' . $f2_default;
}

$col_class = 'jet-abaf-separate-field';

if ( ! empty( $args['cio_fields_position'] ) && 'list' === $args['cio_fields_position'] ) {
	$col_class .= ' jet-form-col-12';
} else {
	$col_class .= ' jet-form-col-6';
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
<div class="jet-abaf-separate-fields">
	<div class="<?php echo $col_class; ?>">
		<?php if ( $f1_label ) { ?>
		<div class="jet-abaf-separate-field__label jet-form__label"><?php
			echo $f1_label;
			if ( ! empty( $args['required'] ) ) {
				echo '<span class="jet-form__required">*</span>';
			}
		?></div>
		<?php } ?>
		<div class="jet-abaf-separate-field__control">
			<input
				type="text"
				id="jet_abaf_field_1"
				class="jet-abaf-field__input jet-form__field"
				placeholder="<?php echo $f1_placeholder; ?>"
				autocomplete="off"
				name="<?php echo $args['name']; ?>__in"
				<?php if ( ! empty( $args['required'] ) ) {
					echo 'required';
				} ?>
				value="<?php echo $f1_default; ?>"
				readonly
			>
		</div>
	</div>
	<div class="<?php echo $col_class; ?>">
		<?php if ( $f2_label ) { ?>
		<div class="jet-abaf-separate-field__label jet-form__label"><?php
			echo $f2_label;
			if ( ! empty( $args['required'] ) ) {
				echo '<span class="jet-form__required">*</span>';
			}
		?></div>
		<?php } ?>
		<div class="jet-abaf-separate-field__control">
			<input
				type="text"
				id="jet_abaf_field_2"
				class="jet-abaf-field__input jet-form__field"
				placeholder="<?php echo $f2_placeholder; ?>"
				autocomplete="off"
				name="<?php echo $args['name']; ?>__out"
				<?php if ( ! empty( $args['required'] ) ) {
					echo 'required';
				} ?>
				value="<?php echo $f2_default; ?>"
				readonly
			>
		</div>
	</div>
	<input
		type="hidden"
		id="jet_abaf_field_range"
		name="<?php echo $args['name']; ?>"
		data-field="checkin-checkout"
		data-format="<?php echo $field_format; ?>"
		class="jet-form__field"
		value="<?php echo $default; ?>"
	>
</div>
<?php jet_abaf()->engine_plugin->ensure_ajax_js(); ?>
