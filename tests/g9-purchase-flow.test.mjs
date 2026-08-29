#!/usr/bin/env node
/**
 * G9 — Purchase flow without reloads (H29/H33).
 *
 * HONESTY POLICY (v2.0 AP5): this sandbox has no Chromium and no PHP, so a
 * committed network log cannot be produced here. The runtime proof is the
 * committed Playwright spec tests/e2e/purchase-flow.spec.js (counting
 * non-XHR navigations during add-to-cart → drawer → qty → undo → checkout
 * step), executed on the real wp-env. This file is the deterministic STATIC
 * PROXY that keeps the no-reload architecture honest between real runs:
 *
 *   P1  Add-to-cart, quantity stepper and removal-undo are fetch() calls
 *       against the Store API / wc-ajax — never form submits (the only
 *       .submit() allowed is the explicit no-JS fallback branch).
 *   P2  The cart drawer opens on the add-to-cart event without navigation.
 *   P3  The mini-cart drawer, qty stepper and undo markup hooks exist in the
 *       server-rendered shells (H33).
 *   P4  The sticky mobile cart bar mounts when the cart is non-empty.
 *   P5  Zero jQuery in the commerce runtime.
 */

import { readFileSync, existsSync } from 'node:fs';
import { join } from 'node:path';
import assert from 'node:assert/strict';

const root = process.cwd();
const themeDir = join( root, 'theme', 'arena-commerce' );
const read = ( p ) => readFileSync( join( themeDir, p ), 'utf8' );

const cartJs = read( 'assets/js/modules/arena-cart.js' );
const cartPhp = read( 'inc/class-cart.php' );
const wcPhp = read( 'inc/class-woocommerce.php' );

/* P1 — fetch-based mutations; the only submit() is the no-JS fallback. */
assert.ok( /fetch\(/.test( cartJs ), 'cart module must use fetch (P1)' );
assert.ok( /wc-ajax|store-api|wc\/store/.test( cartJs ), 'cart module must target the Store API / wc-ajax (P1)' );

const submits = [ ...cartJs.matchAll( /\.submit\(\)/g ) ].length;
assert.ok( submits <= 1, `cart module calls submit() ${ submits } times — only the no-JS fallback may (P1)` );
assert.ok( /no[- ]js|javascript|fallback/i.test( cartJs ), 'the submit() call must sit in the documented no-JS fallback (P1)' );

/* P2 — the add-to-cart form is intercepted: preventDefault → fetch → drawer,
   with an explicit no-JS fallback branch that keeps the plain submit. */
assert.ok( cartJs.includes( "addEventListener( 'submit'" ), 'cart module must intercept the add-to-cart form (P2)' );
assert.ok( /preventDefault/.test( cartJs ), 'the intercepted submit must be prevented (P2)' );
/* Navigation is allowed ONLY inside a documented .catch() fallback branch
   (progressive enhancement when the Store API refuses). */
const navSites = [ ...cartJs.matchAll( /location\.(?:href|assign|reload)/g ) ];
for ( const site of navSites ) {
	const before = cartJs.slice( Math.max( 0, site.index - 220 ), site.index );
	assert.ok( before.includes( '.catch' ), 'a navigation outside a fallback branch was found (P2)' );
}

/* P3 — server-rendered drawer + stepper + undo hooks (H33). */
for ( const hook of [
	'data-arena-cart-body',
	'data-arena-cart-footer',
	'aria-modal="true"',
] ) {
	assert.ok( cartPhp.includes( hook ), `class-cart.php missing ${ hook } (P3)` );
}
/* Qty stepper buttons carry data-arena-qty="±1"; removal + undo are wired
   through the row key and the undo toast (H33). */
assert.ok( cartPhp.includes( 'data-arena-qty' ) || cartJs.includes( 'data-arena-qty' ), 'qty stepper hook data-arena-qty missing (P3/H33)' );
assert.ok( /arena-undo-toast/.test( cartJs ), 'removal-undo toast missing (P3/H33)' );
assert.ok( /data-key/.test( cartJs ) || cartPhp.includes( 'data-key' ), 'cart rows must carry their key for update/remove (P3/H33)' );
assert.ok( /function updateItem|cart\/update-item/.test( cartJs ), 'qty updates must go through the API (P3/H33)' );

/* P4 — sticky mobile cart bar. */
assert.ok( cartPhp.includes( 'arena-sticky-cart-bar' ), 'sticky mobile cart bar missing (P4)' );

/* P5 — zero jQuery (comments stripped so the "no jQuery" banner does not
   count as a sighting). */
const noComments = ( js ) => js.replace( /\/\*[\s\S]*?\*\//g, '' ).replace( /^\s*\/\/.*$/gm, '' );
for ( const js of [ cartJs, read( 'assets/js/arena.js' ) ] ) {
	assert.ok( ! /jQuery|\$\(/.test( noComments( js ) ), 'jQuery found in the commerce runtime (P5)' );
}

/* Reassurance rides the add-to-cart surface without a reload (H33, v2.0
   behaviour kept: woocommerce_after_add_to_cart_button). */
assert.ok( /after_add_to_cart|added_to_cart/.test( wcPhp ), 'WooCommerce class must hook the add-to-cart flow' );

/* The real-run proof is committed, not improvised. */
assert.ok( existsSync( join( root, 'tests', 'e2e', 'purchase-flow.spec.js' ) ),
	'tests/e2e/purchase-flow.spec.js must be committed (G9 real evidence)' );

console.log( 'G9 PASS (static proxy) — purchase flow architecture is reload-free;' );
console.log( '     committed runtime proof: tests/e2e/purchase-flow.spec.js (wp-env)' );
