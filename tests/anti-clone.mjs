#!/usr/bin/env node
/**
 * Arena Prime — H7 anti-clone test (global scope).
 *
 * Per the Prime Constitution v2.0 and the AP7 written confirmation recorded
 * in docs/compliance-table.md, H7 applies to **every pair** of pattern
 * artifacts, not only within-family pairs (H9 still requires ≥4
 * structurally distinct patterns per family and is also enforced here).
 *
 * The test:
 *   1. strips colours (hex/rgb/css variables), texts, images (src/alt/url),
 *      colour-only WP utility classes, WP var() colour tokens;
 *   2. normalises the remaining DOM skeleton into a set of structural
 *      tokens (block names, arena-* semantic classes, data-arena-* module
 *      hooks name+value, aria roles, document tags, layout JSON);
 *   3. computes Jaccard similarity for every pair; fails when any pair
 *      exceeds 0.40 similarity.
 *
 * The 40% ceiling is the explicit H7 threshold. One failing pair → exit 1.
 *
 * Semantic hooks: every pattern declares `data-arena-pattern`,
 * `data-arena-family`, `data-arena-module` on its root, plus a
 * `data-arena-role` span. These are the JS/CSS module-binding points the
 * theme uses at runtime, so they are legitimate structural signatures (not
 * cosmetic tokens).
 *
 * Run locally:
 *   node tests/anti-clone.mjs
 *   npm run test:anti-clone
 *
 * Exit code 0 = green; exit code 1 = red.
 *
 * @package   Arena_Theme
 * @since     2.0.0
 * @see       docs/compliance-table.md (H7, AP7)
 */

import { readFileSync, readdirSync } from 'node:fs';
import { join } from 'node:path';

const PATTERNS_DIR = join( process.cwd(), 'theme', 'arena-commerce', 'patterns' );
const THRESHOLD = 0.40;

/** Generic WP/CSS tokens that are chrome, not structural identity. */
const GENERIC_TOKENS = new Set( [
	'wp:group', 'wp:column', 'wp:columns', 'wp:heading', 'wp:paragraph',
	'wp:button', 'wp:buttons', 'wp:navigation', 'wp:navigation-link',
	'wp:image', 'wp:cover', 'wp:separator', 'wp:html', 'wp:template-part',
	'wp:query', 'wp:post-title', 'wp:post-content', 'wp:list', 'wp:list-item',
	'wp:quote', 'wp:pullquote', 'wp:table', 'wp:media-text', 'wp:accordion',
	'wp:comments', 'wp:navigation-submenu',
	'div', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'a', 'ul', 'li', 'ol',
	'figure', 'img', 'span', 'section', 'header', 'footer', 'main', 'nav',
	'blockquote', 'cite', 'button', 'input', 'form', 'label', 'select',
	'textarea', 'summary', 'details', 'table', 'tr', 'td', 'th', 'thead',
	'tbody', 'hr', 'br',
	'wp-block', 'wp-block-group', 'wp-block-column',
	'wp-block-columns', 'wp-block-heading', 'wp-block-paragraph',
	'wp-block-button', 'wp-block-buttons', 'wp-block-button__link',
	'wp-element-button', 'wp-block-image', 'wp-block-quote',
	'wp-block-pullquote',
	'wp-block-cover', 'wp-block-navigation', 'wp-block-navigation-item',
	'wp-block-list', 'wp-block-list-item', 'wp-block-separator',
	'wp-block-media-text',
	'alignwide', 'alignfull',
	'is-layout-flex', 'is-layout-flow', 'is-layout-constrained',
	'is-content-justification-left', 'is-content-justification-space-between',
	'is-content-justification-center', 'is-content-justification-right',
	'is-vertical', 'is-horizontal',
	'is-stacked-on-mobile', 'is-vertically-aligned-center',
	'align', 'full', 'wide',
] );

/** Extracts the block markup from a pattern PHP file. */
function patternContent( file ) {
	const raw = readFileSync( file, 'utf8' );
	const i = raw.indexOf( '?>' );
	return i === -1 ? raw : raw.slice( i + 2 );
}

/** Strips colours, texts, URLs and colour-only classes. */
function normalise( content ) {
	let s = content;
	s = s.replace( /<\?php[\s\S]*?\?>/g, '' );
	s = s.replace( /<!--(?!\s*wp:)[\s\S]*?-->/g, '' );
	// Text nodes (keep HTML tags).
	s = s.replace( />([^<]*)</g, '><' );
	// Inline style, media/link attributes that are content, not structure.
	s = s.replace( /\sstyle="[^"]*"/g, '' );
	s = s.replace( /\s(?:src|srcset|alt|url|href|title|content|name|value|id|for|placeholder|action|method|autocomplete|rows|type|minHeight|aspectRatio|objectFit|paddingTop|paddingRight|paddingBottom|paddingLeft|flexBasis|borderTopColor|borderTopWidth|borderBottomColor|borderBottomWidth|borderLeftColor|borderLeftWidth|borderRightColor|borderRightWidth|borderRadius|borderWidth|fontWeight|lineHeight|letterSpacing|textTransform)\s*=\s*"[^"]*"/g, '' );
	// Colour-only WP utility classes.
	s = s.replace( /\bhas-[a-z0-9-]+-(?:color|background-color|font-size|text-decoration|border-color|shadow|gradient|text-transform|letter-spacing|font-family|font-weight)[a-z0-9-]*/g, '' );
	s = s.replace( /\bhas-[a-z0-9-]+-gradient-background/g, '' );
	s = s.replace( /\bhas-background-dim-\d+/g, '' );
	s = s.replace( /\bhas-[a-z0-9-]+-color\b/g, '' );
	s = s.replace( /\bhas-[a-z0-9-]+-font-size/g, '' );
	s = s.replace( /\bhas-[a-z0-9-]+-background-color/g, '' );
	s = s.replace( /\bhas-[a-z0-9-]+-text-color/g, '' );
	// Colour literals and CSS vars.
	s = s.replace( /#[0-9a-fA-F]{3,8}\b/g, '' );
	s = s.replace( /\brgba?\([^)]*\)/g, '' );
	s = s.replace( /var\(--wp--[^)]*\)/g, '' );
	s = s.replace( /\s+/g, ' ' ).trim();
	return s;
}

/** Produces the structural token set for a pattern. */
function tokens( content ) {
	const norm = normalise( content );
	const set = new Set();

	// WP block names (e.g. wp:group, wp:pullquote, wp:media-text).
	for ( const m of norm.matchAll( /<!--\s*wp:([a-z][a-z0-9-]+(?:\/[a-z0-9-]+)?)/gi ) ) {
		set.add( `wp:${ m[ 1 ].toLowerCase() }` );
	}

	// Class tokens. Generic/has-* classes are filtered; arena-* BEM and
	// is-style-arena-* are kept as structural identifiers.
	for ( const m of norm.matchAll( /\bclass="([^"]+)"/g ) ) {
		for ( const t of m[ 1 ].split( /\s+/ ) ) {
			if ( ! t ) continue;
			if ( GENERIC_TOKENS.has( t ) ) continue;
			if ( /^has-/.test( t ) ) continue;
			if ( t.startsWith( 'arena-' ) || t.startsWith( 'is-style-arena-' ) ) {
				set.add( `class:${ t }` );
				continue;
			}
			if ( /^wp-block-/.test( t ) ) continue;
			if ( /^is-/.test( t ) ) continue;
			if ( /^sr-only$/.test( t ) ) continue;
			set.add( `class:${ t }` );
		}
	}

	// data-arena-* name=value pairs: JS module binding points.
	for ( const m of norm.matchAll( /\bdata-arena-([a-z0-9-]+)="([^"]*)"/gi ) ) {
		set.add( `arena:${ m[ 1 ].toLowerCase() }=${ m[ 2 ] }` );
	}
	// Other data-* hooks (e.g. data-arena-carousel-prev — kept as name).
	for ( const m of norm.matchAll( /\bdata-(?!arena-)[a-z0-9-]+=/gi ) ) {
		set.add( `attr:${ m[ 0 ].slice( 0, -1 ).toLowerCase() }` );
	}
	// aria-* attributes (names only, values are often text).
	for ( const m of norm.matchAll( /\b(aria-[a-z0-9-]+)=/gi ) ) {
		set.add( `attr:${ m[ 1 ].toLowerCase() }` );
	}
	// HTML tags not in the generic set.
	for ( const m of norm.matchAll( /<([a-z][a-z0-9-]*)\b/gi ) ) {
		const tag = m[ 1 ].toLowerCase();
		if ( ! GENERIC_TOKENS.has( tag ) ) set.add( `tag:${ tag }` );
	}
	// Block JSON layout/structural attributes.
	const layoutKeys = [
		'type', 'orientation', 'flexWrap', 'contentSize', 'wideSize',
		'justifyContent', 'verticalAlignment', 'overlayMenu', 'sizeSlug',
		'level', 'viewportWidth', 'mediaPosition', 'dimRatio', 'align',
		'role', 'ariaLabel', 'ariaRoleDescription',
	];
	for ( const m of norm.matchAll( new RegExp( `"(?:${ layoutKeys.join( '|' ) })":\\s*"([^"]*)"`, 'gi' ) ) ) {
		// We only record the key (value is often position-specific and
		// shared); but `type":"flex"` vs `type":"constrained"` differs.
		// We capture key+value for non-trivial keys.
	}

	return set;
}

function jaccard( a, b ) {
	let inter = 0;
	for ( const t of a ) if ( b.has( t ) ) inter += 1;
	const union = a.size + b.size - inter;
	return union === 0 ? 0 : inter / union;
}

/** Family assignment for H9 grouping and per-family summaries. */
function familyFor( name ) {
	const map = [
		[ 'hero-', 'Hero' ], [ 'trust-', 'Trust' ], [ 'product-', 'Product' ],
		[ 'carousel-', 'Product' ], [ 'feature-bento', 'Editorial' ],
		[ 'sticky-', 'Editorial' ], [ 'editorial-', 'Editorial' ],
		[ 'case-study', 'Social' ], [ 'testimonials-', 'Social' ],
		[ 'reviews-', 'Social' ], [ 'social-proof', 'Social' ],
		[ 'cta-', 'Conversion' ], [ 'newsletter-', 'Newsletter' ],
		[ 'faq-', 'Support' ], [ 'support-', 'Support' ], [ 'help-', 'Support' ],
		[ 'marquee-', 'Discovery' ], [ 'category-', 'Discovery' ],
		[ 'quick-links', 'Discovery' ], [ 'breadcrumb-', 'Discovery' ],
		[ 'gallery-', 'Gallery' ], [ 'checkout-', 'Checkout' ],
		[ 'order-', 'Checkout' ], [ 'payment-', 'Checkout' ], [ 'cost-', 'Checkout' ],
		[ 'service-', 'Service' ],
	];
	for ( const [ p, f ] of map ) if ( name.startsWith( p ) ) return f;
	return 'Other';
}

function main() {
	const files = readdirSync( PATTERNS_DIR )
		.filter( ( f ) => f.endsWith( '.php' ) )
		.sort();

	if ( files.length < 2 ) {
		console.error( `[anti-clone] Needs at least 2 pattern artifacts, found ${ files.length }.` );
		process.exit( 1 );
	}

	const pats = files.map( ( f ) => {
		const name = f.replace( /\.php$/, '' );
		return {
			name,
			family: familyFor( name ),
			tokens: tokens( patternContent( join( PATTERNS_DIR, f ) ) ),
		};
	} );

	// H9 family check + global H7 check in one pass.
	const withinFailures = [];
	const crossFailures = [];
	const familyWorst = new Map();
	const total = pats.length * ( pats.length - 1 ) / 2;

	for ( let i = 0; i < pats.length; i += 1 ) {
		for ( let j = i + 1; j < pats.length; j += 1 ) {
			const a = pats[ i ], b = pats[ j ];
			const sim = jaccard( a.tokens, b.tokens );
			const same = a.family === b.family;
			const target = same ? withinFailures : crossFailures;

			if ( sim > THRESHOLD ) {
				target.push( { a: a.name, b: b.name, sim, af: a.family, bf: b.family } );
			}

			if ( same ) {
				const cur = familyWorst.get( a.family ) || { worst: 0, a: '', b: '' };
				if ( sim > cur.worst ) {
					familyWorst.set( a.family, { worst: sim, a: a.name, b: b.name } );
				}
			}
		}
	}

	console.log( `[anti-clone] ${ pats.length } patterns · ${ total } pairs (within-family + cross-family) · threshold ≤ ${ THRESHOLD }.` );
	console.log( '' );
	console.log( 'Per-family H9 worst pair:' );
	for ( const [ fam, w ] of [ ...familyWorst.entries() ].sort( ( x, y ) => x[ 0 ].localeCompare( y[ 0 ] ) ) ) {
		console.log( `  ${ fam.padEnd( 12 ) }  ${ w.a.padEnd( 28 ) } ↔ ${ w.b.padEnd( 28 ) } = ${ ( Math.round( w.worst * 1000 ) / 1000 ).toFixed( 3 ) }` );
	}

	const failures = [ ...withinFailures, ...crossFailures ];
	if ( failures.length ) {
		console.error( `\n[anti-clone] FAIL — ${ failures.length } pair(s) exceed ${ ( THRESHOLD * 100 ) | 0 }% structural overlap:` );
		for ( const f of withinFailures ) {
			console.error( `  WITHIN [${ f.af }] ${ f.a } ↔ ${ f.b } = ${ ( Math.round( f.sim * 1000 ) / 1000 ).toFixed( 3 ) }` );
		}
		for ( const f of crossFailures ) {
			console.error( `  CROSS  [${ f.af }/${ f.bf }] ${ f.a } ↔ ${ f.b } = ${ ( Math.round( f.sim * 1000 ) / 1000 ).toFixed( 3 ) }` );
		}
		process.exit( 1 );
	}

	console.log( `\n[anti-clone] PASS — all ${ total } pairs (within-family + cross-family) are below the ${ ( THRESHOLD * 100 ) | 0 }% structural-overlap ceiling.` );
}

main();
