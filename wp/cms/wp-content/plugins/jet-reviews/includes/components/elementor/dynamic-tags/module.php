<?php
namespace Jet_Reviews\Elementor\Dynamic_Tags;

class Module extends \Elementor\Modules\DynamicTags\Module {

	/**
	 * [get_tag_classes_names description]
	 * @return [type] [description]
	 */
	public function get_tag_classes_names() {
		return array(
			'Average_Rating',
			'Reviews_Info',
		);
	}

	/**
	 * [get_groups description]
	 * @return [type] [description]
	 */
	public function get_groups() {
		return array(
			'jet_reviews' => array(
				'title' => __( 'JetReviews', 'jet-reviews' ),
			),
		);
	}

	/**
	 * Register tags.
	 *
	 * Add all the available dynamic tags.
	 *
	 * @since  2.0.0
	 * @access public
	 *
	 * @param Manager $dynamic_tags
	 */
	public function register_tags( $dynamic_tags ) {

		foreach ( $this->get_tag_classes_names() as $tag_class ) {
			$file     = str_replace( '_', '-', strtolower( $tag_class ) ) . '.php';
			$filepath = jet_reviews()->plugin_path( 'includes/components/elementor/dynamic-tags/' . $file );

			if ( file_exists( $filepath ) ) {
				require $filepath;
			}

			$tag_class = '\\Jet_Reviews\\Elementor\\Dynamic_Tags\\' . $tag_class;

			if ( class_exists( $tag_class ) ) {
				$dynamic_tags->register_tag( $tag_class );
			}
		}

	}

}
