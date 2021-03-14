<?php
/**
 * Class Jet Woo Product Gallery Video Integration
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Woo_Gallery_Video_Integration' ) ) {

	/**
	 * Define Jet_Woo_Gallery_Video_Integration_class
	 */
	class Jet_Woo_Gallery_Video_Integration {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {
			add_action( 'init', array( $this, 'add_product_meta' ), 99 );
		}

		/**
		 * Initialize template metabox
		 *
		 * @return void
		 */
		public function add_product_meta() {
			new Cherry_X_Post_Meta( array(
				'id'            => 'gallery-video-settings',
				'title'         => esc_html__( 'Jet Product Gallery Video', 'jet-woo-product-gallery' ),
				'page'          => array( 'product' ),
				'context'       => 'side',
				'priority'      => 'low',
				'callback_args' => false,
				'builder_cb'    => array( $this, 'get_builder' ),
				'fields'        => array(
					'_jet_woo_product_video_type'        => array(
						'type'    => 'select',
						'element' => 'control',
						'value'   => 'youtube',
						'options' => array(
							'youtube'     => __( 'Youtube', 'jet-woo-product-gallery' ),
							'vimeo'       => __( 'Vimeo', 'jet-woo-product-gallery' ),
							'self_hosted' => __( 'Self Hosted', 'jet-woo-product-gallery' ),
						),
						'label'   => __( 'Video Type:', 'jet-woo-product-gallery' ),
						'class'   => 'jet-woo-product-gallery-cx-select',
					),
					'_jet_woo_product_video_placeholder' => array(
						'label'              => __( 'Placeholder:', 'jet-woo-product-gallery' ),
						'type'               => 'media',
						'element'            => 'control',
						'upload_button_text' => __( 'Choose Placeholder', 'jet-woo-product-gallery' ),
						'multi_upload'       => false,
						'class'              => 'jet-woo-product-gallery-cx-text',
					),
					'_jet_woo_product_vimeo_video_url'   => array(
						'label'      => __( 'Video URL:', 'jet-woo-product-gallery' ),
						'type'       => 'text',
						'element'    => 'control',
						'conditions' => array(
							'_jet_woo_product_video_type' => 'vimeo',
						),
					),
					'_jet_woo_product_youtube_video_url' => array(
						'label'      => __( 'Video URL:', 'jet-woo-product-gallery' ),
						'type'       => 'text',
						'element'    => 'control',
						'conditions' => array(
							'_jet_woo_product_video_type' => 'youtube',
						),
					),
					'_jet_woo_product_self_hosted_video' => array(
						'label'              => __( 'Video:', 'jet-woo-product-gallery' ),
						'type'               => 'media',
						'element'            => 'control',
						'upload_button_text' => __( 'Choose Video', 'jet-woo-product-gallery' ),
						'multi_upload'       => false,
						'library_type'       => 'video',
						'conditions'         => array(
							'_jet_woo_product_video_type' => 'self_hosted',
						),
					),
				),
			) );
		}

		/**
		 * Return UI builder instance
		 *
		 * @return CX_Interface_Builder
		 */
		public function get_builder() {

			$builder_data = jet_woo_product_gallery()->module_loader->get_included_module_data( 'cherry-x-interface-builder.php' );

			return new CX_Interface_Builder(
				array(
					'path' => $builder_data['path'],
					'url'  => $builder_data['url'],
				)
			);

		}

		/**
		 * Returns product gallery video type
		 *
		 * @return array|int|string
		 */
		public function get_video_type() {
			global $_product;

			return get_post_field( '_jet_woo_product_video_type', $_product->get_id() );
		}

		/**
		 * Returns product gallery custom placeholder
		 *
		 * @return array|int|string
		 */
		public function get_video_custom_placeholder() {
			global $_product;

			return get_post_field( '_jet_woo_product_video_placeholder', $_product->get_id() );
		}

		/**
		 * Returns product gallery youtube video url
		 *
		 * @return array|int|string
		 */
		public function get_youtube_video_url() {
			global $_product;

			return get_post_field( '_jet_woo_product_youtube_video_url', $_product->get_id() );
		}

		/**
		 * Returns product gallery vimeo video url
		 *
		 * @return array|int|string
		 */
		public function get_vimeo_video_url() {
			global $_product;

			return get_post_field( '_jet_woo_product_vimeo_video_url', $_product->get_id() );
		}

		/**
		 * Returns product gallery self-hosted video id
		 *
		 * @return array|int|string
		 */
		public function get_self_hosted_video_id() {
			global $_product;

			return get_post_field( '_jet_woo_product_self_hosted_video', $_product->get_id() );
		}

		/**
		 * Returns the instance.
		 *
		 * @return object
		 * @since  1.0.0
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;

		}

	}

}

/**
 * Returns instance of Jet_Woo_Gallery_Video_Integration
 *
 * @return object
 */
function jet_woo_gallery_video_integration() {
	return Jet_Woo_Gallery_Video_Integration::get_instance();
}
