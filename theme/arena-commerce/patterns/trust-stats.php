<?php
/**
 * Title: Trust — measured stats
 * Slug: arena-commerce/trust-stats
 * Categories: arena-conversion, text
 * Keywords: trust, stats, numbers, proof
 * Description: Three large numbers with tiny captions. Uses the type scale instead of icons, leaving the numbers as the dominant hierarchy.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-trust-stats","backgroundColor":"foreground","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-trust-stats wp-block-group alignfull has-base-color has-foreground-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="trust-stats" data-arena-family="Trust" data-arena-module="trust-stats-dark">
	<!-- wp:paragraph {"className":"is-style-arena-eyebrow arena-eyebrow","fontSize":"xs"} -->
	<p class="is-style-arena-eyebrow arena-eyebrow has-xs-font-size">Audited numbers</p>
	<!-- /wp:paragraph -->
	<!-- wp:columns {"className":"arena-trust-stats__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|70","top":"var:preset|spacing|50"}}}} -->
	<div class="arena-trust-stats__columns wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"level":0,"className":"arena-trust-stats__value","fontSize":"6xl"} -->
			<p class="arena-trust-stats__value has-6xl-font-size">98%</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"sm","textColor":"surface"} -->
			<p class="has-surface-color has-text-color has-sm-font-size">of orders delivered on the promised day</p>
			<!-- /wp:paragraph -->
			<!-- wp:separator {"className":"arena-trust-stats__rule"} -->
			<hr class="wp-block-separator has-alpha-channel-opacity arena-trust-stats__rule"/>
			<!-- /wp:separator -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"level":0,"className":"arena-trust-stats__value","fontSize":"6xl"} -->
			<p class="arena-trust-stats__value has-6xl-font-size">24h</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"sm","textColor":"surface"} -->
			<p class="has-surface-color has-text-color has-sm-font-size">average repair turnaround, including pickup</p>
			<!-- /wp:paragraph -->
			<!-- wp:separator {"className":"arena-trust-stats__rule"} -->
			<hr class="wp-block-separator has-alpha-channel-opacity arena-trust-stats__rule"/>
			<!-- /wp:separator -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"level":0,"className":"arena-trust-stats__value","fontSize":"6xl"} -->
			<p class="arena-trust-stats__value has-6xl-font-size">4.9</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"sm","textColor":"surface"} -->
			<p class="has-surface-color has-text-color has-sm-font-size">average from 12,000 verified reviews</p>
			<!-- /wp:paragraph -->
			<!-- wp:separator {"className":"arena-trust-stats__rule"} -->
			<hr class="wp-block-separator has-alpha-channel-opacity arena-trust-stats__rule"/>
			<!-- /wp:separator -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-trust-stats__trust-stat-number" data-arena-role="trust-stat-number"></span>
</div>
<!-- /wp:group -->
