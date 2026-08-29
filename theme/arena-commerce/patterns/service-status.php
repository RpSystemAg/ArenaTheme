<?php
/**
 * Title: Service — live status
 * Slug: arena-commerce/service-status
 * Categories: arena-commerce, text
 * Keywords: status, tracking, order, service
 * Description: A narrow service-status module with a status chip, an update list and a single action. Intentionally unlike the full order summary: one item, one status, zero totals.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-service-status","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"42rem"}} -->
<div class="arena-service-status wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:group {"className":"arena-service-status__chip","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="arena-service-status__chip wp-block-group">
		<!-- wp:paragraph {"fontSize":"sm"} --><p class="has-sm-font-size">Repair AR-0913</p><!-- /wp:paragraph -->
		<!-- wp:paragraph {"className":"arena-service-status__state","fontSize":"xs"} --><p class="arena-service-status__state has-xs-font-size">In repair · 4h left</p><!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
	<!-- wp:list {"className":"arena-service-status__timeline"} -->
	<ul class="arena-service-status__timeline wp-block-list">
		<!-- wp:list-item --><li>Collected today at 09:40</li><!-- /wp:list-item -->
		<!-- wp:list-item --><li>Estimate approved online</li><!-- /wp:list-item -->
		<!-- wp:list-item --><li>Now being repaired in-house</li><!-- /wp:list-item -->
	</ul>
	<!-- /wp:list -->
	<!-- wp:buttons -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-arena-pill"} -->
		<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/my-account">Open my account</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
