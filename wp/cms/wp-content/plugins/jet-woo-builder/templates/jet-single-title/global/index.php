<?php
/**
 * Product title template
 */
$settings = $this->get_settings();

$title = jet_woo_builder_template_functions()->get_product_title();
$title = jet_woo_builder_tools()->trim_text(
	$title,
	isset( $settings['title_length'] ) ? $settings['title_length'] : 1,
	$settings['title_trim_type'],
	'...'
);

echo '<h1 class="product_title entry-title">' . $title . '</h1>';
