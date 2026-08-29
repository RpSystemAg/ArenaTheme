<?php
/**
 * Title: Hero — media text
 * Slug: arena-commerce/hero-media-text
 * Categories: arena-commerce, banner, featured
 * Keywords: hero, media, text, split, editorial
 * Description: Asymmetric media-text hero with the media pinned to the right. The copy column uses a narrow measure and a single primary action so the message survives the billboard test.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-hero-media-text","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"100%","wideSize":"100%"}} -->
<div class="arena-hero-media-text wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:media-text {"align":"wide","mediaPosition":"right","mediaId":1,"mediaType":"image","mediaSizeSlug":"arena-hero","verticalAlignment":"center","className":"arena-hero-media-text__split","style":{"spacing":{"blockGap":"var:preset|spacing|70"}}} -->
	<div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-center arena-hero-media-text__split">
		<figure class="wp-block-media-text__media"><img src="" alt="Arena hero product on stone" style="object-fit:cover"/></figure>
		<div class="wp-block-media-text__content">
			<!-- wp:paragraph {"className":"is-style-arena-eyebrow arena-eyebrow","fontSize":"xs"} -->
			<p class="is-style-arena-eyebrow arena-eyebrow has-xs-font-size">Built for weather</p>
			<!-- /wp:paragraph -->
			<!-- wp:heading {"level":1,"fontSize":"5xl"} -->
			<h1 class="wp-block-heading has-5xl-font-size">One shell. Every season.</h1>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"lg","textColor":"muted"} -->
			<p class="has-muted-color has-text-color has-lg-font-size">A 3L membrane, taped seams and a hood that works over a helmet. Waterproof in four hours of rain.</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"className":"arena-hero-media-text__price","fontSize":"md"} -->
			<p class="arena-hero-media-text__price has-md-font-size">€340 · free returns</p>
			<!-- /wp:paragraph -->
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-arena-pill"} -->
				<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/shop">Shop the shell</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
	</div>
	<!-- /wp:media-text -->
</div>
<!-- /wp:group -->
