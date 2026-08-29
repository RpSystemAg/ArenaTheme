<?php
/**
 * Title: Discovery — quick link list
 * Slug: arena-commerce/quick-links-list
 * Categories: arena-commerce
 * Keywords: links, navigation, index, menu
 * Description: A compact vertical index of routes, laid out as a two-column menu. No imagery and no covers; the module is navigation-first with the current page highlighted.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-quick-links","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-quick-links wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
	<h2 class="wp-block-heading has-4xl-font-size">Shop index</h2>
	<!-- /wp:heading -->
	<!-- wp:columns {"className":"arena-quick-links__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60","top":"var:preset|spacing|60"}}}} -->
	<div class="arena-quick-links__columns wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:navigation {"className":"arena-quick-links__menu","overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
			<!-- wp:navigation-link {"label":"All products","url":"/shop/","title":"All products"} /-->
			<!-- wp:navigation-link {"label":"New in","url":"/product-category/new-in/","title":"New in"} /-->
			<!-- wp:navigation-link {"label":"Last chance","url":"/product-category/last-chance/","title":"Last chance"} /-->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:navigation {"className":"arena-quick-links__menu","overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
			<!-- wp:navigation-link {"label":"Gift cards","url":"/product/gift-card/","title":"Gift cards"} /-->
			<!-- wp:navigation-link {"label":"Second life","url":"/second-life/","title":"Second life"} /-->
			<!-- wp:navigation-link {"label":"Field notes","url":"/journal/","title":"Field notes"} /-->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
