<?php
/**
 * Title: Footer zone: 5 columns
 * Slug: arena-commerce/footer-5-columns
 * Categories: arena-commerce, footer
 * Keywords: footer, columns, swap, zone
 * Description: Five-zone footer: brand with logo, two navigation columns, a magazine-style tag cloud and a newsletter card with badges. Swappable footer pattern (H30). Core blocks only.
 * Viewport width: 1400
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-footer arena-footer--5","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|70"}},"backgroundColor":"foreground","textColor":"base","layout":{"type":"constrained"}} -->
<div class="arena-footer arena-footer--5 wp-block-group alignfull has-base-color has-foreground-background-color has-text-color has-background" data-arena-pattern="footer-5-columns" data-arena-family="Footer" data-arena-module="footer-zone-5" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:columns {"className":"arena-footer__zones","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
	<div class="arena-footer__zones wp-block-columns">
		<!-- wp:column {"width":"22%"} -->
		<div class="wp-block-column" style="flex-basis:22%">
			<!-- wp:site-logo {"width":96,"className":"arena-footer__logo"} /-->
			<!-- wp:site-title {"level":3,"className":"arena-footer__brand","fontSize":"md"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":3,"className":"arena-footer__heading","fontSize":"xs","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.1em"}}} -->
			<h3 class="arena-footer__heading has-xs-font-size wp-block-heading" style="letter-spacing:0.1em;text-transform:uppercase">Shop</h3>
			<!-- /wp:heading -->
			<!-- wp:navigation {"overlayMenu":"never","className":"arena-footer__menu","layout":{"type":"flex","orientation":"vertical"}} -->
			<!-- wp:navigation-link {"label":"All products","url":"/shop/"} /-->
			<!-- wp:navigation-link {"label":"Gift cards","url":"/product/gift-card/"} /-->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":3,"className":"arena-footer__heading","fontSize":"xs","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.1em"}}} -->
			<h3 class="arena-footer__heading has-xs-font-size wp-block-heading" style="letter-spacing:0.1em;text-transform:uppercase">Company</h3>
			<!-- /wp:heading -->
			<!-- wp:navigation {"overlayMenu":"never","className":"arena-footer__menu","layout":{"type":"flex","orientation":"vertical"}} -->
			<!-- wp:navigation-link {"label":"Our story","url":"/about/"} /-->
			<!-- wp:navigation-link {"label":"Journal","url":"/blog/"} /-->
			<!-- wp:navigation-link {"label":"Contact","url":"/contact/"} /-->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":3,"className":"arena-footer__heading","fontSize":"xs","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.1em"}}} -->
			<h3 class="arena-footer__heading has-xs-font-size wp-block-heading" style="letter-spacing:0.1em;text-transform:uppercase">Topics</h3>
			<!-- /wp:heading -->
			<!-- wp:tag-cloud {"showTagCounts":true,"smallestFontSize":"0.7rem","largestFontSize":"0.9rem","className":"arena-footer__tags"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"arena-footer__card is-style-arena-card","style":{"spacing":{"padding":"var:preset|spacing|50","blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
			<div class="arena-footer__card is-style-arena-card wp-block-group" style="padding:var(--wp--preset--spacing--50)">
				<!-- wp:heading {"level":3,"className":"arena-footer__heading","fontSize":"xs","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.1em"}}} -->
				<h3 class="arena-footer__heading has-xs-font-size wp-block-heading" style="letter-spacing:0.1em;text-transform:uppercase">Newsletter</h3>
				<!-- /wp:heading -->
				<!-- wp:buttons {"layout":{"type":"flex"}} -->
				<div class="wp-block-buttons">
					<!-- wp:button {"className":"is-style-arena-pill","fontSize":"sm"} -->
					<div class="wp-block-button has-sm-font-size has-custom-font-size arena-footer__cta"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/newsletter/">Subscribe</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
				<!-- wp:paragraph {"fontSize":"xs"} -->
				<p class="has-xs-font-size">Secure payments · 30-day returns</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
