<?php
/**
 * Title: Product — editorial grid
 * Slug: arena-commerce/product-editorial-grid
 * Categories: arena-commerce
 * Keywords: product, editorial, grid, collection
 * Description: A mixed-density editorial grid: one hero product, two supporting cuts and a copy block that reads as a catalogue page rather than a shop shelf.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-product-editorial","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-product-editorial wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="product-editorial-grid" data-arena-family="Product" data-arena-module="product-editorial-stack">
	<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
	<h2 class="wp-block-heading has-4xl-font-size">The winter kit</h2>
	<!-- /wp:heading -->
	<!-- wp:columns {"className":"arena-product-editorial__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50","top":"var:preset|spacing|50"},"padding":{"top":"var:preset|spacing|50"}}}} -->
	<div class="arena-product-editorial__columns wp-block-columns" style="padding-top:var(--wp--preset--spacing--50)">
		<!-- wp:column {"width":"58%"} -->
		<div class="wp-block-column" style="flex-basis:58%">
			<!-- wp:image {"sizeSlug":"arena-featured","className":"arena-product-editorial__hero"} -->
			<figure class="arena-product-editorial__hero wp-block-image size-arena-featured"><img src="" alt="Arena expedition shell on snow"/></figure>
			<!-- /wp:image -->
			<!-- wp:heading {"level":3,"fontSize":"2xl"} -->
			<h3 class="wp-block-heading has-2xl-font-size">Expedition Shell</h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"sm","textColor":"muted"} -->
			<p class="has-muted-color has-text-color has-sm-font-size">€420 · 3L Gore-Tex Pro</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":"42%"} -->
		<div class="wp-block-column" style="flex-basis:42%">
			<!-- wp:group {"className":"arena-product-editorial__stack","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
			<div class="arena-product-editorial__stack wp-block-group">
				<!-- wp:image {"sizeSlug":"arena-card","className":"arena-product-editorial__cut"} -->
				<figure class="arena-product-editorial__cut wp-block-image size-arena-card"><img src="" alt="Arena down vest detail"/></figure>
				<!-- /wp:image -->
				<!-- wp:heading {"level":3,"fontSize":"lg"} -->
				<h3 class="wp-block-heading has-lg-font-size">Down Vest</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"xs","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-xs-font-size">€220 · recycled down</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"className":"arena-product-editorial__stack","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
			<div class="arena-product-editorial__stack wp-block-group">
				<!-- wp:image {"sizeSlug":"arena-card","className":"arena-product-editorial__cut"} -->
				<figure class="arena-product-editorial__cut wp-block-image size-arena-card"><img src="" alt="Arena fleece mid-layer"/></figure>
				<!-- /wp:image -->
				<!-- wp:heading {"level":3,"fontSize":"lg"} -->
				<h3 class="wp-block-heading has-lg-font-size">Fleece Mid Layer</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"xs","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-xs-font-size">€110 · 180gsm</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-product-editorial-grid__product-editorial-link" data-arena-role="product-editorial-link"></span>
</div>
<!-- /wp:group -->
