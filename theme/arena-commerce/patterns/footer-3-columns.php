<?php
/**
 * Title: Footer zone: 3 columns
 * Slug: arena-commerce/footer-3-columns
 * Categories: arena-commerce, footer
 * Keywords: footer, columns, swap, zone
 * Description: Three-zone footer — brand with social links, one navigation column, and a search + newsletter column. Swappable footer pattern (H30). Core blocks only.
 * Viewport width: 1400
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-footer arena-footer--3","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"backgroundColor":"foreground","textColor":"base","layout":{"type":"constrained"}} -->
<div class="arena-footer arena-footer--3 wp-block-group alignfull has-base-color has-foreground-background-color has-text-color has-background" data-arena-pattern="footer-3-columns" data-arena-family="Footer" data-arena-module="footer-zone-3" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:columns {"className":"arena-footer__columns","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|70"}}}} -->
	<div class="arena-footer__columns wp-block-columns">
		<!-- wp:column {"width":"40%"} -->
		<div class="wp-block-column" style="flex-basis:40%">
			<!-- wp:site-title {"level":2,"className":"arena-footer__brand","fontSize":"lg"} /-->
			<!-- wp:social-links {"className":"arena-footer__social","size":"has-small-size","layout":{"type":"flex","justifyContent":"left"}} -->
			<ul class="arena-footer__social wp-block-social-links has-small-icon-size">
				<!-- wp:social-link {"url":"https://example.com","service":"x"} /-->
				<!-- wp:social-link {"url":"https://example.com","service":"mastodon"} /-->
			</ul>
			<!-- /wp:social-links -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":3,"className":"arena-footer__heading","fontSize":"sm","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}}} -->
			<h3 class="arena-footer__heading has-sm-font-size wp-block-heading" style="letter-spacing:0.08em;text-transform:uppercase">Explore</h3>
			<!-- /wp:heading -->
			<!-- wp:navigation {"overlayMenu":"never","className":"arena-footer__menu","layout":{"type":"flex","orientation":"vertical"}} -->
			<!-- wp:navigation-link {"label":"Shop","url":"/shop/"} /-->
			<!-- wp:navigation-link {"label":"Journal","url":"/blog/"} /-->
			<!-- wp:navigation-link {"label":"Contact","url":"/contact/"} /-->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":3,"className":"arena-footer__heading","fontSize":"sm","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}}} -->
			<h3 class="arena-footer__heading has-sm-font-size wp-block-heading" style="letter-spacing:0.08em;text-transform:uppercase">Newsletter</h3>
			<!-- /wp:heading -->
			<!-- wp:search {"label":"Subscribe","showLabel":false,"placeholder":"Your email","buttonText":"Subscribe","buttonPosition":"button-inside","buttonUseIcon":true,"className":"arena-footer__search"} /-->
			<!-- wp:paragraph {"fontSize":"xs"} -->
			<p class="has-xs-font-size">One email a week. Unsubscribe anytime.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
