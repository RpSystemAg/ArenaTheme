<?php
/**
 * Title: Conversion — split panel
 * Slug: arena-commerce/cta-split-panel
 * Categories: arena-conversion, call-to-action
 * Keywords: cta, split, panel, update
 * Description: Conversion module divided into a message pane and an image pane. The action sits on the right, within natural thumb reach, and the copy is limited to one sentence.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-cta-split","backgroundColor":"foreground","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-cta-split wp-block-group alignfull has-base-color has-foreground-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:columns {"className":"arena-cta-split__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60","top":"var:preset|spacing|60"}}}} -->
	<div class="arena-cta-split__columns wp-block-columns">
		<!-- wp:column {"width":"55%"} -->
		<div class="wp-block-column" style="flex-basis:55%">
			<!-- wp:paragraph {"fontSize":"xs","textColor":"accent"} -->
			<p class="has-accent-color has-text-color has-xs-font-size">Until the weekend</p>
			<!-- /wp:paragraph -->
			<!-- wp:heading {"level":2,"fontSize":"5xl"} -->
			<h2 class="wp-block-heading has-5xl-font-size">Last season's fit at the next season's price</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"lg","textColor":"surface"} -->
			<p class="has-surface-color has-text-color has-lg-font-size">Twenty percent off the previous colourway, while it lasts.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":"45%"} -->
		<div class="wp-block-column" style="flex-basis:45%">
			<!-- wp:image {"sizeSlug":"arena-featured","className":"arena-cta-split__media"} -->
			<figure class="arena-cta-split__media wp-block-image size-arena-featured"><img src="" alt="Last season colourway on rock"/></figure>
			<!-- /wp:image -->
			<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-arena-pill"} -->
				<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/shop">Take 20% off</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
