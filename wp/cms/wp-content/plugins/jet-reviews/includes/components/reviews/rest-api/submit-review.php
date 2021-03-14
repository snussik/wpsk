<?php
namespace Jet_Reviews\Endpoints;

use Jet_Reviews\Reviews\Data as Reviews_Data;
use Jet_Reviews\User\Manager as User_Manager;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define Posts class
 */
class Submit_Review extends Base {

	/**
	 * [get_method description]
	 * @return [type] [description]
	 */
	public function get_method() {
		return 'POST';
	}

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'submit-review';
	}

	/**
	 * Returns arguments config
	 *
	 * @return [type] [description]
	 */
	public function get_args() {

		return array(
			'post' => array(
				'default'    => '',
				'required'   => false,
			),
			'title' => array(
				'default'    => '',
				'required'   => false,
			),
			'content' => array(
				'default'    => '',
				'required'   => false,
			),
			'author_id' => array(
				'default'    => '',
				'required'   => false,
			),
			'author_name' => array(
				'default'    => '',
				'required'   => false,
			),
			'author_mail' => array(
				'default'    => '',
				'required'   => false,
			),
			'rating_data' => array(
				'default'    => [],
				'required'   => false,
			),
			'captcha_token' => array(
				'default'    => '',
				'required'   => false,
			),
		);
	}

	/**
	 * [callback description]
	 * @param  [type]   $request [description]
	 * @return function          [description]
	 */
	public function callback( $request ) {

		$args = $request->get_params();

		$allowed_html = jet_reviews_tools()->get_content_allowed_html();

		$post_id       = isset( $args['post'] ) ? $args['post'] : false;
		$title         = isset( $args['title'] ) ? wp_kses( $args['title'], 'strip' ) : '';
		$content       = isset( $args['content'] ) ? wp_kses( $args['content'], $allowed_html ) : '';
		$author_id     = isset( $args['author_id'] ) ? $args['author_id'] : '0';
		$author_name   = isset( $args['author_name'] ) ? wp_kses( $args['author_name'], 'strip' ) : '';
		$author_mail   = isset( $args['author_mail'] ) ? sanitize_email( $args['author_mail'] ) : '';
		$rating_data   = isset( $args['rating_data'] ) ? $args['rating_data'] : [];
		$captcha_token = isset( $args['captcha_token'] ) ? $args['captcha_token'] : '';

		if ( jet_reviews_tools()->is_demo_mode() ) {
			return rest_ensure_response( array(
				'success'       => false,
				'message'       => __( 'You can\'t leave a review. Demo mode is active', 'jet-reviews' ),
				'data'          => [],
			) );
		}

		$recaptcha_instance = jet_reviews()->integration_manager->get_integration_module_instance( 'recaptcha' );
		$captcha_verify = $recaptcha_instance->maybe_verify( $captcha_token );

		if ( ! $captcha_verify ) {
			return rest_ensure_response( array(
				'success' => false,
				'message' => __( 'Captcha validation failed', 'jet-reviews' ),
			) );
		}

		$post_type      = get_post_type( $post_id );
		$post_type_data = jet_reviews()->settings->get_post_type_data( $post_type );
		$rating         = $this->calculate_rating( $rating_data );

		$is_guest = false === strpos( $author_id, 'guest' ) ? false : true;

		$prepared_data = array(
			'post_id'      => $post_id,
			'post_type'    => get_post_type( $post_id ),
			'author'       => $author_id,
			'date'         => current_time( 'mysql' ),
			'title'        => $title,
			'content'      => $content,
			'type_slug'    => $post_type_data['review_type'],
			'rating_data'  => maybe_serialize( $rating_data ),
			'rating'       => $rating,
			'approved'     => filter_var( $post_type_data['need_approve'], FILTER_VALIDATE_BOOLEAN ) ? 0 : 1,
			'pinned'       => 0,
		);

		$insert_data = Reviews_Data::get_instance()->add_new_review( $prepared_data );

		if ( ! $insert_data ) {
			return rest_ensure_response( array(
				'success' => false,
				'message' => __( 'DataBase Error', 'jet-reviews' ),
			) );
		}

		$insert_id = $insert_data['insert_id'];

		if ( ! $is_guest ) {
			jet_reviews()->user_manager->add_user_reviewed_post_id( $post_id );
		} else {
			$prepared_guest_data = array(
				'guest_id' => $author_id,
				'name'     => $author_name,
				'mail'     => $author_mail,
			);

			$insert_guest_id = jet_reviews()->user_manager->add_new_guest( $prepared_guest_data );
		}

		/**
		 * Maybe update average rating post meta field
		 */
		if ( filter_var( $post_type_data['metadata'], FILTER_VALIDATE_BOOLEAN ) ) {
			$this->maybe_update_rating_metadata( $post_id, $post_type_data['metadata_rating_key'], $insert_data['rating'], $post_type_data['metadata_ratio_bound'] );
		}

		/**
		 * Check if nessesary moderator approving
		 */
		if ( filter_var( $post_type_data['need_approve'], FILTER_VALIDATE_BOOLEAN ) ) {
			return rest_ensure_response( array(
				'success' => false,
				'message' => __( '*Your review must be approved by the moderator', 'jet-reviews' ),
			) );
		}

		$author_data = jet_reviews()->user_manager->get_current_user_data( $author_id );

		$review_verification_data = jet_reviews()->user_manager->get_verification_data(
			$post_type_data['verifications'],
			array(
				'user_id' => $author_data['id'],
				'post_id' => $post_id,
			)
		);

		$return_data = array(
			'id'      => $insert_id,
			'post'    => array(
				'id'    => $post_id,
				'title' => wp_trim_words( get_the_title( $post_id ), 3, ' ...' ),
				'link'  => get_permalink( $post_id ),
			),
			'post_type'  => $prepared_data['post_type'],
			'author'     => array(
				'id'     => $author_data['id'],
				'name'   => $author_data['name'],
				'mail'   => $author_data['mail'],
				'avatar' => $author_data['avatar'],
				'roles'  => $author_data['roles'],
			),
			'date'         => array(
				'raw'        => $prepared_data['date'],
				'human_diff' => jet_reviews_tools()->human_time_diff_by_date( $prepared_data['date'] ),
			),
			'title'         => $title,
			'content'       => $content,
			'type_slug'     => $prepared_data['type_slug'],
			'rating_data'   => $rating_data,
			'rating'        => $rating,
			'comments'      => array(),
			'approved'      => $post_type_data['need_approve'],
			'like'          => 0,
			'dislike'       => 0,
			'approval'      => jet_reviews()->user_manager->get_review_approval_data( $insert_id ),
			'pinned'        => false,
			'verifications' => $review_verification_data,
		);

		return rest_ensure_response( array(
			'success'       => true,
			'message'       => __( 'New Review has been created', 'jet-reviews' ),
			'data'          => array(
				'item'   => $return_data,
				'rating' => $insert_data['rating'],
			),
		) );
	}

	/**
	 * [calculate_rating description]
	 * @param  [type] $rating_data [description]
	 * @return [type]              [description]
	 */
	public function calculate_rating( $rating_data ) {

		$fields_rating = array();

		foreach ( $rating_data as $key => $field_data ) {
			$value = (int)$field_data['field_value'];
			$max   = (int)$field_data['field_max'];

			$fields_rating[] = round( ( 100 * $value ) / $max, 2 );
		}

		return round( array_sum( $fields_rating ) / count( $fields_rating ) );
	}

	/**
	 * [maybe_update_rating_metadata description]
	 * @param  boolean $post_id     [description]
	 * @param  boolean $meta_key    [description]
	 * @param  integer $rating      [description]
	 * @param  integer $ratio_bound [description]
	 * @return [type]               [description]
	 */
	public function maybe_update_rating_metadata( $post_id = false, $meta_key = false, $rating = 100, $ratio_bound = 5 ) {

		if ( ! $post_id || empty( $meta_key ) ) {
			return false;
		}

		update_post_meta( $post_id, $meta_key, ( $rating / 100 ) * $ratio_bound );
	}

}
