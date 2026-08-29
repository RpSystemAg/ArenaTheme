<?php
/**
 * Title: Newsletter — hero
 * Slug: arena-commerce/newsletter-hero
 * Categories: arena-conversion, text
 * Keywords: newsletter, email, hero, signup
 * Description: Full-bleed newsletter capture centred on a cover. The interactive module is a labelled email field plus consent checkbox, with the primary action inside the same control surface.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:cover {"url":"","dimRatio":72,"gradient":"scrim-bottom","contentPosition":"center center","minHeight":62,"minHeightUnit":"vh","align":"full","className":"arena-newsletter-hero"} -->
<div class="arena-newsletter-hero wp-block-cover alignfull has-background-dim-72 has-background-dim" style="min-height:62vh">
	<span aria-hidden="true" class="wp-block-cover__gradient-background has-background-gradient has-scrim-bottom-gradient-background"></span>
	<div class="wp-block-cover__inner-container">
		<!-- wp:group {"className":"arena-newsletter-hero__content","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"38rem"}} -->
		<div class="arena-newsletter-hero__content wp-block-group">
			<!-- wp:heading {"textAlign":"center","level":2,"textColor":"base","fontSize":"5xl"} -->
			<h2 class="wp-block-heading has-text-align-center has-base-color has-text-color has-5xl-font-size">Field notes before the drop</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"align":"center","textColor":"surface","fontSize":"lg"} -->
			<p class="has-text-align-center has-surface-color has-text-color has-lg-font-size">One email a week. Test data, repairs and early access, no noise.</p>
			<!-- /wp:paragraph -->
			<!-- wp:html -->
			<form class="arena-form arena-newsletter-hero__form" action="#" method="post">
				<label for="arena-newsletter-hero-email">Email address</label>
				<input id="arena-newsletter-hero-email" name="arena-newsletter-hero-email" type="email" autocomplete="email" required="">
				<label class="arena-newsletter-hero__consent"><input type="checkbox" name="arena-newsletter-hero-consent" required=""> I agree to receive the weekly field note.</label>
				<button type="submit" class="wp-element-button">Subscribe</button>
			</form>
			<!-- /wp:html -->
		</div>
		<!-- /wp:group -->
	</div>
</div>
<!-- /wp:cover -->
