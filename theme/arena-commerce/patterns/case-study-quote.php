<?php
/**
 * Title: Social proof — case study pull
 * Slug: arena-commerce/case-study-quote
 * Categories: arena-commerce, text
 * Keywords: case study, quote, brand, feature, pull quote
 * Description: A pull-quote-led case study: a large quoted line dominates, with avatar, name, role and a contextual tag. The photo is inset alongside the quote (NOT a generic media-text split) so the hierarchy reads quote-first.
 * Viewport width: 1440
 *
 * @package Arena_Theme
 */

?>
<!-- wp:group {"align":"full","className":"arena-pull-case","backgroundColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained","contentSize":"64rem"}} -->
<div class="arena-pull-case wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--50)" data-arena-pattern="case-study-quote" data-arena-family="Social" data-arena-module="social-pull-avatar">
	<!-- wp:paragraph {"className":"is-style-arena-eyebrow","fontSize":"xs","textColor":"accent"} -->
	<p class="is-style-arena-eyebrow has-accent-color has-text-color has-xs-font-size" data-arena-context-tag>Guides' programme</p>
	<!-- /wp:paragraph -->

	<!-- wp:columns {"className":"arena-pull-case__columns","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|70","top":"var:preset|spacing|70"}}}} -->
	<div class="arena-pull-case__columns wp-block-columns">
		<!-- wp:column {"width":"35%"} -->
		<div class="wp-block-column" style="flex-basis:35%">
			<!-- wp:image {"sizeSlug":"arena-card","className":"arena-pull-case__portrait","style":{"border":{"radius":"var:preset|spacing|20"}}} -->
			<figure class="arena-pull-case__portrait wp-block-image size-arena-card" style="border-radius:var(--wp--preset--spacing|20)"><img src="" alt="Marta B., mountain guide" style="aspect-ratio:3/4;object-fit:cover"/></figure>
			<!-- /wp:image -->
			<!-- wp:group {"className":"arena-pull-case__byline","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
			<div class="arena-pull-case__byline wp-block-group" style="padding-top:var(--wp--preset--spacing--40)">
				<!-- wp:paragraph {"fontSize":"md","style":{"typography":{"fontWeight":"700"}}} -->
				<p class="has-md-font-size" style="font-weight:700" data-arena-attribution-name>Marta B.</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"sm","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-sm-font-size" data-arena-attribution-role>UIAGM mountain guide, Dolomites</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"65%"} -->
		<div class="wp-block-column" style="flex-basis:65%">
			<!-- wp:pullquote {"className":"arena-pull-case__quote","style":{"typography":{"fontSize":"var:preset|font-size|4xl","fontWeight":"700","lineHeight":"var:custom|line-height|heading"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}},"border":{"width":"0 0 0 4px","color":"var:preset|color|accent"}}} -->
			<figure class="arena-pull-case__quote wp-block-pullquote" style="border-left-color:var(--wp--preset--color--accent);border-left-width:4px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:var(--wp--preset--spacing--50)">
				<blockquote>
					<p>It is the only kit I take into the field.</p>
				</blockquote>
				<cite>Three seasons, zero failures.</cite>
			</figure>
			<!-- /wp:pullquote -->
			<!-- wp:paragraph {"fontSize":"md","textColor":"muted"} -->
			<p class="has-muted-color has-text-color has-md-font-size">Three hundred mountain professionals test every prototype and keep the ones that pass.</p>
			<!-- /wp:paragraph -->
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-arena-pill"} -->
				<div class="wp-block-button"><a class="wp-block-button__link is-style-arena-pill wp-element-button" href="/guides">Read the guide field report</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<span aria-hidden="true" class="arena-case-study-quote__case-pull-line" data-arena-role="case-pull-avatar"></span>
</div>
<!-- /wp:group -->
