<?php

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Smart_Filters_Apply_Button_Widget extends Widget_Base {

	public function get_name() {
		return 'jet-smart-filters-apply-button';
	}

	public function get_title() {
		return __( 'Apply Button', 'jet-smart-filters' );
	}

	public function get_icon() {
		return 'jet-smart-filters-icon-apply-filter';
	}

	public function get_help_url() {
		return jet_smart_filters()->widgets->prepare_help_url(
			'https://crocoblock.com/knowledge-base/articles/jetsmartfilters-how-to-specify-the-widget-for-which-to-apply-the-jetsmartfilter-widgets-filter/',
			$this->get_name()
		);
	}

	public function get_categories() {
		return array( jet_smart_filters()->widgets->get_category() );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'Content', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'content_provider',
			array(
				'label'   => __( 'This filter for', 'jet-smart-filters' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => jet_smart_filters()->data->content_providers(),
			)
		);

		$this->add_control(
			'additional_providers_enabled',
			array(
				'label'        => esc_html__( 'Additional Providers Enabled', 'jet-smart-filters' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => '',
				'label_on'     => esc_html__( 'Yes', 'jet-smart-filters' ),
				'label_off'    => esc_html__( 'No', 'jet-smart-filters' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'additional_providers',
			array(
				'label'       => __( 'Additional Providers List', 'jet-smart-filters' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'default'     => '',
				'options'     => jet_smart_filters()->data->content_providers(),
				'condition'   => array(
					'additional_providers_enabled' => 'yes',
				),
			)
		);

		$this->add_control(
			'apply_type',
			array(
				'label'   => __( 'Apply type', 'jet-smart-filters' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ajax',
				'options' => array(
					'ajax'   => __( 'AJAX', 'jet-smart-filters' ),
					'reload' => __( 'Page reload', 'jet-smart-filters' )
				),
			)
		);

		$this->add_control(
			'apply_button_text',
			array(
				'label'     => esc_html__( 'Apply button text', 'jet-smart-filters' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Apply filters', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'apply_redirect',
			array(
				'label'        => esc_html__( 'Apply Redirect', 'jet-smart-filters' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => '',
				'label_on'     => esc_html__( 'Yes', 'jet-smart-filters' ),
				'label_off'    => esc_html__( 'No', 'jet-smart-filters' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'redirect_path',
			array(
				'label'       => esc_html__( 'Redirect Path', 'jet-smart-filters' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => array(
					'apply_redirect' => 'yes',
				),
			)
		);

		$this->add_control(
			'query_id',
			array(
				'label'       => esc_html__( 'Query ID', 'jet-smart-filters' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'Set unique query ID if you use multiple widgets of same provider on the page. Same ID you need to set for filtered widget.', 'jet-smart-filters' ),
			)
		);

		$this->end_controls_section();

		$css_scheme = apply_filters(
			'jet-smart-filters/widgets/apply-button/css-scheme',
			array(
				'filter'               => '.jet-filter',
				'apply-filters'        => '.apply-filters',
				'apply-filters-button' => '.apply-filters__button',
			)
		);

		$this->start_controls_section(
			'section_filter_apply_button_style',
			array(
				'label'      => esc_html__( 'Button', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_apply_button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['apply-filters-button'],
			)
		);

		$this->start_controls_tabs( 'filter_apply_button_style_tabs' );

		$this->start_controls_tab(
			'filter_apply_button_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'filter_apply_button_normal_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'filter_apply_button_normal_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_apply_button_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'filter_apply_button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'filter_apply_button_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'filter_apply_button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] . ':hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'filter_apply_button_border_border!' => '',
				)
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'filter_apply_button_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['apply-filters-button'],
				'separator'   => 'before'
			)
		);

		$this->add_control(
			'filter_apply_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'filter_apply_button_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['apply-filters-button'],
			)
		);

		$this->add_responsive_control(
			'filter_apply_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before'
			)
		);

		$this->add_responsive_control(
			'filter_apply_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_apply_button_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-smart-filters' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start'   => array(
						'title' => esc_html__( 'Left', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'  => array(
						'title' => esc_html__( 'Right', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-right',
					),
					'stretch'  => array(
						'title' => esc_html__( 'Stretch', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] => '-webkit-align-self:{{VALUE}}; align-self:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Apply button container data attributes
	 *
	 * @return String
	 */
	public function container_data_atts() {
		$output       = '';
		$settings     = $this->get_settings();
		$redirect     = ! empty( $settings['apply_redirect'] ) ? $settings['apply_redirect'] : false;
		$redirectPath = ! empty( $settings['redirect_path'] ) ? $settings['redirect_path'] : false;

		$data_atts = array(
			'data-content-provider'     => ! empty( $settings['content_provider'] ) ? $settings['content_provider'] : '',
			'data-additional-providers' => ! empty( $settings['additional_providers'] ) && $settings['additional_providers_enabled'] === 'yes' ?  htmlspecialchars( json_encode( $settings['additional_providers'] ) ) : '',
			'data-apply-type'           => ! empty( $settings['apply_type'] ) ? $settings['apply_type'] : 'ajax',
			'data-query-id'             => ! empty( $settings['query_id'] ) ? $settings['query_id'] : 'default',
		);
		
		$data_atts['data-redirect'] = $redirect;
		if ( $redirect && $redirectPath ) {
			$data_atts['data-redirect-path'] = $redirectPath;
		}

		foreach ( $data_atts as $key => $value ) {
			$output .= sprintf( ' %1$s="%2$s"', $key, $value );
		}

		return $output;
	}

	protected function render() {
		$base_class           = $this->get_name();
		$settings             = $this->get_settings();
		$data_atts            = $this->container_data_atts();

		echo '<div class="' . $base_class . ' jet-filter">';
		include jet_smart_filters()->get_template( 'common/apply-filters.php' );
		echo '</div>';

	}
}
