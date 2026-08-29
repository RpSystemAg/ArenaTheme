<?php
/**
 * Title: Order — confirmation
 * Slug: arena-commerce/order-confirmation
 * Categories: arena-commerce, text
 * Keywords: order, confirmation, success, receipt
 * Description: A single-column confirmation summary with a prominent order number and next-step action. Low density, centred, zero imagery.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-order-confirmation","backgroundColor":"foreground","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"38rem"}} -->
<div class="arena-order-confirmation wp-block-group alignfull has-base-color has-foreground-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:group {"className":"arena-order-confirmation__card","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
	<div class="arena-order-confirmation__card wp-block-group">
		<!-- wp:paragraph {"className":"arena-order-confirmation__check","fontSize":"4xl","textColor":"success"} -->
		<p class="arena-order-confirmation__check has-success-color has-text-color has-4xl-font-size">✓</p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"textAlign":"center","level":1,"fontSize":"5xl"} -->
		<h1 class="wp-block-heading has-text-align-center has-5xl-font-size">Order confirmed</h1>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","fontSize":"lg","textColor":"surface"} -->
		<p class="has-text-align-center has-surface-color has-text-color has-lg-font-size">AR-2026-0913 · A confirmation email is on its way.</p>
		<!-- /wp:paragraph -->
		<!-- wp:separator {"className":"arena-order-confirmation__rule"} -->
		<hr class="wp-block-separator has-alpha-channel-opacity arena-order-confirmation__rule"/>
		<!-- /wp:separator -->
		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"is-style-arena-pill"} -->
			<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/my-account">View order in account</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
