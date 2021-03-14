<?php
namespace Jet_Reviews\Comments;

use Jet_Reviews\DB\Manager as DB_Manager;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Data {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Constructor for the class
	 */
	function __construct() {}

	/**
	 * [insert_review description]
	 * @param  array  $args [description]
	 * @return [type]       [description]
	 */
	public function get_admin_comments_list_by_page( $page = 0, $per_page = 20, $search = '' ) {

		$table_name = DB_Manager::get_instance()->tables( 'review_comments', 'name' );

		$offset = $page * $per_page;

		$count_query = "SELECT COUNT(*) FROM $table_name ORDER BY id DESC";

		$page_query = "SELECT * FROM $table_name ORDER BY id DESC LIMIT $offset, $per_page";

		if ( ! empty( $search ) ) {

			$search_like = jet_reviews()->db->wpdb()->esc_like( $search );

			$count_query = jet_reviews()->db->wpdb()->prepare(
				"SELECT COUNT(*) FROM $table_name WHERE content LIKE '%s'",
				'%' . $search_like .'%'
			);

			$page_query = jet_reviews()->db->wpdb()->prepare(
				"SELECT * FROM $table_name WHERE content LIKE '%s' ORDER BY id DESC LIMIT $offset, $per_page",
				'%' . $search_like .'%'
			);
		}

		$raw_result = DB_Manager::get_instance()->wpdb()->get_results( $page_query, ARRAY_A );

		$result_count = 0;

		$prepare_data = array();

		if ( ! empty( $raw_result ) ) {

			foreach ( $raw_result as $key => $comment_data ) {

				$user_data = jet_reviews()->user_manager->get_raw_user_data( $comment_data['author'] );

				$prepare_data[] = array(
					'id'        => $comment_data['id'],
					'post'      => array(
						'id'    => $comment_data['post_id'],
						'title' => wp_trim_words( get_the_title( $comment_data['post_id'] ), 3, ' ...' ),
						'link'  => get_permalink( $comment_data['post_id'] ),
						'type'  => get_post_type( $comment_data['post_id'] ),
					),
					'author'    => array(
						'id'     => $user_data['id'],
						'name'   => $user_data['name'],
						'mail'   => $user_data['mail'],
						'avatar' => $user_data['avatar'],
						'roles'  => $user_data['roles'],
						'url'    => add_query_arg( array( 'user_id' => $user_data['id'] ), esc_url( admin_url( 'user-edit.php' ) ) ),
					),
					'date'        => array(
						'raw'        => $comment_data['date'],
						'human_diff' => jet_reviews_tools()->human_time_diff_by_date( $comment_data['date'] ),
					),
					'content'     => $comment_data['content'],
					'approved'    => filter_var( $comment_data['approved'], FILTER_VALIDATE_BOOLEAN ),
				);
			}

			$result_count = jet_reviews()->db->wpdb()->get_var( $count_query );

		}

		return array(
			'page_list'   => $prepare_data,
			'total_count' => $result_count,
		);

	}

	/**
	 * [delete_review_by_id description]
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function submit_review_comment( $data = array() ) {

		if ( empty( $data ) ) {
			return false;
		}

		$table_name = DB_Manager::get_instance()->tables( 'review_comments', 'name' );

		$prepare_data = array(
			'post_id'       => $data['post_id'],
			'parent_id'     => $data['parent_id'],
			'review_id'     => $data['review_id'],
			'author'        => $data['author'],
			'content'       => $data['content'],
			'date'          => $data['date'],
			'approved'      => $data['approved'],
		);

		$query = DB_Manager::get_instance()->wpdb()->insert( $table_name, $prepare_data );

		if ( ! $query ) {
			return false;
		}

		return DB_Manager::get_instance()->wpdb()->insert_id;
	}

	/**
	 * [insert_review description]
	 * @param  array  $args [description]
	 * @return [type]       [description]
	 */
	public function get_review_comments_by_post_id( $post_id = 0 ) {

		$table_name = DB_Manager::get_instance()->tables( 'review_comments', 'name' );

		$query = DB_Manager::get_instance()->wpdb()->prepare(
			"SELECT * FROM $table_name WHERE post_id = %d AND approved=1 ORDER BY date DESC",
			$post_id
		);

		$raw_result = DB_Manager::get_instance()->wpdb()->get_results( $query, ARRAY_A );

		$prepare_data = array();

		if ( ! empty( $raw_result ) ) {

			$post_type = get_post_type( $post_id );

			$post_type_data = jet_reviews()->settings->get_post_type_data( $post_type );

			$verifications = $post_type_data['comment_verifications'];

			foreach ( $raw_result as $key => $data ) {

				$user_data = get_user_by( 'id', $data['author'] );

				$user_data = jet_reviews()->user_manager->get_raw_user_data( $data['author'] );

				$review_verification_data = jet_reviews()->user_manager->get_verification_data(
					$verifications,
					array(
						'user_id' => $user_data['id'],
						'post_id' => $post_id,
					)
				);

				$prepare_data[] = array(
					'id'      => $data['id'],
					'post_id' => $data['post_id'],
					'parent_id' => $data['parent_id'],
					'review_id' => $data['review_id'],
					'author' => array(
						'id'     => $user_data['id'],
						'name'   => $user_data['name'],
						'mail'   => $user_data['mail'],
						'avatar' => $user_data['avatar'],
					),
					'date'          => array(
						'raw'        => $data['date'],
						'human_diff' => jet_reviews_tools()->human_time_diff_by_date( $data['date'] ),
					),
					'content'       => $data['content'],
					'approved'      => filter_var( $data['approved'], FILTER_VALIDATE_BOOLEAN ),
					'verifications' => $review_verification_data,
				);

			}
		}

		return $prepare_data;
	}

	/**
	 * [get_review_comments description]
	 * @return [type] [description]
	 */
	public function get_comments_count_by_reviews() {

		$table_name = DB_Manager::get_instance()->tables( 'review_comments', 'name' );

		$query = "SELECT review_id AS review, COUNT(*) AS comments FROM $table_name GROUP BY review_id";

		$raw_result = DB_Manager::get_instance()->wpdb()->get_results( $query, OBJECT_K );

		return $raw_result;
	}

	/**
	 * [update_review_type description]
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public function update_comment( $data = array() ) {

		if ( empty( $data ) ) {
			return false;
		}

		$table_name = DB_Manager::get_instance()->tables( 'review_comments', 'name' );

		$prepared_data = array(
			'content'     => $data['content'],
			'approved'    => filter_var( $data['approved'], FILTER_VALIDATE_BOOLEAN ) ? 1 : 0,
		);

		$query = DB_Manager::get_instance()->wpdb()->update(
			$table_name,
			$prepared_data,
			array(
				'id' => $data['id'],
			)
		);

		return $query;
	}

	/**
	 * [delete_review_by_id description]
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function delete_comment_by_id( $id = 0 ) {

		$table_name = DB_Manager::get_instance()->tables( 'review_comments', 'name' );

		$deleted_comment = DB_Manager::get_instance()->wpdb()->delete( $table_name, array( 'id' => $id ) );
		$deleted_childs = DB_Manager::get_instance()->wpdb()->delete( $table_name, array( 'parent_id' => $id ) );

		return $deleted_comment;
	}

	/**
	 * [get_review_count description]
	 * @return [type] [description]
	 */
	public function get_comment_count() {
		$table_name = DB_Manager::get_instance()->tables( 'review_comments', 'name' );

		$count_query = "SELECT COUNT(*) FROM $table_name";

		$result_count = jet_reviews()->db->wpdb()->get_var( $count_query );

		return $result_count;
	}

	/**
	 * [get_approved_review_count description]
	 * @return [type] [description]
	 */
	public function get_approved_comment_count() {
		$table_name = DB_Manager::get_instance()->tables( 'review_comments', 'name' );

		$count_query = "SELECT COUNT(*) FROM $table_name WHERE approved = 1";

		$result_count = jet_reviews()->db->wpdb()->get_var( $count_query );

		return $result_count;
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
}
