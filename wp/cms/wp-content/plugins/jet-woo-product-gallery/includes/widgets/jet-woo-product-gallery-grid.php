<?php
/**
 * Class: Jet_Woo_Product_Gallery_Grid
 * Name: Gallery Grid
 * Slug: jet-woo-product-gallery-grid
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Product_Gallery_Grid extends Jet_Woo_Product_Gallery_Base {

	public function get_name() {
		return 'jet-woo-product-gallery-grid';
	}

	public function get_title() {
		return esc_html__( 'Gallery Grid', 'jet-woo-product-gallery' );
	}

	public function get_script_depends() {
		return array( 'zoom', 'wc-single-product', 'mediaelement', 'photoswipe-ui-default', 'photoswipe' );
	}

	public function get_style_depends() {
		return array( 'mediaelement', 'photoswipe', 'photoswipe-default-skin' );
	}

	public function get_icon() {
		return 'jet-woo-product-gallery-icon-grid';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/product-gallery-grid-layout-how-to-showcase-product-images-within-the-grid-layout/';
	}

	public function get_categories() {
		return array( 'jet-woo-product-gallery' );
	}

	public function register_product_gallery_controls() {

		$this->start_controls_section(
			'section_product_images',
			array(
				'label'      => esc_html__( 'Images', 'jet-woo-product-gallery' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$this->add_control(
			'image_size',
			array(
				'label'   => esc_html__( 'Image Size', 'jet-woo-product-gallery' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => jet_woo_product_gallery_tools()->get_image_sizes(),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'   => esc_html__( 'Columns', 'jet-woo-product-gallery' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'desktop' => 4,
				'tablet'  => 3,
				'mobile'  => 2,
				'min'     => 1,
				'max'     => 6,
				'step'    => 1,
			)
		);

		$this->end_controls_section();

		$css_scheme = apply_filters(
			'jet-woo-product-gallery-grid/css-scheme',
			array(
				'row'     => '.jet-woo-product-gallery-grid',
				'columns' => '.jet-woo-product-gallery-grid .jet-woo-product-gallery__image-item',
				'images'  => '.jet-woo-product-gallery-grid .jet-woo-product-gallery__image',
			)
		);

		$this->register_controls_columns_style( $css_scheme );

		$this->register_controls_images_style( $css_scheme );

	}

	public function register_controls_columns_style( $css_scheme ) {

		$this->start_controls_section(
			'section_columns_style',
			array(
				'label'      => esc_html__( 'Columns', 'jet-woo-product-gallery' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'columns_padding',
			array(
				'label'      => esc_html__( 'Columns Gutter', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['columns'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['row']     => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right: -{{RIGHT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	public function register_controls_images_style( $css_scheme ) {

		$this->start_controls_section(
			'section_gallery_images_style',
			array(
				'label'      => esc_html__( 'Images', 'jet-woo-product-gallery' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'gallery_images_border',
				'label'       => esc_html__( 'Border', 'jet-woo-product-gallery' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['images'],
			)
		);

		$this->add_control(
			'gallery_images_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['images'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'gallery_images_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['images'],
			)
		);

		$this->add_control(
			'gallery_images_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['images'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'gallery_images_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['images'] . ':not(.jet-woo-product-gallery--with-video)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}


	protected function render() {

		global $post, $product, $_product;

		$settings = $this->get_settings();

		if ( ! empty( $settings['product_id'] ) ) {
			$_product = wc_get_product( $settings['product_id'] );
		} else {
			$_product = wc_get_product();
		}

		if ( ! empty( $_product ) ) {
			if ( 'variable' === $_product->get_type() ) {
				$variation_images = $this->get_variation_images_data( $post, $_product, $settings );

				$this->set_render_attribute(
					'gallery_variation_images_data',
					'data-variation-images',
					$variation_images
				);
			}

			$this->__context = 'render';

			$this->__open_wrap();
			include $this->__get_global_template( 'index' );
			$this->__close_wrap();
		} else {
			printf(
				'<div class="jet-woo-product-gallery__content">%s</div>',
				esc_html__( 'Not found product with current id', 'jet-woo-product-gallery' )
			);
		}

	}

}