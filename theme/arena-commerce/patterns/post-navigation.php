<?php
/**
 * Title: Post navigation
 * Slug: arena-commerce/post-navigation
 * Categories: arena-commerce, text
 * Keywords: navigation, previous, next, journal
 * Description: Previous / next post navigation with titles, rendered as an accessible two-slot list. Core blocks only.
 * Viewport width: 1200
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"className":"arena-post-nav-group","layout":{"type":"constrained"}} -->
<div class="arena-post-nav-group wp-block-group" data-arena-pattern="post-navigation" data-arena-family="Blog" data-arena-module="post-nav">
	<!-- wp:list {"className":"arena-post-nav","fontSize":"sm"} -->
	<ul class="arena-post-nav has-sm-font-size wp-block-list">
		<!-- wp:list-item -->
		<li>
			<!-- wp:post-navigation-link {"type":"previous","showTitle":true,"arrow":"arrow","className":"arena-post-nav__prev"} /-->
		</li>
		<!-- /wp:list-item -->
		<!-- wp:list-item -->
		<li>
			<!-- wp:post-navigation-link {"showTitle":true,"arrow":"arrow","className":"arena-post-nav__next"} /-->
		</li>
		<!-- /wp:list-item -->
	</ul>
	<!-- /wp:list -->
</div>
<!-- /wp:group -->
