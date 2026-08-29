<?php
/**
 * Title: Product — feature podium
 * Slug: arena-commerce/product-feature-podium
 * Categories: arena-commerce
 * Keywords: product, feature, podium, detail, sticky
 * Description: A sticky media column beside a dense product detail module. Unlike the grid, the unit of design is the single product, not the catalogue.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-product-podium","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-product-podium wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:columns {"className":"arena-product-podium__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|70","top":"var:preset|spacing|70"}}}} -->
	<div class="arena-product-podium__columns wp-block-columns">
		<!-- wp:column {"width":"55%"} -->
		<div class="wp-block-column" style="flex-basis:55%">
			<!-- wp:image {"sizeSlug":"arena-featured","className":"is-style-arena-sticky arena-product-podium__media"} -->
			<figure class="is-style-arena-sticky arena-product-podium__media wp-block-image size-arena-featured"><img src="" alt="Arena 3L shell jacket in deep teal"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":"45%"} -->
		<div class="wp-block-column" style="flex-basis:45%">
			<!-- wp:group {"className":"arena-product-podium__details","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
			<div class="arena-product-podium__details wp-block-group">
				<!-- wp:paragraph {"fontSize":"xs","textColor":"accent"} -->
				<p class="has-accent-color has-text-color has-xs-font-size">The technical choice</p>
				<!-- /wp:paragraph -->
				<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
				<h2 class="wp-block-heading has-4xl-font-size">Shell Jacket 3L</h2>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"className":"arena-product-podium__price","fontSize":"xl"} -->
				<p class="arena-product-podium__price has-xl-font-size">€340</p>
				<!-- /wp:paragraph -->
				<!-- wp:navigation {"className":"arena-product-podium__sizes","overlayMenu":"never","layout":{"type":"flex","orientation":"horizontal","justifyContent":"left"}} -->
				<!-- wp:navigation-link {"label":"XS","url":"#","title":"Size XS"} /-->
				<!-- wp:navigation-link {"label":"S","url":"#","title":"Size S"} /-->
				<!-- wp:navigation-link {"label":"M","url":"#","title":"Size M"} /-->
				<!-- wp:navigation-link {"label":"L","url":"#","title":"Size L"} /-->
				<!-- /wp:navigation -->
				<!-- wp:paragraph {"fontSize":"sm","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-sm-font-size">Fits true to size. The hood is helmet-compatible.</p>
				<!-- /wp:paragraph -->
				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button {"className":"is-style-arena-pill"} -->
					<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/product/shell-jacket-3l">Add to workshop</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
