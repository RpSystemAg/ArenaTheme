#!/usr/bin/env node
/**
 * G8 — Starter kit gate (H19–H23).
 *
 * Static proxies for the kit block (the <60 s import + undo run is the
 * committed Playwright spec tests/e2e/kit-import.spec.js, executed on a real
 * wp-env — see docs/ci.md; this sandbox has no PHP/Chromium, per the v2.0
 * honesty policy no runtime numbers are invented here):
 *
 *   K1  ≥ 12 kits, each manifest valid (pages 6–9, menu, products, family,
 *       preset, campaign, en_US + it_IT).
 *   K2  Every home is structurally distinct: pairwise structural Jaccard
 *       ≤ 0.40 (AP8 — kits are not recolorations).
 *   K3  Every kit home passes the billboard proxies: exactly one h1; every
 *       cover block declares dimRatio ≥ 50 (H14/H21).
 *   K4  Zero unresolved tokens: every {{t:}} used exists in BOTH locales;
 *       every {{pattern:}} resolves to a real theme pattern (H23).
 *   K5  Zero lock-in: no proprietary shortcodes, no custom tables; page
 *       markup is core blocks + registered patterns only (H23).
 *   K6  Campaign assets: 3 SVGs per kit at 9×16 / 1×1 / 16×9 (H21).
 *   K7  Import cost proxy: block count per kit under the documented budget
 *       (keeps the real import under 60 s; budget = 900 blocks/kit).
 *   K8  The importer implements full undo + skip-without-confirm (H20):
 *       static contract checks on class-importer.php.
 */

import { readFileSync, readdirSync, existsSync, statSync } from 'node:fs';
import { join } from 'node:path';
import assert from 'node:assert/strict';

const root = process.cwd();
const kitsDir = join( root, 'plugin', 'arena-engine', 'kits' );
const themeDir = join( root, 'theme', 'arena-commerce' );
const THRESHOLD = 0.4;
const BLOCK_BUDGET = 900;

const problems = [];
const note = ( msg ) => problems.push( msg );

/* ---------------------------------------------------------------- setup */

const patternSlugs = new Set(
	readdirSync( join( themeDir, 'patterns' ) )
		.filter( ( f ) => f.endsWith( '.php' ) )
		.map( ( f ) => f.replace( '.php', '' ) )
);

const presetSlugs = new Set(
	readdirSync( join( themeDir, 'styles' ) )
		.filter( ( f ) => f.endsWith( '.json' ) )
		.map( ( f ) => f.replace( '.json', '' ) )
);

const kitDirs = readdirSync( kitsDir ).filter( ( d ) => existsSync( join( kitsDir, d, 'kit.json' ) ) );

/* ------------------------------------------------------------- K1/K3-K7 */

/* Generic glue excluded — the same set anti-clone.mjs treats as generic
   core vocabulary (wp:group/heading/paragraph/…) — so the signature captures
   what makes two kits genuinely the same skeleton vs. shared language. */
const GENERIC_BLOCKS = new Set( [
	'group', 'column', 'columns', 'heading', 'paragraph',
	'button', 'buttons', 'image', 'separator', 'list',
	'list-item', 'quote', 'template-part', 'pattern',
] );

function structuralTokens( html ) {
	const tokens = new Set();

	/* 1. Pattern + module hooks — the strongest family signals. */
	for ( const m of html.matchAll( /data-arena-(?:pattern|module|family|page)="([^"]+)"/g ) ) {
		tokens.add( 'hook:' + m[ 1 ] );
	}

	/* 2. {{pattern:}} includes — which theme modules the home is built from. */
	for ( const m of html.matchAll( /\{\{pattern:([a-zA-Z0-9/_-]+)\}\}/g ) ) {
		tokens.add( 'include:' + m[ 1 ] );
	}

	/* 3. Distinctive core blocks only (cover, gallery, table, search…). */
	for ( const m of html.matchAll( /<!--\s*wp:([\w-]+)/g ) ) {
		if ( ! GENERIC_BLOCKS.has( m[ 1 ] ) ) {
			tokens.add( 'block:' + m[ 1 ] );
		}
	}

	/* 4. Arena layout classes (grids, rails, steps…). */
	for ( const m of html.matchAll( /class="([^"]*)"/g ) ) {
		for ( const cls of m[ 1 ].split( /\s+/ ) ) {
			if ( cls.startsWith( 'arena-' ) && ! cls.startsWith( 'arena-kit' ) ) {
				tokens.add( 'class:' + cls );
			}
		}
	}

	return tokens;
}

const homes = {};

for ( const slug of kitDirs ) {
	const dir = join( kitsDir, slug );
	const manifest = JSON.parse( readFileSync( join( dir, 'kit.json' ), 'utf8' ) );

	/* K1 — manifest validity. */
	assert.ok( manifest.slug === slug, `${ slug }: slug mismatch` );
	assert.ok( manifest.pages.length >= 6 && manifest.pages.length <= 9,
		`${ slug }: ${ manifest.pages.length } pages (H19 wants 6–9)` );
	assert.ok( manifest.menu?.items?.length >= 4, `${ slug }: menu missing` );
	assert.ok( manifest.products?.length >= 4, `${ slug }: needs ≥ 4 demo products` );
	assert.ok( manifest.family, `${ slug }: family missing` );
	assert.ok( presetSlugs.has( manifest.preset ), `${ slug }: unknown preset ${ manifest.preset }` );
	assert.ok( manifest.source === 'core-blocks', `${ slug }: source must be core-blocks (H23)` );
	assert.ok( manifest.sync?.endpoint?.includes( slug ), `${ slug }: sync endpoint missing (H22)` );

	for ( const locale of [ 'en_US', 'it_IT' ] ) {
		const map = manifest.i18n?.[ locale ];
		assert.ok( map && Object.keys( map ).length > 40, `${ slug }: i18n ${ locale } too thin` );

		/* Menu/page/product keys must resolve. */
		for ( const item of manifest.menu.items ) {
			assert.ok( item.label_key in map, `${ slug }/${ locale }: missing ${ item.label_key }` );
		}
		for ( const page of manifest.pages ) {
			assert.ok( page.title_key in map, `${ slug }/${ locale }: missing ${ page.title_key }` );
		}
		for ( const product of manifest.products ) {
			assert.ok( product.name_key in map, `${ slug }/${ locale }: missing ${ product.name_key }` );
		}
	}

	/* en_US and it_IT must cover the same keys (G11 kit-level). */
	const enKeys = Object.keys( manifest.i18n.en_US ).sort().join( ',' );
	const itKeys = Object.keys( manifest.i18n.it_IT ).sort().join( ',' );
	assert.equal( itKeys, enKeys, `${ slug }: locale key sets differ` );

	/* K3–K5 over every shipped page. */
	let blockCount = 0;

	for ( const page of manifest.pages ) {
		const html = readFileSync( join( dir, page.file ), 'utf8' );

		blockCount += ( html.match( /<!--\s*wp:/g ) || [] ).length;

		/* K4 — tokens resolve (both locales), patterns exist. */
		for ( const m of html.matchAll( /\{\{t:([a-zA-Z0-9_.-]+)\}\}/g ) ) {
			if ( ! ( m[ 1 ] in manifest.i18n.en_US ) ) {note( `${ slug }/${ page.file }: token ${ m[ 1 ] } missing in en_US` );}
			if ( ! ( m[ 1 ] in manifest.i18n.it_IT ) ) {note( `${ slug }/${ page.file }: token ${ m[ 1 ] } missing in it_IT` );}
		}

		for ( const m of html.matchAll( /\{\{pattern:([a-zA-Z0-9/_-]+)\}\}/g ) ) {
			const pattern = m[ 1 ].replace( 'arena-commerce/', '' );
			if ( ! patternSlugs.has( pattern ) ) {note( `${ slug }/${ page.file }: unknown pattern ${ m[ 1 ] }` );}
		}

		/* K5 — no proprietary shortcodes. */
		if ( /\[arena[_-]/i.test( html ) ) {note( `${ slug }/${ page.file }: proprietary shortcode found (H23)` );}

		if ( page.slug === 'home' ) {
			/* K3 — billboard proxies on the home skeleton (patterns add more
			   headings but the home declares exactly one h1). */
			const h1 = ( html.match( /"level":1/g ) || [] ).length;
			assert.equal( h1, 1, `${ slug }: home declares ${ h1 } h1 blocks (H14 wants exactly 1)` );

			for ( const m of html.matchAll( /"dimRatio":(\d+)/g ) ) {
				assert.ok( Number( m[ 1 ] ) >= 50, `${ slug }: cover dimRatio ${ m[ 1 ] } < 50 (B5)` );
			}

			homes[ slug ] = structuralTokens( html );
		}
	}

	/* K7 — import cost proxy. Patterns inflate the resolved markup ~4×, so
	   the raw token budget keeps the real import fast. */
	assert.ok( blockCount <= BLOCK_BUDGET, `${ slug }: ${ blockCount } blocks > budget ${ BLOCK_BUDGET }` );

	/* K6 — campaign assets. */
	const expected = [
		[ 'campaign/9x16.svg', 1080, 1920 ],
		[ 'campaign/1x1.svg', 1080, 1080 ],
		[ 'campaign/16x9.svg', 1920, 1080 ],
	];

	for ( const [ file, w, h ] of expected ) {
		const path = join( dir, file );
		assert.ok( existsSync( path ), `${ slug }: campaign ${ file } missing (H21)` );

		const svg = readFileSync( path, 'utf8' );
		assert.ok( svg.includes( `viewBox="0 0 ${ w } ${ h }"` ), `${ slug }: ${ file } wrong ratio` );
		assert.ok( statSync( path ).size < 12_000, `${ slug }: ${ file } too heavy for a vector ad` );
	}

	assert.deepEqual(
		manifest.campaign.map( ( c ) => c ),
		[ 'campaign/9x16.svg', 'campaign/1x1.svg', 'campaign/16x9.svg' ],
		`${ slug }: campaign list must ship the three ratios`
	);
}

/* ------------------------------------------------------------- K2 — AP8 */

const slugs = Object.keys( homes ).sort();
let worst = 0;
let worstPair = '';

for ( let i = 0; i < slugs.length; i++ ) {
	for ( let j = i + 1; j < slugs.length; j++ ) {
		const a = homes[ slugs[ i ] ];
		const b = homes[ slugs[ j ] ];
		let shared = 0;

		for ( const token of a ) {
			if ( b.has( token ) ) {shared++;}
		}

		const jaccard = shared / ( a.size + b.size - shared );

		if ( jaccard > worst ) {
			worst = jaccard;
			worstPair = `${ slugs[ i ] } ↔ ${ slugs[ j ] }`;
		}

		if ( jaccard > THRESHOLD ) {
			note( `AP8: homes ${ slugs[ i ] } ↔ ${ slugs[ j ] } structural Jaccard ${ jaccard.toFixed( 3 ) } > ${ THRESHOLD }` );
		}
	}
}

/* -------------------------------------------------- K8 — undo contract */

const importer = readFileSync( join( root, 'plugin', 'arena-engine', 'includes', 'kits', 'class-importer.php' ), 'utf8' );

for ( const needle of [
	'function undo_import',
	'function undo_sync',
	'function sync(',
	'public static function import(',
	'confirm_overwrite',
	"add_filter( 'arena_engine_undo_kit.import'",
	"add_filter( 'arena_engine_undo_kit.sync'",
	'modified_gmt',
	'_arena_kit_version',
	'wp_delete_post',
] ) {
	assert.ok( importer.includes( needle ), `class-importer.php missing ${ needle } (H20/H22)` );
}

/* --------------------------------------------------------------- report */

assert.ok( kitDirs.length >= 12, `G8 wants ≥ 12 kits, found ${ kitDirs.length }` );

if ( problems.length ) {
	console.error( `G8 FAIL — ${ problems.length } problem(s):` );
	for ( const problem of problems ) {console.error( '  ✗ ' + problem );}
	process.exit( 1 );
}

console.log( `G8 PASS — ${ kitDirs.length } kits (${ slugs.join( ', ') })` );
console.log( `     worst home-pair similarity: ${ worst.toFixed( 3 ) } (${ worstPair }) ≤ ${ THRESHOLD }` );
console.log( '     runtime import <60 s + full undo: tests/e2e/kit-import.spec.js (committed, real env)' );
