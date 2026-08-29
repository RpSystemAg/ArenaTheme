<?php
/**
 * Title: Social proof — compact reviews
 * Slug: arena-commerce/reviews-compact
 * Categories: arena-commerce, text
 * Keywords: reviews, cards, quotes, compact
 * Description: Three compact review cards in a column-shift layout. The unit is a single verified quote, with the name and location in a separate citestack beneath it.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-reviews-compact","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-reviews-compact wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
	<h2 class="wp-block-heading has-4xl-font-size">What the field testers say</h2>
	<!-- /wp:heading -->
	<!-- wp:columns {"className":"arena-reviews-compact__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50","top":"var:preset|spacing|50"}}}} -->
	<div class="arena-reviews-compact__columns wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"className":"arena-reviews-compact__stars","fontSize":"sm","textColor":"accent"} -->
			<p class="arena-reviews-compact__stars has-accent-color has-text-color has-sm-font-size">★★★★★</p>
			<!-- /wp:paragraph -->
			<!-- wp:quote -->
			<blockquote class="wp-block-quote"><p>It survived three seasons as a mountain guide's shell. That is the only review that matters.</p><cite>Silvia R. — Aosta</cite></blockquote>
			<!-- /wp:quote -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"className":"arena-reviews-compact__stars","fontSize":"sm","textColor":"accent"} -->
			<p class="arena-reviews-compact__stars has-accent-color has-text-color has-sm-font-size">★★★★★</p>
			<!-- /wp:paragraph -->
			<!-- wp:quote -->
			<blockquote class="wp-block-quote"><p>Repairs are free for two years and the dispatch was genuinely same-day.</p><cite>Marco D. — Trento</cite></blockquote>
			<!-- /wp:quote -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"className":"arena-reviews-compact__stars","fontSize":"sm","textColor":"accent"} -->
			<p class="arena-reviews-compact__stars has-accent-color has-text-color has-sm-font-size">★★★★★</p>
			<!-- /wp:paragraph -->
			<!-- wp:quote -->
			<blockquote class="wp-block-quote"><p>The first size chart that matched my measurements on the first order.</p><cite>Elena T. — Palermo</cite></blockquote>
			<!-- /wp:quote -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
