<?php
/**
 * Title: Checkout steps
 * Slug: arena-commerce/checkout-steps
 * Categories: arena-commerce, woocommerce, featured
 * Keywords: checkout, steps, progress, distraction free
 * Description: A three-step checkout progress indicator (contact → shipping → payment) for the distraction-free checkout mode (H36). Core blocks only.
 * Viewport width: 1200
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"className":"arena-checkout-steps-group","layout":{"type":"constrained"}} -->
<div class="arena-checkout-steps-group wp-block-group" data-arena-pattern="checkout-steps" data-arena-family="Commerce" data-arena-module="checkout-progress">
	<!-- wp:list {"className":"arena-checkout-steps","fontSize":"sm"} -->
	<ol class="arena-checkout-steps has-sm-font-size wp-block-list">
		<!-- wp:list-item -->
		<li class="arena-checkout-steps__item" data-step="1" aria-current="step"><span>Information</span></li>
		<!-- /wp:list-item -->
		<!-- wp:list-item -->
		<li class="arena-checkout-steps__item" data-step="2"><span>Shipping</span></li>
		<!-- /wp:list-item -->
		<!-- wp:list-item -->
		<li class="arena-checkout-steps__item" data-step="3"><span>Payment</span></li>
		<!-- /wp:list-item -->
	</ol>
	<!-- /wp:list -->
</div>
<!-- /wp:group -->
