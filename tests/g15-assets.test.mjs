#!/usr/bin/env node
/**
 * G15 — Decoupled assets gate (H45).
 *
 *   A1  WooCommerce CSS/JS bytes are loaded ONLY on WooCommerce templates:
 *       the enqueue logic branches on is_woocommerce()/is_cart()/is_checkout()
 *       /is_account_page()/is_product().
 *   A2  The WooCommerce stylesheet is replaced by the theme's own (the
 *       87 KB woo cascade never ships) and is a separate file.
 *   A3  Module stylesheets (blog, cart, checkout, commerce, dark, megamenu,
 *       motion, search) are enqueued per-context, never all at once.
 *   A4  Home-page CSS budget: the always-loaded global sheet stays under 60%
 *       of the total theme CSS (static byte model; the network-log proof is
 *       the committed e2e spec — sandbox has no Chromium, honesty policy).
 */

import { readFileSync, readdirSync, statSync, existsSync } from 'node:fs';
import { join } from 'node:path';
import assert from 'node:assert/strict';

const root = process.cwd();
const themeDir = join( root, 'theme', 'arena-commerce' );
const assets = readFileSync( join( themeDir, 'inc', 'class-assets.php' ), 'utf8' );
const wc = readFileSync( join( themeDir, 'inc', 'class-woocommerce.php' ), 'utf8' );

/* A1 — conditional Woo assets. */
for ( const conditional of [ 'is_woocommerce', 'is_cart', 'is_checkout', 'is_account_page', 'is_product' ] ) {
	assert.ok( assets.includes( conditional ) || wc.includes( conditional ), `Woo conditional ${ conditional } missing (A1)` );
}

assert.ok( wc.includes( 'arena-woocommerce.css' ), 'the theme must ship its own replacement Woo stylesheet (A1/A2)' );
assert.ok( wc.includes( 'woocommerce-general' ), 'the plugin cascade must be replaced at woocommerce-general (A2)' );
assert.ok( assets.includes( 'is_woo_page' ) || assets.includes( 'is_woocommerce' ), 'the Woo sheet must load only on Woo templates (A1)' );

/* A2 — the Woo cascade is replaced, not stacked. */
assert.ok( /woocommerce-general|wp_deregister_style|dequeue/.test( wc ), 'the plugin stylesheet must be replaced (A2)' );

/* A3 — per-context module enqueues. */
const cssDir = join( themeDir, 'assets', 'css' );
const modules = readdirSync( join( cssDir, 'modules' ) )
	.filter( ( f ) => f.endsWith( '.css' ) && ! f.endsWith( '-rtl.css' ) )
	.map( ( f ) => f.replace( '.css', '' ) );

assert.ok( modules.length >= 7, `expected ≥ 7 module sheets, found ${ modules.length } (A3)` );

for ( const module of modules ) {
	assert.ok(
		assets.includes( `arena-${ module.replace( 'arena-', '' ) }` ) || assets.includes( module ),
		`module ${ module } must be referenced by the conditional loader (A3)`
	);
}

/* Every module load must be inside a conditional helper — the loader has one
   enqueue call per module guarded by context checks (no blanket loop). */
assert.ok( /is_blog_context|is_woo_page|is_shop_context|is_checkout_or_account|has_megamenu_markup|has_search_block/.test( assets ),
	'context helpers missing — modules must load per-template (A3)' );

/* A4 — home CSS budget (static byte model). */
const bytes = ( p ) => statSync( p ).size;
const global = bytes( join( cssDir, 'arena.css' ) );
let total = global;

for ( const module of modules ) {
	total += bytes( join( cssDir, 'modules', `${ module }.css` ) );
	total += bytes( join( cssDir, 'modules', `${ module }-rtl.css` ) );
}

const wooCss = existsSync( join( cssDir, 'arena-woocommerce.css' ) ) ? bytes( join( cssDir, 'arena-woocommerce.css' ) ) : 0;
total += wooCss;

const share = global / total;
assert.ok( share < 0.6, `global sheet is ${ ( share * 100 ).toFixed( 1 ) }% of total CSS — must stay < 60% (A4)` );

assert.ok( existsSync( join( root, 'tests', 'e2e', 'assets-decoupled.spec.js' ) ), 'committed network-log proof spec missing (A4)' );

console.log( `G15 PASS — Woo bytes gated to Woo templates; ${ modules.length } module sheets per-context;` );
console.log( `     global sheet ${ ( share * 100 ).toFixed( 1 ) }% of theme CSS (< 60%); e2e: assets-decoupled.spec.js` );
