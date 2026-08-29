<?php
/**
 * Title: Hero — full bleeze cover
 * Slug: arena-commerce/hero-cover-short
 * Categories: arena-commerce, banner, featured
 * Keywords: hero, cover, image, display
 * Description: One dominant full-bleed statement. A single message, one primary button and a scrim that keeps the copy legible in 1 second at 6 metres.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:cover {"url":"","dimRatio":65,"gradient":"scrim-bottom","contentPosition":"center center","minHeight":70,"minHeightUnit":"vh","align":"full","className":"arena-hero-cover-short"} -->
<div class="arena-hero-cover-short wp-block-cover alignfull has-background-dim-65 has-background-dim" style="min-height:70vh" data-arena-pattern="hero-cover-short" data-arena-family="Hero" data-arena-module="hero-cover-short">
	<span aria-hidden="true" class="wp-block-cover__gradient-background has-background-gradient has-scrim-bottom-gradient-background"></span>
	<div class="wp-block-cover__inner-container">
		<!-- wp:group {"className":"arena-hero-cover-short__content","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
		<div class="arena-hero-cover-short__content wp-block-group">
			<!-- wp:heading {"textAlign":"center","level":1,"textColor":"base","fontSize":"6xl"} -->
			<h1 class="wp-block-heading has-text-align-center has-base-color has-text-color has-6xl-font-size">Equipment that earns its place</h1>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"align":"center","textColor":"surface","fontSize":"lg"} -->
			<p class="has-text-align-center has-surface-color has-text-color has-lg-font-size">Tested in the field, repaired in-house and made to be kept.</p>
			<!-- /wp:paragraph -->
			<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-arena-pill"} -->
				<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/shop">Explore</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->
	</div>

	<span aria-hidden="true" class="arena-hero-cover-short__hero-scrim-cta" data-arena-role="hero-scrim-cta"></span>
</div>
<!-- /wp:cover -->
