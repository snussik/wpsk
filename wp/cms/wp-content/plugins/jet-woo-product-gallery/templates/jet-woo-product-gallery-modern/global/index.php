<?php
/**
 * Product Gallery Modern template
 */

$product_id     = $_product->get_id();
$attachment_ids = $_product->get_gallery_image_ids();
$images_size    = $settings['image_size'];
$enable_gallery = filter_var( $settings['enable_gallery'], FILTER_VALIDATE_BOOLEAN );
$zoom           = 'yes' === $settings['enable_zoom'] ? 'jet-woo-product-gallery__image--with-zoom' : '';
$gallery        = '[jet-woo-product-gallery]';

$video_thumbnail_url = $this->__get_video_thumbnail_url();
$video_type          = jet_woo_gallery_video_integration()->get_video_type();
$video               = $this->__get_video_html();
$first_place_video   = filter_var( $settings['first_place_video'], FILTER_VALIDATE_BOOLEAN );

?>
<div class="jet-woo-product-gallery__content">
	<div class="jet-woo-product-gallery-modern">
		<?php
		if ( 'content' === $settings['video_display_in'] && $first_place_video ) {
			include $this->__get_global_template( 'video' );
		}

		if ( has_post_thumbnail( $product_id ) ) {
			include $this->__get_global_template( 'image' );
		} else {
			printf(
				'<div class="jet-woo-product-gallery__image-item featured no-image"><div class="jet-woo-product-gallery__image image-with-placeholder"><img src="%s" alt="%s" class="%s" /></div></div>',
				wc_placeholder_img_src(),
				esc_html__( 'Placeholder', 'jet-woo-product-gallery' ),
				'wp-post-image'
			);
		}

		if ( $attachment_ids ) {
			foreach ( $attachment_ids as $attachment_id ) {
				include $this->__get_global_template( 'thumbnails' );
			}
		}

		if ( 'content' === $settings['video_display_in'] && ! $first_place_video ) {
			include $this->__get_global_template( 'video' );
		}
		?>
	</div>

	<?php if ( 'popup' === $settings['video_display_in'] ) {
		include $this->__get_global_template( 'popup-video' );
	} ?>
</div>