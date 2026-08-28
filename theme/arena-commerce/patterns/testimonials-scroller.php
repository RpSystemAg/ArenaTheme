<?php
/**
 * Title: Testimonial scroller
 * Slug: arena-commerce/testimonials-scroller
 * Categories: arena-motion, text
 * Keywords: testimonials, reviews, quotes, scroller, carousel
 * Description: Horizontally scrollable reviews with the same scroll-snap mechanics as the product carousel: keyboard operable, never auto-rotating, and silent to assistive technology until a slide changes.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-testimonials","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="arena-testimonials wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
		<h2 class="wp-block-heading has-4xl-font-size">From people who use it</h2>
		<!-- /wp:heading -->

		<!-- wp:group {"className":"arena-carousel__controls","layout":{"type":"flex","justifyContent":"right"}} -->
		<div class="arena-carousel__controls wp-block-group">
			<!-- wp:button {"className":"arena-carousel__control","text":"Previous slide"} -->
			<div class="wp-block-button"><a class="wp-block-button__link arena-carousel__control wp-element-button" data-arena-carousel-prev href="#" aria-label="Previous slide">Previous slide</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"arena-carousel__control","text":"Next slide"} -->
			<div class="wp-block-button"><a class="wp-block-button__link arena-carousel__control wp-element-button" data-arena-carousel-next href="#" aria-label="Next slide">Next slide</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"arena-carousel","ariaLabel":"Customer reviews","layout":{"type":"constrained","contentSize":"100%","wideSize":"100%"}} -->
	<div class="arena-carousel wp-block-group" aria-label="Customer reviews">
		<!-- wp:group {"className":"arena-carousel__viewport","layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="arena-carousel__viewport wp-block-group">
			<!-- wp:quote {"className":"arena-card is-style-arena-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|60","right":"var:preset|spacing|60"}}}} -->
			<blockquote class="arena-card is-style-arena-card wp-block-quote has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--60)">
				<p>Three winters on the north face and the tape has not lifted once. I have stopped buying jackets every season.</p>
				<cite>Marta B. — Dolomites</cite>
			</blockquote>
			<!-- /wp:quote -->

			<!-- wp:quote {"className":"arena-card is-style-arena-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|60","right":"var:preset|spacing|60"}}}} -->
			<blockquote class="arena-card is-style-arena-card wp-block-quote has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--60)">
				<p>The repair team answered in four hours and sent a patch kit free. That is why I recommend this to my clients.</p>
				<cite>Jonas K. — Chamonix guide</cite>
			</blockquote>
			<!-- /wp:quote -->

			<!-- wp:quote {"className":"arena-card is-style-arena-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|60","right":"var:preset|spacing|60"}}}} -->
			<blockquote class="arena-card is-style-arena-card wp-block-quote has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--60)">
				<p>The size chart is the first one I have ever trusted. Ordered once, no return, which never happens to me.</p>
				<cite>Priya R. — Lake District</cite>
			</blockquote>
			<!-- /wp:quote -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"arena-carousel__progress","layout":{"type":"constrained","contentSize":"100%","wideSize":"100%"}} -->
		<div class="arena-carousel__progress wp-block-group"><span class="arena-carousel__progress-bar"></span></div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
