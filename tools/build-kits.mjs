#!/usr/bin/env node
/**
 * Arena Prime v3.1 — starter kit builder (H19–H23).
 *
 * Generates the 12 installable kits under plugin/arena-engine/kits/:
 *
 *   kits/<slug>/kit.json          manifest (validated by Kit_Repository::validate)
 *   kits/<slug>/pages/*.html      core-block markup with {{t:}} and {{pattern:}} tokens
 *   kits/<slug>/campaign/*.svg    H21 campaign assets in three ratios
 *
 * Deterministic: same specs → same output (committed, not generated in CI).
 *
 * Usage: node tools/build-kits.mjs
 */

import { mkdirSync, writeFileSync, readdirSync, rmSync } from 'node:fs';
import { join, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';
import { KITS, PAGE_TYPES } from './kits/specs.mjs';
import { buildHome, buildPage } from './kits/builders.mjs';
import { makeCopy } from './kits/copy.mjs';
import { campaignSvg, palette, RATIOS } from './kits/campaign.mjs';

const root = join( dirname( fileURLToPath( import.meta.url ) ), '..' );
const themeDir = join( root, 'theme', 'arena-commerce' );
const outDir = join( root, 'plugin', 'arena-engine', 'kits' );

/* Guard: every pattern referenced by a kit must exist in the theme. */
const patternSlugs = new Set(
	readdirSync( join( themeDir, 'patterns' ) )
		.filter( ( f ) => f.endsWith( '.php' ) )
		.map( ( f ) => f.replace( '.php', '' ) )
);

let failures = 0;

rmSync( outDir, { recursive: true, force: true } );
mkdirSync( outDir, { recursive: true } );

for ( const kit of KITS ) {
	const kitDir = join( outDir, kit.slug );

	mkdirSync( join( kitDir, 'pages' ), { recursive: true } );
	mkdirSync( join( kitDir, 'campaign' ), { recursive: true } );

	/* ------------------------------------------------------ pages */
	const pageFiles = [];

	for ( const pattern of kit.home.patterns ) {
		if ( ! patternSlugs.has( pattern ) ) {
			console.error( `✗ ${ kit.slug }: unknown pattern "${ pattern }"` );
			failures++;
		}
	}

	writeFileSync( join( kitDir, 'pages', 'home.html' ), buildHome( kit ) + '\n' );
	pageFiles.push( { slug: 'home', file: 'pages/home.html', title_key: 'kit.name', template: '', is_front: true } );

	for ( const pageKey of kit.pages ) {
		const spec = PAGE_TYPES[ pageKey ];

		if ( ! spec ) {
			console.error( `✗ ${ kit.slug }: no PAGE_TYPES entry for "${ pageKey }"` );
			failures++;
			continue;
		}

		writeFileSync( join( kitDir, 'pages', `${ pageKey }.html` ), buildPage( pageKey, spec ).replaceAll( '{{t:page.', `{{t:${ pageKey }.` ) + '\n' );
		pageFiles.push( { slug: pageKey, file: `pages/${ pageKey }.html`, title_key: `${ pageKey }.title`, template: '', is_front: false } );
	}

	/* ------------------------------------------------------- copy */
	const copy = makeCopy( kit, PAGE_TYPES );

	/* --------------------------------------------------- campaign */
	const pal = palette( kit.preset, themeDir );

	for ( const ratio of RATIOS ) {
		writeFileSync( join( kitDir, 'campaign', `${ ratio.key }.svg` ), campaignSvg( kit, pal, ratio, 'en_US' ) );
	}

	/* --------------------------------------------------- manifest */
	const manifest = {
		$schema: 'https://arena.example/schemas/kit.json',
		slug: kit.slug,
		name: kit.name.en_US,
		version: '1.0.0',
		description: kit.description,
		family: kit.family,
		preset: kit.preset,
		headerVariant: kit.headerVariant,
		campaign: [ 'campaign/9x16.svg', 'campaign/1x1.svg', 'campaign/16x9.svg' ],
		menu: {
			location: 'primary',
			items: [
				{ label_key: 'menu.home', href: '/' },
				{ label_key: 'menu.about', href: `/${ kit.pages[ 0 ] }/` },
				{ label_key: 'menu.collection', href: `/${ kit.pages[ 4 ] }/` },
				{ label_key: 'menu.journal', href: `/${ kit.pages[ 5 ] }/` },
				{ label_key: 'menu.contact', href: `/${ kit.pages[ 1 ] }/` },
			],
		},
		pages: pageFiles,
		products: kit.products.map( ( product, index ) => ( {
			name_key: `product.${ index }.name`,
			price: product.price,
			category_key: `product.${ index }.category`,
			description_key: `product.${ index }.description`,
		} ) ),
		i18n: {
			en_US: copy.en_US,
			it_IT: copy.it_IT,
		},
		sync: {
			endpoint: `arena/v1/kits/${ kit.slug }/sync`,
			strategy: 'content-only',
		},
		source: 'core-blocks',
	};

	writeFileSync( join( kitDir, 'kit.json' ), JSON.stringify( manifest, null, '\t' ) + '\n' );

	/* ------------------------------------------- token completeness */
	for ( const locale of [ 'en_US', 'it_IT' ] ) {
		const html = [ buildHome( kit ), ...kit.pages.map( ( p ) => buildPage( p, PAGE_TYPES[ p ] ).replaceAll( '{{t:page.', `{{t:${ p }.` ) ) ].join( '\n' );
		const tokens = [ ...html.matchAll( /\{\{t:([a-zA-Z0-9_.-]+)\}\}/g ) ].map( ( m ) => m[ 1 ] );
		const missing = [ ...new Set( tokens ) ].filter( ( token ) => ! ( token in copy[ locale ] ) );

		if ( missing.length ) {
			console.error( `✗ ${ kit.slug } (${ locale }): missing tokens ${ missing.join( ', ' ) }` );
			failures++;
		}
	}

	console.log( `✓ ${ kit.slug } — ${ pageFiles.length } pages, ${ kit.products.length } products, preset ${ kit.preset }` );
}

if ( failures ) {
	console.error( `\n${ failures } failure(s).` );
	process.exit( 1 );
}

console.log( `\n${ KITS.length } kits generated in ${ outDir }` );
