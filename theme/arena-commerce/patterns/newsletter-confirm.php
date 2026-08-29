<?php
/**
 * Title: Newsletter — confirmation
 * Slug: arena-commerce/newsletter-confirm
 * Categories: arena-conversion, text
 * Keywords: newsletter, confirm, success, email
 * Description: A low-density confirmation module for the post-subscribe state. One success symbol, one sentence and one route back. High whitespace, no imagery.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-newsletter-confirm","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"38rem"}} -->
<div class="arena-newsletter-confirm wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:group {"className":"arena-newsletter-confirm__card","style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"border":{"radius":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
	<div class="arena-newsletter-confirm__card wp-block-group" style="border-radius:var(--wp--preset--spacing--50);padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--40)">
		<!-- wp:paragraph {"className":"arena-newsletter-confirm__check","fontSize":"4xl","textColor":"success"} -->
		<p class="arena-newsletter-confirm__check has-success-color has-text-color has-4xl-font-size">✓</p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"textAlign":"center","level":2,"fontSize":"3xl"} -->
		<h2 class="wp-block-heading has-text-align-center has-3xl-font-size">You are on the list</h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","fontSize":"lg","textColor":"muted"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-lg-font-size">Check your inbox to confirm the address. The next field note arrives Friday.</p>
		<!-- /wp:paragraph -->
		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"is-style-arena-pill"} -->
			<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/shop">Keep browsing</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
