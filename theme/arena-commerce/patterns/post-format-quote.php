<?php
/**
 * Title: Post format: quote
 * Slug: arena-commerce/post-format-quote
 * Categories: arena-commerce, text, featured
 * Keywords: quote, post format, pull quote, journal
 * Description: Quote post-format lead: an oversized pull-quote with attribution, sized by the semantic quote type level (H24). Core blocks only.
 * Viewport width: 1200
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"className":"arena-format-quote","layout":{"type":"constrained"}} -->
<div class="arena-format-quote wp-block-group" data-arena-pattern="post-format-quote" data-arena-family="Blog" data-arena-module="format-quote-lead">
	<!-- wp:quote {"className":"arena-format-quote__quote","fontSize":"quote","style":{"typography":{"fontWeight":"500","lineHeight":"1.35"}}} -->
	<blockquote class="wp-block-quote has-quote-font-size arena-format-quote__quote" style="font-weight:500;line-height:1.35">
		<!-- wp:paragraph -->
		<p>Every constraint we accepted on paper turned into a decision we never had to make again at two in the morning.</p>
		<!-- /wp:paragraph -->
		<!-- wp:cite -->
		<cite>— The Arena editorial team</cite>
		<!-- /wp:cite -->
	</blockquote>
	<!-- /wp:quote -->
</div>
<!-- /wp:group -->
