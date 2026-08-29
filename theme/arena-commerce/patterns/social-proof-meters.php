<?php
/**
 * Title: Social proof — ratings
 * Slug: arena-commerce/social-proof-meters
 * Categories: arena-commerce, text
 * Keywords: reviews, ratings, satisfaction, meter
 * Description: Rating distribution rendered as labelled progress bars. The hierarchy is dominated by the number, the bars are accessible text not charts.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-social-proof-meters","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-social-proof-meters wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="social-proof-meters" data-arena-family="Social" data-arena-module="social-score-meters">
	<!-- wp:columns {"className":"arena-social-proof-meters__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|70","top":"var:preset|spacing|60"}}}} -->
	<div class="arena-social-proof-meters__columns wp-block-columns">
		<!-- wp:column {"width":"45%"} -->
		<div class="wp-block-column" style="flex-basis:45%">
			<!-- wp:paragraph {"className":"arena-social-proof-meters__score","fontSize":"6xl"} -->
			<p class="arena-social-proof-meters__score has-6xl-font-size">4.9</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"sm","textColor":"muted"} -->
			<p class="has-muted-color has-text-color has-sm-font-size">from 12,000 verified buyers</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":"55%"} -->
		<div class="wp-block-column" style="flex-basis:55%">
			<!-- wp:list {"className":"arena-social-proof-meters__bars"} -->
			<ul class="arena-social-proof-meters__bars wp-block-list">
				<!-- wp:list-item { "className":"arena-meter" } --><li class="arena-meter"><span class="arena-meter__label">5 ★</span><span class="arena-meter__track"><span class="arena-meter__fill" style="inline-size:90%"></span></span></li><!-- /wp:list-item -->
				<!-- wp:list-item { "className":"arena-meter" } --><li class="arena-meter"><span class="arena-meter__label">4 ★</span><span class="arena-meter__track"><span class="arena-meter__fill" style="inline-size:7%"></span></span></li><!-- /wp:list-item -->
				<!-- wp:list-item { "className":"arena-meter" } --><li class="arena-meter"><span class="arena-meter__label">3 ★</span><span class="arena-meter__track"><span class="arena-meter__fill" style="inline-size:2%"></span></span></li><!-- /wp:list-item -->
				<!-- wp:list-item { "className":"arena-meter" } --><li class="arena-meter"><span class="arena-meter__label">2 ★</span><span class="arena-meter__track"><span class="arena-meter__fill" style="inline-size:1%"></span></span></li><!-- /wp:list-item -->
				<!-- wp:list-item { "className":"arena-meter" } --><li class="arena-meter"><span class="arena-meter__label">1 ★</span><span class="arena-meter__track"><span class="arena-meter__fill" style="inline-size:0%"></span></span></li><!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-social-proof-meters__meter-bar-distribution" data-arena-role="meter-bar-distribution"></span>
</div>
<!-- /wp:group -->
