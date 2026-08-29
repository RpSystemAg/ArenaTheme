<?php
/**
 * Title: Related posts
 * Slug: arena-commerce/related-posts
 * Categories: arena-commerce, featured, query
 * Keywords: related, recommended, journal, loop
 * Description: A three-card related-posts query driven by shared categories, with featured images and titles. Core blocks only.
 * Viewport width: 1200
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"className":"arena-related","style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="arena-related wp-block-group" data-arena-pattern="related-posts" data-arena-family="Blog" data-arena-module="related-query">
	<!-- wp:query {"queryId":10,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"taxQuery":{"category":1},"parents":[]},"className":"arena-related__query","layout":{"type":"constrained"}} -->
	<div class="arena-related__query wp-block-query">
		<!-- wp:post-template {"className":"arena-related__items","layout":{"type":"grid","columnCount":3}} -->
		<!-- wp:group {"className":"arena-related__item","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
		<div class="arena-related__item wp-block-group">
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2","sizeSlug":"arena-card","className":"arena-related__media"} /-->
			<!-- wp:post-title {"level":3,"isLink":true,"className":"arena-related__title","fontSize":"md"} /-->
			<!-- wp:post-date {"className":"arena-related__date","fontSize":"xs"} /-->
		</div>
		<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
