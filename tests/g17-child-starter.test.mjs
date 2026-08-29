#!/usr/bin/env node
/**
 * G17 — Child theme starter gate (H50).
 *
 *   C1  A child theme starter ships in the repository (theme/arena-commerce-child)
 *       with Template: arena-commerce and its own header.
 *   C2  It overrides a design token SAFELY: the child enqueues the parent
 *       stylesheet then its own one-variable override — no parent file is
 *       copied or duplicated.
 *   C3  Child-safe hooks: the starter demonstrates the documented extension
 *       points (filters) instead of replacing theme internals.
 *   C4  The starter has a real template (index.html) so it activates, and a
 *       docs/utente page explains it (AP14).
 */

import { readFileSync, existsSync } from 'node:fs';
import { join } from 'node:path';
import assert from 'node:assert/strict';

const root = process.cwd();
const child = join( root, 'theme', 'arena-commerce-child' );

/* C1 — starter present and well-formed. */
const style = readFileSync( join( child, 'style.css' ), 'utf8' );
assert.ok( /Theme Name:\s*./.test( style ), 'child style.css needs a theme name (C1)' );
assert.ok( /Template:\s*arena-commerce/.test( style ), 'child must declare Template: arena-commerce (C1)' );
assert.ok( /Text Domain:\s*arena-commerce-child/.test( style ), 'child needs its own text domain (C1)' );

/* C2 — safe token override, parent untouched. */
const functions = readFileSync( join( child, 'functions.php' ), 'utf8' );
assert.ok( /array\(\s*'arena-commerce'\s*\)/.test( functions ) || functions.includes( "'arena-commerce'," ),
	'child sheet must depend on the parent handle (parent cascade first) (C2)' );
assert.ok( /wp_enqueue_style\(\s*'arena-commerce-child'/.test( functions ), 'child must enqueue its own sheet second (C2)' );

const childCss = readFileSync( join( child, 'assets', 'css', 'child.css' ), 'utf8' );
assert.ok( childCss.includes( '--wp--preset--color--accent' ) || childCss.includes( 'var(--wp--preset--' ),
	'the starter must demonstrate a token override (C2)' );
assert.ok( childCss.length < 1200, 'the starter override must stay tiny — extend, don\'t rebuild (C2)' );

/* C3 — extension through documented hooks, not overrides of internals. */
assert.ok( functions.includes( 'add_filter' ), 'the starter must use the documented filters (C3)' );
assert.ok( /arena_theme_bottom_nav_items|arena_theme_wishlist_url|arena_theme_checkout_mode|arena_engine_modules/.test( functions ),
	'the starter should demonstrate a real Arena hook (C3)' );

/* C4 — activates as a block theme + has a doc page. */
assert.ok( existsSync( join( child, 'templates', 'index.html' ) ), 'child needs templates/index.html to activate (C4)' );
assert.ok( existsSync( join( child, 'theme.json' ) ), 'child needs its own theme.json (C4)' );
assert.ok( existsSync( join( root, 'docs', 'utente', 'child-theme.md' ) ), 'docs/utente/child-theme.md missing (C4)' );

console.log( 'G17 PASS — child starter overrides tokens safely through hooks, documented' );
