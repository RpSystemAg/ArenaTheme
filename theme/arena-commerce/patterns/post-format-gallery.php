<?php
/**
 * Title: Post format: gallery
 * Slug: arena-commerce/post-format-gallery
 * Categories: arena-commerce, featured, media
 * Keywords: gallery, post format, journal, images
 * Description: Gallery post-format lead: a two-column mosaic where the first image spans full width. Core blocks only.
 * Viewport width: 1200
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"className":"arena-format-gallery","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="arena-format-gallery wp-block-group" data-arena-pattern="post-format-gallery" data-arena-family="Blog" data-arena-module="format-gallery-lead">
	<!-- wp:gallery {"linkTo":"none","sizeSlug":"arena-card","className":"arena-format-gallery__grid"} -->
	<figure class="wp-block-gallery has-nested-images has-3-images arena-format-gallery__grid">
		<!-- wp:image {"sizeSlug":"large"} -->
		<figure class="wp-block-image size-large"><img src="https://picsum.photos/seed/arena-gallery-lead/1200/700" alt=""/></figure>
		<!-- /wp:image -->
		<!-- wp:image {"sizeSlug":"arena-card"} -->
		<figure class="wp-block-image size-arena-card"><img src="https://picsum.photos/seed/arena-gallery-b/600/600" alt=""/></figure>
		<!-- /wp:image -->
		<!-- wp:image {"sizeSlug":"arena-card"} -->
		<figure class="wp-block-image size-arena-card"><img src="https://picsum.photos/seed/arena-gallery-c/600/600" alt=""/></figure>
		<!-- /wp:image -->
	</figure>
	<!-- /wp:gallery -->
</div>
<!-- /wp:group -->
