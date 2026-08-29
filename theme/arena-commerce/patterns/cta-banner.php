<?php
/**
 * Title: Closing CTA banner
 * Slug: arena-commerce/cta-banner
 * Categories: arena-conversion, call-to-action, banner
 * Keywords: cta, banner, closing, call to action
 * Description: Slim closing band with a single primary action and a countdown-urgency sticker. Deliberately compact (NOT a full-screen cover) so it reads as a closing nudge rather than a hero. Baymard's work on distraction applies to the last screen too.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-cta-band","backgroundColor":"foreground","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="arena-cta-band wp-block-group alignfull has-base-color has-foreground-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="cta-banner" data-arena-family="Conversion" data-arena-module="cta-band-single">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"className":"arena-cta-band__sticker","fontSize":"xs","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em","fontWeight":"700"}}} -->
		<p class="arena-cta-band__sticker has-xs-font-size" data-arena-urgency-sticker><span aria-hidden="true" class="arena-cta-band__dot"></span>Final days</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":2,"fontSize":"3xl","textColor":"base"} -->
		<h2 class="wp-block-heading has-base-color has-text-color has-3xl-font-size">Start with one good piece</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"fontSize":"sm","textColor":"surface"} -->
		<p class="has-surface-color has-text-color has-sm-font-size">Free delivery, free returns and a repair service that picks up.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"right"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-arena-pill arena-cta-band__cta"} -->
		<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill arena-cta-band__cta wp-element-button" href="/shop" data-arena-cta-primary>Shop the collection</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

	<span aria-hidden="true" class="arena-cta-banner__cta-urgency-dot" data-arena-role="cta-urgency-dot"></span>
</div>
<!-- /wp:group -->
