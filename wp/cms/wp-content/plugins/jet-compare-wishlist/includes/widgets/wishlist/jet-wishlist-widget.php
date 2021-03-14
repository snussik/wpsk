<?php
/**
 * Class: Jet_Wishlist_Widget
 * Name: Wishlist
 * Slug: jet-wishlist
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Wishlist_Widget extends Jet_CW_Base {

	public function get_name() {
		return 'jet-wishlist';
	}

	public function get_title() {
		return esc_html__( 'Wishlist', 'jet-cw' );
	}

	public function get_icon() {
		return 'jet-cw-icon-wishlist';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-adjust-the-wishlist-settings-for-woocommerce-shop-using-jetcomparewishlist/';
	}

	public function get_categories() {
		return array( 'jet-cw' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-compare-wishlist/jet-wishlist/css-scheme',
			array(
				'row'                    => '.cw-col-row',
				'cols'                   => '.cw-col-row > div',
				'item'                   => '.jet-wishlist .jet-wishlist-item',
				'item-content'           => '.jet-wishlist-item__content',
				'item-thumbnail'         => '.jet-cw-thumbnail',
				'item-thumbnail-wrapper' => '.jet-wishlist-item__thumbnail',
				'item-title'             => '.jet-wishlist .jet-cw-product-title',
				'item-price'             => '.jet-wishlist .jet-cw-price',
				'item-currency'          => '.jet-wishlist .jet-cw-price .woocommerce-Price-currencySymbol',
				'item-rating'            => '.jet-wishlist .jet-cw-rating-stars',
				'item-button-wrapper'    => '.jet-cw-add-to-cart',
				'item-button'            => '.jet-cw-add-to-cart .button',
				'item-remove-button'     => '.jet-cw-remove-button.jet-wishlist-item-remove-button',
				'empty-text'             => '.jet-wishlist-empty',
			)
		);

		$columns = jet_cw_tools()->get_select_range( 6 );

		$this->start_controls_section(
			'section_general_style',
			array(
				'label'      => esc_html__( 'General', 'jet-cw' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$this->add_control(
			'empty_wishlist_text',
			array(
				'label'   => esc_html__( 'Empty Wishlist Text', 'jet-cw' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'No products were added to the wishlist.', 'jet-cw' ),
			)
		);

		$this->add_responsive_control(
			'wishlist_columns',
			array(
				'label'   => esc_html__( 'Columns', 'jet-cw' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 3,
				'options' => $columns,
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label'     => esc_html__( 'Title', 'jet-cw' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'jet-cw' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h5',
				'options' => jet_cw_tools()->get_available_title_html_tags(),
			)
		);

		$this->add_control(
			'thumbnail_heading',
			array(
				'label'     => esc_html__( 'Thumbnail', 'jet-cw' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'cw_thumbnail_size',
				'default' => 'thumbnail',
			)
		);

		$this->add_control(
			'thumbnail_position',
			array(
				'label'   => esc_html__( 'Position', 'jet-cw' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Default', 'jet-cw' ),
					'left'    => esc_html__( 'Left', 'jet-cw' ),
					'right'   => esc_html__( 'Right', 'jet-cw' ),
				),
			)
		);

		$this->add_control(
			'rating_heading',
			array(
				'label'     => esc_html__( 'Rating', 'jet-cw' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'cw_rating_icon',
			array(
				'label'   => esc_html__( 'Rating Icon', 'jet-cw' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'jetcomparewishlist-icon-rating-1',
				'options' => jet_cw_tools()->get_available_rating_icons_list(),
			)
		);

		$this->end_controls_section();

		$this->wishlist_columns_styles( $css_scheme );

		$this->wishlist_item_styles( $css_scheme );

		$this->wishlist_thumbnail_styles( $css_scheme );

		$this->wishlist_title_styles( $css_scheme );

		$this->wishlist_price_styles( $css_scheme );

		$this->wishlist_rating_styles( $css_scheme );

		$this->wishlist_add_to_cart_styles( $css_scheme );

		$this->wishlist_remove_button_styles( $css_scheme );

		$this->wishlist_empty_text_styles( $css_scheme );
	}

	protected function render() {

		$settings = $this->get_settings();

		$widget_settings = array(
			'empty_wishlist_text'     => $settings['empty_wishlist_text'],
			'wishlist_remove_text'    => $settings['remove_button_text'],
			'wishlist_remove_icon'    => htmlspecialchars( $this->__render_icon( 'remove_button_icon', '%s', '', false ) ),
			'title_html_tag'          => $settings['title_html_tag'],
			'thumbnail_position'      => $settings['thumbnail_position'],
			'cw_thumbnail_size_size'  => $settings['cw_thumbnail_size_size'],
			'cw_rating_icon'          => $settings['cw_rating_icon'],
			'wishlist_columns'        => $settings['wishlist_columns'],
			'wishlist_columns_tablet' => $settings['wishlist_columns_tablet'],
			'wishlist_columns_mobile' => $settings['wishlist_columns_mobile'],
			'_widget_id'              => $this->get_id(),
		);

		$selector = 'div.jet-wishlist__content[data-widget-id="' . $widget_settings['_widget_id'] . '"]';

		jet_cw()->widgets_store->store_widgets_types( 'jet-wishlist', $selector, $widget_settings, 'wishlist' );

		$this->__context = 'render';

		$this->__open_wrap();

		jet_cw_widgets_functions()->get_widget_wishlist( $widget_settings );

		$this->__close_wrap();

	}

	public function wishlist_columns_styles( $css_scheme ) {

		$this->start_controls_section(
			'section_columns_style',
			array(
				'label'      => esc_html__( 'Columns', 'jet-cw' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'columns_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cols'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['row']  => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right:-{{RIGHT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	public function wishlist_item_styles( $css_scheme ) {

		$this->start_controls_section(
			'section_item_style',
			array(
				'label'      => esc_html__( 'Item', 'jet-cw' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'item_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'item_border',
				'label'       => esc_html__( 'Border', 'jet-cw' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item'],
			)
		);

		$this->add_control(
			'item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'],
			)
		);

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'content_heading',
			array(
				'label'     => esc_html__( 'Content', 'jet-cw' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'item_content_vert_align',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'jet-cw' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'jet-cw' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => esc_html__( 'Middle', 'jet-cw' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Bottom', 'jet-cw' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-content'] => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_content_hor_align',
			array(
				'label'     => esc_html__( 'Horizontal Alignment', 'jet-cw' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => jet_cw_tools()->get_available_flex_horizontal_alignment(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-content'] => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

	public function wishlist_thumbnail_styles( $css_scheme ) {

		$this->start_controls_section(
			'section_thumbnail_style',
			array(
				'label'      => esc_html__( 'Thumbnail', 'jet-cw' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'thumbnail_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-cw' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 150,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . '.jet-wishlist-thumbnail-left ' . $css_scheme['item-thumbnail-wrapper']  => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . '.jet-wishlist-thumbnail-right ' . $css_scheme['item-thumbnail-wrapper'] => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'thumbnail_position!' => 'default',
				),
			)
		);

		$this->add_control(
			'thumbnail_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-thumbnail'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'thumbnail_border',
				'label'       => esc_html__( 'Border', 'jet-cw' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item-thumbnail'],
			)
		);

		$this->add_control(
			'thumbnail_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-thumbnail'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'thumbnail_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item-thumbnail'],
			)
		);

		$this->add_responsive_control(
			'thumbnail_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-thumbnail'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'thumbnail_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-thumbnail'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnail_order',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Order', 'jet-cw' ),
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-thumbnail'] => 'order: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

	}

	public function wishlist_title_styles( $css_scheme ) {

		$this->start_controls_section(
			'section_title_style',
			array(
				'label'      => esc_html__( 'Title', 'jet-cw' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item-title'],
			)
		);

		$this->start_controls_tabs( 'title_style_tabs' );

		$this->start_controls_tab(
			'title_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-cw' ),
			)
		);

		$this->add_control(
			'title_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-title'] . ' a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-cw' ),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-title'] . ' a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'title_text_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item-title'],
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'title_order',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Order', 'jet-cw' ),
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-title'] => 'order: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

	}

	public function wishlist_price_styles( $css_scheme ) {

		$this->start_controls_section(
			'section_price_style',
			array(
				'label'      => esc_html__( 'Price', 'jet-cw' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item-price'],
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'price_space_between',
			array(
				'label'     => esc_html__( 'Space Between Prices', 'jet-cw' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] . ' del+ins' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_price_style' );

		$this->start_controls_tab(
			'tab_price_regular',
			array(
				'label' => __( 'Regular', 'jet-cw' ),
			)
		);

		$this->add_control(
			'price_regular_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] . ' del' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'price_regular_decoration',
			array(
				'label'     => esc_html__( 'Text Decoration', 'jet-cw' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'line-through',
				'options'   => jet_cw_tools()->get_available_text_decoration_styles(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] . ' del' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'price_regular_size',
			array(
				'label'     => esc_html__( 'Size', 'jet-cw' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] . ' del' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'price_regular_weight',
			array(
				'label'     => esc_html__( 'Font Weight', 'jet-cw' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '400',
				'options'   => jet_cw_tools()->get_available_font_weight_styles(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] . ' del' => 'font-weight: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_price_sale',
			array(
				'label' => __( 'Sale', 'jet-cw' ),
			)
		);

		$this->add_control(
			'price_sale_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] . ' ins' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'price_sale_decoration',
			array(
				'label'     => esc_html__( 'Text Decoration', 'jet-cw' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => jet_cw_tools()->get_available_text_decoration_styles(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] . ' ins' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'price_sale_size',
			array(
				'label'     => esc_html__( 'Size', 'jet-cw' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] . ' ins' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'price_sale_weight',
			array(
				'label'     => esc_html__( 'Font Weight', 'jet-cw' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '400',
				'options'   => jet_cw_tools()->get_available_font_weight_styles(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] . ' ins' => 'font-weight: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'price_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'price_order',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Order', 'jet-cw' ),
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] => 'order: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'currency_sign_heading',
			array(
				'label'     => esc_html__( 'Currency Sign', 'jet-cw' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'currency_sign_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-currency'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'currency_sign_size',
			array(
				'label'     => esc_html__( 'Size', 'jet-cw' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-currency'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'currency_sign_vertical_align',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'jet-cw' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => jet_cw_tools()->verrtical_align_attr(),
				'default'   => 'baseline',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-currency'] => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_currency_sign_style' );

		$this->start_controls_tab(
			'tab_currency_sign_regular',
			array(
				'label' => __( 'Regular', 'jet-cw' ),
			)
		);

		$this->add_control(
			'currency_sign_color_regular',
			array(
				'label'     => esc_html__( 'Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] . ' del .woocommerce-Price-currencySymbol' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'currency_sign_size_regular',
			array(
				'label'     => esc_html__( 'Size', 'jet-cw' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] . ' del .woocommerce-Price-currencySymbol' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_currency_sign_sale',
			array(
				'label' => esc_html__( 'Sale', 'jet-cw' ),
			)
		);

		$this->add_control(
			'currency_sign_color_sale',
			array(
				'label'     => esc_html__( 'Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] . ' ins .woocommerce-Price-currencySymbol' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'currency_sign_size_sale',
			array(
				'label'     => esc_html__( 'Size', 'jet-cw' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-price'] . ' ins .woocommerce-Price-currencySymbol' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function wishlist_rating_styles( $css_scheme ) {

		$this->start_controls_section(
			'section_rating_styles',
			array(
				'label'      => esc_html__( 'Rating', 'jet-cw' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'rating_font_size',
			array(
				'label'      => esc_html__( 'Font Size (px)', 'jet-cw' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 16,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-rating'] . ' .product-rating__icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_rating_styles' );

		$this->start_controls_tab(
			'tab_rating_all',
			array(
				'label' => esc_html__( 'All', 'jet-cw' ),
			)
		);

		$this->add_control(
			'rating_color_all',
			array(
				'label'     => esc_html__( 'Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#a1a2a4',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-rating'] . ' .product-rating__icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_rating_rated',
			array(
				'label' => esc_html__( 'Rated', 'jet-cw' ),
			)
		);

		$this->add_control(
			'rating_color_rated',
			array(
				'label'     => esc_html__( 'Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fdbc32',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-rating'] . ' > .product-rating__icon.active' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rating_space_between',
			array(
				'label'      => esc_html__( 'Space Between Stars (px)', 'jet-cw' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 2,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-rating'] . ' .product-rating__icon + .product-rating__icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rating_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-rating'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'rating_order',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Order', 'jet-cw' ),
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-rating'] => 'order: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

	}

	public function wishlist_add_to_cart_styles( $css_scheme ) {

		$this->start_controls_section(
			'section_add_to_cart_style',
			array(
				'label'      => esc_html__( 'Add To Cart', 'jet-cw' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'        => 'add_to_cart_typography',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item-button'],
				'placeholder' => '1px',
			)
		);

		$this->add_responsive_control(
			'add_to_cart_width',
			array(
				'label'      => esc_html__( 'Button Width', 'jet-cw' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'%',
					'px',
				),
				'range'      => array(
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
					'px' => array(
						'min' => 50,
						'max' => 1000,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_add_to_cart_style' );

		$this->start_controls_tab(
			'tab_add_to_cart_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-cw' ),
			)
		);

		$this->add_control(
			'add_to_cart_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'add_to_cart_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'add_to_cart_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item-button'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_add_to_cart_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-cw' ),
			)
		);

		$this->add_control(
			'add_to_cart_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] . ':hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'add_to_cart_background_hover_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'add_to_cart_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] . ':hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'add_to_cart_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item-button'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_add_to_cart_added',
			array(
				'label' => esc_html__( 'Added', 'jet-cw' ),
			)
		);

		$this->add_control(
			'add_to_cart_disabled_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] . '.added' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'add_to_cart_background_disabled_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] . '.added' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'add_to_cart_added_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] . '.added' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['item-button'] . '.added' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'add_to_cart_added_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item-button'] . '.added',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_add_to_cart_loading',
			array(
				'label' => esc_html__( 'Loading', 'jet-cw' ),
			)
		);

		$this->add_control(
			'add_to_cart_loading_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] . '.loading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'add_to_cart_background_loading_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] . '.loading' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'add_to_cart_loading_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] . '.loading' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'add_to_cart_loading_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item-button'] . '.loading',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'add_to_cart_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item-button'],
				'separator'   => 'before',

			)
		);

		$this->add_control(
			'add_to_cart_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'add_to_cart_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'add_to_cart_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'add_to_cart_order',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Order', 'jet-cw' ),
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-button-wrapper'] => 'order: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

	}

	public function wishlist_remove_button_styles( $css_scheme ) {

		$this->start_controls_section(
			'section_remove_button_style',
			array(
				'label'      => esc_html__( 'Remove Button', 'jet-cw' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'remove_button_text',
			array(
				'label' => esc_html__( 'Button Text', 'jet-cw' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->__add_advanced_icon_control(
			'remove_button_icon',
			array(
				'label'       => esc_html__( 'Button Icon', 'jet-cw' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-remove',
				'fa5_default' => array(
					'value'   => 'fas fa-remove',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'remove_button_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item-remove-button'],
			)
		);

		$this->start_controls_tabs( 'tabs_remove_button_style' );

		$this->start_controls_tab(
			'tab_remove_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-cw' ),
			)
		);

		$this->add_control(
			'remove_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-remove-button'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'remove_button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-remove-button'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_remove_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-cw' ),
			)
		);

		$this->add_control(
			'remove_button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-remove-button'] . ':hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'remove_button_background_hover_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-remove-button'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'remove_button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'remove_button_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-remove-button'] . ':hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'remove_button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item-remove-button'],
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'remove_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-remove-button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'remove_button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item-remove-button'],
			)
		);

		$this->add_responsive_control(
			'remove_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-remove-button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'remove_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-remove-button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'remove_button_order',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Order', 'jet-cw' ),
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-remove-button'] => 'order: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'remove_button_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'jet-cw' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'remove_button_icon_size',
			array(
				'label'      => esc_html__( 'Size', 'jet-cw' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 40,
					),
				),
				'default'    => array(
					'size' => 12,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-remove-button'] . ' .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'remove_button_icon_offset',
			array(
				'label'      => esc_html__( 'Offset', 'jet-cw' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'    => array(
					'size' => 12,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item-remove-button'] . ' .icon'      => 'margin-right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} ' . $css_scheme['item-remove-button'] . ' .icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'remove_button_icon_style_tabs' );

		$this->start_controls_tab(
			'remove_button_icon_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-cw' ),
			)
		);

		$this->add_control(
			'remove_button_icon_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-remove-button'] . ' .icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'remove_button_icon_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-cw' ),
			)
		);

		$this->add_control(
			'remove_button_icon_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item-remove-button'] . ':hover .icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function wishlist_empty_text_styles( $css_scheme ) {

		$this->start_controls_section(
			'section_empty_text_style',
			array(
				'label'      => esc_html__( 'Empty Text', 'jet-cw' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'empty_text_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['empty-text'],
			)
		);

		$this->add_control(
			'empty_text_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['empty-text'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'empty_text_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['empty-text'],
			)
		);

		$this->add_control(
			'empty_text_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-cw' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['empty-text'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'empty_text_border',
				'label'       => esc_html__( 'Border', 'jet-cw' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['empty-text'],
			)
		);

		$this->add_control(
			'empty_text_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['empty-text'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
			)
		);

		$this->add_responsive_control(
			'empty_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['empty-text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'empty_text_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-cw' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['empty-text'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'empty_text_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-cw' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => jet_cw_tools()->get_available_horizontal_alignment(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['empty-text'] => 'text-align: {{VALUE}};',
				),
				'classes'   => 'elementor-control-align',
			)
		);

		$this->end_controls_section();

	}

}