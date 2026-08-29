<?php
/**
 * Title: Service — membership tiers
 * Slug: arena-commerce/service-membership
 * Categories: arena-commerce, text
 * Keywords: membership, tiers, service, repairs
 * Description: Three service tiers arranged as a pricing ladder. Each tier is a card with a heading, a price paragraph and a single button; the hierarchy is price-first.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-service-membership","backgroundColor":"foreground","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-service-membership wp-block-group alignfull has-base-color has-foreground-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="service-membership" data-arena-family="Service" data-arena-module="service-tier-pricing">
	<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
	<h2 class="wp-block-heading has-4xl-font-size">A service level for every kit</h2>
	<!-- /wp:heading -->
	<!-- wp:columns {"className":"arena-service-membership__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50","top":"var:preset|spacing|50"}}}} -->
	<div class="arena-service-membership__columns wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"arena-service-tier","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}}} -->
			<div class="arena-service-tier wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:heading {"level":3,"fontSize":"lg"} --><h3 class="wp-block-heading has-lg-font-size">Basic</h3><!-- /wp:heading -->
				<!-- wp:paragraph {"className":"arena-service-tier__price","fontSize":"3xl"} --><p class="arena-service-tier__price has-3xl-font-size">€0</p><!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"sm","textColor":"surface"} --><p class="has-surface-color has-text-color has-sm-font-size">Two-year warranty and standard repairs.</p><!-- /wp:paragraph -->
				<!-- wp:button {"className":"is-style-arena-pill"} --><div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/service">Choose</a></div><!-- /wp:button -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"arena-service-tier is-style-arena-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}}} -->
			<div class="arena-service-tier is-style-arena-card wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:heading {"level":3,"fontSize":"lg"} --><h3 class="wp-block-heading has-lg-font-size">Guide</h3><!-- /wp:heading -->
				<!-- wp:paragraph {"className":"arena-service-tier__price","fontSize":"3xl"} --><p class="arena-service-tier__price has-3xl-font-size">€29/yr</p><!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"sm","textColor":"surface"} --><p class="has-surface-color has-text-color has-sm-font-size">Priority pickups and free adjustments.</p><!-- /wp:paragraph -->
				<!-- wp:button {"className":"is-style-arena-pill"} --><div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/service">Choose</a></div><!-- /wp:button -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"arena-service-tier","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}}} -->
			<div class="arena-service-tier wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:heading {"level":3,"fontSize":"lg"} --><h3 class="wp-block-heading has-lg-font-size">Pro</h3><!-- /wp:heading -->
				<!-- wp:paragraph {"className":"arena-service-tier__price","fontSize":"3xl"} --><p class="arena-service-tier__price has-3xl-font-size">€79/yr</p><!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"sm","textColor":"surface"} --><p class="has-surface-color has-text-color has-sm-font-size">Repairs in 24 hours and free express both ways.</p><!-- /wp:paragraph -->
				<!-- wp:button {"className":"is-style-arena-pill"} --><div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/service">Choose</a></div><!-- /wp:button -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-service-membership__tier-price-card" data-arena-role="tier-price-card"></span>
</div>
<!-- /wp:group -->
