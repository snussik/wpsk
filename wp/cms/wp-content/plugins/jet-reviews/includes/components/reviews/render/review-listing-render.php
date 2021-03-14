<?php
namespace Jet_Reviews\Reviews;

use Jet_Reviews\Base_Render as Base_Render;
use Jet_Reviews\DB\Manager as DB_Manager;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Review_Listing_Render extends Base_Render {

	/**
	 * [$name description]
	 * @var string
	 */
	protected $name = 'review-listing-render';

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function init() {}

	/**
	 * [get_name description]
	 * @return [type] [description]
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	public function render() {

		$uniqid = uniqid();

		$post_id        = get_the_ID();
		$post_type      = get_post_type( $post_id );
		$post_type_data = jet_reviews()->settings->get_post_type_data( $post_type );

		if ( 'jet-theme-core' === $post_type ) {
			echo __( 'JetReviews unavailable for jetThemeCore template preview', 'jet-reviews' );

			return;
		}

		if ( ! $post_type_data['allowed'] ) {
			echo __( 'JetReviews unavailable for this type of post', 'jet-reviews' );

			return;
		}

		$options = array(
			'uniqId'           => $uniqid,
			'postId'           => $post_id,
			'allowed'          => $post_type_data['allowed'],
			'commentsAllowed'  => $post_type_data['comments_allowed'],
			'approvalAllowed'  => $post_type_data['approval_allowed'],
			'ratingLayout'     => $this->get( 'ratingLayout' ),
			'ratingInputType'  => $this->get( 'ratingInputType' ),
			'reviewRatingType' => $this->get( 'reviewRatingType' ),
			'pageSize'         => $this->get( 'reviewsPerPage' ),
			'labels'           => $this->get( 'labels' ),
		);

		$options_attr = sprintf( 'data-options=\'%1$s\'', json_encode( $options ) );

		$icons_data = $this->get( 'icons' );

		$icons_html = '';

		foreach ( $icons_data as $slug => $icon_data ) {
			$icons_html .= sprintf( '<div ref="%s">%s</div>', $slug, jet_reviews_tools()->get_elementor_icon_html( $icon_data ) );
		}

		$widget_refs = sprintf( '<div class="jet-reviews-advanced__refs">%s</div>', $icons_html );

		require jet_reviews()->plugin_path( 'templates/public/widgets/jet-reviews-advanced-widget.php' );

	}
}
