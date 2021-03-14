<?php
/**
 * Class: Jet_Woo_Builder_MyAccount_Content
 * Name: My Account Content
 * Slug: jet-myaccount-content
 */

namespace Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Builder_MyAccount_Content extends Jet_Woo_Builder_Base {

	public function get_name() {
		return 'jet-myaccount-content';
	}

	public function get_title() {
		return esc_html__( 'My Account Content', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-my-account-content';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetwoobuilder-how-to-create-my-account-page-template/';
	}

	public function get_categories() {
		return array( 'jet-woo-builder' );
	}

	public function show_in_panel() {
		return jet_woo_builder()->documents->is_document_type( 'myaccount' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'my_account_content',
			[
				'label' => esc_html__( 'Content', 'jet-woo-builder' ),
			]
		);

		$this->add_control(
			'my_account_content_info',
			[
				'raw'             => esc_html__( 'Use this widget for display My Account Page Endpoints.', 'jet-woo-builder' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$this->__context = 'render';

		$this->__open_wrap();

		do_action( 'woocommerce_account_content' );

		$this->__close_wrap();

	}
}
