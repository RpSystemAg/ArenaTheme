<?php
/**
 * Title: FAQ accordion
 * Slug: arena-commerce/faq-accordion
 * Categories: arena-conversion, text
 * Keywords: faq, accordion, questions, answers, support
 * Description: Uses the native core Accordion block, so disclosure is handled by the browser and the block is keyboard and screen-reader correct with zero JavaScript shipped by the theme.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-faq","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="arena-faq wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"level":2,"align":"wide","fontSize":"4xl"} -->
	<h2 class="wp-block-heading alignwide has-4xl-font-size">Questions, answered plainly</h2>
	<!-- /wp:heading -->

	<!-- wp:accordion {"align":"wide","showIcon":true,"autoclose":false,"headingLevel":3} -->
	<div class="wp-block-accordion alignwide">
		<!-- wp:accordion-item -->
		<div class="wp-block-accordion-item">
			<!-- wp:accordion-heading {"title":"How long does delivery take?"} /-->
			<!-- wp:accordion-panel -->
			<div class="wp-block-accordion-panel">
				<!-- wp:paragraph -->
				<p>Orders placed before 15:00 CET leave the same day. Europe arrives in one to two working days, the rest of the world in three to five. You get a tracking link the moment the label is printed.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:accordion-panel -->
		</div>
		<!-- /wp:accordion-item -->

		<!-- wp:accordion-item -->
		<div class="wp-block-accordion-item">
			<!-- wp:accordion-heading {"title":"What does the two-year warranty cover?"} /-->
			<!-- wp:accordion-panel -->
			<div class="wp-block-accordion-panel">
				<!-- wp:paragraph -->
				<p>Any manufacturing fault: seams, zips, hardware and membrane delamination. Normal wear is not covered, but we will still repair it at cost, and we stock parts for ten years after a line is retired.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:accordion-panel -->
		</div>
		<!-- /wp:accordion-item -->

		<!-- wp:accordion-item -->
		<div class="wp-block-accordion-item">
			<!-- wp:accordion-heading {"title":"Can I return something I have worn?"} /-->
			<!-- wp:accordion-panel -->
			<div class="wp-block-accordion-panel">
				<!-- wp:paragraph -->
				<p>Yes, within 30 days, as long as it can be resold. The prepaid label is already in the parcel, so a return costs you nothing and takes one trip to a drop-off point.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:accordion-panel -->
		</div>
		<!-- /wp:accordion-item -->

		<!-- wp:accordion-item -->
		<div class="wp-block-accordion-item">
			<!-- wp:accordion-heading {"title":"Do you ship outside the EU?"} /-->
			<!-- wp:accordion-panel -->
			<div class="wp-block-accordion-panel">
				<!-- wp:paragraph -->
				<p>We ship to 60 countries. Duties are calculated and shown at checkout, so the price you pay is the price you owe — nothing arrives as a surprise on the doorstep.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:accordion-panel -->
		</div>
		<!-- /wp:accordion-item -->
	</div>
	<!-- /wp:accordion -->
</div>
<!-- /wp:group -->
