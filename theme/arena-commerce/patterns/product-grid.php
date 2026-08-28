<?php
/**
 * Title: Product grid
 * Slug: arena-commerce/product-grid
 * Categories: arena-commerce, gallery
 * Keywords: products, grid, catalogue, collection
 * Description: Four-up product grid using a core Query Loop over the product post type, with an editorial no-results fallback. It renders real products when WooCommerce is active and never leaves an empty hole when it is not.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-products","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="arena-products wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
		<h2 class="wp-block-heading has-4xl-font-size">Best sellers</h2>
		<!-- /wp:heading -->

		<!-- wp:buttons -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/shop">See everything</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

	<!-- wp:query {"queryId":0,"query":{"perPage":4,"pages":1,"offset":0,"postType":"product","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"align":"wide","className":"arena-products__query"} -->
	<div class="arena-products__query wp-block-query alignwide">
		<!-- wp:post-template {"align":"wide","className":"arena-products__grid","layout":{"type":"grid","columnCount":4}} -->
		<!-- wp:group {"className":"arena-product-card is-style-arena-card","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"bottom":"var:preset|spacing|50"}},"border":{"radius":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
		<div class="arena-product-card is-style-arena-card wp-block-group" style="border-radius:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--50)">
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/5","sizeSlug":"arena-card","className":"arena-product-card__media"} /-->

			<!-- wp:group {"style":{"spacing":{"padding":{"left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
				<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"md"} /-->
				<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"fontSize":"sm"} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results {"className":"arena-products__empty"} -->
		<!-- wp:paragraph {"textColor":"muted"} -->
		<p class="has-muted-color has-text-color">Your catalogue is not published yet. Activate WooCommerce, add four products and they will appear here automatically.</p>
		<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
