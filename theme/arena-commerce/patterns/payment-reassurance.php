<?php
/**
 * Title: Order — payment reassurance
 * Slug: arena-commerce/payment-reassurance
 * Categories: arena-commerce, text
 * Keywords: payment, secure, reassurance, checkout
 * Description: Three payment-reassurance cells that sit right next to the purchase action. Each cell is an icon-plus-copy unit, structurally distinct from the trust bar because the icon is lead.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-payment-reassurance","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-payment-reassurance wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="payment-reassurance" data-arena-family="Checkout" data-arena-module="checkout-pay-icons">
	<!-- wp:columns {"className":"arena-payment-reassurance__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60","top":"var:preset|spacing|40"}}}} -->
	<div class="arena-payment-reassurance__columns wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"className":"arena-payment-reassurance__icon","fontSize":"2xl"} --><p class="arena-payment-reassurance__icon has-2xl-font-size">🔒</p><!-- /wp:paragraph -->
			<!-- wp:heading {"level":3,"fontSize":"sm"} --><h3 class="wp-block-heading has-sm-font-size">Encrypted checkout</h3><!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"xs","textColor":"muted"} --><p class="has-muted-color has-text-color has-xs-font-size">256-bit TLS on every page.</p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"className":"arena-payment-reassurance__icon","fontSize":"2xl"} --><p class="arena-payment-reassurance__icon has-2xl-font-size">💳</p><!-- /wp:paragraph -->
			<!-- wp:heading {"level":3,"fontSize":"sm"} --><h3 class="wp-block-heading has-sm-font-size">Cards and wallets</h3><!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"xs","textColor":"muted"} --><p class="has-muted-color has-text-color has-xs-font-size">Visa, Mastercard, PayPal and Apple Pay.</p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"className":"arena-payment-reassurance__icon","fontSize":"2xl"} --><p class="arena-payment-reassurance__icon has-2xl-font-size">↩</p><!-- /wp:paragraph -->
			<!-- wp:heading {"level":3,"fontSize":"sm"} --><h3 class="wp-block-heading has-sm-font-size">30-day refunds</h3><!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"xs","textColor":"muted"} --><p class="has-muted-color has-text-color has-xs-font-size">Paid back to the original method.</p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-payment-reassurance__payment-icon-cell" data-arena-role="payment-icon-cell"></span>
</div>
<!-- /wp:group -->
