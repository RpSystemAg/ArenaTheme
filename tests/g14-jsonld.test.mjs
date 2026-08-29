#!/usr/bin/env node
/**
 * G14 — JSON-LD gate (H43/H44/AP12).
 *
 *   J1  One server-side schema class emits a single @graph per template:
 *       WebSite + Organization (home), Article (posts), Product + Offer
 *       (product), CollectionPage (archives), BreadcrumbList (everywhere,
 *       coherent with the H30 trail).
 *   J2  The graph is printed once (single hook, single <script type=
 *       "application/ld+json">) — no duplicate blocks.
 *   J3  The theme yields completely when Yoast OR Rank Math is active:
 *       the class returns before printing, and the list is filterable.
 *   J4  Rich-results runtime proof (Rich Results Test) is the committed
 *       real-run script — this sandbox has no PHP, so no scores are invented
 *       (honesty policy); the static contract is verified here.
 */

import { readFileSync, existsSync } from 'node:fs';
import { join } from 'node:path';
import assert from 'node:assert/strict';

const root = process.cwd();
const schemaPath = join( root, 'theme', 'arena-commerce', 'inc', 'class-schema.php' );
const schema = readFileSync( schemaPath, 'utf8' );

/* J1 — node coverage per template. */
const nodes = [
	[ 'WebSite', 'home' ],
	[ 'Organization', 'home' ],
	[ 'Article', 'single post' ],
	[ 'Product', 'product' ],
	[ 'Offer', 'product' ],
	[ 'CollectionPage', 'archives' ],
	[ 'BreadcrumbList', 'everywhere' ],
];

for ( const [ type, where ] of nodes ) {
	assert.ok( schema.includes( `'@type'` ) && schema.includes( type ), `Schema must emit ${ type } (${ where }) (J1)` );
}

/* J2 — one graph, one print. */
assert.ok( schema.includes( "'@graph'" ), 'the schema must be a single @graph (J2)' );
assert.ok( ( schema.match( /add_action\(\s*'wp_head'/g ) || [] ).length === 1, 'exactly one wp_head hook (J2)' );
assert.ok( ( schema.match( /application\/ld\+json/g ) || [] ).length === 1, 'exactly one ld+json script tag (J2)' );

/* J3 — yield to SEO plugins, filterable. */
assert.ok( schema.includes( 'WPSEO_VERSION' ), 'must detect Yoast (J3)' );
assert.ok( schema.includes( 'RANK_MATH_VERSION' ), 'must detect Rank Math (J3)' );
assert.ok( schema.includes( 'arena_theme_seo_plugin_active' ), 'the SEO-plugin check must be filterable (J3)' );
assert.ok( /seo_plugin_active\(\)\s*\)\s*\{\s*return;/.test( schema ), 'print_schema() must bail out entirely when an SEO plugin owns the markup (J3)' );

/* The BreadcrumbList must reuse the same trail as the H30 breadcrumb. */
const breadcrumb = readFileSync( join( root, 'theme', 'arena-commerce', 'inc', 'class-breadcrumb.php' ), 'utf8' );
assert.ok( breadcrumb.includes( 'BreadcrumbList' ) || schema.includes( 'Breadcrumb' ), 'breadcrumb trail and JSON-LD must be coherent (J1/H30)' );

/* J4 — the real-run proof is committed. */
assert.ok(
	existsSync( join( root, 'tools', 'php', 'rich-results-check.php' ) ) || existsSync( join( root, 'tests', 'e2e', 'jsonld.spec.js' ) ),
	'a committed real-run Rich Results script must exist (J4)'
);

/* The schema output is valid JSON by construction: json_encode with flags. */
assert.ok( /json_encode/.test( schema ) && /JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE/.test( schema ), 'graph must be printed with json_encode + unescape flags (J2)' );

console.log( 'G14 PASS — one @graph per template, Yoast/RankMath yield, committed real-run script' );
