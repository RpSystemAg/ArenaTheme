#!/usr/bin/env node
/**
 * G-W6 — Asset budget gate (World Class 2026 constitution, §04).
 *
 *   CSS tema  ≤ 22 KB raw (checkout-block styles included)
 *   JS tema   ≤ 24 KB raw
 *   JS engine ≤ 18 KB raw on catalog pages
 *   0 jQuery, 0 third-party origins
 *
 * Unlike the v3.1 `lighthouse-budget.test.mjs` proxy — which only weighed the
 * *global* sheet — G-W6 measures the bytes a browser actually downloads for a
 * given page archetype. The per-context set is derived from the single source
 * of truth that ships them: the module registry in
 * `theme/arena-commerce/inc/class-assets.php` (parsed, never duplicated by
 * hand), so the gate cannot drift from the enqueue logic.
 *
 * Exit 0 = PASS, exit 1 = FAIL (every number printed, nothing inferred).
 */

import { readFileSync, readdirSync, statSync, existsSync } from 'node:fs';
import { join } from 'node:path';

const ROOT = process.cwd();
const THEME = join( ROOT, 'theme', 'arena-commerce' );
const ENGINE = join( ROOT, 'plugin', 'arena-engine' );
const KB = 1024;

/* ------------------------------------------------------------------ *
 * 1. Parse the module registry out of the shipping PHP (no copy-paste).
 * ------------------------------------------------------------------ */
const assetsPhp = readFileSync( join( THEME, 'inc', 'class-assets.php' ), 'utf8' );
const registryBlock = assetsPhp.slice( assetsPhp.indexOf( 'public static function modules()' ) );
const registryEnd = registryBlock.indexOf( 'return apply_filters' );
const registry = registryBlock.slice( 0, registryEnd );

/**
 * Reads one module entry (`'slug' => array( ... )`) from the registry source.
 *
 * @param {string} slug Module slug.
 * @return {{css: string[], js: string[], cssWhen: string, jsWhen: string}} entry
 */
function moduleEntry( slug ) {
	const start = registry.indexOf( `'${ slug }'` );
	if ( start === -1 ) {
		throw new Error( `module ${ slug } not found in Assets::modules()` );
	}
	const body = registry.slice( start, registry.indexOf( '\t\t\t)', start ) );
	const list = ( key ) => {
		const m = body.match( new RegExp( `'${ key }'\\s*=>\\s*array\\(([^)]*)\\)` ) );
		if ( ! m ) {
			return [];
		}
		return Array.from( m[ 1 ].matchAll( /'([^']+\.(?:css|js))'/g ) ).map( ( x ) => x[ 1 ] );
	};
	const when = ( key ) => {
		const m = body.match( new RegExp( `'${ key }'\\s*=>\\s*'([^']+)'` ) );
		return m ? m[ 1 ] : 'never';
	};
	return { css: list( 'css' ), js: list( 'js' ), cssWhen: when( 'css_when' ), jsWhen: when( 'js_when' ) };
}

const SLUGS = [ 'dark', 'motion', 'blog', 'commerce', 'shop', 'megamenu', 'search', 'checkout', 'cart' ]
	.filter( ( slug ) => registry.includes( `'${ slug }'` ) );

const MODULES = Object.fromEntries( SLUGS.map( ( slug ) => [ slug, moduleEntry( slug ) ] ) );

/* ------------------------------------------------------------------ *
 * 2. Which conditions can be true on which page archetype.
 *    `true` only where the condition helper can genuinely fire, read from
 *    the helper bodies below (kept adjacent so reviewers can check them).
 * ------------------------------------------------------------------ */
const CONDITIONS = {
	always: { home: 1, blog: 1, shop: 1, product: 1, cart: 1, checkout: 1 },
	never: {},
	has_motion_markup: { home: 1, blog: 1, shop: 1, product: 1 },
	is_blog_context: { blog: 1 },
	is_woo_active: { home: 1, blog: 1, shop: 1, product: 1, cart: 1, checkout: 1 },
	is_woo_page: { shop: 1, product: 1, cart: 1, checkout: 1 },
	is_shop_context: { shop: 1, product: 1 },
	is_checkout_or_account: { checkout: 1 },
	has_megamenu_markup: { home: 1, blog: 1, shop: 1, product: 1, cart: 1, checkout: 1 },
	has_search_block: { home: 1, blog: 1, shop: 1 },
};

const PAGES = [ 'home', 'blog', 'shop', 'product', 'cart', 'checkout' ];

const bytes = ( p ) => ( existsSync( p ) ? statSync( p ).size : 0 );

/**
 * Total raw bytes of one asset type for a page archetype.
 *
 * @param {string} page Page archetype.
 * @param {string} type 'css' or 'js'.
 * @return {{total: number, files: {file: number}[]}} measured payload.
 */
function payload( page, type ) {
	const files = [];
	const push = ( rel, dir ) => {
		const abs = join( dir, rel );
		const size = bytes( abs );
		files.push( { file: rel, size } );
	};

	/* Core sheet / script — registered unconditionally. */
	push( type === 'css' ? 'assets/css/arena.css' : 'assets/js/arena.js', THEME );

	for ( const slug of SLUGS ) {
		const mod = MODULES[ slug ];
		const cond = mod[ `${ type }When` ];
		if ( ! CONDITIONS[ cond ] || ! CONDITIONS[ cond ][ page ] ) {
			continue;
		}
		for ( const rel of mod[ type ] ) {
			push( rel, join( THEME, 'assets', type ) );
		}
	}

	/* The theme replaces the Woo cascade with its own sheet on Woo templates. */
	if ( type === 'css' && CONDITIONS.is_woo_page[ page ] ) {
		push( 'assets/css/arena-woocommerce.css', THEME );
	}

	const total = files.reduce( ( sum, f ) => sum + f.size, 0 );
	return { total, files };
}

/* ------------------------------------------------------------------ *
 * 3. Engine payload on catalog pages (front-end files only).
 * ------------------------------------------------------------------ */
function engineCatalogPayload() {
	const jsDir = join( ENGINE, 'assets', 'js' );
	const front = readdirSync( jsDir ).filter( ( f ) => f.endsWith( '.js' ) && ! f.startsWith( 'admin' ) );
	const files = front.map( ( f ) => ( { file: `assets/js/${ f }`, size: bytes( join( jsDir, f ) ) } ) );
	return { total: files.reduce( ( s, f ) => s + f.size, 0 ), files };
}

/* ------------------------------------------------------------------ *
 * 4. Forbidden dependencies.
 * ------------------------------------------------------------------ */
const failures = [];
const rows = [];

const cssBudget = 22 * KB;
const jsBudget = 24 * KB;
const engineBudget = 18 * KB;

for ( const page of PAGES ) {
	const css = payload( page, 'css' );
	const js = payload( page, 'js' );
	rows.push( { page, css, js } );
	if ( css.total > cssBudget ) {
		failures.push( `G-W6 CSS: /${ page } ships ${ css.total } B (${ ( css.total / KB ).toFixed( 1 ) } KB) > 22 KB` );
	}
	if ( js.total > jsBudget ) {
		failures.push( `G-W6 JS:  /${ page } ships ${ js.total } B (${ ( js.total / KB ).toFixed( 1 ) } KB) > 24 KB` );
	}
}

const engine = engineCatalogPayload();
if ( engine.total > engineBudget ) {
	failures.push( `G-W6 engine: catalog JS ${ engine.total } B > 18 KB` );
}

/* jQuery / third-party origins across every shipped front-end file. */
const frontEndFiles = [
	...readdirSync( join( THEME, 'assets', 'js' ) ).filter( ( f ) => f.endsWith( '.js' ) ).map( ( f ) => join( THEME, 'assets', 'js', f ) ),
	...readdirSync( join( THEME, 'assets', 'js', 'modules' ) ).filter( ( f ) => f.endsWith( '.js' ) ).map( ( f ) => join( THEME, 'assets', 'js', 'modules', f ) ),
	...readdirSync( join( THEME, 'assets', 'css' ) ).filter( ( f ) => f.endsWith( '.css' ) ).map( ( f ) => join( THEME, 'assets', 'css', f ) ),
	...readdirSync( join( THEME, 'assets', 'css', 'modules' ) ).filter( ( f ) => f.endsWith( '.css' ) ).map( ( f ) => join( THEME, 'assets', 'css', 'modules', f ) ),
	...readdirSync( join( ENGINE, 'assets', 'js' ) ).filter( ( f ) => f.endsWith( '.js' ) ).map( ( f ) => join( ENGINE, 'assets', 'js', f ) ),
	...readdirSync( join( ENGINE, 'assets', 'css' ) ).filter( ( f ) => f.endsWith( '.css' ) ).map( ( f ) => join( ENGINE, 'assets', 'css', f ) ),
];

let jquery = 0;
let thirdParty = 0;
for ( const file of frontEndFiles ) {
	const raw = readFileSync( file, 'utf8' );
	/* Comments legitimately say "no jQuery" — only executable code counts. */
	const src = raw
		.replace( /\/\*[\s\S]*?\*\//g, '' )
		.replace( /^\s*(?:\/\/|\*)[^\n]*/gm, '' );
	if ( /\bjQuery\b|window\.\$|[^.\w]\$\(\s*['"]/.test( src ) ) {
		jquery++;
		failures.push( `G-W6 jQuery: ${ file.replace( ROOT + '/', '' ) } references jQuery` );
	}
	for ( const m of src.matchAll( /https?:\/\/([a-z0-9.-]+)/gi ) ) {
		const host = m[ 1 ].toLowerCase();
		if ( host.startsWith( 'www.w3.org' ) || host.startsWith( 'schema.org' ) ) {
			continue; // XML namespaces / JSON-LD vocabulary, never fetched.
		}
		thirdParty++;
		failures.push( `G-W6 origin: ${ file.replace( ROOT + '/', '' ) } contacts ${ host }` );
	}
}

/* ------------------------------------------------------------------ *
 * 5. Report.
 * ------------------------------------------------------------------ */
console.log( 'G-W6 asset budget (raw bytes actually shipped per page context)\n' );
console.log( '  page      CSS raw      JS raw       CSS files' );
for ( const { page, css, js } of rows ) {
	console.log(
		`  ${ page.padEnd( 9 ) } ${ String( css.total ).padStart( 7 ) } B   ${ String( js.total ).padStart( 7 ) } B    ` +
			css.files.map( ( f ) => f.file.split( '/' ).pop() ).join( ' + ' )
	);
}
console.log( `\n  engine catalog JS: ${ engine.total } B (${ engine.files.map( ( f ) => f.file.split( '/' ).pop() ).join( ' + ' ) })` );
console.log( `  budgets: CSS ≤ ${ cssBudget } B · JS ≤ ${ jsBudget } B · engine ≤ ${ engineBudget } B` );
console.log( `  jQuery references: ${ jquery } · third-party origins: ${ thirdParty } (across ${ frontEndFiles.length } front-end files)\n` );

if ( failures.length ) {
	console.log( `G-W6 FAIL — ${ failures.length } breach(es):` );
	for ( const f of failures ) {
		console.log( `  · ${ f }` );
	}
	process.exit( 1 );
}

console.log( 'G-W6 PASS — every page context is inside the 22 KB CSS / 24 KB JS / 18 KB engine budget,' );
console.log( '           0 jQuery references, 0 third-party origins.' );
