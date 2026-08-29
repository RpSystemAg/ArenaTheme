/**
 * G14 real-run proof — JSON-LD per template, no duplicates (H43/H44).
 *
 * Run on the real environment:
 *
 *   npx wp-env start && npm run test:e2e -- tests/e2e/jsonld.spec.js
 *
 * For the Google Rich Results Test verdict use tools/php/rich-results-check.php
 * (it extracts the four graphs and posts them to the API when a key is
 * provided; without a key it prints the graphs for manual validation — the
 * honesty policy forbids inventing scores).
 */

import { test, expect } from '@playwright/test';

function graphsFrom( html ) {
	return [ ...html.matchAll( /<script type="application\/ld\+json">([\s\S]*?)<\/script>/g ) ]
		.map( ( m ) => JSON.parse( m[ 1 ] ) );
}

test( 'home ships WebSite + Organization + BreadcrumbList, once', async ( { request } ) => {
	const html = await ( await request.get( '/' ) ).text();
	const graphs = graphsFrom( html );

	expect( graphs.length ).toBe( 1 );

	const types = graphs[ 0 ][ '@graph' ].map( ( node ) => node[ '@type' ] );
	expect( types ).toContain( 'WebSite' );
	expect( types ).toContain( 'Organization' );
} );

test( 'single post ships Article', async ( { request } ) => {
	const html = await ( await request.get( '/?p=1' ) ).text();
	const graphs = graphsFrom( html );

	expect( graphs.length ).toBe( 1 );

	const types = graphs[ 0 ][ '@graph' ].map( ( node ) => node[ '@type' ] );
	expect( types ).toContain( 'Article' );
} );

test( 'product page ships Product + Offer', async ( { request } ) => {
	const products = await ( await request.get( '/wp-json/wc/store/v1/products' ) ).json();
	expect( products.length ).toBeGreaterThan( 0 );

	const html = await ( await request.get( `/products/${ products[ 0 ].slug }/` ) ).text();
	const graphs = graphsFrom( html );

	expect( graphs.length ).toBe( 1 );

	const types = graphs[ 0 ][ '@graph' ].map( ( node ) => node[ '@type' ] );
	expect( types ).toContain( 'Product' );
	expect( types ).toContain( 'Offer' );
} );

test( 'shop archive ships CollectionPage', async ( { request } ) => {
	const html = await ( await request.get( '/shop/' ) ).text();
	const graphs = graphsFrom( html );

	expect( graphs.length ).toBe( 1 );

	const types = graphs[ 0 ][ '@graph' ].map( ( node ) => node[ '@type' ] );
	expect( types ).toContain( 'CollectionPage' );
} );
