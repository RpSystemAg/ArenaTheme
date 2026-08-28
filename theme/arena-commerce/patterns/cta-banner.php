<?php
/**
 * Title: Closing CTA banner
 * Slug: arena-commerce/cta-banner
 * Categories: arena-conversion, call-to-action, banner
 * Keywords: cta, banner, closing, call to action
 * Description: Single primary action, one sentence of support and a gradient scrim that keeps the copy legible over any background image. Deliberately one button: Baymard's work on distraction applies to the last screen too.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:cover {"url":"","dimRatio":60,"minHeight":60,"minHeightUnit":"vh","gradient":"scrim-bottom","contentPosition":"center center","align":"full","className":"arena-cta"} -->
<div class="arena-cta wp-block-cover alignfull has-background-dim-60 has-background-dim" style="min-height:60vh">
	<span aria-hidden="true" class="wp-block-cover__gradient-background has-background-gradient has-scrim-bottom-gradient-background"></span>
	<div class="wp-block-cover__inner-container">
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"textAlign":"center","level":2,"textColor":"base","fontSize":"6xl"} -->
			<h2 class="wp-block-heading has-base-color has-text-color has-text-align-center has-6xl-font-size">Start with one good piece</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","textColor":"surface","fontSize":"lg"} -->
			<p class="has-surface-color has-text-color has-text-align-center has-lg-font-size">Free delivery, free returns and a repair service that picks up. Try it for 30 days.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-arena-pill"} -->
				<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/shop">Shop the collection</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->
	</div>
</div>
<!-- /wp:cover -->
