<?php
/**
 * Product Gallery Anchor template
 */

$product_id                = $_product->get_id();
$attachment_ids            = $_product->get_gallery_image_ids();
$images_size               = $settings['image_size'];
$enable_gallery            = filter_var( $settings['enable_gallery'], FILTER_VALIDATE_BOOLEAN );
$zoom                      = 'yes' === $settings['enable_zoom'] ? 'jet-woo-product-gallery__image--with-zoom' : '';
$gallery                   = '[jet-woo-product-gallery]';
$anchor_nav_controller_ids = array( $this->get_unique_controller_id() );
$video_thumbnail_url       = $this->__get_video_thumbnail_url();
$video_type                = jet_woo_gallery_video_integration()->get_video_type();
$video                     = $this->__get_video_html();
$first_place_video         = 'content' === $settings['video_display_in'] ? filter_var( $settings['first_place_video'], FILTER_VALIDATE_BOOLEAN ) : false;
?>

<div class="jet-woo-product-gallery-anchor-nav">
	<div class="jet-woo-product-gallery-anchor-nav-items">
		<?php
		if ( 'content' === $settings['video_display_in'] && $this->product_has_video() && $first_place_video ) {
			include $this->__get_global_template( 'video' );
		}

		if ( has_post_thumbnail( $product_id ) ) {
			include $this->__get_global_template( 'image' );
		} else {
			printf(
				'<div class="jet-woo-product-gallery__image-item featured no-image" id="%s"><div class="jet-woo-product-gallery__image image-with-placeholder"><img src="%s" alt="%s" class="%s" /></div></div>',
				$anchor_nav_controller_ids[0],
				wc_placeholder_img_src(),
				__( 'Placeholder', 'jet-woo-product-gallery' ),
				'wp-post-image'
			);
		}

		if ( $attachment_ids ) {
			foreach ( $attachment_ids as $attachment_id ) {
				include $this->__get_global_template( 'thumbnails' );
			}
		}

		if ( 'content' === $settings['video_display_in'] && $this->product_has_video() && ! $first_place_video ) {
			include $this->__get_global_template( 'video' );
			array_push( $anchor_nav_controller_ids, $anchor_nav_controller_id );
		}
		?>
	</div>
	<?php include $this->__get_global_template( 'controller' ); ?>
</div>