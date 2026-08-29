<?php
/**
 * Title: Conversion — countdown
 * Slug: arena-commerce/cta-countdown
 * Categories: arena-conversion, call-to-action
 * Keywords: cta, countdown, drop, launch
 * Description: A countdown-style conversion module. The hierarchy is lead-promise → date units → action, and the units are list items rather than a scripted timer, so it degrades to static copy when JavaScript is unavailable.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-cta-countdown","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-cta-countdown wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="cta-countdown" data-arena-family="Conversion" data-arena-module="cta-countdown-live">
	<!-- wp:columns {"className":"arena-cta-countdown__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60","top":"var:preset|spacing|60"}}}} -->
	<div class="arena-cta-countdown__columns wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
			<h2 class="wp-block-heading has-4xl-font-size">Drop 04 opens Friday</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"lg","textColor":"muted"} -->
			<p class="has-muted-color has-text-color has-lg-font-size">Eleven products. One release. Waitlist gets two hours of early access on Friday.</p>
			<!-- /wp:paragraph -->
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-arena-pill"} -->
				<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/waitlist">Join the waitlist</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:list {"className":"arena-cta-countdown__units"} -->
			<ol class="arena-cta-countdown__units wp-block-list">
				<!-- wp:list-item --><li><span class="arena-cta-countdown__unit">02</span><span class="arena-cta-countdown__caption">days</span></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><span class="arena-cta-countdown__unit">14</span><span class="arena-cta-countdown__caption">hours</span></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><span class="arena-cta-countdown__unit">38</span><span class="arena-cta-countdown__caption">minutes</span></li><!-- /wp:list-item -->
			</ol>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-cta-countdown__countdown-unit-tick" data-arena-role="countdown-unit-tick"></span>
</div>
<!-- /wp:group -->
