#!/usr/bin/env node
/**
 * Global anti-clone: checks ALL pairs (cross-family included) against ≤40%.
 * Runs after the family-scoped test; reports failure when any cross-family
 * pair exceeds 0.40. This is the strict H7 reading confirmed for v2.0.
 */
import { readFileSync, readdirSync } from 'node:fs';
import { join } from 'node:path';

const PATTERNS_DIR = join( process.cwd(), 'theme', 'arena-commerce', 'patterns' );
const THRESHOLD = 0.40;

const GENERIC_TOKENS = new Set( [
	'wp:group', 'wp:column', 'wp:columns', 'wp:heading', 'wp:paragraph',
	'wp:button', 'wp:buttons', 'wp:navigation', 'wp:navigation-link',
	'wp:image', 'wp:cover', 'wp:separator', 'wp:html', 'wp:template-part',
	'wp:query', 'wp:post-title', 'wp:post-content', 'wp:list',
	'wp:quote', 'wp:table', 'wp:media-text', 'wp:accordion', 'wp:comments',
	'wp:list-item',
	'div', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'a', 'ul', 'li', 'ol',
	'figure', 'img', 'span', 'section', 'header', 'footer', 'main', 'nav',
	'blockquote', 'cite', 'button', 'input', 'form', 'label', 'select',
	'textarea', 'summary', 'details', 'table', 'tr', 'td', 'th', 'thead',
	'tbody', 'hr', 'br',
	'wp-block', 'wp-block-group', 'wp-block-column',
	'wp-block-columns', 'wp-block-heading', 'wp-block-paragraph',
	'wp-block-button', 'wp-block-buttons', 'wp-block-button__link',
	'wp-element-button', 'wp-block-image', 'wp-block-quote',
	'wp-block-cover', 'wp-block-navigation', 'wp-block-navigation-item',
	'wp-block-list', 'wp-block-list-item', 'wp-block-separator',
	'wp-block-media-text', 'wp-block-buttons',
	'alignwide', 'alignfull', 'has-background',
	'has-text-color', 'has-text-align-center', 'has-text-align-left',
	'has-text-align-right', 'is-layout-flex', 'is-layout-flow',
	'is-layout-constrained', 'is-content-justification-left',
	'is-content-justification-space-between', 'is-content-justification-center',
	'is-vertical', 'is-horizontal', 'is-style', 'align', 'full', 'wide',
	'is-stacked-on-mobile', 'is-vertically-aligned-center',
	'has-background-dim',
] );

function patternContent( file ) {
	const raw = readFileSync( file, 'utf8' );
	const i = raw.indexOf( '?>' );
	return i === -1 ? raw : raw.slice( i + 2 );
}
function normalise( content ) {
	let s = content;
	s = s.replace( /<\?php[\s\S]*?\?>/g, '' );
	s = s.replace( /<!--(?!\s*wp:)[\s\S]*?-->/g, '' );
	s = s.replace( />([^<]*)</g, '><' );
	s = s.replace( /\sstyle="[^"]*"/g, '' );
	s = s.replace( /\s(?:src|srcset|alt|url|href|title|content|name|id|for|placeholder|action|method|autocomplete|rows|type)=\"[^\"]*\"/g, '' );
	s = s.replace( /\bhas-[a-z0-9-]+-(?:color|background-color|font-size|text-decoration|border-color|shadow|gradient|text-transform|letter-spacing|font-family|font-weight)[a-z0-9-]*/g, '' );
	s = s.replace( /\bhas-[a-z0-9-]+-gradient-background/g, '' );
	s = s.replace( /\bhas-background-dim-\d+/g, '' );
	s = s.replace( /\bhas-[a-z0-9-]+-color\b/g, '' );
	s = s.replace( /\bhas-[a-z0-9-]+-font-size/g, '' );
	s = s.replace( /\bhas-[a-z0-9-]+-background-color/g, '' );
	s = s.replace( /#[0-9a-fA-F]{3,8}/g, '' );
	s = s.replace( /\brgba?\([^)]*\)/g, '' );
	s = s.replace( /var\(--wp--[^)]*\)/g, '' );
	s = s.replace( /\s+/g, ' ' ).trim();
	return s;
}
function tokens( content ) {
	const norm = normalise( content );
	const set = new Set();
	// WP block names.
	for ( const m of norm.matchAll( /<!--\s*wp:([a-z0-9-]+(?:\/[a-z0-9-]+)?)/gi ) ) {
		set.add( `wp:${ m[ 1 ].toLowerCase() }` );
	}
	// Semantic class tokens (BEM-like arena-* and is-style-arena-*).
	for ( const m of norm.matchAll( /\bclass="([^"]+)"/g ) ) {
		for ( const t of m[ 1 ].split( /\s+/ ) ) {
			if ( ! t ) continue;
			if ( GENERIC_TOKENS.has( t ) ) continue;
			if ( /^has-/.test( t ) ) continue;
			// Arena-specific semantic classes are always structural.
			if ( t.startsWith( 'arena-' ) || t.startsWith( 'is-style-arena-' ) ) {
				set.add( `class:${ t }` );
				continue;
			}
			// wp-block-* utilities: skip except data-driven ones.
			if ( /^wp-block-/.test( t ) ) continue;
			// is-* layout/is-* flags: skip except arena-specific.
			if ( /^is-/.test( t ) ) continue;
			set.add( `class:${ t }` );
		}
	}
	// data-arena-* name=value pairs are semantic module hooks.
	for ( const m of norm.matchAll( /\bdata-arena-([a-z0-9-]+)="([^"]*)"/gi ) ) {
		set.add( `arena:${ m[ 1 ].toLowerCase() }=${ m[ 2 ] }` );
	}
	// Generic aria-* names only (values are usually text).
	for ( const m of norm.matchAll( /\b(aria-[a-z0-9-]+)=/gi ) ) {
		set.add( `attr:${ m[ 1 ].toLowerCase() }` );
	}
	// Non-arena, non-stripped data-* names (e.g. data-arena-carousel-prev).
	for ( const m of norm.matchAll( /\bdata-(?!arena-)[a-z0-9-]+=/gi ) ) {
		set.add( `attr:${ m[ 0 ].slice( 0, -1 ).toLowerCase() }` );
	}
	// Non-generic tag names.
	for ( const m of norm.matchAll( /<([a-z][a-z0-9-]*)\b/gi ) ) {
		const tag = m[ 1 ].toLowerCase();
		if ( ! GENERIC_TOKENS.has( tag ) ) set.add( `tag:${ tag }` );
	}
	// Layout/grid attribute tokens (from JSON in block comments).
	for ( const m of norm.matchAll( /"(layout|grid|orientation|flexWrap|contentSize|wideSize|justifyContent|verticalAlignment|overlayMenu|hasIcon|query|sizeSlug|level|viewportWidth|mediaPosition|dimRatio|minHeight|align|aspectRatio|textColor|backgroundColor|gradient)":"([^"]*)"/gi ) ) {
		set.add( `attr:${ m[ 1 ] }:${ m[ 2 ] }` );
	}
	return set;
}

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

function jaccard( a, b ) {
	let inter = 0;
	for ( const t of a ) if ( b.has( t ) ) inter += 1;
	const union = a.size + b.size - inter;
	return union === 0 ? 0 : inter / union;
}

const files = readdirSync( PATTERNS_DIR ).filter( f => f.endsWith( '.php' ) ).sort();
const pats = files.map( f => {
	const name = f.replace( /\.php$/, '' );
	return { name, family: familyFor( name ), toks: tokens( patternContent( join( PATTERNS_DIR, f ) ) ) };
} );

const withinFailures = [];
const crossFailures = [];
const withinSummary = new Map();

for ( let i = 0; i < pats.length; i++ ) {
	for ( let j = i + 1; j < pats.length; j++ ) {
		const sim = jaccard( pats[ i ].toks, pats[ j ].toks );
		const target = pats[ i ].family === pats[ j ].family ? withinFailures : crossFailures;
		if ( sim > THRESHOLD ) {
			target.push( { a: pats[ i ].name, b: pats[ j ].name, sim, af: pats[ i ].family, bf: pats[ j ].family } );
		}
		if ( pats[ i ].family === pats[ j ].family ) {
			const key = pats[ i ].family;
			const cur = withinSummary.get( key ) || { worst: 0, a: '', b: '' };
			if ( sim > cur.worst ) withinSummary.set( key, { worst: sim, a: pats[ i ].name, b: pats[ j ].name, n: (cur.n||0)+1 } );
		}
	}
}

console.log( `[anti-clone:global] ${ pats.length } patterns, ${ pats.length * (pats.length-1) / 2 } pairs, threshold <= ${ THRESHOLD }.` );
console.log( `\nPer-family worst pairs (informational):` );
for ( const [ fam, s ] of withinSummary ) {
	console.log( `  ${ fam.padEnd( 12 ) } worst ${ s.a.padEnd( 28 ) } ↔ ${ s.b.padEnd( 28 ) } = ${ s.worst.toFixed( 3 ) }` );
}
console.log( `\nWithin-family failures: ${ withinFailures.length }` );
for ( const f of withinFailures ) console.log( `  [${ f.af }] ${ f.a } ↔ ${ f.b } = ${ f.sim.toFixed( 3 ) }` );
console.log( `\nCross-family failures: ${ crossFailures.length }` );
for ( const f of crossFailures.slice( 0, 30 ) ) console.log( `  [${ f.af }/${ f.bf }] ${ f.a.padEnd( 28 ) } ↔ ${ f.b.padEnd( 28 ) } = ${ f.sim.toFixed( 3 ) }` );
if ( crossFailures.length > 30 ) console.log( `  ... and ${ crossFailures.length - 30 } more.` );

if ( withinFailures.length === 0 && crossFailures.length === 0 ) {
	console.log( `\n[anti-clone:global] PASS — all ${ pats.length * (pats.length-1)/2 } pairs (within + cross-family) are below the 40% structural-overlap ceiling.` );
	process.exit( 0 );
}
console.error( `\n[anti-clone:global] FAIL — ${ withinFailures.length + crossFailures.length } pairs exceed ${ (THRESHOLD*100)|0 }%.` );
process.exit( 1 );
