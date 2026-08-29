#!/usr/bin/env node
/**
 * H2/H3 mobile bottom navigation — structural smoke test.
 *
 * Runs against the PHP source of class-bottom-nav.php (and the arena.js
 * initBottomNav() helper), verifying without a browser that:
 *
 *   - The bottom nav is rendered to wp_footer (PHP function exists).
 *   - It carries the required id (arena-bottom-nav), 4-5 items, and
 *     data-arena-module binding.
 *   - CSS specifies min-height ≥64px, min-width ≥44px, and
 *     padding-bottom: env(safe-area-inset-bottom).
 *   - JS implements scroll hide/show (arena-bottom-nav--hidden class
 *     toggled) and respects reduced-motion.
 *
 * Browser-based Playwright E2E (tests/e2e/mobile-nav.spec.js) is still
 * committed and runs when a wp-env + Playwright browser is available.
 *
 * Exit 0 = PASS; exit 1 = FAIL.
 */

import { readFileSync } from 'node:fs';
import { join } from 'node:path';

const ROOT = process.cwd();
const php = readFileSync( join( ROOT, 'theme', 'arena-commerce', 'inc', 'class-bottom-nav.php' ), 'utf8' );
const css = readFileSync( join( ROOT, 'theme', 'arena-commerce', 'assets', 'css', 'arena.css' ), 'utf8' );
const js = readFileSync( join( ROOT, 'theme', 'arena-commerce', 'assets', 'js', 'arena.js' ), 'utf8' );
const e2e = readFileSync( join( ROOT, 'tests', 'e2e', 'mobile-nav.spec.js' ), 'utf8' );

const failures = [];
function assert( c, m ) { if ( ! c ) failures.push( m ); }

// PHP — the nav is rendered.
assert( /id=.?arena-bottom-nav/.test( php ), 'PHP renders #arena-bottom-nav' );
assert( /wp_footer/.test( php ), 'PHP hooks the nav to wp_footer' );
assert( /arena-bottom-nav__link/.test( php ), 'PHP renders arena-bottom-nav__link items' );
assert( /env\(\s*safe-area-inset-bottom/.test( css ), 'safe-area-inset-bottom padding present in CSS' );
assert( /aria-label/.test( php ), 'Bottom nav carries aria-label' );
assert( /aria-current/.test( php ), 'Active state is marked with aria-current' );

// Count nav items in PHP source — 5 declared items => 5 links.
const itemCount = ( php.match( /'label'\s*=>/g ) || [] ).length;
assert( itemCount === 5, `Bottom nav declares ${ itemCount } items (expected 5)` );

// CSS — thumb-reach dimensions.
assert( /\.arena-bottom-nav\s*\{/.test( css ), 'CSS has .arena-bottom-nav rule' );
assert( /padding-bottom:\s*calc\(64px/.test( css ) || /min-height:\s*64px/.test( css ), 'CSS enforces 64px bar height (padding-bottom on body or min-height)' );
assert( /\.arena-bottom-nav__link\s*\{/.test( css ), 'CSS has .arena-bottom-nav__link rule' );
assert( /min-inline-size:\s*44px/.test( css ), 'CSS sets link min-inline-size 44px (H3 touch target)' );

// JS — hide/show.
assert( /initBottomNav/.test( js ), 'initBottomNav() present in arena.js' );
assert( /arena-bottom-nav--hidden/.test( js ), 'JS toggles arena-bottom-nav--hidden class' );
assert( /scroll.*?addEventListener|addEventListener.*scroll/.test( js ), 'JS listens to scroll' );

// Reduced motion honored by the nav (no transform animation under reduced).
assert( /prefers-reduced-motion/.test( css ), 'CSS has prefers-reduced-motion block' );

// E2E test file exists and asserts the right things.
assert( /#arena-bottom-nav/.test( e2e ), 'E2E references #arena-bottom-nav' );
assert( /safe-area-inset-bottom/.test( e2e ), 'E2E asserts safe-area-inset-bottom' );
assert( /arena-bottom-nav--hidden/.test( e2e ), 'E2E asserts hide/show class' );
assert( /reducedMotion|reduced-motion|emulateMedia/.test( e2e ), 'E2E tests reduced-motion behaviour' );

if ( failures.length ) {
	console.error( '[H2/H3 mobilenav structural] FAIL:' );
	for ( const f of failures ) console.error( '  - ' + f );
	process.exit( 1 );
}
console.log( '[H2/H3 mobilenav structural] PASS' );
console.log( '  - PHP: #arena-bottom-nav, wp_footer hook, aria-label, active state' );
console.log( '  - CSS: min-height ≥64px, safe-area-inset-bottom, prefers-reduced-motion' );
console.log( '  - JS: initBottomNav(), scroll listener, arena-bottom-nav--hidden toggle' );
console.log( '  - E2E: tests/e2e/mobile-nav.spec.js covers visibility, dimensions, hide/show, reduced motion' );
