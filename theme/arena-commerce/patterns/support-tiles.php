<?php
/**
 * Title: Support — tiles
 * Slug: arena-commerce/support-tiles
 * Categories: arena-conversion, text
 * Keywords: support, help, tiles, help centre
 * Description: Three help-desk tiles in a horizontal shelf. Each tile opens a separate route; the interactive module is a small tappable card rather than a carousel or accordion.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-support-tiles","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-support-tiles wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
	<h2 class="wp-block-heading has-4xl-font-size">How can we help today?</h2>
	<!-- /wp:heading -->
	<!-- wp:columns {"className":"arena-support-tiles__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50","top":"var:preset|spacing|50"}}}} -->
	<div class="arena-support-tiles__columns wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"is-style-arena-card arena-support-tile","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"blockGap":"var:preset|spacing|30"}} -->
			<div class="is-style-arena-card arena-support-tile wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
				<!-- wp:heading {"level":3,"fontSize":"lg"} -->
				<h3 class="wp-block-heading has-lg-font-size">Shipping</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"sm","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-sm-font-size">Check dispatch time, tracking and delivery zones.</p>
				<!-- /wp:paragraph -->
				<!-- wp:button {"className":"is-style-arena-pill"} -->
				<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/shipping">Track an order</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"is-style-arena-card arena-support-tile","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"blockGap":"var:preset|spacing|30"}} -->
			<div class="is-style-arena-card arena-support-tile wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
				<!-- wp:heading {"level":3,"fontSize":"lg"} -->
				<h3 class="wp-block-heading has-lg-font-size">Repairs</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"sm","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-sm-font-size">Book a pickup and get a transparent repair estimate.</p>
				<!-- /wp:paragraph -->
				<!-- wp:button {"className":"is-style-arena-pill"} -->
				<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/repairs">Start a repair</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"is-style-arena-card arena-support-tile","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"blockGap":"var:preset|spacing|30"}} -->
			<div class="is-style-arena-card arena-support-tile wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
				<!-- wp:heading {"level":3,"fontSize":"lg"} -->
				<h3 class="wp-block-heading has-lg-font-size">Returns</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"sm","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-sm-font-size">Generate a prepaid label and send it back in thirty days.</p>
				<!-- /wp:paragraph -->
				<!-- wp:button {"className":"is-style-arena-pill"} -->
				<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/returns">Start a return</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
