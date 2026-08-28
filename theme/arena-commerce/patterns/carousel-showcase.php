<?php
/**
 * Title: Product carousel
 * Slug: arena-commerce/carousel-showcase
 * Categories: arena-motion, arena-commerce
 * Keywords: carousel, slider, scroller, products, snap
 * Description: Scroll-snap carousel built from core blocks. It works with no JavaScript, with a trackpad, a wheel and touch; the enhancement script adds prev/next buttons, a progress bar, arrow-key control and a live region announcement.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-carousel-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="arena-carousel-section wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:group {"className":"arena-carousel-section__head","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="arena-carousel-section__head wp-block-group">
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"className":"is-style-arena-eyebrow","fontSize":"xs","textColor":"accent"} -->
			<p class="is-style-arena-eyebrow has-accent-color has-text-color has-xs-font-size">This week</p>
			<!-- /wp:paragraph -->
			<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
			<h2 class="wp-block-heading has-4xl-font-size">Built to be used, not displayed</h2>
			<!-- /wp:heading -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"arena-carousel__controls","layout":{"type":"flex","justifyContent":"right"}} -->
		<div class="arena-carousel__controls wp-block-group">
			<!-- wp:button {"className":"arena-carousel__control","text":"Previous slide","icon":"arena/arrow-left"} -->
			<div class="wp-block-button"><a class="wp-block-button__link arena-carousel__control wp-element-button" data-arena-carousel-prev href="#" aria-label="Previous slide">Previous slide</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"arena-carousel__control","text":"Next slide","icon":"arena/arrow-right"} -->
			<div class="wp-block-button"><a class="wp-block-button__link arena-carousel__control wp-element-button" data-arena-carousel-next href="#" aria-label="Next slide">Next slide</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"arena-carousel","ariaLabel":"Featured products","layout":{"type":"constrained","contentSize":"100%","wideSize":"100%"}} -->
	<div class="arena-carousel wp-block-group" aria-label="Featured products">
		<!-- wp:group {"className":"arena-carousel__viewport","layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="arena-carousel__viewport wp-block-group">
			<!-- wp:group {"className":"arena-card is-style-arena-card","style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"bottom":"var:preset|spacing|50"}},"border":{"radius":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
			<div class="arena-card is-style-arena-card wp-block-group" style="border-radius:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--50)">
				<!-- wp:image {"aspectRatio":"4/5","sizeSlug":"arena-card","className":"arena-card__media"} -->
				<figure class="arena-card__media wp-block-image size-arena-card"><img src="" alt="Arena shell jacket in deep teal, front view" style="aspect-ratio:4/5;object-fit:cover" /></figure>
				<!-- /wp:image -->
				<!-- wp:group {"style":{"spacing":{"padding":{"left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
					<!-- wp:heading {"level":3,"fontSize":"lg"} -->
					<h3 class="wp-block-heading has-lg-font-size">Shell Jacket 3L</h3>
					<!-- /wp:heading -->
					<!-- wp:paragraph {"fontSize":"md"} -->
					<p class="has-md-font-size">€340</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"className":"arena-card is-style-arena-card","style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"bottom":"var:preset|spacing|50"}},"border":{"radius":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
			<div class="arena-card is-style-arena-card wp-block-group" style="border-radius:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--50)">
				<!-- wp:image {"aspectRatio":"4/5","sizeSlug":"arena-card","className":"arena-card__media"} -->
				<figure class="arena-card__media wp-block-image size-arena-card"><img src="" alt="Arena alpine backpack, 32 litres, side view" style="aspect-ratio:4/5;object-fit:cover" /></figure>
				<!-- /wp:image -->
				<!-- wp:group {"style":{"spacing":{"padding":{"left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
					<!-- wp:heading {"level":3,"fontSize":"lg"} -->
					<h3 class="wp-block-heading has-lg-font-size">Alpine Pack 32L</h3>
					<!-- /wp:heading -->
					<!-- wp:paragraph {"fontSize":"md"} -->
					<p class="has-md-font-size">€210</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"className":"arena-card is-style-arena-card","style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"bottom":"var:preset|spacing|50"}},"border":{"radius":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
			<div class="arena-card is-style-arena-card wp-block-group" style="border-radius:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--50)">
				<!-- wp:image {"aspectRatio":"4/5","sizeSlug":"arena-card","className":"arena-card__media"} -->
				<figure class="arena-card__media wp-block-image size-arena-card"><img src="" alt="Arena approach shoes on rock" style="aspect-ratio:4/5;object-fit:cover" /></figure>
				<!-- /wp:image -->
				<!-- wp:group {"style":{"spacing":{"padding":{"left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
					<!-- wp:heading {"level":3,"fontSize":"lg"} -->
					<h3 class="wp-block-heading has-lg-font-size">Approach Low</h3>
					<!-- /wp:heading -->
					<!-- wp:paragraph {"fontSize":"md"} -->
					<p class="has-md-font-size">€165</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"arena-carousel__progress","layout":{"type":"constrained","contentSize":"100%","wideSize":"100%"}} -->
		<div class="arena-carousel__progress wp-block-group"><span class="arena-carousel__progress-bar"></span></div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
