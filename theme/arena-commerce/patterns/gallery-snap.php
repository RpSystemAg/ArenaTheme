<?php
/**
 * Title: Gallery — snap rail
 * Slug: arena-commerce/gallery-snap
 * Categories: arena-commerce, gallery, arena-motion
 * Keywords: gallery, snap, rail, horizontal
 * Description: Horizontal scroll-snap photo rail with prev/next controls. The module is gesture and keyboard friendly, and differs from the product carousel by being imagery-only.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-gallery-snap","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-gallery-snap wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="gallery-snap" data-arena-family="Gallery" data-arena-module="gallery-snap-rail">
	<!-- wp:group {"className":"arena-gallery-snap__head","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="arena-gallery-snap__head wp-block-group">
		<!-- wp:heading {"level":2,"fontSize":"4xl"} --><h2 class="wp-block-heading has-4xl-font-size">Detail rail</h2><!-- /wp:heading -->
		<!-- wp:group {"className":"arena-gallery-snap__controls","layout":{"type":"flex","justifyContent":"right"}} -->
		<div class="arena-gallery-snap__controls wp-block-group">
			<!-- wp:button {"className":"arena-gallery-snap__control","text":"Previous"} -->
			<div class="wp-block-button"><a class="wp-block-button__link arena-gallery-snap__control wp-element-button" role="button" data-arena-carousel-prev href="#" aria-label="Previous image">Previous</a></div>
			<!-- /wp:button -->
			<!-- wp:button {"className":"arena-gallery-snap__control","text":"Next"} -->
			<div class="wp-block-button"><a class="wp-block-button__link arena-gallery-snap__control wp-element-button" role="button" data-arena-carousel-next href="#" aria-label="Next image">Next</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
	<!-- wp:group {"className":"arena-gallery-snap__rail arena-carousel__viewport","layout":{"type":"flex","flexWrap":"nowrap"}} -->
	<div class="arena-gallery-snap__rail arena-carousel__viewport wp-block-group">
		<!-- wp:image {"sizeSlug":"arena-featured","className":"arena-gallery-snap__shot"} --><figure class="arena-gallery-snap__shot wp-block-image size-arena-featured"><img src="" alt="Shell hood detail"/></figure><!-- /wp:image -->
		<!-- wp:image {"sizeSlug":"arena-featured","className":"arena-gallery-snap__shot"} --><figure class="arena-gallery-snap__shot wp-block-image size-arena-featured"><img src="" alt="Backpack carrying system"/></figure><!-- /wp:image -->
		<!-- wp:image {"sizeSlug":"arena-featured","className":"arena-gallery-snap__shot"} --><figure class="arena-gallery-snap__shot wp-block-image size-arena-featured"><img src="" alt="Boot lace detail"/></figure><!-- /wp:image -->
	</div>
	<!-- /wp:group -->

	<span aria-hidden="true" class="arena-gallery-snap__gallery-snap-control" data-arena-role="gallery-snap-control"></span>
</div>
<!-- /wp:group -->
