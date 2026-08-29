<?php
/**
 * Title: Newsletter — benefit cards
 * Slug: arena-commerce/newsletter-cards
 * Categories: arena-conversion, text
 * Keywords: newsletter, benefits, cards, email
 * Description: Three reasons to join the list, presented as tappable cards with a single subscription action at the end. The grid is 3-up with a different density from the hero capture.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-newsletter-cards","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-newsletter-cards wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"level":2,"fontSize":"4xl"} -->
	<h2 class="wp-block-heading has-4xl-font-size">Why the waitlist is worth it</h2>
	<!-- /wp:heading -->
	<!-- wp:columns {"className":"arena-newsletter-cards__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50","top":"var:preset|spacing|50"}}}} -->
	<div class="arena-newsletter-cards__columns wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"arena-newsletter-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}}} -->
			<div class="arena-newsletter-card wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:paragraph {"className":"arena-newsletter-card__icon","fontSize":"2xl"} --><p class="arena-newsletter-card__icon has-2xl-font-size">01</p><!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"fontSize":"lg"} --><h3 class="wp-block-heading has-lg-font-size">Early access</h3><!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"sm","textColor":"muted"} --><p class="has-muted-color has-text-color has-sm-font-size">Two hours before the public drop.</p><!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"arena-newsletter-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}}} -->
			<div class="arena-newsletter-card wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:paragraph {"className":"arena-newsletter-card__icon","fontSize":"2xl"} --><p class="arena-newsletter-card__icon has-2xl-font-size">02</p><!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"fontSize":"lg"} --><h3 class="wp-block-heading has-lg-font-size">Repair access</h3><!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"sm","textColor":"muted"} --><p class="has-muted-color has-text-color has-sm-font-size">Priority pickup windows twice a year.</p><!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"arena-newsletter-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}}} -->
			<div class="arena-newsletter-card wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:paragraph {"className":"arena-newsletter-card__icon","fontSize":"2xl"} --><p class="arena-newsletter-card__icon has-2xl-font-size">03</p><!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"fontSize":"lg"} --><h3 class="wp-block-heading has-lg-font-size">No noise</h3><!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"sm","textColor":"muted"} --><p class="has-muted-color has-text-color has-sm-font-size">Unsubscribe in one click, always.</p><!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
	<!-- wp:buttons -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-arena-pill"} -->
		<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/newsletter">Join the list</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
