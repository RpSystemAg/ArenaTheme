#!/usr/bin/env node
/**
 * G13 — Preset gate (H40).
 *
 *   S1  Eight presets ship: default, midnight, editorial, commerce,
 *       magazine, minimal, brutal, soft — as styles/ variations.
 *   S2  Each preset is a complete pairing: palette (all 14 semantic slugs),
 *       font families (system stacks, zero webfonts), spacing density and
 *       radius tokens — not a palette-only recolour (except midnight, the
 *       v2.0 palette variation, which keeps its original scope).
 *   S3  Presets are applied as a tracked styles/variation via the panel
 *       (preset.json) with undo (interlocks with G12).
 *   S4  Within the v2.0 budget: every preset file is lightweight; the theme
 *       never loads a preset stylesheet (it is declarative theme.json data).
 */

import { readFileSync, readdirSync, existsSync } from 'node:fs';
import { join } from 'node:path';
import assert from 'node:assert/strict';

const root = process.cwd();
const stylesDir = join( root, 'theme', 'arena-commerce', 'styles' );

const REQUIRED = [ 'default', 'midnight', 'editorial', 'commerce', 'magazine', 'minimal', 'brutal', 'soft' ];
const PALETTE_SLUGS = [
	'base', 'surface', 'surface-alt', 'foreground', 'muted', 'primary',
	'primary-hover', 'accent', 'accent-soft', 'success', 'warning', 'danger',
	'border-strong', 'outline',
];

const files = readdirSync( stylesDir ).filter( ( f ) => f.endsWith( '.json' ) );
const slugs = files.map( ( f ) => f.replace( '.json', '' ) );

/* S1 — all eight present. */
for ( const preset of REQUIRED ) {
	assert.ok( slugs.includes( preset ), `preset ${ preset } missing (S1)` );
}

assert.ok( slugs.length >= 8, `expected ≥ 8 presets, found ${ slugs.length } (S1)` );

/* S2 — complete pairings. */
for ( const slug of REQUIRED ) {
	const preset = JSON.parse( readFileSync( join( stylesDir, `${ slug }.json` ), 'utf8' ) );

	assert.ok( preset.title, `${ slug }: title missing (S2)` );

	const palette = preset.settings?.color?.palette ?? [];
	const presetSlugs = palette.map( ( c ) => c.slug );

	for ( const colorSlug of PALETTE_SLUGS ) {
		assert.ok(
			presetSlugs.includes( colorSlug ),
			`${ slug }: palette slot ${ colorSlug } missing (S2 — every preset is a full pairing, not a recolour)`
		);
	}

	if ( slug === 'midnight' ) {
		continue; /* v2.0 palette-only variation, grandfathered scope. */
	}

	const families = preset.settings?.typography?.fontFamilies ?? [];
	assert.ok( families.length >= 3, `${ slug }: needs ≥ 3 font families (S2)` );
	assert.ok(
		families.every( ( f ) => ! /https?:|\.woff/i.test( JSON.stringify( f.fontFamily ) ) ),
		`${ slug }: webfont URLs are forbidden — system stacks only (H26/S2)`
	);

	assert.ok( ( preset.settings?.spacing?.spacingSizes ?? [] ).length >= 6, `${ slug }: spacing density missing (S2)` );
	assert.ok( preset.settings?.custom?.radius, `${ slug }: radius tokens missing (S2)` );

	/* Heading pairing: the display family is wired to h1–h6. */
	const elements = preset.styles?.elements ?? {};
	assert.ok( elements.h1?.typography?.fontFamily, `${ slug }: h1 must carry the display family (S2)` );

	/* S4 — declarative budget: theme.json data, featherweight. */
	const bytes = readFileSync( join( stylesDir, `${ slug }.json` ) ).length;
	assert.ok( bytes < 6000, `${ slug }: ${ bytes } bytes — a preset must stay declarative data (S4)` );
}

/* S3 — applied as a tracked variation with undo (panel path). */
const restPanel = readFileSync( join( root, 'plugin', 'arena-engine', 'includes', 'admin', 'class-rest-panel.php' ), 'utf8' );
assert.ok( restPanel.includes( "Variations::write( 'preset.json'" ), 'presets must be applied through the tracked variation (S3)' );
assert.ok( restPanel.includes( 'arena_active_preset' ), 'the active preset pointer must be stored (S3)' );

/* The dark twin of every palette ships (H47 interlock). */
const dark = readFileSync( join( root, 'theme', 'arena-commerce', 'assets', 'css', 'modules', 'arena-dark.css' ), 'utf8' );
for ( const slug of [ 'commerce', 'editorial', 'magazine', 'minimal', 'brutal', 'soft' ] ) {
	assert.ok( dark.includes( `[data-arena-preset="${ slug }"][data-theme="dark"]` ), `${ slug }: dark twin missing (S3/H47)` );
}

console.log( `G13 PASS — ${ slugs.length } presets (complete pairings), tracked application, dark twins in place` );
