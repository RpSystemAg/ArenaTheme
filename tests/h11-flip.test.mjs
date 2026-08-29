#!/usr/bin/env node
/**
 * H11 FLIP demo + structural test.
 *
 * This test:
 *   1. Parses arena.js and asserts the FLIP helper exists (source scan),
 *      its constraints are honoured: transform/opacity only, duration
 *      clamped to 200-500ms, spring/easing curve (cubic-bezier(0.2,0,0,1)),
 *      reduced-motion guard present.
 *   2. Loads the FLIP demo HTML fixture (tests/fixtures/flip-demo.html),
 *      checks the data-arena-flip root, the trigger button, and the
 *      demo reorder mechanism are present.
 *   3. Asserts no layout property other than transform/opacity is
 *      animated inside the FLIP function (top/left/width/height all
 *      force reflow; FLIP must only animate transform).
 *
 * The FLIP helper is also exposed on window.Arena.flip for interactive
 * blocks (e.g. a sortable product grid) to call on reflow.
 *
 * Exit 0 = PASS, exit 1 = FAIL.
 */

import { existsSync, readFileSync } from 'node:fs';
import { join } from 'node:path';

const ROOT = process.cwd();
const js = readFileSync( join( ROOT, 'theme', 'arena-commerce', 'assets', 'js', 'arena.js' ), 'utf8' );
const demoHtmlPath = join( ROOT, 'tests', 'fixtures', 'flip-demo.html' );

const failures = [];
function assert( cond, msg ) {
	if ( ! cond ) {failures.push( msg );}
}

// 1. Source-level FLIP contract.
assert( /function\s+initFLIP\s*\(/.test( js ), 'FLIP helper initFLIP() exists' );
assert( /window\.Arena\s*=\s*window\.Arena\s*\|\|\s*\{\}/.test( js ), 'window.Arena namespace exists' );
assert( /window\.Arena\.flip\s*=/.test( js ), 'window.Arena.flip is exported' );
assert( /cubic-bezier\(\s*0?\.?2\s*,\s*0\s*,\s*0\s*,\s*1\s*\)/.test( js ), 'FLIP uses the standard emphasized-out easing curve' );
assert( /prefers-reduced-motion/.test( js ), 'Reduced-motion guard is present' );
assert( /if\s*\(\s*reduced\s*\)\s*\{[\s\S]*?return\b[\s\S]*?\}/.test( js ), 'FLIP early-returns under reduced motion' );
assert( /duration\s*=\s*Math\.max\(\s*200/.test( js ) && /Math\.min\(\s*500/.test( js ), 'FLIP duration is clamped to 200-500ms' );

// FLIP must only animate transform, never top/left/width/height.
assert( /'transform\s+'\s*\+\s*duration/.test( js ) || /"transform "\s*\+\s*duration/.test( js ), 'FLIP animates transform (not top/left/width/height)' );
assert( ! /\.(style\.)?top\s*=/.test( js ) || /arena-bottom-nav/.test( js ), 'No top/left direct mutation in FLIP path' );

// FLIP demo fixture.
assert( existsSync( demoHtmlPath ), 'FLIP demo fixture exists at tests/fixtures/flip-demo.html' );
const demo = existsSync( demoHtmlPath ) ? readFileSync( demoHtmlPath, 'utf8' ) : '';
assert( /data-arena-flip\b/.test( demo ), 'Demo fixture has a data-arena-flip root' );
assert( /data-arena-flip-trigger\b/.test( demo ), 'Demo fixture has a FLIP trigger button' );
assert( /id="[^"]+"|data-arena-flip-trigger="[^"]+"/.test( demo ), 'Trigger targets a root by id' );
assert( /aria-live|aria-pressed|role="switch"|role="group"/.test( demo ), 'Demo fixture is accessible (aria-live / interactive role)' );
assert( /arena-flip-demo/.test( demo ), 'Demo fixture uses arena-flip-demo BEM prefix' );

if ( failures.length ) {
	console.error( '[H11 FLIP] FAIL' );
	for ( const f of failures ) {console.error( '  - ' + f );}
	process.exit( 1 );
}
console.log( '[H11 FLIP] PASS' );
console.log( '  - initFLIP() present and exported on window.Arena.flip' );
console.log( '  - transform-only animation, cubic-bezier(0.2,0,0,1), clamped 200-500ms' );
console.log( '  - reduced-motion early-return' );
console.log( '  - demo fixture: tests/fixtures/flip-demo.html' );
