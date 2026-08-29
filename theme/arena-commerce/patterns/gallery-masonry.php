<?php
/**
 * Title: Gallery — masonry
 * Slug: arena-commerce/gallery-masonry
 * Categories: arena-commerce, gallery
 * Keywords: gallery, masonry, images, editorial
 * Description: A photo masonry with three staggered columns. No interactive module; the hierarchy is photographic and the scroll axis is vertical.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-gallery-masonry","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-gallery-masonry wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="gallery-masonry" data-arena-family="Gallery" data-arena-module="gallery-masonry-columns">
	<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
	<h2 class="wp-block-heading has-4xl-font-size">Shot on the way up</h2>
	<!-- /wp:heading -->
	<!-- wp:columns {"className":"arena-gallery-masonry__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40","top":"var:preset|spacing|40"}}}} -->
	<div class="arena-gallery-masonry__columns wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"sizeSlug":"arena-featured","className":"arena-gallery-masonry__tall"} --><figure class="arena-gallery-masonry__tall wp-block-image size-arena-featured"><img src="" alt="Ridge in evening light"/></figure><!-- /wp:image -->
			<!-- wp:image {"sizeSlug":"arena-card","className":"arena-gallery-masonry__short"} --><figure class="arena-gallery-masonry__short wp-block-image size-arena-card"><img src="" alt="Campsite tent"/></figure><!-- /wp:image -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"sizeSlug":"arena-card","className":"arena-gallery-masonry__short"} --><figure class="arena-gallery-masonry__short wp-block-image size-arena-card"><img src="" alt="Packing the shell"/></figure><!-- /wp:image -->
			<!-- wp:image {"sizeSlug":"arena-featured","className":"arena-gallery-masonry__tall"} --><figure class="arena-gallery-masonry__tall wp-block-image size-arena-featured"><img src="" alt="Trailhead sign"/></figure><!-- /wp:image -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"sizeSlug":"arena-card","className":"arena-gallery-masonry__short"} --><figure class="arena-gallery-masonry__short wp-block-image size-arena-card"><img src="" alt="Glacier blue hour"/></figure><!-- /wp:image -->
			<!-- wp:image {"sizeSlug":"arena-card","className":"arena-gallery-masonry__short"} --><figure class="arena-gallery-masonry__short wp-block-image size-arena-card"><img src="" alt="Zoomed zipper detail"/></figure><!-- /wp:image -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-gallery-masonry__masonry-column-set" data-arena-role="masonry-column-set"></span>
</div>
<!-- /wp:group -->
