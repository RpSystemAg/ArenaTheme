<?php
/**
 * Title: Order — cost transparency
 * Slug: arena-commerce/cost-transparency
 * Categories: arena-commerce, text
 * Keywords: cost, transparency, fees, shipping
 * Description: A costs table shown before the place-order action. Plain-language rows and a final total, addressing a documented cause of checkout abandonment.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-cost-transparency","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"44rem"}} -->
<div class="arena-cost-transparency wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
	<h2 class="wp-block-heading has-4xl-font-size">What you pay, before you pay</h2>
	<!-- /wp:heading -->
	<!-- wp:table {"className":"arena-cost-transparency__table"} -->
	<figure class="wp-block-table arena-cost-transparency__table"><table><tbody><tr><td>Subtotal</td><td>€925.00</td></tr><tr><td>Delivery</td><td>€0.00 · free</td></tr><tr><td>Import duties</td><td>€0.00 · included</td></tr><tr><td>Returns label</td><td>€0.00 · prepaid</td></tr><tr><td><strong>Total</strong></td><td><strong>€925.00</strong></td></tr></tbody></table></figure>
	<!-- /wp:table -->
	<!-- wp:paragraph {"fontSize":"sm","textColor":"muted"} -->
	<p class="has-muted-color has-text-color has-sm-font-size">No shipping fees at checkout. The price on the page is the price you pay.</p>
	<!-- /wp:paragraph -->
	<!-- wp:buttons -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-arena-pill"} -->
		<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/checkout">Continue to checkout</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
