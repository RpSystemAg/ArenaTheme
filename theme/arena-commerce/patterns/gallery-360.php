<?php
/**
 * Title: Gallery — spin view
 * Slug: arena-commerce/gallery-360
 * Categories: arena-commerce, gallery
 * Keywords: gallery, 360, spin, product
 * Description: A single hero image with a thumbnail index and a spin action. The interactive module is the thumbnail rail; the hero arranges around it in a vertical stack.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-gallery-360","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-gallery-360 wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:image {"sizeSlug":"arena-featured","className":"arena-gallery-360__hero"} -->
	<figure class="arena-gallery-360__hero wp-block-image size-arena-featured"><img src="" alt="Shell jacket front view"/></figure>
	<!-- /wp:image -->
	<!-- wp:columns {"className":"arena-gallery-360__below","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40","top":"var:preset|spacing|40"}}}} -->
	<div class="arena-gallery-360__below wp-block-columns">
		<!-- wp:column {"width":"70%"} -->
		<div class="wp-block-column" style="flex-basis:70%">
			<!-- wp:navigation {"className":"arena-gallery-360__thumbs","overlayMenu":"never","layout":{"type":"flex","orientation":"horizontal","justifyContent":"left"}} -->
			<!-- wp:navigation-link {"label":"01","url":"#","title":"Front"} /-->
			<!-- wp:navigation-link {"label":"02","url":"#","title":"Side"} /-->
			<!-- wp:navigation-link {"label":"03","url":"#","title":"Back"} /-->
			<!-- wp:navigation-link {"label":"04","url":"#","title":"Detail"} /-->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":"30%"} -->
		<div class="wp-block-column" style="flex-basis:30%">
			<!-- wp:button {"className":"is-style-arena-pill"} -->
			<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="#">Spin 360°</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
