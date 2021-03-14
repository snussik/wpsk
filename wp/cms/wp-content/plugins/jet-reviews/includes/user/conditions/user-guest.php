<?php
namespace Jet_Reviews\User\Conditions;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class User_Guest extends Base_Condition {

	/**
	 * [$slug description]
	 * @var string
	 */
	private $slug = 'user-guest';

	/**
	 * [$invalid_message description]
	 * @var boolean
	 */
	private $invalid_message = false;

	/**
	 * [__construct description]
	 */
	public function __construct() {
		$this->invalid_message = __( '*Guests cannot publish reviews', 'jet-reviews' );
	}

	/**
	 * [get_slug description]
	 * @return [type] [description]
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * [get_valid_message description]
	 * @return [type] [description]
	 */
	public function get_invalid_message() {
		return apply_filters( 'jet-reviews/user/conditions/invalid-message/{$this->slug}', $this->invalid_message, $this );
	}

	/**
	 * [check description]
	 * @return [type] [description]
	 */
	public function check() {

		$user_data = jet_reviews()->user_manager->get_raw_user_data();

		$user_roles = $user_data['roles'];

		$post_type_data = jet_reviews()->settings->get_the_post_type_data();

		$allowed_roles = $post_type_data['allowed_roles'];

		if ( in_array( 'guest', $user_data['roles'] ) && ! in_array( 'guest', $allowed_roles ) ) {
			return false;
		}

		return true;
	}

}
