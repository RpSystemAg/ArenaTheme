<?php
/**
 * Title: Editorial — timeline
 * Slug: arena-commerce/editorial-timeline
 * Categories: arena-commerce, text
 * Keywords: timeline, story, process, history
 * Description: Vertical development timeline built from separators and a numbered list. The scroll axis is vertical, the hierarchy is chapter-first.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-editorial-timeline","backgroundColor":"foreground","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"52rem"}} -->
<div class="arena-editorial-timeline wp-block-group alignfull has-base-color has-foreground-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="editorial-timeline" data-arena-family="Editorial" data-arena-module="editorial-timeline">
	<!-- wp:paragraph {"fontSize":"xs","textColor":"accent"} -->
	<p class="has-accent-color has-text-color has-xs-font-size">How it was made</p>
	<!-- /wp:paragraph -->
	<!-- wp:heading {"level":2,"fontSize":"5xl"} -->
	<h2 class="wp-block-heading has-5xl-font-size">Four years to one product</h2>
	<!-- /wp:heading -->
	<!-- wp:separator {"className":"arena-editorial-timeline__intro"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity arena-editorial-timeline__intro"/>
	<!-- /wp:separator -->
	<!-- wp:list {"className":"arena-editorial-timeline__list"} -->
	<ol class="arena-editorial-timeline__list wp-block-list">
		<!-- wp:list-item --><li>Year one — 63 prototypes, all rejected.</li><!-- /wp:list-item -->
		<!-- wp:list-item --><li>Year two — the waterproof membrane is tuned in the rain room.</li><!-- /wp:list-item -->
		<!-- wp:list-item --><li>Year three — 240 field testers across 14 countries.</li><!-- /wp:list-item -->
		<!-- wp:list-item --><li>Year four — one shell enters the catalogue.</li><!-- /wp:list-item -->
	</ol>
	<!-- /wp:list -->
	<!-- wp:separator {"className":"arena-editorial-timeline__outro"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity arena-editorial-timeline__outro"/>
	<!-- /wp:separator -->
	<!-- wp:paragraph {"fontSize":"sm","textColor":"surface"} -->
	<p class="has-surface-color has-text-color has-sm-font-size">We publish the full field-test log with every product.</p>
	<!-- /wp:paragraph -->

	<span aria-hidden="true" class="arena-editorial-timeline__timeline-year-marker" data-arena-role="timeline-year-marker"></span>
</div>
<!-- /wp:group -->
