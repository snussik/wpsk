<?php
namespace Jet_Reviews\Reviews\Source;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Post extends Base {

	/**
	 * [get_slug description]
	 * @return [type] [description]
	 */
	public function get_slug() {
		return 'post';
	}

	/**
	 * [get_slug description]
	 * @return [type] [description]
	 */
	public function get_name() {
		return __( 'Post Type', 'jet-reviews' );
	}

	/**
	 * [get_source_id description]
	 * @return [type] [description]
	 */
	public function get_source_current_id() {
		return get_the_ID();
	}

	/**
	 * [get_source_settings description]
	 * @return [type] [description]
	 */
	public function get_source_settings() {
		return array();
	}

}
