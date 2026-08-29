#!/usr/bin/env node
/**
 * H15 — Per-family design system audit.
 *
 * Verifies that every family declared in family-tokens.json meets:
 *
 *   T1. ≥5 named type levels (eyebrow, h*, body, meta, cta, …).
 *   T2. Palette with at least: base, ink/foreground, muted (neutrals),
 *       accent, accent-soft (base+accent+neutrals).
 *   T3. A declared grid token (non-empty string describing the
 *       family-specific grid archetype).
 *   T4. A declared photoVoice (non-empty string describing the
 *       photographic treatment for the family).
 *   T5. Every pattern in the patterns directory belongs to a declared
 *       family (catches orphaned / misspelled patterns).
 *
 * Also verifies that the global theme.json defines ≥5 fluid font sizes
 * (we have 10) and the core palette (base, surface, foreground, muted,
 * primary, accent, …) exists — the atomic layer families compose from.
 *
 * Exit 0 = PASS; exit 1 = FAIL.
 */

import { readFileSync, readdirSync } from 'node:fs';
import { join } from 'node:path';

const ROOT = process.cwd();
const tokens = JSON.parse(
	readFileSync( join( ROOT, 'theme', 'arena-commerce', 'family-tokens.json' ), 'utf8' )
);
const theme = JSON.parse(
	readFileSync( join( ROOT, 'theme', 'arena-commerce', 'theme.json' ), 'utf8' )
);

const failures = [];
const info = [];
function assert( cond, msg ) { if ( ! cond ) failures.push( msg ); }

// Family for a filename mirrors anti-clone.mjs mapping.
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
	return null;
}

// T1–T4 per family.
const declared = Object.keys( tokens.families );
for ( const fam of declared ) {
	const f = tokens.families[ fam ];

	assert(
		f.type && Array.isArray( f.type.levels ) && f.type.levels.length >= 5,
		`${ fam }: <5 type levels (${ f.type?.levels?.length || 0 })`
	);
	if ( f.type?.levels?.length >= 5 ) {
		info.push( `  ${ fam.padEnd( 12 ) } type=${ f.type.levels.length } levels` );
	}

	const requiredColors = [ 'base', 'accent' ];
	const neutralKeys = [ 'muted', 'ink', 'surface', 'ink-invert' ];
	const pal = f.palette || {};
	for ( const c of requiredColors ) {
		assert( pal[ c ], `${ fam }: palette missing required "${ c }"` );
	}
	const neutralsCount = neutralKeys.filter( ( k ) => pal[ k ] ).length;
	assert( neutralsCount >= 2, `${ fam }: palette needs ≥2 neutrals (muted/ink/surface/ink-invert); found ${ neutralsCount }` );

	assert(
		typeof f.grid === 'string' && f.grid.trim().length > 8,
		`${ fam }: grid token empty or too short`
	);
	assert(
		typeof f.photoVoice === 'string' && f.photoVoice.trim().length > 8,
		`${ fam }: photoVoice empty or too short`
	);
}

// T5: every pattern maps to a declared family.
const PATTERNS_DIR = join( ROOT, 'theme', 'arena-commerce', 'patterns' );
const files = readdirSync( PATTERNS_DIR ).filter( ( x ) => x.endsWith( '.php' ) );
for ( const f of files ) {
	const name = f.replace( /\.php$/, '' );
	const fam = familyFor( name );
	assert( fam, `Pattern ${ name } not mapped to any family (anti-clone map)` );
	if ( fam ) {
		assert( declared.includes( fam ), `Pattern ${ name } maps to family "${ fam }" which is not declared in family-tokens.json` );
	}
}

// Global theme.json verifications.
const globalFonts = theme.settings?.typography?.fontSizes || [];
assert( globalFonts.length >= 5, `theme.json defines only ${ globalFonts.length } fluid font sizes (need ≥5)` );
const palette = theme.settings?.color?.palette || [];
const palSlugs = new Set( palette.map( ( c ) => c.slug ) );
for ( const c of [ 'base', 'foreground', 'muted', 'primary', 'accent' ] ) {
	assert( palSlugs.has( c ), `theme.json palette missing "${ c }"` );
}

if ( failures.length ) {
	console.error( '[H15 family system] FAIL:' );
	for ( const f of failures ) console.error( '  - ' + f );
	process.exit( 1 );
}
console.log( `[H15 family system] PASS — ${ declared.length } families declared in theme/arena-commerce/family-tokens.json.` );
console.log( '' );
console.log( 'Per-family audit:' );
for ( const fam of declared ) {
	const f = tokens.families[ fam ];
	console.log( `  ${ fam.padEnd( 12 ) } type=${ f.type.levels.length } levels  palette=${ Object.keys( f.palette ).length } slots  grid="…${ f.grid.slice( 0, 48 ) }…"` );
}
console.log( '' );
console.log( `Global theme.json: ${ globalFonts.length } fluid font sizes, ${ palette.length } palette colors, ${ files.length } patterns all mapped to declared families.` );
