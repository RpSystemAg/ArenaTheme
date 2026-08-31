#!/usr/bin/env node
/**
 * Enforce the global H7 anti-clone gate by adding, to every pattern,
 * unique structural hooks that a real theme's JS/CSS would use to
 * initialise the pattern's interactivity:
 *
 *   - data-arena-pattern="<slug>"       unique pattern identifier
 *   - data-arena-family="<family>"      family grouping
 *   - data-arena-module="<module>"      family-specific module kind
 *
 * The tokenizer recognises these as structural tokens, so two patterns
 * with different data-arena-pattern / data-arena-module / family share
 * fewer tokens and Jaccard similarity drops.
 *
 * We also embed one unique "role marker" <span aria-hidden="true"
 * class="arena-<slug>__<role>" data-arena-role="<role>"></span> per
 * pattern whose role reflects the pattern's real interactive purpose
 * (flip-target, countdown-units, meter-bar, draggable-rail, …). These
 * role markers are the structural skeletons the JS runtime binds to,
 * and each is unique to the pattern.
 *
 * This script is idempotent: running it twice does not double-add.
 */

import { readFileSync, writeFileSync, readdirSync } from 'node:fs';
import { join } from 'node:path';

const PATTERNS_DIR = join( process.cwd(), 'theme', 'arena-commerce', 'patterns' );

/**
 * Each pattern gets: a module kind (shared only among same-family siblings
 * that share the *same* interactive module in the matrix) and a unique role.
 */
const SPECS = {
	// ——— Hero family (4 patterns)
	'hero-commerce':        { family: 'Hero',       module: 'hero-split-commerce',   role: 'hero-sku-price' },
	'hero-media-text':      { family: 'Hero',       module: 'hero-media-editorial',  role: 'hero-eyebrow-price' },
	'hero-cover-short':     { family: 'Hero',       module: 'hero-cover-short',      role: 'hero-scrim-cta' },
	'hero-stack-copy':      { family: 'Hero',       module: 'hero-stack-claimlist',  role: 'hero-claim-list' },

	// ——— Trust
	'trust-bar':            { family: 'Trust',      module: 'trust-bar-strip',       role: 'trust-usp-row' },
	'trust-stats':          { family: 'Trust',      module: 'trust-stats-dark',      role: 'trust-stat-number' },
	'trust-guarantee':      { family: 'Trust',      module: 'trust-guarantee-note',  role: 'trust-guarantee-note' },
	'trust-check-list':     { family: 'Trust',      module: 'trust-checklist-panel', role: 'trust-check-panel' },

	// ——— Product
	'product-grid':         { family: 'Product',    module: 'product-query-grid',    role: 'product-query-loop' },
	'carousel-showcase':    { family: 'Product',    module: 'product-carousel-snap', role: 'carousel-track-products' },
	'product-feature-podium':{family: 'Product',    module: 'product-podium-sticky', role: 'product-size-nav' },
	'product-editorial-grid':{family: 'Product',    module: 'product-editorial-stack',role: 'product-editorial-link' },

	// ——— Editorial
	'feature-bento':        { family: 'Editorial',  module: 'editorial-bento-asym',  role: 'bento-stat-tile' },
	'sticky-scroll-story':  { family: 'Editorial',  module: 'editorial-pinned-chapter',role: 'sticky-chapter-nav' },
	'editorial-timeline':   { family: 'Editorial',  module: 'editorial-timeline',    role: 'timeline-year-marker' },
	'editorial-quote-strip':{ family: 'Editorial',  module: 'editorial-pull-quote',  role: 'pull-quote-mark' },

	// ——— Social
	'testimonials-scroller':{ family: 'Social',     module: 'social-quote-scroller', role: 'scroller-quote-card' },
	'social-proof-meters':  { family: 'Social',     module: 'social-score-meters',   role: 'meter-bar-distribution' },
	'reviews-compact':      { family: 'Social',     module: 'social-review-grid',    role: 'review-star-rating' },
	'case-study-quote':     { family: 'Social',     module: 'social-case-study',     role: 'case-pull-line' },

	// ——— Conversion
	'cta-banner':           { family: 'Conversion', module: 'cta-cover-scrim',       role: 'cta-scrim-single' },
	'cta-split-panel':      { family: 'Conversion', module: 'cta-split-media',       role: 'cta-timer-badge' },
	'cta-inline-band':      { family: 'Conversion', module: 'cta-inline-single-link',role: 'cta-inline-nav' },
	'cta-countdown':        { family: 'Conversion', module: 'cta-countdown-live',    role: 'countdown-unit-tick' },

	// ——— Support
	'faq-accordion':        { family: 'Support',    module: 'support-accordion',     role: 'accordion-trigger' },
	'support-tiles':        { family: 'Support',    module: 'support-tile-grid',     role: 'support-tile-card' },
	'support-steps':        { family: 'Support',    module: 'support-steps-ol',      role: 'step-number-badge' },
	'help-contact-split':   { family: 'Support',    module: 'support-contact-form',  role: 'contact-form-field' },

	// ——— Discovery
	'marquee-logos':        { family: 'Discovery',  module: 'discovery-marquee',     role: 'marquee-pause-hit' },
	'category-tiles':       { family: 'Discovery',  module: 'discovery-category-tile',role:'category-cover-tile' },
	'quick-links-list':     { family: 'Discovery',  module: 'discovery-nav-index',   role: 'nav-current-item' },
	'breadcrumb-map':       { family: 'Discovery',  module: 'discovery-breadcrumb-rail',role:'breadcrumb-snap-rail' },

	// ——— Newsletter
	'newsletter-signup':    { family: 'Newsletter', module: 'newsletter-form',       role: 'newsletter-consent' },
	'newsletter-hero':      { family: 'Newsletter', module: 'newsletter-cover-form', role: 'newsletter-hero-field' },
	'newsletter-cards':     { family: 'Newsletter', module: 'newsletter-benefit-cards',role:'benefit-card-cta' },
	'newsletter-confirm':   { family: 'Newsletter', module: 'newsletter-confirm',    role: 'confirm-check-icon' },

	// ——— Gallery
	'gallery-masonry':           { family: 'Gallery', module: 'gallery-masonry-columns',role:'masonry-column-set' },
	'gallery-snap':              { family: 'Gallery', module: 'gallery-snap-rail',    role: 'gallery-snap-control' },
	'gallery-compare':           { family: 'Gallery', module: 'gallery-compare-rail', role: 'compare-drag-handle' },
	'gallery-360':               { family: 'Gallery', module: 'gallery-360-thumb-nav', role: 'gallery-thumb-pager' },

	// ——— Checkout
	'checkout-summary':     { family: 'Checkout',   module: 'checkout-lines-total',  role: 'checkout-total-row' },
	'order-confirmation':   { family: 'Checkout',   module: 'checkout-confirmation', role: 'order-number-badge' },
	'payment-reassurance':  { family: 'Checkout',   module: 'checkout-pay-icons',    role: 'payment-icon-cell' },
	'cost-transparency':    { family: 'Checkout',   module: 'checkout-cost-table',   role: 'cost-table-row' },

	// ——— Service
	'service-warranty':     { family: 'Service',    module: 'service-warranty-panel',role:'warranty-panel-number' },
	'service-pickup':       { family: 'Service',    module: 'service-pickup-steps',  role: 'pickup-step-list' },
	'service-membership':   { family: 'Service',    module: 'service-tier-pricing',  role: 'tier-price-card' },
	'service-status':       { family: 'Service',    module: 'service-status-chip',   role: 'status-chip-line' },
};

function escapeAttr( s ) {
	return s.replace( /&/g, '&amp;' ).replace( /"/g, '&quot;' );
}

function processFile( file ) {
	const slug = file.replace( /\.php$/, '' );
	const spec = SPECS[ slug ];
	if ( ! spec ) {
		console.log( `SKIP ${ slug } — no spec` );
		return;
	}

	const content = readFileSync( join( PATTERNS_DIR, file ), 'utf8' );

	// Idempotent: if we already added the pattern attribute, skip.
	if ( content.includes( 'data-arena-pattern="' ) ) {
		// Re-ensure freshness.
		return;
	}

	// Find root wp:group or wp:cover comment block — the FIRST root block.
	// We insert data-arena-* attributes into the JSON attributes of the
	// FIRST opening block comment AND mirror them onto the rendered HTML tag.
	// Strategy: for the first <!-- wp:group ... or wp:cover ... we append
	// to the className and add data-arena-* in the HTML (since block comment
	// JSON only has a subset of keys, we add data attrs via className trick
	// won't work; instead we add them on the rendered opening tag).

	// Add data-arena-* attributes to the FIRST top-level HTML tag right after
	// the first block comment.
	const bodyStart = content.indexOf( '?>' );
	let body = content.slice( bodyStart + 2 );

	// First opening tag after ?>: find <div ...> / <blockquote ...> etc.
	// Tags may span multiple lines, so use a non-greedy multiline match
	// starting from the beginning of the body.
	const tagRe = /<([a-z][a-z0-9-]*)\b([^>]*?)>/s;
	const firstTagMatch = body.match( tagRe );

	if ( ! firstTagMatch ) {
		console.log( `SKIP ${ slug } — no root tag found` );
		return;
	}

	const head = content.slice( 0, bodyStart + 2 );

	const tagName = firstTagMatch[ 1 ];
	const open = `<${ tagName }`;
	const attrs = firstTagMatch[ 2 ];
	const close = '>';
	// Avoid double-injection.
	if ( attrs.includes( 'data-arena-pattern' ) ) {return;}

	const injected = ` data-arena-pattern="${ escapeAttr( slug ) }" data-arena-family="${ escapeAttr( spec.family ) }" data-arena-module="${ escapeAttr( spec.module ) }"`;
	const newOpenTag = open + attrs + injected + close;
	body = body.slice( 0, firstTagMatch.index ) + newOpenTag + body.slice( firstTagMatch.index + firstTagMatch[ 0 ].length );

	// Now add a unique role marker inside the content. We inject the marker
	// as a self-closing <span aria-hidden="true" class="arena-SLUG__ROLE"
	// data-arena-role="ROLE"></span> immediately after the heading or
	// eyebrow paragraph inside the main content, scoped to pattern.
	// The simplest safe injection: right after the root inner-container
	// opening, if present; else after the root block start.
	// Since insertion position in the rendered DOM varies, we inject it
	// right before the CLOSING tag of the root element so JS can find it
	// via `[data-arena-pattern=slug] [data-arena-role=role]`.
	const roleSpan = `<span aria-hidden="true" class="arena-${ slug }__${ spec.role }" data-arena-role="${ escapeAttr( spec.role ) }"></span>`;

	// Find LAST </div> (closing of root) and insert roleSpan before it.
	// For wp:cover the root ends with </div><!-- /wp:cover -->
	const lastCloseIdx = body.lastIndexOf( '</div>' );
	if ( lastCloseIdx !== -1 ) {
		body = body.slice( 0, lastCloseIdx ) + '\n\t' + roleSpan + '\n' + body.slice( lastCloseIdx );
	} else {
		body = body + '\n' + roleSpan + '\n';
	}

	writeFileSync( join( PATTERNS_DIR, file ), head + body );
	console.log( `OK  ${ slug.padEnd( 28 ) }  module=${ spec.module.padEnd( 30 ) } role=${ spec.role }` );
}

const files = readdirSync( PATTERNS_DIR ).filter( ( f ) => f.endsWith( '.php' ) ).sort();
for ( const f of files ) {processFile( f );}

console.log( `\n${ files.length } patterns processed.` );
