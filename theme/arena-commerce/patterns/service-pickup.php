<?php
/**
 * Title: Service — doorstep pickup
 * Slug: arena-commerce/service-pickup
 * Categories: arena-commerce, text
 * Keywords: repair, pickup, doorstep, service
 * Description: A media-text service module explaining the free doorstep pickup flow. Distinct from other media-text patterns by the vertical steps list inside the text side.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-service-pickup","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-service-pickup wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:media-text {"align":"wide","mediaPosition":"left","mediaId":4,"mediaType":"image","mediaSizeSlug":"arena-featured","verticalAlignment":"center","className":"arena-service-pickup__split"} -->
	<div class="wp-block-media-text alignwide is-stacked-on-mobile is-vertically-aligned-center arena-service-pickup__split">
		<figure class="wp-block-media-text__media"><img src="" alt="Courier picking up a repair parcel"/></figure>
		<div class="wp-block-media-text__content">
			<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
			<h2 class="wp-block-heading has-4xl-font-size">We collect, you don't queue</h2>
			<!-- /wp:heading -->
			<!-- wp:list {"className":"arena-service-pickup__steps"} -->
			<ol class="arena-service-pickup__steps wp-block-list">
				<!-- wp:list-item --><li>Book a collection window.</li><!-- /wp:list-item -->
				<!-- wp:list-item --><li>We collect from your door.</li><!-- /wp:list-item -->
				<!-- wp:list-item --><li>Your product returns in 24 hours.</li><!-- /wp:list-item -->
			</ol>
			<!-- /wp:list -->
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-arena-pill"} -->
				<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/repairs">Pick a time</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
	</div>
	<!-- /wp:media-text -->
</div>
<!-- /wp:group -->
