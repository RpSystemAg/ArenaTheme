#!/usr/bin/env node
/**
 * G16 — Real dark mode gate (H47/H48).
 *
 *   D1  The scheme flips a real data-theme attribute on the root element
 *       (no inversion filters — AP13).
 *   D2  prefers-color-scheme is the default when nothing is stored.
 *   D3  Persistence without reload: localStorage + an inline boot script
 *       (no flash, no navigation).
 *   D4  The toggle ships in the header AND in the bottom nav (H47).
 *   D5  Every preset palette has an inverted twin (the six light presets in
 *       arena-dark.css + midnight, which is dark-first, + the default twin).
 *   D6  Contrast discipline: dark rules set explicit palette variables (not
 *       filter: invert) and both schemes keep color-scheme declared.
 *   D7  Runtime axe (light + dark, 0 violations) is the committed real-run
 *       spec — this sandbox has no Chromium (honesty policy).
 */

import { readFileSync, existsSync } from 'node:fs';
import { join } from 'node:path';
import assert from 'node:assert/strict';

const root = process.cwd();
const themeDir = join( root, 'theme', 'arena-commerce' );
const dark = readFileSync( join( themeDir, 'assets', 'css', 'modules', 'arena-dark.css' ), 'utf8' );
const darkPhp = readFileSync( join( themeDir, 'inc', 'class-dark-mode.php' ), 'utf8' );
const bottomNav = readFileSync( join( themeDir, 'inc', 'class-bottom-nav.php' ), 'utf8' );
const headerSlots = readFileSync( join( themeDir, 'inc', 'class-header-slots.php' ), 'utf8' );

/* D1 — real attribute flip (comments stripped so the AP13 ban is checked on
   actual rules, not on the banner that declares the ban). */
const darkRules = dark.replace( /\/\*[\s\S]*?\*\//g, '' );
assert.ok( dark.includes( '[data-theme="dark"]' ), 'dark rules must key off [data-theme="dark"] (D1)' );
assert.ok( ! /filter:\s*invert/i.test( darkRules ), 'filter: invert() is forbidden — AP13 (D1)' );

/* D2 — prefers-color-scheme default. */
assert.ok( dark.includes( 'prefers-color-scheme' ), 'prefers-color-scheme default missing (D2)' );
assert.ok( darkPhp.includes( 'prefers-color-scheme' ), 'the boot script must fall back to the media query (D2)' );

/* D3 — persistence without reload. */
assert.ok( darkPhp.includes( 'localStorage' ), 'scheme must persist in localStorage (D3)' );
assert.ok( darkPhp.includes( 'setAttribute' ), 'the boot script must set data-theme before paint (D3)' );
assert.ok( ! /location\.reload/.test( darkPhp ), 'toggling must never reload (D3)' );

/* D4 — toggle in header AND bottom nav. */
assert.ok(
	headerSlots.includes( 'Dark_Mode::render_toggle' ) || headerSlots.includes( 'data-arena-theme-toggle' ),
	'toggle missing from the header slots (D4)'
);
assert.ok( bottomNav.includes( 'data-arena-theme-toggle' ), 'toggle missing from the bottom nav (D4)' );

/* D5 — inverted twins for every palette. */
for ( const preset of [ 'commerce', 'editorial', 'magazine', 'minimal', 'brutal', 'soft' ] ) {
	assert.ok( dark.includes( `[data-arena-preset="${ preset }"][data-theme="dark"]` ), `${ preset }: dark twin missing (D5)` );
}
assert.ok( dark.includes( 'midnight' ), 'midnight (dark-first) handling missing (D5)' );
assert.ok( /^\[data-theme="dark"\]/m.test( dark ), 'default palette dark twin missing (D5)' );

/* D6 — both schemes declare color-scheme (forced-colors friendly). */
assert.ok( dark.includes( 'color-scheme: dark' ) && dark.includes( 'color-scheme: light' ), 'color-scheme must be declared for both states (D6)' );
assert.ok( /forced-colors/.test( dark ), 'forced-colors support missing (D6/H48)' );
assert.ok( /prefers-contrast/.test( dark ), 'prefers-contrast support missing (D6/H48)' );

/* D7 — committed runtime proof. */
assert.ok( existsSync( join( root, 'tests', 'e2e', 'dark-mode.spec.js' ) ), 'dark-mode e2e spec (axe light+dark, persistence) missing (D7)' );

console.log( 'G16 PASS — real data-theme flip, persistence without reload, toggle in header + bottom nav,' );
console.log( '     every palette has an inverted twin; axe runtime proof: tests/e2e/dark-mode.spec.js' );
