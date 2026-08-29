<?php
/**
 * Title: Order — summary
 * Slug: arena-commerce/checkout-summary
 * Categories: arena-commerce, text
 * Keywords: checkout, order, summary, cart
 * Description: A side-by-side order summary. The left rail lists cart lines, the right rail totals them with one primary action. The interactive module is the single checkout button.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-checkout-summary","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-checkout-summary wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
	<h2 class="wp-block-heading has-4xl-font-size">Your order</h2>
	<!-- /wp:heading -->
	<!-- wp:columns {"className":"arena-checkout-summary__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60","top":"var:preset|spacing|60"}}}} -->
	<div class="arena-checkout-summary__columns wp-block-columns">
		<!-- wp:column {"width":"58%"} -->
		<div class="wp-block-column" style="flex-basis:58%">
			<!-- wp:list {"className":"arena-checkout-summary__lines"} -->
			<ul class="arena-checkout-summary__lines wp-block-list">
				<!-- wp:list-item --><li><span class="arena-checkout-summary__qty">1×</span><span class="arena-checkout-summary__name">Shell Jacket 3L</span><span class="arena-checkout-summary__amount">€340</span></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><span class="arena-checkout-summary__qty">2×</span><span class="arena-checkout-summary__name">Alpine Pack 32L</span><span class="arena-checkout-summary__amount">€420</span></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><span class="arena-checkout-summary__qty">1×</span><span class="arena-checkout-summary__name">Approach Low</span><span class="arena-checkout-summary__amount">€165</span></li><!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":"42%"} -->
		<div class="wp-block-column" style="flex-basis:42%">
			<!-- wp:group {"className":"arena-checkout-summary__totals","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"border":{"radius":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
			<div class="arena-checkout-summary__totals wp-block-group" style="border-radius:var(--wp--preset--spacing--40);padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
				<!-- wp:paragraph {"fontSize":"sm"} --><p class="has-sm-font-size">Subtotal · €925</p><!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"sm"} --><p class="has-sm-font-size">Shipping · Free</p><!-- /wp:paragraph -->
				<!-- wp:separator {} --><hr class="wp-block-separator has-alpha-channel-opacity"/><!-- /wp:separator -->
				<!-- wp:paragraph {"fontSize":"md"} --><p class="has-md-font-size">Total · €925</p><!-- /wp:paragraph -->
				<!-- wp:buttons -->
				<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-arena-pill"} --><div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/checkout">Go to checkout</a></div><!-- /wp:button --></div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
