<?php
/**
 * Title: Service — warranty
 * Slug: arena-commerce/service-warranty
 * Categories: arena-commerce, text
 * Keywords: warranty, service, guarantee, product
 * Description: A two-column warranty explainer where a colour-free cover panel carries the leading message and the text column carries the detail. The visual hierarchy is panel-first.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-service-warranty","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-service-warranty wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="service-warranty" data-arena-family="Service" data-arena-module="service-warranty-panel">
	<!-- wp:columns {"className":"arena-service-warranty__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50","top":"var:preset|spacing|50"}}}} -->
	<div class="arena-service-warranty__columns wp-block-columns">
		<!-- wp:column {"width":"44%"} -->
		<div class="wp-block-column" style="flex-basis:44%">
			<!-- wp:cover {"url":"","dimRatio":66,"gradient":"primary-ink","contentPosition":"center","minHeight":50,"minHeightUnit":"vh","className":"arena-service-warranty__panel"} -->
			<div class="arena-service-warranty__panel wp-block-cover alignfull has-background-dim-66 has-background-dim" style="min-height:50vh"><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"className":"arena-service-warranty__big","fontSize":"6xl","textColor":"base"} --><p class="arena-service-warranty__big has-base-color has-text-color has-6xl-font-size">2-year</p><!-- /wp:paragraph --></div></div>
			<!-- /wp:cover -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":"56%"} -->
		<div class="wp-block-column" style="flex-basis:56%">
			<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
			<h2 class="wp-block-heading has-4xl-font-size">Warranty that is easy to read</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"lg","textColor":"muted"} -->
			<p class="has-muted-color has-text-color has-lg-font-size">Two years against any manufacturing fault. After that we still repair at fixed, transparent prices.</p>
			<!-- /wp:paragraph -->
			<!-- wp:button {"className":"is-style-arena-pill"} -->
			<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/warranty">Read the warranty</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-service-warranty__warranty-panel-number" data-arena-role="warranty-panel-number"></span>
</div>
<!-- /wp:group -->
