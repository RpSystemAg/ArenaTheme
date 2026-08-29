<?php
/**
 * Title: Conversion — inline band
 * Slug: arena-commerce/cta-inline-band
 * Categories: arena-conversion, call-to-action
 * Keywords: cta, band, inline, subscribe
 * Description: Bare, single-row conversion band. One line, one button, no imagery. The opposite of the cover CTA in density; both survive the billboard test in different registers.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-cta-inline","backgroundColor":"accent","textColor":"foreground","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="arena-cta-inline wp-block-group alignfull has-foreground-color has-accent-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"level":2,"fontSize":"2xl"} -->
		<h2 class="wp-block-heading has-2xl-font-size">One email a week. No noise.</h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"fontSize":"sm"} -->
		<p class="has-sm-font-size">Restocks, repairs and real field-test notes.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
	<!-- wp:navigation {"className":"arena-cta-inline__action","overlayMenu":"never","layout":{"type":"flex","orientation":"horizontal","justifyContent":"left"}} -->
	<!-- wp:navigation-link {"label":"Get the newsletter","url":"/newsletter/","title":"Get the newsletter"} /-->
	<!-- /wp:navigation -->
</div>
<!-- /wp:group -->
