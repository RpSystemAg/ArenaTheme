<?php
/**
 * Title: Footer zone: 4 columns
 * Slug: arena-commerce/footer-4-columns
 * Categories: arena-commerce, footer
 * Keywords: footer, columns, swap, zone
 * Description: Four-zone footer built as link lists (no navigation block): brand, shop links, support links and contact with payment badges. Swappable footer pattern (H30). Core blocks only.
 * Viewport width: 1400
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-footer arena-footer--4","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|70"}},"backgroundColor":"foreground","textColor":"base","layout":{"type":"constrained"}} -->
<div class="arena-footer arena-footer--4 wp-block-group alignfull has-base-color has-foreground-background-color has-text-color has-background" data-arena-pattern="footer-4-columns" data-arena-family="Footer" data-arena-module="footer-zone-4" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:columns {"className":"arena-footer__zones","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|80"}}}} -->
	<div class="arena-footer__zones wp-block-columns">
		<!-- wp:column {"width":"33%"} -->
		<div class="wp-block-column" style="flex-basis:33%">
			<!-- wp:site-title {"level":2,"className":"arena-footer__brand","fontSize":"lg"} /-->
			<!-- wp:paragraph {"className":"arena-footer__tagline","fontSize":"sm"} -->
			<p class="arena-footer__tagline has-sm-font-size">Considered gear, honest prices.</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"xs"} -->
			<p class="has-xs-font-size">Via del Lavoro 4, 20100 Milano<br>hello@example.com</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":3,"className":"arena-footer__heading","fontSize":"sm","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}}} -->
			<h3 class="arena-footer__heading has-sm-font-size wp-block-heading" style="letter-spacing:0.08em;text-transform:uppercase">Shop</h3>
			<!-- /wp:heading -->
			<!-- wp:list {"className":"arena-footer__links","fontSize":"sm"} -->
			<ul class="arena-footer__links has-sm-font-size wp-block-list">
				<!-- wp:list-item --><li><a href="/shop/">All products</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="/product-category/new-in/">New in</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="/product-category/bestsellers/">Bestsellers</a></li><!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":3,"className":"arena-footer__heading","fontSize":"sm","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}}} -->
			<h3 class="arena-footer__heading has-sm-font-size wp-block-heading" style="letter-spacing:0.08em;text-transform:uppercase">Support</h3>
			<!-- /wp:heading -->
			<!-- wp:list {"className":"arena-footer__links","fontSize":"sm"} -->
			<ul class="arena-footer__links has-sm-font-size wp-block-list">
				<!-- wp:list-item --><li><a href="/shipping-returns/">Shipping &amp; returns</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="/my-account/">Track an order</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="/contact/">Contact us</a></li><!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":3,"className":"arena-footer__heading","fontSize":"sm","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}}} -->
			<h3 class="arena-footer__heading has-sm-font-size wp-block-heading" style="letter-spacing:0.08em;text-transform:uppercase">Good to know</h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"xs"} -->
			<p class="has-xs-font-size">Secure payments · 30-day returns · Carbon-neutral delivery</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"xs"} -->
			<p class="has-xs-font-size">© Arena Labs. All rights reserved.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
