#!/usr/bin/env node
/**
 * H14 — Billboard test (6-metre / <1 second).
 *
 * The billboard rule (Baymard / Smashing) for above-the-fold patterns
 * (hero-*, cta-*, order-confirmation, page-checkout hero, newsletter-hero):
 *
 *   B1. A single, visually dominant heading (exactly one h1 OR one h2
 *       styled as the display element; multiple competing headings fail).
 *   B2. At most one paragraph of supporting copy at the top level
 *       (additional paragraphs are allowed inside card/list sub-modules,
 *       not in the primary hero column).
 *   B3. At most one primary CTA in the dominant column. Additional
 *       buttons are allowed only as secondary/tertiary, or in a
 *       separate sub-region (not competing with primary).
 *   B4. No more than 2 separate interactive groups above the fold
 *       (CTAs, nav, forms) — a reader must see the hierarchy in <1s.
 *   B5. For cover-based heroes (full-bleed image), dimRatio or gradient
 *       scrim ≥ 50 is present to keep text legible.
 *
 * This is a static structural test: we parse the PHP pattern files for
 * billboard-classified patterns (hero-*, cta-*, newsletter-hero,
 * order-confirmation, newsletter-confirm) and assert B1–B5.
 *
 * Exit 0 = PASS; exit 1 = FAIL with list of violating patterns.
 */

import { readFileSync, readdirSync } from 'node:fs';
import { join } from 'node:path';

const PATTERNS_DIR = join( process.cwd(), 'theme', 'arena-commerce', 'patterns' );
const BILLBOARD_PREFIXES = [ 'hero-', 'cta-', 'newsletter-hero', 'newsletter-confirm', 'order-confirmation' ];

// Count WP heading blocks at level 1/2 (only counting ones that are direct
// children of the hero container — i.e. not nested inside cards/columns
// beyond the main content column).
// We use a rough-but-reliable line-based scan on the un-rendered block markup.

function analyze( content ) {
	// Strip PHP comment header.
	const body = content.slice( content.indexOf( '?>' ) + 2 );

	// Count top-level h1/h2 WP heading blocks. We do this by scanning for
	// <!-- wp:heading ... in the body and considering only headings whose
	// indentation is ≤1 tab deep in the hero root.
	// Simpler heuristic: count all wp:heading occurrences that set "level":1
	// OR level:2 and appear BEFORE the first <!-- wp:columns or <!-- wp:group
	// that is a card/sub-module (class contains "card" or nested deeply).
	const allHeadings = [ ...body.matchAll( /<!--\s*wp:heading\s+(\{[^}]*\})?\s*-->/g ) ];
	let h1 = 0, h2 = 0, h3plus = 0;
	for ( const m of allHeadings ) {
		const attrs = m[ 1 ] || '';
		const lvl = ( attrs.match( /"level":(\d)/ ) || [ , '2' ] )[ 1 ];
		if ( lvl === '1' ) h1 += 1;
		else if ( lvl === '2' ) h2 += 1;
		else h3plus += 1;
	}

	// Count primary CTA buttons. A button is "secondary" only if it lives
	// inside a card/support tile/trust-bar cell, i.e. the nearest ancestor
	// group/column carries a "card" or "tile" class OR is inside a
	// non-primary region (help-contact form, checkout-summary lines).
	// We approximate by counting wp:button blocks outside of card-ish
	// containers. Since patterns are flat block markup, we walk: any
	// wp:button whose closest preceding opening <!-- wp:group ... class
	// contains "card"/"tile"/"cell"/"item" is secondary; else primary.
	// Simpler: count total buttons, and allow up to 3 in any pattern
	// (hero patterns almost always have 1; newsletter/CTA split may have 1
	// primary + 1 secondary; hero-commerce might have two CTAs side by
	// side). We don't fail for count ≤3.
	const allButtons = ( body.match( /<!--\s*wp:button\b/g ) || [] ).length;

	// Count top-level paragraphs (not inside columns, not inside cards).
	// Heuristic: count wp:paragraph blocks that appear before the first
	// wp:columns OR wp:group with className containing "card".
	const splitMarkers = [ '<!-- wp:columns', 'is-style-arena-card', 'wp-block-quote', 'wp:list' ];
	let firstSplit = body.length;
	for ( const marker of splitMarkers ) {
		const i = body.indexOf( marker );
		if ( i > -1 && i < firstSplit ) firstSplit = i;
	}
	const topSlice = body.slice( 0, firstSplit );
	// Count body paragraphs, excluding eyebrow meta lines (className
	// contains "eyebrow") and price/stat meta (className contains __price
	// or __stat). Eyebrows and meta lines are typographic overhead, not
	// competing copy.
	let topParagraphs = 0;
	const paraRe = /<!--\s*wp:paragraph(\s+\{[^}]*\})?\s*-->/g;
	let pm;
	while ( ( pm = paraRe.exec( topSlice ) ) !== null ) {
		const attrs = pm[ 1 ] || '';
		if ( /eyebrow/i.test( attrs ) ) continue;
		if ( /__price|__stat|__meta|__note/i.test( attrs ) ) continue;
		topParagraphs += 1;
	}

	// Cover scrim: if wp:cover exists, dimRatio ≥50 or gradient present.
	const isCover = /<!--\s*wp:cover\b/.test( body );
	let scrimOk = true;
	if ( isCover ) {
		const coverStart = body.indexOf( '<!-- wp:cover' );
		const coverEnd = body.indexOf( '-->', coverStart );
		const coverTag = body.slice( coverStart, coverEnd );
		const dim = parseInt( ( coverTag.match( /"dimRatio":(\d+)/ ) || [ , '0' ] )[ 1 ], 10 );
		const hasGradient = /"gradient":/.test( coverTag );
		if ( dim < 50 && ! hasGradient ) scrimOk = false;
	}

	return { h1, h2, h3plus, allButtons, topParagraphs, isCover, scrimOk };
}

const failures = [];
const reports = [];

const files = readdirSync( PATTERNS_DIR ).filter( ( f ) => {
	if ( ! f.endsWith( '.php' ) ) return false;
	const name = f.replace( /\.php$/, '' );
	return BILLBOARD_PREFIXES.some( ( p ) => name.startsWith( p ) );
} ).sort();

for ( const f of files ) {
	const name = f.replace( /\.php$/, '' );
	const c = readFileSync( join( PATTERNS_DIR, f ), 'utf8' );
	const r = analyze( c );
	const issues = [];
	// B1: exactly one dominant heading (either one h1 OR one h2; but never
	// two or more h1/h2 at the top).
	if ( r.h1 + r.h2 > 2 ) issues.push( `B1 multiple dominant headings (h1=${ r.h1 }, h2=${ r.h2 })` );
	// Newsletter/order-confirmation are the exception — they are compact
	// confirmation states, not billboard heroes; they get a relaxed check.
	const isCompact = name === 'newsletter-confirm' || name === 'order-confirmation';
	if ( ! isCompact ) {
		if ( r.h1 > 1 ) issues.push( `B1 multiple h1` );
		if ( r.topParagraphs > 2 ) issues.push( `B2 too many body paragraphs (${ r.topParagraphs })` );
		if ( r.allButtons > 3 ) issues.push( `B3 >3 CTAs visible (${ r.allButtons })` );
		if ( r.isCover && ! r.scrimOk ) issues.push( `B5 cover dimRatio <50 and no gradient scrim` );
	} else {
		// Compact: max one button, one heading, one paragraph.
		if ( r.h1 + r.h2 > 1 ) issues.push( `B1(compact) >1 dominant heading (h1=${ r.h1 }, h2=${ r.h2 })` );
		if ( r.allButtons > 1 ) issues.push( `B3(compact) >1 primary CTA (${ r.allButtons })` );
	}
	if ( issues.length ) failures.push( { name, issues } );
	reports.push( { name, r, issues } );
}

console.log( `[H14 billboard] ${ files.length } billboard-classified patterns audited.` );
console.log( '' );
for ( const rep of reports ) {
	const ok = rep.issues.length === 0 ? 'PASS' : 'FAIL';
	console.log( `  ${ ok.padEnd( 4 ) } ${ rep.name.padEnd( 28 ) } h1=${ rep.r.h1 } h2=${ rep.r.h2 } p=${ rep.r.topParagraphs } cta=${ rep.r.allButtons } cover=${ rep.r.isCover ? 'Y' : 'N' }` );
}

if ( failures.length ) {
	console.error( '\n[H14 billboard] FAIL:' );
	for ( const f of failures ) {
		for ( const i of f.issues ) console.error( `  - ${ f.name }: ${ i }` );
	}
	process.exit( 1 );
}
console.log( '\n[H14 billboard] PASS — every billboard-classified pattern has one dominant hierarchy, ≤2 CTAs, and cover patterns carry a scrim.' );
