<?php
/**
 * Title: Editorial — quote strip
 * Slug: arena-commerce/editorial-quote-strip
 * Categories: arena-commerce, text
 * Keywords: editorial, quote, pull, brand
 * Description: A full-bleed cover quote with a single sentence and a small attribution. Built to read as a billboard when scaled to 16:9.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:cover {"url":"","dimRatio":68,"gradient":"canvas-surface","contentPosition":"center center","minHeight":56,"minHeightUnit":"vh","align":"full","className":"arena-editorial-quote-strip"} -->
<div class="arena-editorial-quote-strip wp-block-cover alignfull has-background-dim-68 has-background-dim" style="min-height:56vh">
	<span aria-hidden="true" class="wp-block-cover__gradient-background has-background-gradient has-canvas-surface-gradient-background"></span>
	<div class="wp-block-cover__inner-container">
		<!-- wp:group {"className":"arena-editorial-quote-strip__content","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"56rem"}} -->
		<div class="arena-editorial-quote-strip__content wp-block-group">
			<!-- wp:quote {"className":"arena-editorial-quote-strip__quote"} -->
			<blockquote class="arena-editorial-quote-strip__quote wp-block-quote">
				<p>We will not make anything we cannot repair.</p>
				<cite>Arena Labs · Design principles</cite>
			</blockquote>
			<!-- /wp:quote -->
		</div>
		<!-- /wp:group -->
	</div>
</div>
<!-- /wp:cover -->
