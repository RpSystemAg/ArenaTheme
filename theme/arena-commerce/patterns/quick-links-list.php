<?php
/**
 * Title: Discovery — quick link index
 * Slug: arena-commerce/quick-links-list
 * Categories: arena-commerce
 * Keywords: links, navigation, index, menu, sitemap
 * Description: A compact alphabetical A–Z route index with a live filter input. No imagery and no covers; the module is navigation-first with a jump-to-letter rail.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-quick-index","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="arena-quick-index wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="quick-links-list" data-arena-family="Discovery" data-arena-module="discovery-a-z-index">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
		<h2 class="wp-block-heading has-4xl-font-size">Shop index</h2>
		<!-- /wp:heading -->

		<!-- wp:html -->
		<div class="arena-quick-index__filter" data-arena-index-filter role="search" aria-label="Filter routes">
			<label class="sr-only" for="arena-quick-index-input">Filter routes</label>
			<input id="arena-quick-index-input" type="search" name="q" placeholder="Filter routes…" autocomplete="off" data-arena-index-input />
		</div>
		<nav class="arena-quick-index__letter-rail" aria-label="Jump to letter" data-arena-index-rail role="navigation">
			<a href="#idx-a" data-arena-index-letter>A</a><a href="#idx-b" data-arena-index-letter>B</a><a href="#idx-c" data-arena-index-letter>C</a><a href="#idx-d" data-arena-index-letter>D</a><a href="#idx-f" data-arena-index-letter>F</a><a href="#idx-g" data-arena-index-letter>G</a><a href="#idx-j" data-arena-index-letter>J</a><a href="#idx-n" data-arena-index-letter>N</a><a href="#idx-o" data-arena-index-letter>O</a><a href="#idx-p" data-arena-index-letter>P</a><a href="#idx-r" data-arena-index-letter>R</a><a href="#idx-s" data-arena-index-letter>S</a><a href="#idx-w" data-arena-index-letter>W</a>
		</nav>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->

	<!-- wp:columns {"className":"arena-quick-index__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60","top":"var:preset|spacing|60"}}}} -->
	<div class="arena-quick-index__columns wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column" data-arena-index-section id="idx-all">
			<!-- wp:heading {"level":3,"className":"arena-quick-index__letter","fontSize":"sm"} -->
			<h3 class="arena-quick-index__letter wp-block-heading has-sm-font-size">All</h3>
			<!-- /wp:heading -->
			<!-- wp:navigation {"className":"arena-quick-index__menu","overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
			<!-- wp:navigation-link {"label":"All products","url":"/shop/","title":"All products"} /-->
			<!-- wp:navigation-link {"label":"New in","url":"/product-category/new-in/","title":"New in"} /-->
			<!-- wp:navigation-link {"label":"Last chance","url":"/product-category/last-chance/","title":"Last chance"} /-->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column" data-arena-index-section>
			<!-- wp:heading {"level":3,"className":"arena-quick-index__letter","fontSize":"sm"} -->
			<h3 class="arena-quick-index__letter wp-block-heading has-sm-font-size">Services</h3>
			<!-- /wp:heading -->
			<!-- wp:navigation {"className":"arena-quick-index__menu","overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
			<!-- wp:navigation-link {"label":"Gift cards","url":"/product/gift-card/","title":"Gift cards"} /-->
			<!-- wp:navigation-link {"label":"Second life","url":"/second-life/","title":"Second life"} /-->
			<!-- wp:navigation-link {"label":"Repairs","url":"/repairs/","title":"Repairs"} /-->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-quick-links-list__nav-current-item" data-arena-role="nav-index-filter"></span>
</div>
<!-- /wp:group -->
