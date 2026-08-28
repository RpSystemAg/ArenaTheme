<?php
/**
 * Title: Newsletter signup
 * Slug: arena-commerce/newsletter-signup
 * Categories: arena-conversion, text
 * Keywords: newsletter, signup, email, subscribe, gdpr
 * Description: Labelled email field with correct autocomplete, an explicit consent checkbox and a clear unsubscribe promise. Replace the action URL with your own provider endpoint or swap in a form block.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-newsletter","backgroundColor":"foreground","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="arena-newsletter wp-block-group alignfull has-base-color has-foreground-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"level":2,"align":"center","fontSize":"4xl"} -->
	<h2 class="wp-block-heading aligncenter has-4xl-font-size">Restocks before anyone else</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","textColor":"surface","fontSize":"lg"} -->
	<p class="has-surface-color has-text-color has-lg-font-size">One email a week. No tracking pixels, no third-party sharing, unsubscribe in one click.</p>
	<!-- /wp:paragraph -->

	<!-- wp:html -->
	<form class="arena-form arena-form--newsletter" action="/newsletter" method="post" novalidate>
		<div class="arena-form__row">
			<label for="arena-newsletter-email">Email address</label>
			<input id="arena-newsletter-email" name="email" type="email" inputmode="email" autocomplete="email" required aria-describedby="arena-newsletter-hint" placeholder="you@example.com" />
			<button class="wp-element-button is-style-arena-pill" type="submit">Subscribe</button>
		</div>
		<label class="arena-form__consent">
			<input type="checkbox" name="consent" value="1" required />
			<span id="arena-newsletter-hint">I agree to receive the weekly email. I can unsubscribe at any time.</span>
		</label>
	</form>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->
