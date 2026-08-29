<?php
/**
 * Title: Trust — check list
 * Slug: arena-commerce/trust-check-list
 * Categories: arena-conversion, text
 * Keywords: trust, checklist, reassurance, shipping
 * Description: Split layout: a check-list of purchase promises beside a highlighted cover summary with a single action.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-trust-check-list","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-trust-check-list wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="trust-check-list" data-arena-family="Trust" data-arena-module="trust-checklist-panel">
	<!-- wp:columns {"className":"arena-trust-check-list__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60","top":"var:preset|spacing|60"}}}} -->
	<div class="arena-trust-check-list__columns wp-block-columns">
		<!-- wp:column {"width":"55%"} -->
		<div class="wp-block-column" style="flex-basis:55%">
			<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
			<h2 class="wp-block-heading has-4xl-font-size">What buying here actually means</h2>
			<!-- /wp:heading -->
			<!-- wp:list {"className":"arena-trust-check-list__items"} -->
			<ul class="arena-trust-check-list__items wp-block-list">
				<!-- wp:list-item --><li>Free shipping over 75 EUR</li><!-- /wp:list-item -->
				<!-- wp:list-item --><li>Prepaid 30-day returns, no questions</li><!-- /wp:list-item -->
				<!-- wp:list-item --><li>Cards, wallets and invoice available</li><!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":"45%"} -->
		<div class="wp-block-column" style="flex-basis:45%">
			<!-- wp:cover {"url":"","dimRatio":72,"gradient":"primary-ink","minHeight":46,"minHeightUnit":"vh","contentPosition":"bottom center","className":"arena-trust-check-list__panel"} -->
			<div class="arena-trust-check-list__panel wp-block-cover alignfull has-background-dim-72 has-background-dim" style="min-height:46vh">
				<span aria-hidden="true" class="wp-block-cover__gradient-background has-background-gradient has-primary-ink-gradient-background"></span>
				<div class="wp-block-cover__inner-container">
					<!-- wp:paragraph {"textColor":"base","fontSize":"xs"} -->
					<p class="has-base-color has-text-color has-xs-font-size">Confidence built in</p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"textColor":"base","fontSize":"xl"} -->
					<p class="has-base-color has-text-color has-xl-font-size">The guarantee is printed on the receipt, not buried in terms.</p>
					<!-- /wp:paragraph -->
					<!-- wp:buttons -->
					<div class="wp-block-buttons">
						<!-- wp:button {"className":"is-style-arena-pill"} -->
						<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/shipping">Read the shipping policy</a></div>
						<!-- /wp:button -->
					</div>
					<!-- /wp:buttons -->
				</div>
			</div>
			<!-- /wp:cover -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-trust-check-list__trust-check-panel" data-arena-role="trust-check-panel"></span>
</div>
<!-- /wp:group -->
