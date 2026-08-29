<?php
/**
 * Title: Sticky scroll story
 * Slug: arena-commerce/sticky-scroll-story
 * Categories: arena-motion, text
 * Keywords: sticky, scroll, story, pinned, narrative
 * Description: Pinned left column against scrolling chapters. Position sticky, so it needs no JavaScript and degrades to a plain two-column layout on small screens where pinning would be harmful.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-story","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-story wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="sticky-scroll-story" data-arena-family="Editorial" data-arena-module="editorial-pinned-chapter">
	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|80"}}}} -->
	<div class="wp-block-columns alignwide">
		<!-- wp:column {"width":"42%"} -->
		<div class="wp-block-column" style="flex-basis:42%">
			<!-- wp:group {"className":"is-style-arena-sticky","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
			<div class="is-style-arena-sticky wp-block-group">
				<!-- wp:paragraph {"className":"is-style-arena-eyebrow","fontSize":"xs","textColor":"accent"} -->
				<p class="is-style-arena-eyebrow has-accent-color has-text-color has-xs-font-size">How it is made</p>
				<!-- /wp:paragraph -->

				<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
				<h2 class="wp-block-heading has-4xl-font-size">Three passes before it ships</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"fontSize":"lg","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-lg-font-size">Every line is cut, tested and re-cut. Scroll to follow one jacket from pattern table to ridge line.</p>
				<!-- /wp:paragraph -->

				<!-- wp:image {"aspectRatio":"4/5","sizeSlug":"arena-card-lg","className":"is-style-arena-frame"} -->
				<figure class="is-style-arena-frame wp-block-image size-arena-card-lg"><img src="" alt="Pattern pieces for the Arena shell jacket laid out on a cutting table" style="aspect-ratio:4/5;object-fit:cover" /></figure>
				<!-- /wp:image -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"58%"} -->
		<div class="wp-block-column" style="flex-basis:58%">
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|80"}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group">
				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--80)">
					<!-- wp:paragraph {"fontSize":"xs","textColor":"accent"} -->
					<p class="has-accent-color has-text-color has-xs-font-size">01</p>
					<!-- /wp:paragraph -->
					<!-- wp:heading {"level":3,"fontSize":"2xl"} -->
					<h3 class="wp-block-heading has-2xl-font-size">Cut from a single sheet</h3>
					<!-- /wp:heading -->
					<!-- wp:paragraph {"fontSize":"lg","textColor":"muted"} -->
					<p class="has-muted-color has-text-color has-lg-font-size">Nesting the panels together removes 14 percent of off-cut waste before the first stitch is sewn.</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--80)">
					<!-- wp:paragraph {"fontSize":"xs","textColor":"accent"} -->
					<p class="has-accent-color has-text-color has-xs-font-size">02</p>
					<!-- /wp:paragraph -->
					<!-- wp:heading {"level":3,"fontSize":"2xl"} -->
					<h3 class="wp-block-heading has-2xl-font-size">Sealed, then soaked</h3>
					<!-- /wp:heading -->
					<!-- wp:paragraph {"fontSize":"lg","textColor":"muted"} -->
					<p class="has-muted-color has-text-color has-lg-font-size">Every seam is taped and the finished garment spends four hours in a rain room before it is approved.</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">
					<!-- wp:paragraph {"fontSize":"xs","textColor":"accent"} -->
					<p class="has-accent-color has-text-color has-xs-font-size">03</p>
					<!-- /wp:paragraph -->
					<!-- wp:heading {"level":3,"fontSize":"2xl"} -->
					<h3 class="wp-block-heading has-2xl-font-size">Eighteen months on the hill</h3>
					<!-- /wp:heading -->
					<!-- wp:paragraph {"fontSize":"lg","textColor":"muted"} -->
					<p class="has-muted-color has-text-color has-lg-font-size">Guides wear the prototype through two full seasons. Nothing enters the catalogue without their sign-off.</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-sticky-scroll-story__sticky-chapter-nav" data-arena-role="sticky-chapter-nav"></span>
</div>
<!-- /wp:group -->
