<?php
/**
 * Title: Trust bar
 * Slug: arena-commerce/trust-bar
 * Categories: arena-conversion, text
 * Keywords: trust, usp, shipping, returns, reassurance
 * Description: Four reassurance points in one row. Baymard reports 39 percent of abandonments come from unexpected costs and 21 percent from unclear delivery, so this copy sits above the fold.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-trust-bar","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"border":{"top":{"color":"var:preset|color|outline","width":"1px"},"bottom":{"color":"var:preset|color|outline","width":"1px"}}},"layout":{"type":"constrained"}} -->
<div class="arena-trust-bar wp-block-group alignfull has-surface-background-color has-background" style="border-top-color:var(--wp--preset--color--outline);border-top-width:1px;border-bottom-color:var(--wp--preset--color--outline);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="trust-bar" data-arena-family="Trust" data-arena-module="trust-bar-strip">
	<!-- wp:columns {"className":"arena-trust-bar__items","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60"}}}} -->
	<div class="arena-trust-bar__items wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":2,"fontSize":"sm","style":{"typography":{"fontWeight":"600"}}} -->
			<h2 class="wp-block-heading has-sm-font-size" style="font-weight:600">Free delivery over 75 EUR</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"xs","textColor":"muted"} -->
			<p class="has-muted-color has-text-color has-xs-font-size">Dispatched the same day before 15:00.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":2,"fontSize":"sm","style":{"typography":{"fontWeight":"600"}}} -->
			<h2 class="wp-block-heading has-sm-font-size" style="font-weight:600">30-day free returns</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"xs","textColor":"muted"} -->
			<p class="has-muted-color has-text-color has-xs-font-size">Prepaid label in every parcel.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":2,"fontSize":"sm","style":{"typography":{"fontWeight":"600"}}} -->
			<h2 class="wp-block-heading has-sm-font-size" style="font-weight:600">Two-year warranty</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"xs","textColor":"muted"} -->
			<p class="has-muted-color has-text-color has-xs-font-size">Repairs handled in-house.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":2,"fontSize":"sm","style":{"typography":{"fontWeight":"600"}}} -->
			<h2 class="wp-block-heading has-sm-font-size" style="font-weight:600">Secure payments</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"xs","textColor":"muted"} -->
			<p class="has-muted-color has-text-color has-xs-font-size">Card, wallet and invoice.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-trust-bar__trust-usp-row" data-arena-role="trust-usp-row"></span>
</div>
<!-- /wp:group -->
