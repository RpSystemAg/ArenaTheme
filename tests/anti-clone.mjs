#!/usr/bin/env node
/**
 * Arena Prime — H7 anti-clone test (family-scoped, per H9).
 *
 * H9 requires each template family to contain 4 structurally distinct
 * patterns. For every within-family pair the test:
 *   1. strips colours (hex/rgb/css variables), texts, images (src/alt/url)
 *      and colour-only classes,
 *   2. normalises the remaining DOM skeleton to a set of structural tokens
 *      (block names, semantic component classes, data/aria hooks, document
 *      tags),
 *   3. fails when a within-family pair is structurally identical for more
 *      than 40% (Jaccard similarity > 0.40).
 *
 * The 40% ceiling is the explicit H7 threshold; a single within-family pair
 * above it rejects the family (AP1/AP6).
 *
 * Note on scope: the Constitution line "ogni coppia di artifact" (any pair,
 * globally) is stricter than the H9 per-family requirement that the
 * variation matrix and this gate implement. The global cross-family reading
 * is reported in `docs/compliance-table.md` as an explicit conflict awaiting
 * written confirmation (AP7 is not silently deroga — see H7 row).
 *
 * Run locally with:
 *   node tests/anti-clone.mjs
 *
 * @package   Arena_Theme
 * @since     1.0.0
 * @see       docs/compliance-table.md
 */

import { readFileSync, readdirSync } from 'node:fs';
import { join } from 'node:path';

const PATTERNS_DIR = join( process.cwd(), 'theme', 'arena-commerce', 'patterns' );
const THRESHOLD = 0.40;

/** Tokens that are generic chrome and do not identify a skeleton. */
const GENERIC_TOKENS = new Set( [
	'wp:group', 'wp:column', 'wp:columns', 'wp:heading', 'wp:paragraph',
	'wp:button', 'wp:buttons', 'wp:navigation', 'wp:navigation-link',
	'wp:image', 'wp:cover', 'wp:separator', 'wp:html', 'wp:template-part',
	'wp:query', 'wp:post-title', 'wp:post-content', 'wp:list',
	'wp:quote', 'wp:table', 'wp:media-text', 'wp:accordion', 'wp:comments',
	'div', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'a', 'ul', 'li', 'ol',
	'figure', 'img', 'span', 'section', 'header', 'footer', 'main', 'nav',
	'blockquote', 'cite', 'button', 'input', 'form', 'label', 'select',
	'textarea', 'summary', 'details', 'table', 'tr', 'td', 'th', 'thead',
	'tbody', 'wp-block', 'wp-block-group', 'wp-block-column',
	'wp-block-columns', 'wp-block-heading', 'wp-block-paragraph',
	'wp-block-button', 'wp-block-buttons', 'wp-block-button__link',
	'wp-element-button', 'wp-block-image', 'wp-block-quote',
	'wp-block-cover', 'wp-block-navigation', 'wp-block-navigation-item',
	'wp-block-buttons', 'alignwide', 'alignfull', 'has-background',
	'has-text-color', 'has-text-align-center', 'has-text-align-left',
	'has-text-align-right', 'is-layout-flex', 'is-layout-flow',
	'is-layout-constrained', 'is-content-justification-left',
	'is-content-justification-space-between', 'is-content-justification-center',
	'is-vertical', 'is-horizontal', 'is-style', 'align', 'full', 'wide',
] );

/** Extracts the block markup from a pattern PHP file. */
function patternContent( file ) {
	const raw = readFileSync( file, 'utf8' );
	const bodyStart = raw.indexOf( '?>' );

	if ( bodyStart === -1 ) {
		return raw;
	}

	return raw.slice( bodyStart + 2 );
}

/**
 * Removes colours, texts, images, URLs and colour-only classes while keeping
 * the DOM skeleton (tags, semantic classes, data/aria hooks).
 */
function normalise( content ) {
	let s = content;

	// Drop PHP tags if any remain, and inline comments.
	s = s.replace( /<\?php[\s\S]*?\?>/g, '' );
	s = s.replace( /<!--(?!\s*wp:)[\s\S]*?-->/g, '' );

	// Text nodes.
	s = s.replace( />([^<]*)</g, '><' );

	// Colour and image attributes. Class and data/aria attributes are kept
	// because they are part of the structural skeleton.
	s = s.replace( /\sstyle="[^"]*"/g, '' );
	s = s.replace( /\s(?:src|srcset|alt|url|href|title|content|name|value|id)="[^"]*"/g, '' );

	// Colour-only classes and visual modifiers.
	s = s.replace( /\bhas-[a-z0-9-]+-(?:color|background-color|font-size|text-decoration|border-color|shadow|gradient|text-transform|letter-spacing|font-family|font-weight)[a-z0-9-]*/g, '' );
	s = s.replace( /\bhas-[a-z0-9-]+-gradient-background/g, '' );
	s = s.replace( /\bhas-background-dim/g, '' );
	s = s.replace( /\bhas-[a-z0-9-]+-color/g, '' );
	s = s.replace( /\bhas-[a-z0-9-]+-font-size/g, '' );

	// Colour literals.
	s = s.replace( /#[0-9a-fA-F]{3,8}/g, '' );
	s = s.replace( /\brgba?\([^)]*\)/g, '' );
	s = s.replace( /var\(--wp--[^)]*\)/g, '' );

	// Collapse whitespace.
	s = s.replace( /\s+/g, ' ' ).trim();

	return s;
}

/** Produces the structural token set for a pattern. */
function tokens( content ) {
	const normalised = normalise( content );
	const set = new Set();

	// WP block names from block comments.
	for ( const match of normalised.matchAll( /<!--\s*wp:([a-z0-9-]+)/gi ) ) {
		set.add( `wp:${ match[ 1 ] }` );
	}

	// Semantic class tokens.
	for ( const match of normalised.matchAll( /\bclass="([^"]+)"/g ) ) {
		for ( const token of match[ 1 ].split( /\s+/ ) ) {
			if ( token && ! GENERIC_TOKENS.has( token ) && ! /^has-/.test( token ) ) {
				set.add( `class:${ token }` );
			}
		}
	}

	// Structural data/aria hooks.
	for ( const match of normalised.matchAll( /\b(data-[a-z0-9-]+|aria-[a-z0-9-]+)=/gi ) ) {
		set.add( `attr:${ match[ 1 ] }` );
	}

	// Document tags (excluding the generic list above).
	for ( const match of normalised.matchAll( /<([a-z][a-z0-9-]*)\b/gi ) ) {
		const tag = match[ 1 ];

		if ( ! GENERIC_TOKENS.has( tag ) ) {
			set.add( `tag:${ tag }` );
		}
	}

	// Layout shape tokens encoded in block attributes (structure, not style).
	for ( const match of normalised.matchAll( /"(layout|grid|orientation|flexWrap|contentSize|wideSize|justifyContent|verticalAlignment|overlayMenu|hasIcon|query|sizeSlug|level|viewportWidth)":\s*"[^"]*"/gi ) ) {
		set.add( `attr:${ match[ 1 ] }:${ match[ 2 ] }` );
	}

	return set;
}

function jaccard( a, b ) {
	let intersection = 0;

	for ( const token of a ) {
		if ( b.has( token ) ) {
			intersection += 1;
		}
	}

	const union = a.size + b.size - intersection;
	return union === 0 ? 0 : intersection / union;
}

/** Assigns each pattern to its template family (H9). */
function familyFor( name ) {
	const map = [
		[ 'hero-', 'Hero' ],
		[ 'trust-', 'Trust' ],
		[ 'product-', 'Product' ],
		[ 'carousel-', 'Product' ],
		[ 'feature-bento', 'Editorial' ],
		[ 'sticky-', 'Editorial' ],
		[ 'editorial-', 'Editorial' ],
		[ 'case-study', 'Social' ],
		[ 'testimonials-', 'Social' ],
		[ 'reviews-', 'Social' ],
		[ 'social-proof', 'Social' ],
		[ 'cta-', 'Conversion' ],
		[ 'newsletter-', 'Newsletter' ],
		[ 'faq-', 'Support' ],
		[ 'support-', 'Support' ],
		[ 'help-', 'Support' ],
		[ 'marquee-', 'Discovery' ],
		[ 'category-', 'Discovery' ],
		[ 'quick-links', 'Discovery' ],
		[ 'breadcrumb-', 'Discovery' ],
		[ 'gallery-', 'Gallery' ],
		[ 'checkout-', 'Checkout' ],
		[ 'order-', 'Checkout' ],
		[ 'payment-', 'Checkout' ],
		[ 'cost-', 'Checkout' ],
		[ 'service-', 'Service' ],
	];

	for ( const [ prefix, family ] of map ) {
		if ( name.startsWith( prefix ) ) {
			return family;
		}
	}

	return 'Other';
}

function main() {
	const files = readdirSync( PATTERNS_DIR )
		.filter( ( file ) => file.endsWith( '.php' ) )
		.sort();

	if ( files.length < 2 ) {
		console.error( `[anti-clone] Needs at least 2 pattern artifacts, found ${ files.length }.` );
		process.exit( 1 );
	}

	// Patterns are grouped by family because H9 tests 4 structurally distinct
	// patterns per family. Cross-family patterns may share generic wrappers
	// (group/columns/button) and are intentionally not compared here; the
	// variation matrix is the cross-family source of truth.
	const byFamily = new Map();

	for ( const file of files ) {
		const name = file.replace( /\.php$/, '' );
		const family = familyFor( name );

		if ( ! byFamily.has( family ) ) {
			byFamily.set( family, [] );
		}

		byFamily.get( family ).push( {
			name,
			tokens: tokens( patternContent( join( PATTERNS_DIR, file ) ) ),
		} );
	}

	const failures = [];
	const summaries = [];

	for ( const [ family, signatures ] of byFamily ) {
		let worst = { a: '', b: '', sim: 0 };

		for ( let i = 0; i < signatures.length; i += 1 ) {
			for ( let j = i + 1; j < signatures.length; j += 1 ) {
				const a = signatures[ i ];
				const b = signatures[ j ];
				const sim = jaccard( a.tokens, b.tokens );

				if ( sim > worst.sim ) {
					worst = { a: a.name, b: b.name, sim };
				}

				if ( sim > THRESHOLD ) {
					failures.push( {
						family,
						a: a.name,
						b: b.name,
						sim: Math.round( sim * 1000 ) / 1000,
					} );
				}
			}
		}

		summaries.push( `${ family }: ${ signatures.length } patterns, worst ${ worst.a } vs ${ worst.b } = ${ Math.round( worst.sim * 1000 ) / 1000 }` );
	}

	console.log( `[anti-clone] ${ files.length } patterns across ${ byFamily.size } families, threshold <= ${ THRESHOLD }.` );
	console.log( summaries.join( '\n' ) );

	if ( failures.length ) {
		console.error( '\n[anti-clone] FAIL — structurally over-similar pairs within a family:' );
		for ( const failure of failures ) {
			console.error( `  [${ failure.family }] ${ failure.a } vs ${ failure.b } = ${ failure.sim }` );
		}
		process.exit( 1 );
	}

	console.log( '[anti-clone] PASS — every within-family pair is below the 40% structural overlap ceiling.' );
}

main();
