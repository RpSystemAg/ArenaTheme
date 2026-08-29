#!/usr/bin/env node
/**
 * G11 — i18n gate (H42).
 *
 *   I1  Both POTs are committed and current (regenerated via
 *       `node tools/make-pot.mjs --check` — no drift between sources and pot).
 *   I2  Zero untranslated domain mismatches: every gettext call in theme and
 *       plugin uses its own text domain.
 *   I3  The WPML/Polylang adapters exist, are guarded (they register only
 *       when the plugin is active) and are documented.
 *   I4  Every kit ships complete en_US + it_IT with identical key sets
 *       (checked per kit here, at kit level as G11 requires).
 *   I5  The make-pot step is part of the documented release/CI flow.
 */

import { readFileSync, readdirSync, existsSync } from 'node:fs';
import { join } from 'node:path';
import { execFileSync } from 'node:child_process';
import assert from 'node:assert/strict';

const root = process.cwd();
const problems = [];

/* I1 — pots committed and fresh. */
const potTheme = join( root, 'theme', 'arena-commerce', 'languages', 'arena-commerce.pot' );
const potPlugin = join( root, 'plugin', 'arena-engine', 'languages', 'arena-engine.pot' );

assert.ok( existsSync( potTheme ), 'theme pot missing (I1)' );
assert.ok( existsSync( potPlugin ), 'plugin pot missing (I1)' );

execFileSync( 'node', [ 'tools/make-pot.mjs', '--check' ], { cwd: root, stdio: 'pipe' } );

for ( const pot of [ potTheme, potPlugin ] ) {
	const content = readFileSync( pot, 'utf8' );
	assert.ok( content.includes( 'Project-Id-Version' ) && content.includes( 'msgid ""' ), `${ pot } malformed (I1)` );
	assert.ok( ( content.match( /^msgid /gm ) || [] ).length > 40, `${ pot } suspiciously thin (I1)` );
}

/* I2 — domain hygiene: no cross-domain gettext calls. The one documented
   exception: WooCommerce-native strings ("Sale!") are translated in the
   WOOCOMMERCE domain on purpose, so the shop's own .mo files apply and the
   theme never ships a duplicate translation. */
const FOREIGN_DOMAINS_OK = new Set( [ 'woocommerce' ] );

function checkDomains( dir, domain ) {
	const files = readdirSync( dir, { recursive: true } ).filter( ( f ) => String( f ).endsWith( '.php' ) );

	for ( const file of files ) {
		const code = readFileSync( join( dir, String( file ) ), 'utf8' );

		for ( const m of code.matchAll( /(?:__|_e|esc_html__|esc_html_e|esc_attr__|esc_attr_e)\(\s*'((?:[^'\\]|\\.)*)'\s*,\s*'([^']+)'/g ) ) {
			if ( m[ 2 ] === domain || FOREIGN_DOMAINS_OK.has( m[ 2 ] ) ) continue;

			const problem = `${ file }: domain "${ m[ 2 ] }" (expected ${ domain })`;
			if ( ! problems.includes( problem ) ) {
				problems.push( problem );
			}
		}
	}
}

checkDomains( join( root, 'theme', 'arena-commerce' ), 'arena-commerce' );
checkDomains( join( root, 'plugin', 'arena-engine' ), 'arena-engine' );

/* I3 — adapters guarded + documented. */
const integrations = readFileSync( join( root, 'plugin', 'arena-engine', 'includes', 'i18n', 'class-integrations.php' ), 'utf8' );
for ( const needle of [ 'wpml_active', 'polylang_active', 'pll_register_string', 'wpml_register_single_string' ] ) {
	assert.ok( integrations.includes( needle ), `i18n adapters missing ${ needle } (I3)` );
}
assert.ok( existsSync( join( root, 'docs', 'dev', 'i18n.md' ) ), 'docs/dev/i18n.md missing (I3)' );

/* I4 — kit locale parity. */
const kitsDir = join( root, 'plugin', 'arena-engine', 'kits' );
let kits = 0;

for ( const slug of readdirSync( kitsDir ) ) {
	const manifestPath = join( kitsDir, slug, 'kit.json' );
	if ( ! existsSync( manifestPath ) ) continue;

	const manifest = JSON.parse( readFileSync( manifestPath, 'utf8' ) );
	kits++;

	const en = Object.keys( manifest.i18n.en_US ).sort().join( '|' );
	const it = Object.keys( manifest.i18n.it_IT ).sort().join( '|' );
	assert.equal( it, en, `${ slug }: locale key sets differ (I4)` );

	/* No empty values: an untranslated slot is a hard-coded string. */
	for ( const [ locale, map ] of Object.entries( manifest.i18n ) ) {
		for ( const [ key, value ] of Object.entries( map ) ) {
			assert.ok( String( value ).trim().length > 0, `${ slug }/${ locale }: empty value for ${ key } (I4)` );
		}
	}
}

assert.ok( kits >= 12, `expected ≥ 12 kits with i18n, found ${ kits } (I4)` );

/* I5 — the release flow documents the pot step. */
const ci = readFileSync( join( root, 'docs', 'ci.md' ), 'utf8' );
assert.ok( ci.includes( 'make-pot' ), 'docs/ci.md must document the make-pot step (I5)' );

if ( problems.length ) {
	console.error( `G11 FAIL — ${ problems.length } domain problem(s):` );
	problems.forEach( ( p ) => console.error( '  ✗ ' + p ) );
	process.exit( 1 );
}

console.log( `G11 PASS — pots fresh & committed, domains clean, adapters guarded, ${ kits } kits bilingual` );
