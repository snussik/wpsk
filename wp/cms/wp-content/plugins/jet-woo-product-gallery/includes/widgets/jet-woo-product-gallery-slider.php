<?php
/**
 * Class: Jet_Woo_Product_Gallery_Slider
 * Name: Gallery Slider
 * Slug: jet-woo-product-gallery-slider
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Product_Gallery_Slider extends Jet_Woo_Product_Gallery_Base {

	public function get_name() {
		return 'jet-woo-product-gallery-slider';
	}

	public function get_title() {
		return esc_html__( 'Gallery Slider', 'jet-woo-product-gallery' );
	}

	public function get_script_depends() {
		return [ 'swiper', 'zoom', 'wc-single-product', 'mediaelement', 'photoswipe-ui-default', 'photoswipe' ];
	}

	public function get_style_depends() {
		return array( 'mediaelement', 'photoswipe', 'photoswipe-default-skin' );
	}

	public function get_icon() {
		return 'jet-woo-product-gallery-icon-slider';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-create-a-horizontal-product-images-slider-with-jetproductgallery/';
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

		$this->add_control(
			'thumbs_image_size',
			array(
				'label'   => esc_html__( 'Thumbnails Image Size', 'jet-woo-product-gallery' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => jet_woo_product_gallery_tools()->get_image_sizes(),
			)
		);

		$this->end_controls_section();

		$css_scheme = apply_filters(
			'jet-woo-product-gallery-slider/css-scheme',
			array(
				'slider'                 => '.jet-woo-product-gallery-slider',
				'slider-item'            => '.jet-woo-product-gallery-slider .jet-woo-product-gallery__image-item',
				'images'                 => '.jet-woo-product-gallery-slider .jet-woo-product-gallery__image',
				'images_wrapper'         => '.jet-woo-product-gallery-slider .jet-woo-product-gallery__image',
				'images-arrows'          => '.jet-woo-swiper .jet-swiper-nav',
				'pagination'             => '.swiper-pagination',
				'pagination-items'       => '.swiper-pagination .swiper-pagination-bullet',
				'pagination-active-item' => '.swiper-pagination .swiper-pagination-bullet-active',
				'thumbnails-wrapper'     => '.jet-woo-swiper-gallery-thumbs',
				'thumbnails-list'        => '.jet-woo-swiper-gallery-thumbs .swiper-wrapper',
				'thumbnails-list-slide'  => '.jet-woo-swiper-gallery-thumbs .swiper-wrapper .swiper-slide',
				'thumbnails'             => '.jet-woo-swiper-control-thumbs__item-image',
				'thumbnails-arrows'      => '.jet-woo-swiper-gallery-thumbs .jet-swiper-nav',
			)
		);

		$this->register_controls_slider( $css_scheme );

		$this->register_controls_images_styles( $css_scheme );

		$this->register_controls_thumbnails_styles( $css_scheme );

		$this->register_controls_pagination_styles( $css_scheme );

	}

	public function register_controls_slider( $css_scheme ) {

		$this->start_controls_section(
			'section_slider_style',
			array(
				'label'      => esc_html__( 'Slider', 'jet-woo-product-gallery' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$this->add_control(
			'slider_enable_infinite_loop',
			array(
				'label'        => esc_html__( 'Infinite Loop', 'jet-woo-product-gallery' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-woo-product-gallery' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-product-gallery' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'enable_gallery!' => 'yes',
				),
			)
		);

		$this->add_control(
			'slider_equal_slides_height',
			array(
				'label'        => esc_html__( 'Equal Slides Height', 'jet-woo-product-gallery' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-woo-product-gallery' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-product-gallery' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'slider_sensitivity',
			array(
				'label'   => esc_html__( 'Slider Sensitivity', 'jet-woo-product-gallery' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 1,
				'step'    => 0.1,
				'default' => 0.8,
			)
		);

		$this->add_control(
			'slider_enable_center_mode',
			array(
				'label'        => esc_html__( 'Enable Center Mode', 'jet-woo-product-gallery' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-woo-product-gallery' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-product-gallery' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_responsive_control(
			'slider_center_mode_slides',
			array(
				'label'     => esc_html__( 'Slides to Show', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 4,
				'options'   => array(
					2  => 2,
					3  => 3,
					4  => 4,
					5  => 5,
					6  => 6,
					7  => 7,
					8  => 8,
					9  => 9,
					10 => 10,
				),
				'condition' => array(
					'slider_enable_center_mode' => 'yes',
				),
			)
		);

		$this->add_control(
			'slider_center_mode_padding',
			array(
				'label'     => esc_html__( 'Space Between', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'default'   => 10,
				'condition' => array(
					'slider_enable_center_mode' => 'yes',
				),
			)
		);

		$this->add_control(
			'slider_nav_heading',
			array(
				'label'     => esc_html__( 'Navigation', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'slider_show_nav',
			array(
				'label'        => esc_html__( 'Show Navigation', 'jet-woo-product-gallery' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-woo-product-gallery' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-product-gallery' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->__add_advanced_icon_control(
			'slider_nav_arrow_prev',
			array(
				'label'       => esc_html__( 'Arrow Previous', 'jet-woo-product-gallery' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => ! is_rtl() ? 'fa fa-angle-left' : 'fa fa-angle-right',
				'fa5_default' => array(
					'value'   => ! is_rtl() ? 'fas fa-angle-left' : 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'slider_show_nav' => 'yes',
				),
			)
		);

		$this->__add_advanced_icon_control(
			'slider_nav_arrow_next',
			array(
				'label'       => esc_html__( 'Arrow next', 'jet-woo-product-gallery' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => ! is_rtl() ? 'fa fa-angle-right' : 'fa fa-angle-left',
				'fa5_default' => array(
					'value'   => ! is_rtl() ? 'fas fa-angle-right' : 'fas fa-angle-left',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'slider_show_nav' => 'yes',
				),
			)
		);

		$this->add_control(
			'slider_pagination_heading',
			array(
				'label'     => esc_html__( 'Pagination', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'slider_show_pagination',
			array(
				'label'        => esc_html__( 'Show Pagination', 'jet-woo-product-gallery' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-woo-product-gallery' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-product-gallery' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'slider_pagination_type',
			array(
				'label'     => esc_html__( 'Type :', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'bullets',
				'options'   => array(
					'bullets'    => array(
						'title' => esc_html__( 'Bullets', 'jet-woo-product-gallery' ),
						'icon'  => 'fa fa-ellipsis-h',
					),
					'thumbnails' => array(
						'title' => esc_html__( 'Thumbnails', 'jet-woo-product-gallery' ),
						'icon'  => 'fa fa-image',
					),
				),
				'condition' => array(
					'slider_show_pagination' => 'yes',
				),
				'toggle'    => true,
			)
		);

		$this->add_control(
			'slider_pagination_direction',
			array(
				'label'     => esc_html__( 'Direction:', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'horizontal',
				'options'   => array(
					'vertical'   => esc_html__( 'Vertical', 'jet-woo-product-gallery' ),
					'horizontal' => esc_html__( 'Horizontal', 'jet-woo-product-gallery' ),
				),
				'condition' => array(
					'slider_show_pagination' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'vertical_slider_height',
			array(
				'label'       => esc_html__( 'Carousel Height', 'jet-woo-product-gallery' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '550',
				'render_type' => 'template',
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_scheme['slider']             => 'max-height: {{VALUE}}px;',
					'{{WRAPPER}} ' . $css_scheme['thumbnails-wrapper'] => 'max-height: {{VALUE}}px;',
				),
				'condition'   => array(
					'slider_show_pagination'      => 'yes',
					'slider_pagination_direction' => 'vertical',
				),
			)
		);

		$this->add_control(
			'slider_pagination_v_position',
			array(
				'label'     => esc_html__( 'Position :', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'options'   => array(
					'left'  => array(
						'title' => esc_html__( 'Start', 'jet-woo-product-gallery' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'right' => array(
						'title' => esc_html__( 'End', 'jet-woo-product-gallery' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
				),
				'condition' => array(
					'slider_show_pagination'      => 'yes',
					'slider_pagination_direction' => 'vertical',
				),
			)
		);

		$this->add_control(
			'slider_pagination_h_position',
			array(
				'label'     => esc_html__( 'Position :', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'bottom',
				'options'   => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'jet-woo-product-gallery' ),
						'icon'  => 'eicon-v-align-top',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'jet-woo-product-gallery' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'condition' => array(
					'slider_show_pagination'      => 'yes',
					'slider_pagination_direction' => 'horizontal',
				),
			)
		);

		$this->add_control(
			'slider_pagination_thumbnails_heading',
			array(
				'label'     => esc_html__( 'Thumbnails', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->__add_advanced_icon_control(
			'pagination_thumbnails_slider_arrow_prev',
			array(
				'label'       => esc_html__( 'Arrow Previous', 'jet-woo-product-gallery' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => ! is_rtl() ? 'fa fa-angle-left' : 'fa fa-angle-right',
				'fa5_default' => array(
					'value'   => ! is_rtl() ? 'fas fa-angle-left' : 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->__add_advanced_icon_control(
			'pagination_thumbnails_slider_arrow_next',
			array(
				'label'       => esc_html__( 'Arrow next', 'jet-woo-product-gallery' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => ! is_rtl() ? 'fa fa-angle-right' : 'fa fa-angle-left',
				'fa5_default' => array(
					'value'   => ! is_rtl() ? 'fas fa-angle-right' : 'fas fa-angle-left',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_thumbnails_columns',
			array(
				'label'     => esc_html__( 'Visible Items Count', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 4,
				'options'   => array(
					2  => 2,
					3  => 3,
					4  => 4,
					5  => 5,
					6  => 6,
					7  => 7,
					8  => 8,
					9  => 9,
					10 => 10,
					11 => 11,
					12 => 12,
				),
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_thumbnails_space_between',
			array(
				'label'     => esc_html__( 'Space Between', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'default'   => 10,
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->end_controls_section();

	}

	public function register_controls_images_styles( $css_scheme ) {
		$this->start_controls_section(
			'section_images_style',
			array(
				'label'      => esc_html__( 'Images', 'jet-woo-product-gallery' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'images_alignment',
			array(
				'label'     => esc_html__( 'Image Alignment', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'jet-woo-product-gallery' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-woo-product-gallery' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'jet-woo-product-gallery' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['images_wrapper'] => 'text-align: {{VALUE}};',
				),
				'classes'   => 'elementor-control-align',
			)
		);

		$this->add_control(
			'images_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['images'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'images_border',
				'label'       => esc_html__( 'Border', 'jet-woo-product-gallery' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['images'],
			)
		);

		$this->add_responsive_control(
			'images_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['images'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				),
			)
		);

		$this->add_control(
			'images_arrows_style_heading',
			array(
				'label'     => esc_html__( 'Prev/Next Arrows', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'images_arrows_size',
			array(
				'label'     => esc_html__( 'Size', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 80,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'slider_show_nav' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_images_arrows_style' );

		$this->start_controls_tab(
			'images_arrows_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-product-gallery' ),
			)
		);

		$this->add_control(
			'images_arrows_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'images_arrows_normal_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'images_arrows_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-woo-product-gallery' ),
			)
		);

		$this->add_control(
			'images_arrows_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'images_arrows_hover_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'images_arrows_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . ':hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'images_arrows_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'images_arrows_disabled',
			array(
				'label' => esc_html__( 'Disabled', 'jet-woo-product-gallery' ),
			)
		);

		$this->add_control(
			'images_arrows_disabled_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . '.swiper-button-disabled' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'images_arrows_disabled_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . '.swiper-button-disabled' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'images_arrows_disabled_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . '.swiper-button-disabled' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'images_arrows_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'images_arrows_border',
				'label'       => esc_html__( 'Border', 'jet-woo-product-gallery' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['images-arrows'],
				'separator'   => 'before',
				'condition'   => array(
					'slider_show_nav' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'images_arrows_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'slider_show_nav' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'images_arrows_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'slider_show_nav' => 'yes',
				),
			)
		);

		$this->add_control(
			'images_prev_arrow_heading',
			array(
				'label'     => esc_html__( 'Prev Arrow', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'images_prev_arrow_v_position',
			array(
				'label'   => esc_html__( 'Vertical Position by: ', 'jet-woo-product-gallery' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-woo-product-gallery' ),
					'bottom' => esc_html__( 'Bottom', 'jet-woo-product-gallery' ),
				),
			)
		);

		$this->add_responsive_control(
			'images_prev_arrow_top_position',
			array(
				'label'      => esc_html__( 'Top Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'images_prev_arrow_v_position' => 'top',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . '.jet-swiper-button-prev' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'images_prev_arrow_bottom_position',
			array(
				'label'      => esc_html__( 'Bottom Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'images_prev_arrow_v_position' => 'bottom',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . '.jet-swiper-button-prev' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				),
			)
		);

		$this->add_control(
			'images_prev_arrow_h_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by: ', 'jet-woo-product-gallery' ),
				'type'    => Controls_Manager::SELECT,
				'default' => ! is_rtl() ? 'left' : 'right',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-woo-product-gallery' ),
					'right' => esc_html__( 'Right', 'jet-woo-product-gallery' ),
				),
			)
		);

		$this->add_responsive_control(
			'images_prev_arrow_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'images_prev_arrow_h_position' => 'left',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . '.jet-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'images_prev_arrow_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'images_prev_arrow_h_position' => 'right',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . '.jet-swiper-button-prev' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'images_next_arrow_heading',
			array(
				'label'     => esc_html__( 'Next Arrow', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'images_next_arrow_v_position',
			array(
				'label'   => esc_html__( 'Vertical Position by: ', 'jet-woo-product-gallery' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-woo-product-gallery' ),
					'bottom' => esc_html__( 'Bottom', 'jet-woo-product-gallery' ),
				),
			)
		);

		$this->add_responsive_control(
			'images_next_arrow_top_position',
			array(
				'label'      => esc_html__( 'Top Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'images_next_arrow_v_position' => 'top',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . '.jet-swiper-button-next' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'images_next_arrow_bottom_position',
			array(
				'label'      => esc_html__( 'Bottom Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'images_next_arrow_v_position' => 'bottom',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . '.jet-swiper-button-next' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				),
			)
		);

		$this->add_control(
			'images_next_arrow_h_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by: ', 'jet-woo-product-gallery' ),
				'type'    => Controls_Manager::SELECT,
				'default' => ! is_rtl() ? 'right' : 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-woo-product-gallery' ),
					'right' => esc_html__( 'Right', 'jet-woo-product-gallery' ),
				),
			)
		);

		$this->add_responsive_control(
			'images_next_arrow_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'images_next_arrow_h_position' => 'left',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . '.jet-swiper-button-next' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'images_next_arrow_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'images_next_arrow_h_position' => 'right',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['images-arrows'] . '.jet-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	public function register_controls_pagination_styles( $css_scheme ) {
		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label'      => esc_html__( 'Pagination', 'jet-woo-product-gallery' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'bullets',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_items_size',
			array(
				'label'     => esc_html__( 'Size', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 40,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['pagination-items'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'pagination_items_style' );

		$this->start_controls_tab(
			'pagination_items_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-product-gallery' ),
			)
		);

		$this->add_control(
			'pagination_items_background',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['pagination-items'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_items_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-woo-product-gallery' ),
			)
		);

		$this->add_control(
			'pagination_items_background_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['pagination-items'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_items_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['pagination-items'] . ':hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'pagination_items_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_items_active',
			array(
				'label' => esc_html__( 'Active', 'jet-woo-product-gallery' ),
			)
		);

		$this->add_control(
			'pagination_items_background_active',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['pagination-active-item'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_items_border_color_active',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['pagination-active-item'] => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'pagination_items_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'pagination_items_border',
				'label'       => esc_html__( 'Border', 'jet-woo-product-gallery' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['pagination-items'],
				'separator'   => 'before',
			)
		);

		$this->add_responsive_control(
			'pagination_items_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['pagination-items'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_items_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['pagination-items'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	public function register_controls_thumbnails_styles( $css_scheme ) {
		$this->start_controls_section(
			'section_thumbnails_style',
			array(
				'label'      => esc_html__( 'Thumbnails', 'jet-woo-product-gallery' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_vertical_width',
			array(
				'label'       => esc_html__( 'Width', 'jet-woo-product-gallery' ),
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'size_units'  => array(
					'px',
					'%',
				),
				'range'       => array(
					'px' => array(
						'min' => 70,
						'max' => 500,
					),
					'%'  => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'     => array(
					'size' => 150,
					'unit' => 'px',
				),
				'selectors'   => array(
					'{{WRAPPER}} .swiper-container-vertical ' . $css_scheme['thumbnails-wrapper'] => 'flex-basis: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'slider_show_pagination'      => 'yes',
					'slider_pagination_direction' => 'vertical',
				),
			)
		);

		$this->add_control(
			'thumbnails_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'thumbnails_border',
				'label'       => esc_html__( 'Border', 'jet-woo-product-gallery' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['thumbnails'],
			)
		);

		$this->add_responsive_control(
			'thumbnails_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_gutter_h',
			array(
				'label'              => esc_html__( 'Gutter', 'jet-woo-product-gallery' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', '%' ),
				'allowed_dimensions' => 'vertical',
				'render_type'        => 'template',
				'placeholder'        => array(
					'top'    => '',
					'right'  => 'auto',
					'bottom' => '',
					'left'   => 'auto',
				),
				'selectors'          => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-wrapper'] => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
				),
				'condition'          => array(
					'slider_pagination_direction' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_gutter_v',
			array(
				'label'              => esc_html__( 'Gutter', 'jet-woo-product-gallery' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', '%' ),
				'allowed_dimensions' => 'horizontal',
				'render_type'        => 'template',
				'placeholder'        => array(
					'top'    => 'auto',
					'right'  => '',
					'bottom' => 'auto',
					'left'   => '',
				),
				'selectors'          => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-wrapper'] => 'padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
				),
				'condition'          => array(
					'slider_pagination_direction' => 'vertical',
				),
			)
		);

		$this->add_control(
			'thumbnails_arrows_style_heading',
			array(
				'label'     => esc_html__( 'Prev/Next Arrows', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_arrows_size',
			array(
				'label'     => esc_html__( 'Size', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 80,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_thumbnails_arrows_style' );

		$this->start_controls_tab(
			'thumbnails_arrows_normal',
			array(
				'label'     => esc_html__( 'Normal', 'jet-woo-product-gallery' ),
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_control(
			'thumbnails_arrows_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] => 'color: {{VALUE}}',
				),
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_control(
			'thumbnails_arrows_normal_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'thumbnails_arrows_hover',
			array(
				'label'     => esc_html__( 'Hover', 'jet-woo-product-gallery' ),
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_control(
			'thumbnails_arrows_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . ':hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_control(
			'thumbnails_arrows_hover_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . ':hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_control(
			'thumbnails_arrows_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . ':hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'thumbnails_arrows_border_border!' => '',
					'slider_show_pagination'           => 'yes',
					'slider_pagination_type'           => 'thumbnails',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'thumbnails_arrows_disabled',
			array(
				'label'     => esc_html__( 'Disabled', 'jet-woo-product-gallery' ),
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_control(
			'thumbnails_arrows_disabled_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . '.swiper-button-disabled' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_control(
			'thumbnails_arrows_disabled_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . '.swiper-button-disabled' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_control(
			'thumbnails_arrows_disabled_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . '.swiper-button-disabled' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'thumbnails_arrows_border_border!' => '',
					'slider_show_pagination'           => 'yes',
					'slider_pagination_type'           => 'thumbnails',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'thumbnails_arrows_border',
				'label'       => esc_html__( 'Border', 'jet-woo-product-gallery' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'],
				'separator'   => 'before',
				'condition'   => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_arrows_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_arrows_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'slider_show_pagination' => 'yes',
					'slider_pagination_type' => 'thumbnails',
				),
			)
		);

		$this->add_control(
			'thumbnails_prev_arrow_heading',
			array(
				'label'     => esc_html__( 'Prev Arrow', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'thumbnails_prev_arrow_v_position',
			array(
				'label'   => esc_html__( 'Vertical Position by: ', 'jet-woo-product-gallery' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-woo-product-gallery' ),
					'bottom' => esc_html__( 'Bottom', 'jet-woo-product-gallery' ),
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_prev_arrow_top_position',
			array(
				'label'      => esc_html__( 'Top Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'thumbnails_prev_arrow_v_position' => 'top',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . '.jet-swiper-button-prev' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_prev_arrow_bottom_position',
			array(
				'label'      => esc_html__( 'Bottom Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'thumbnails_prev_arrow_v_position' => 'bottom',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . '.jet-swiper-button-prev' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				),
			)
		);

		$this->add_control(
			'thumbnails_prev_arrow_h_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by: ', 'jet-woo-product-gallery' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-woo-product-gallery' ),
					'right' => esc_html__( 'Right', 'jet-woo-product-gallery' ),
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_prev_arrow_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'thumbnails_prev_arrow_h_position' => 'left',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . '.jet-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_prev_arrow_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'thumbnails_prev_arrow_h_position' => 'right',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . '.jet-swiper-button-prev' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'thumbnails_next_arrow_heading',
			array(
				'label'     => esc_html__( 'Next Arrow', 'jet-woo-product-gallery' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'thumbnails_next_arrow_v_position',
			array(
				'label'   => esc_html__( 'Vertical Position by: ', 'jet-woo-product-gallery' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-woo-product-gallery' ),
					'bottom' => esc_html__( 'Bottom', 'jet-woo-product-gallery' ),
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_next_arrow_top_position',
			array(
				'label'      => esc_html__( 'Top Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'thumbnails_next_arrow_v_position' => 'top',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . '.jet-swiper-button-next' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_next_arrow_bottom_position',
			array(
				'label'      => esc_html__( 'Bottom Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'thumbnails_next_arrow_v_position' => 'bottom',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . '.jet-swiper-button-next' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				),
			)
		);

		$this->add_control(
			'thumbnails_next_arrow_h_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by: ', 'jet-woo-product-gallery' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-woo-product-gallery' ),
					'right' => esc_html__( 'Right', 'jet-woo-product-gallery' ),
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_next_arrow_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'thumbnails_next_arrow_h_position' => 'left',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . '.jet-swiper-button-next' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_next_arrow_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-woo-product-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => jet_woo_product_gallery_tools()->get_slider_arrows_position_ranges(),
				'condition'  => array(
					'thumbnails_next_arrow_h_position' => 'right',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails-arrows'] . '.jet-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
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

	/**
	 * Returns swiper slider setting options
	 *
	 * @return string
	 */
	public function get_slider_data_settings() {

		$settings = $this->get_settings();

		$slider_settings = array(
			'slider_enable_infinite_loop'      => ! filter_var( $settings['enable_gallery'], FILTER_VALIDATE_BOOLEAN ) ? $settings['slider_enable_infinite_loop'] : 'no',
			'slider_equal_slides_height'       => ! filter_var( $settings['slider_equal_slides_height'], FILTER_VALIDATE_BOOLEAN ),
			'slider_sensitivity'               => ! empty( $settings['slider_sensitivity'] ) ? $settings['slider_sensitivity'] : 1,
			'slider_enable_center_mode'        => filter_var( $settings['slider_enable_center_mode'], FILTER_VALIDATE_BOOLEAN ),
			'slider_center_mode_padding'       => ! empty( $settings['slider_center_mode_padding'] ) ? $settings['slider_center_mode_padding'] : 10,
			'slider_center_mode_slides'        => ! empty( $settings['slider_center_mode_slides'] ) ? $settings['slider_center_mode_slides'] : 1,
			'slider_center_mode_slides_tablet' => ! empty( $settings['slider_center_mode_slides_tablet'] ) ? $settings['slider_center_mode_slides_tablet'] : 1,
			'slider_center_mode_slides_mobile' => ! empty( $settings['slider_center_mode_slides_mobile'] ) ? $settings['slider_center_mode_slides_mobile'] : 1,
			'show_pagination'                  => filter_var( $settings['slider_show_pagination'], FILTER_VALIDATE_BOOLEAN ),
			'pagination_type'                  => $settings['slider_pagination_type'],
			'pagination_direction'             => $settings['slider_pagination_direction'],
			'thumbnails_columns'               => $settings['pagination_thumbnails_columns'],
			'thumbnails_columns_tablet'        => $settings['pagination_thumbnails_columns_tablet'],
			'thumbnails_columns_mobile'        => $settings['pagination_thumbnails_columns_mobile'],
			'thumbnails_space_between'         => ! empty( $settings['pagination_thumbnails_space_between'] ) ? $settings['pagination_thumbnails_space_between'] : 10,
			'thumbnails_space_between_tablet'  => ! empty( $settings['pagination_thumbnails_space_between_tablet'] ) ? $settings['pagination_thumbnails_space_between_tablet'] : 10,
			'thumbnails_space_between_mobile'  => ! empty( $settings['pagination_thumbnails_space_between_mobile'] ) ? $settings['pagination_thumbnails_space_between_mobile'] : 10,
		);

		$slider_settings = apply_filters( 'jet-woo-product-gallery/slider/pre-options', $slider_settings, $settings );

		$options = [
			'loop'            => filter_var( $slider_settings['slider_enable_infinite_loop'], FILTER_VALIDATE_BOOLEAN ),
			'autoHeight'      => $slider_settings['slider_equal_slides_height'],
			'longSwipesRatio' => $slider_settings['slider_sensitivity'],
			'centeredSlides'  => $slider_settings['slider_enable_center_mode'],
			'direction'       => $slider_settings['show_pagination'] ? $slider_settings['pagination_direction'] : 'horizontal',
			'showPagination'  => $slider_settings['show_pagination'],
			'paginationType'  => $slider_settings['pagination_type'],
		];

		if ( $options['centeredSlides'] ) {
			$options['breakpoints']  = [
				0    => [ 'slidesPerView' => $slider_settings['slider_center_mode_slides_mobile'], ],
				768  => [ 'slidesPerView' => $slider_settings['slider_center_mode_slides_tablet'], ],
				1025 => [ 'slidesPerView' => $slider_settings['slider_center_mode_slides'], ],
			];
			$options['spaceBetween'] = $slider_settings['slider_center_mode_padding'];
		}

		$thumb_options = [];

		if ( $slider_settings['show_pagination'] && 'thumbnails' === $slider_settings['pagination_type'] ) {
			$thumb_options = [
				'breakpoints' => [
					0    => [
						'slidesPerView' => $slider_settings['thumbnails_columns_mobile'],
						'spaceBetween'  => $slider_settings['thumbnails_space_between_mobile'],
					],
					768  => [
						'slidesPerView' => $slider_settings['thumbnails_columns_tablet'],
						'spaceBetween'  => $slider_settings['thumbnails_space_between_tablet'],
					],
					1025 => [
						'slidesPerView' => $slider_settings['thumbnails_columns'],
						'spaceBetween'  => $slider_settings['thumbnails_space_between'],
					],
				],
			];
		}

		$options = apply_filters( 'jet-woo-product-gallery/slider/options', $options, $settings );
		$options = json_encode( $options );

		$thumb_options = apply_filters( 'jet-woo-product-gallery/slider/thumb-options', $thumb_options, $settings );
		$thumb_options = json_encode( $thumb_options );

		return sprintf( 'data-swiper-settings=\'%1$s\' data-swiper-thumb-settings=\'%2$s\'', $options, $thumb_options );

	}

	/**
	 * Returns slider navigation arrows
	 *
	 * @param $prev_arrow
	 * @param $next_arrow
	 *
	 * @return string|null
	 */
	public function get_slider_navigation( $prev_arrow, $next_arrow ) {

		$settings   = $this->get_settings();
		$enable_nav = filter_var( $settings['slider_show_nav'], FILTER_VALIDATE_BOOLEAN );

		if ( ! $enable_nav ) {
			return null;
		}

		$nav_prev_icon = $this->__render_icon( $prev_arrow, '%s', '', false );
		$nav_next_icon = $this->__render_icon( $next_arrow, '%s', '', false );

		$swiper_prev_arrow = jet_woo_product_gallery_functions()->get_slider_arrow( 'jet-swiper-nav jet-swiper-button-prev', $nav_prev_icon );
		$swiper_next_arrow = jet_woo_product_gallery_functions()->get_slider_arrow( 'jet-swiper-nav jet-swiper-button-next', $nav_next_icon );

		return $swiper_prev_arrow . $swiper_next_arrow;

	}

}