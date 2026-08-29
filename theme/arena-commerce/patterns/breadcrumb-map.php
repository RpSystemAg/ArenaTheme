<?php
/**
 * Title: Discovery — breadcrumb map
 * Slug: arena-commerce/breadcrumb-map
 * Categories: arena-commerce
 * Keywords: breadcrumb, map, path, discovery
 * Description: A breadcrumb-style orientation module plus a photo strip. The scroll axis is horizontal for the strip; the hierarchy is path → destination.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-breadcrumb-map","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-breadcrumb-map wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="breadcrumb-map" data-arena-family="Discovery" data-arena-module="discovery-breadcrumb-rail">
	<!-- wp:group {"className":"arena-breadcrumb-map__crumbs","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
	<div class="arena-breadcrumb-map__crumbs wp-block-group">
		<!-- wp:paragraph {"fontSize":"xs","textColor":"muted"} --><p class="has-muted-color has-text-color has-xs-font-size">From</p><!-- /wp:paragraph -->
		<!-- wp:paragraph {"fontSize":"xs"} --><p class="has-xs-font-size">Shop</p><!-- /wp:paragraph -->
		<!-- wp:paragraph {"fontSize":"xs"} --><p class="has-xs-font-size">→</p><!-- /wp:paragraph -->
		<!-- wp:paragraph {"fontSize":"xs"} --><p class="has-xs-font-size">Shells</p><!-- /wp:paragraph -->
		<!-- wp:paragraph {"fontSize":"xs"} --><p class="has-xs-font-size">→</p><!-- /wp:paragraph -->
		<!-- wp:paragraph {"fontSize":"xs","textColor":"accent"} --><p class="has-accent-color has-text-color has-xs-font-size">Expedition</p><!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
	<!-- wp:group {"className":"arena-breadcrumb-map__rail arena-carousel__viewport","layout":{"type":"flex","flexWrap":"nowrap"}} -->
	<div class="arena-breadcrumb-map__rail arena-carousel__viewport wp-block-group">
		<!-- wp:image {"sizeSlug":"arena-card","className":"arena-breadcrumb-map__shot"} --><figure class="arena-breadcrumb-map__shot wp-block-image size-arena-card"><img src="" alt="Expedition shell on ridge"/></figure><!-- /wp:image -->
		<!-- wp:image {"sizeSlug":"arena-card","className":"arena-breadcrumb-map__shot"} --><figure class="arena-breadcrumb-map__shot wp-block-image size-arena-card"><img src="" alt="Expedition pack detail"/></figure><!-- /wp:image -->
		<!-- wp:image {"sizeSlug":"arena-card","className":"arena-breadcrumb-map__shot"} --><figure class="arena-breadcrumb-map__shot wp-block-image size-arena-card"><img src="" alt="Expedition boots"/></figure><!-- /wp:image -->
	</div>
	<!-- /wp:group -->

	<span aria-hidden="true" class="arena-breadcrumb-map__breadcrumb-snap-rail" data-arena-role="breadcrumb-snap-rail"></span>
</div>
<!-- /wp:group -->
