<?php
namespace Jet_Reviews\Reviews\Source;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class User extends Base {

	/**
	 * [get_slug description]
	 * @return [type] [description]
	 */
	public function get_slug() {
		return 'user';
	}

	/**
	 * [get_slug description]
	 * @return [type] [description]
	 */
	public function get_name() {
		return __( 'User', 'jet-reviews' );
	}

	/**
	 * [get_source_id description]
	 * @return [type] [description]
	 */
	public function get_source_current_id() {
		return get_current_user_id();
	}

	/**
	 * [get_source_settings description]
	 * @return [type] [description]
	 */
	public function get_source_settings() {
		return array();
	}

}
