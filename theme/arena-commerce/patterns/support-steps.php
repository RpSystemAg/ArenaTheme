<?php
/**
 * Title: Support — steps
 * Slug: arena-commerce/support-steps
 * Categories: arena-conversion, text
 * Keywords: support, steps, process, repair
 * Description: A numbered repair journey in a single vertical column. Uses an ordered list and separators; scrolling is vertical and the progress hierarchy is explicit.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-support-steps","backgroundColor":"foreground","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"50rem"}} -->
<div class="arena-support-steps wp-block-group alignfull has-base-color has-foreground-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
	<h2 class="wp-block-heading has-4xl-font-size">Start a repair in four steps</h2>
	<!-- /wp:heading -->
	<!-- wp:list {"className":"arena-support-steps__steps"} -->
	<ol class="arena-support-steps__steps wp-block-list">
		<!-- wp:list-item --><li>Tell us the product and the fault.</li><!-- /wp:list-item -->
		<!-- wp:list-item --><li>Book a free pickup window.</li><!-- /wp:list-item -->
		<!-- wp:list-item --><li>Receive a fixed-price estimate by email.</li><!-- /wp:list-item -->
		<!-- wp:list-item --><li>Approve and your product ships back in 24 hours.</li><!-- /wp:list-item -->
	</ol>
	<!-- /wp:list -->
	<!-- wp:separator {"className":"arena-support-steps__rule"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity arena-support-steps__rule"/>
	<!-- /wp:separator -->
	<!-- wp:buttons -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-arena-pill"} -->
		<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/repairs">Open a repair ticket</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
