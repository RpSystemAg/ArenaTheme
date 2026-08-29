<?php
/**
 * Title: Commerce hero
 * Slug: arena-commerce/hero-commerce
 * Categories: arena-commerce, banner, featured
 * Keywords: hero, storefront, landing, above the fold
 * Description: Full-bleed split hero with one primary and one secondary action, social proof and a 4:5 media frame. Core blocks only, so it renders with or without the Arena Engine plugin.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-hero","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="arena-hero wp-block-group alignfull" data-arena-stagger style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="hero-commerce" data-arena-family="Hero" data-arena-module="hero-split-commerce">
	<!-- wp:columns {"verticalAlignment":"center","className":"arena-hero__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|80"}}}} -->
	<div class="arena-hero__columns wp-block-columns are-vertically-aligned-center">
		<!-- wp:column {"verticalAlignment":"center","width":"52%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:52%">
			<!-- wp:paragraph {"className":"arena-hero__eyebrow is-style-arena-eyebrow","fontSize":"xs","textColor":"accent","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.14em","fontWeight":"600"}}} -->
			<p class="arena-hero__eyebrow" data-arena-reveal="rise" is-style-arena-eyebrow has-accent-color has-text-color has-xs-font-size" style="font-weight:600;letter-spacing:0.14em;text-transform:uppercase">New season · Free returns</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"textAlign":"left","level":1,"className":"arena-hero__title","fontSize":"6xl","style":{"typography":{"letterSpacing":"-0.035em"}}} -->
			<h1 class="arena-hero__title" data-arena-reveal="rise" has-text-align-left wp-block-heading has-6xl-font-size" style="letter-spacing:-0.035em">Equipment that keeps up with you</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"arena-hero__copy","fontSize":"lg","textColor":"muted"} -->
			<p class="arena-hero__copy has-muted-color has-text-color has-lg-font-size">Designed in Milan, tested at altitude, shipped carbon-neutral in 48 hours. Everything we make carries a two-year warranty and a repair service that answers.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"className":"arena-hero__actions","style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"flex","justifyContent":"left"}} -->
			<div class="arena-hero__actions wp-block-buttons" style="margin-top:var(--wp--preset--spacing--60)">
				<!-- wp:button {"className":"is-style-arena-pill"} -->
				<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/shop">Shop the collection</a></div>
				<!-- /wp:button -->

				<!-- wp:button {"className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/story">Read the story</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

			<!-- wp:group {"className":"arena-hero__proof","style":{"spacing":{"blockGap":"var:preset|spacing|40","margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
			<div class="arena-hero__proof wp-block-group" style="margin-top:var(--wp--preset--spacing--60)">
				<!-- wp:paragraph {"fontSize":"xs","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-xs-font-size">4.8/5 from 2,431 reviews</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"fontSize":"xs","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-xs-font-size">Shipped to 60 countries</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","width":"48%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:48%">
			<!-- wp:image {"sizeSlug":"arena-card-lg","aspectRatio":"4/5","className":"arena-hero__media is-style-arena-frame"} -->
			<figure class="arena-hero__media is-style-arena-frame wp-block-image size-arena-card-lg" data-arena-parallax="0.12"><img src="" alt="Athlete wearing the Arena shell jacket on a mountain ridge at sunrise" style="aspect-ratio:4/5;object-fit:cover" /></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-hero-commerce__hero-sku-price" data-arena-role="hero-sku-price"></span>
</div>
<!-- /wp:group -->
