<?php
/**
 * Title: Logo marquee
 * Slug: arena-commerce/marquee-logos
 * Categories: arena-motion, text
 * Keywords: marquee, logos, press, social proof, scroll
 * Description: Infinite horizontal marquee. The track is authored once and duplicated by the enhancement script, which hides the clone from assistive technology; the animation pauses on hover, on focus and entirely under reduced motion.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-marquee","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="arena-marquee wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:paragraph {"align":"center","className":"arena-marquee__label","fontSize":"xs","textColor":"muted","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.14em","fontWeight":"600"}}} -->
	<p class="has-text-align-center arena-marquee__label has-muted-color has-text-color has-xs-font-size" style="font-weight:600;letter-spacing:0.14em;text-transform:uppercase">Featured in</p>
	<!-- /wp:paragraph -->

	<!-- wp:group {"className":"arena-marquee__viewport","ariaLabel":"Press mentions","layout":{"type":"constrained","contentSize":"100%","wideSize":"100%"}} -->
	<div class="arena-marquee__viewport wp-block-group" aria-label="Press mentions">
		<!-- wp:group {"className":"arena-marquee__track","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
		<div class="arena-marquee__track wp-block-group">
			<!-- wp:paragraph {"fontSize":"xl","textColor":"muted","style":{"typography":{"fontWeight":"700","letterSpacing":"-0.02em"}}} -->
			<p class="has-muted-color has-text-color has-xl-font-size" style="font-weight:700;letter-spacing:-0.02em">Alpinist</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"xl","textColor":"muted","style":{"typography":{"fontWeight":"700","letterSpacing":"-0.02em"}}} -->
			<p class="has-muted-color has-text-color has-xl-font-size" style="font-weight:700;letter-spacing:-0.02em">Monocle</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"xl","textColor":"muted","style":{"typography":{"fontWeight":"700","letterSpacing":"-0.02em"}}} -->
			<p class="has-muted-color has-text-color has-xl-font-size" style="font-weight:700;letter-spacing:-0.02em">Field Notes</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"xl","textColor":"muted","style":{"typography":{"fontWeight":"700","letterSpacing":"-0.02em"}}} -->
			<p class="has-muted-color has-text-color has-xl-font-size" style="font-weight:700;letter-spacing:-0.02em">Corriere Design</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"xl","textColor":"muted","style":{"typography":{"fontWeight":"700","letterSpacing":"-0.02em"}}} -->
			<p class="has-muted-color has-text-color has-xl-font-size" style="font-weight:700;letter-spacing:-0.02em">Kinfolk</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"xl","textColor":"muted","style":{"typography":{"fontWeight":"700","letterSpacing":"-0.02em"}}} -->
			<p class="has-muted-color has-text-color has-xl-font-size" style="font-weight:700;letter-spacing:-0.02em">The Outpost</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
