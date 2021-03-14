<?php
/**
 * Class: Jet_Woo_Builder_Cart_Return_To_Shop
 * Name: Cart Return To Shop
 * Slug: jet-cart-return-to-shop
 */

namespace Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Builder_Cart_Return_To_Shop extends Jet_Woo_Builder_Base {

	public function get_name() {
		return 'jet-cart-return-to-shop';
	}

	public function get_title() {
		return esc_html__( 'Cart Return To Shop', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-cart-return-to-shop';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetwoobuilder-how-to-create-a-cart-page-template/';
	}

	public function get_categories() {
		return array( 'jet-woo-builder' );
	}

	public function show_in_panel() {
		return jet_woo_builder()->documents->is_document_type( 'cart' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-woo-builder/jet-cart-return-to-shop/css-scheme',
			array(
				'button' => '.return-to-shop .button',
			)
		);

		$this->start_controls_section(
			'cart_return_to_shop_button_content',
			array(
				'label' => esc_html__( 'Content', 'jet-woo-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'cart_return_to_shop_button_text',
			array(
				'label'       => esc_html__( 'Custom Button Text', 'jet-woo-builder' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Custom text', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'cart_return_to_shop_button_link',
			array(
				'label'       => esc_html__( 'Custom Button Link', 'jet-woo-builder' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Custom link', 'jet-woo-builder' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'return_to_shop_button_styles',
			array(
				'label' => esc_html__( 'Styles', 'jet-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		jet_woo_builder_common_controls()->register_button_style_controls( $this, 'return_to_shop', $css_scheme['button'] );

		$this->add_responsive_control(
			'return_to_shop_button_align',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => jet_woo_builder_tools()->get_available_h_align_types(),
				'selectors' => array(
					'{{WRAPPER}} .return-to-shop' => 'text-align: {{VALUE}}',
				),
				'separator' => 'before',
				'classes'   => 'elementor-control-align',
			)
		);

		$this->end_controls_section();

	}

	protected function render() {

		$this->__context = 'render';

		$this->__open_wrap();

		include $this->__get_global_template( 'index' );

		$this->__close_wrap();

	}
}
