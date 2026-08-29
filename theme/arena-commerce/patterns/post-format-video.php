<?php
/**
 * Title: Post format: video
 * Slug: arena-commerce/post-format-video
 * Categories: arena-commerce, media, featured
 * Keywords: video, post format, embed, journal
 * Description: Video post-format lead: a framed video embed with a caption slot, sized by the semantic caption level (H24). Core blocks only.
 * Viewport width: 1200
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"className":"arena-format-video","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="arena-format-video wp-block-group" data-arena-pattern="post-format-video" data-arena-family="Blog" data-arena-module="format-video-lead">
	<!-- wp:video {"className":"arena-format-video__player"} -->
	<figure class="wp-block-video arena-format-video__player"><video controls src="https://example.com/arena-demo.mp4"></video></figure>
	<!-- /wp:video -->

	<!-- wp:paragraph {"align":"center","className":"arena-format-video__caption","fontSize":"caption","textColor":"muted"} -->
	<p class="has-text-align-center has-caption-font-size has-muted-color has-text-color arena-format-video__caption">Field test footage, chapter 4.</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
