<?php
/**
 * Title: Trust — guarantee
 * Slug: arena-commerce/trust-guarantee
 * Categories: arena-conversion, text
 * Keywords: guarantee, repair, warranty, product
 * Description: A media-text reassurance block pairing a repair image with the two-year guarantee and the repair policy.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-trust-guarantee","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-trust-guarantee wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="trust-guarantee" data-arena-family="Trust" data-arena-module="trust-guarantee-note">
	<!-- wp:media-text {"align":"wide","mediaPosition":"left","mediaId":2,"mediaType":"image","mediaSizeSlug":"arena-featured","verticalAlignment":"center","className":"arena-trust-guarantee__split"} -->
	<div class="wp-block-media-text alignwide is-stacked-on-mobile is-vertically-aligned-center arena-trust-guarantee__split">
		<figure class="wp-block-media-text__media"><img src="" alt="Workshop repairing a jacket zip"/></figure>
		<div class="wp-block-media-text__content">
			<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
			<h2 class="wp-block-heading has-4xl-font-size">Two years, then we still fix it.</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"lg","textColor":"muted"} -->
			<p class="has-muted-color has-text-color has-lg-font-size">Every product carries a two-year guarantee. After that, repairs are priced transparently and never exceed the value of what is being repaired.</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"className":"arena-trust-guarantee__note","fontSize":"sm"} -->
			<p class="arena-trust-guarantee__note has-sm-font-size">Free pickup in 14 European countries.</p>
			<!-- /wp:paragraph -->
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-arena-pill"} -->
				<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/repairs">Start a repair</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
	</div>
	<!-- /wp:media-text -->

	<span aria-hidden="true" class="arena-trust-guarantee__trust-guarantee-note" data-arena-role="trust-guarantee-note"></span>
</div>
<!-- /wp:group -->
