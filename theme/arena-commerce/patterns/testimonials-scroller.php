<?php
/**
 * Title: Testimonial scroller
 * Slug: arena-commerce/testimonials-scroller
 * Categories: arena-motion, text
 * Keywords: testimonials, reviews, quotes, scroller, carousel
 * Description: Horizontally scrollable customer quotes with a pagination-dot indicator (NOT arrow buttons — distinct interaction model from the product carousel). Star rating + cite per slide; aria-live region announces the active testimonial.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-testimonials","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="arena-testimonials wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="testimonials-scroller" data-arena-family="Social" data-arena-module="social-quote-dots">
	<!-- wp:paragraph {"className":"is-style-arena-eyebrow","fontSize":"xs","textColor":"accent"} -->
	<p class="is-style-arena-eyebrow has-accent-color has-text-color has-xs-font-size">Verified owners</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
	<h2 class="wp-block-heading has-4xl-font-size">From people who use it</h2>
	<!-- /wp:heading -->

	<!-- wp:group {"className":"arena-testimonials__rail","ariaLabel":"Customer reviews","ariaRoleDescription":"carousel","layout":{"type":"constrained","contentSize":"100%","wideSize":"100%"}} -->
	<div class="arena-testimonials__rail wp-block-group" aria-label="Customer reviews" role="region" aria-roledescription="carousel" data-arena-testimonials-rail>
		<!-- wp:group {"className":"arena-testimonials__track","layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="arena-testimonials__track wp-block-group">
			<!-- wp:quote {"className":"arena-testimonial-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|40"}},"backgroundColor":"base"} -->
			<blockquote class="arena-testimonial-card wp-block-quote has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--60)">
				<!-- wp:paragraph {"className":"arena-testimonial-card__stars","fontSize":"lg","textColor":"accent"} -->
				<p class="arena-testimonial-card__stars has-accent-color has-text-color has-lg-font-size" data-arena-rating="5">★★★★★</p>
				<!-- /wp:paragraph -->
				<p>Three winters on the north face and the tape has not lifted once. I have stopped buying jackets every season.</p>
				<cite>Marta B. — Dolomites</cite>
			</blockquote>
			<!-- /wp:quote -->

			<!-- wp:quote {"className":"arena-testimonial-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|40"}},"backgroundColor":"base"} -->
			<blockquote class="arena-testimonial-card wp-block-quote has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--60)">
				<!-- wp:paragraph {"className":"arena-testimonial-card__stars","fontSize":"lg","textColor":"accent"} -->
				<p class="arena-testimonial-card__stars has-accent-color has-text-color has-lg-font-size" data-arena-rating="5">★★★★★</p>
				<!-- /wp:paragraph -->
				<p>The repair team answered in four hours and sent a patch kit free. That is why I recommend this to my clients.</p>
				<cite>Jonas K. — Chamonix guide</cite>
			</blockquote>
			<!-- /wp:quote -->

			<!-- wp:quote {"className":"arena-testimonial-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|40"}},"backgroundColor":"base"} -->
			<blockquote class="arena-testimonial-card wp-block-quote has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--60)">
				<!-- wp:paragraph {"className":"arena-testimonial-card__stars","fontSize":"lg","textColor":"accent"} -->
				<p class="arena-testimonial-card__stars has-accent-color has-text-color has-lg-font-size" data-arena-rating="4">★★★★☆</p>
				<!-- /wp:paragraph -->
				<p>The size chart is the first one I have ever trusted. Ordered once, no return, which never happens to me.</p>
				<cite>Priya R. — Lake District</cite>
			</blockquote>
			<!-- /wp:quote -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"arena-testimonials__dots","layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="arena-testimonials__dots wp-block-group" role="tablist" aria-label="Select testimonial" data-arena-testimonials-dots>
			<!-- wp:html -->
			<button type="button" class="arena-testimonials__dot" role="tab" aria-selected="true" aria-label="Testimonial 1 of 3" data-arena-testimonials-dot="0"></button>
			<button type="button" class="arena-testimonials__dot" role="tab" aria-selected="false" aria-label="Testimonial 2 of 3" data-arena-testimonials-dot="1"></button>
			<button type="button" class="arena-testimonials__dot" role="tab" aria-selected="false" aria-label="Testimonial 3 of 3" data-arena-testimonials-dot="2"></button>
			<!-- /wp:html -->
		</div>
		<!-- /wp:group -->

		<!-- wp:paragraph {"className":"sr-only","ariaLive":"polite"} -->
		<p class="sr-only" aria-live="polite" data-arena-testimonials-live>Showing testimonial 1 of 3.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<span aria-hidden="true" class="arena-testimonials-scroller__scroller-quote-dot" data-arena-role="scroller-quote-dot"></span>
</div>
<!-- /wp:group -->
