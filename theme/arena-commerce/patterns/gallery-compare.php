<?php
/**
 * Title: Gallery — before / after
 * Slug: arena-commerce/gallery-compare
 * Categories: arena-commerce, gallery
 * Keywords: compare, before, after, material
 * Description: A before/after comparison module split by a draggable divider line. Uses two equal columns and a separator rail; no carousel mechanics.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-gallery-compare","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-gallery-compare wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
	<h2 class="wp-block-heading has-4xl-font-size">Worn in, still waterproof</h2>
	<!-- /wp:heading -->
	<!-- wp:columns {"className":"arena-gallery-compare__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40","top":"var:preset|spacing|40"}}}} -->
	<div class="arena-gallery-compare__columns wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"sizeSlug":"arena-featured","className":"arena-gallery-compare__before"} -->
			<figure class="arena-gallery-compare__before wp-block-image size-arena-featured"><img src="" alt="Fabric before field use"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"sizeSlug":"arena-featured","className":"arena-gallery-compare__after"} -->
			<figure class="arena-gallery-compare__after wp-block-image size-arena-featured"><img src="" alt="Same fabric after 12 months"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
	<!-- wp:separator {"className":"arena-gallery-compare__rail"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity arena-gallery-compare__rail"/>
	<!-- /wp:separator -->
	<!-- wp:paragraph {"className":"arena-gallery-compare__caption","fontSize":"sm","textColor":"muted"} -->
	<p class="arena-gallery-compare__caption has-muted-color has-text-color has-sm-font-size">Slide 01: the shell after twelve months in the Dolomites. Repaired once, still dry.</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
