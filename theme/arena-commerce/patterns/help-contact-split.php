<?php
/**
 * Title: Support — contact split
 * Slug: arena-commerce/help-contact-split
 * Categories: arena-conversion, text
 * Keywords: support, contact, form, help
 * Description: A two-column support contact module: quick links on the left, a compact manually-authored contact form on the right. Uses a real HTML form inside core/html so no unknown block is registered.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-help-contact","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="arena-help-contact wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="help-contact-split" data-arena-family="Support" data-arena-module="support-contact-form">
	<!-- wp:columns {"className":"arena-help-contact__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60","top":"var:preset|spacing|60"}}}} -->
	<div class="arena-help-contact__columns wp-block-columns">
		<!-- wp:column {"width":"38%"} -->
		<div class="wp-block-column" style="flex-basis:38%">
			<!-- wp:heading {"level":2,"fontSize":"3xl"} -->
			<h2 class="wp-block-heading has-3xl-font-size">Still stuck?</h2>
			<!-- /wp:heading -->
			<!-- wp:navigation {"className":"arena-help-contact__links","overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
			<!-- wp:navigation-link {"label":"Order status","url":"/my-account/","title":"Order status"} /-->
			<!-- wp:navigation-link {"label":"Size guide","url":"/size-guide/","title":"Size guide"} /-->
			<!-- wp:navigation-link {"label":"Delivery zones","url":"/shipping/","title":"Delivery zones"} /-->
			<!-- wp:navigation-link {"label":"Care instructions","url":"/care/","title":"Care instructions"} /-->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":"62%"} -->
		<div class="wp-block-column" style="flex-basis:62%">
			<!-- wp:html -->
			<form class="arena-form arena-help-contact__form" action="#" method="post">
				<label for="arena-help-name">Name</label>
				<input id="arena-help-name" name="arena-help-name" type="text" autocomplete="name" required="">
				<label for="arena-help-email">Email</label>
				<input id="arena-help-email" name="arena-help-email" type="email" autocomplete="email" required="">
				<label for="arena-help-order">Order number <span aria-hidden="true">(optional)</span></label>
				<input id="arena-help-order" name="arena-help-order" type="text" autocomplete="off">
				<label for="arena-help-message">What happened?</label>
				<textarea id="arena-help-message" name="arena-help-message" rows="4" required=""></textarea>
				<button type="submit" class="wp-element-button">Send to support</button>
			</form>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-help-contact-split__contact-form-field" data-arena-role="contact-form-field"></span>
</div>
<!-- /wp:group -->
