/**
 * G15 real-run proof — decoupled assets (H45).
 *
 * Run on the real environment:
 *
 *   npx wp-env start && npm run test:e2e -- tests/e2e/assets-decoupled.spec.js
 *
 * Evidence produced (committed network log):
 *   - on a BLOG page, ZERO WooCommerce CSS/JS bytes ship (G15);
 *   - on the HOME page, the always-loaded global CSS stays under 60% of the
 *     total CSS bytes for that page load;
 *   - WooCommerce assets load only on WooCommerce templates.
 */

import { test, expect } from '@playwright/test';

test( 'blog page ships zero WooCommerce bytes', async ( { page } ) => {
	const wooUrls = [];

	page.on( 'response', ( response ) => {
		const url = response.url();
		if ( /woocommerce|woo|wc-|cart|checkout/i.test( url ) && /\.(css|js)/.test( url ) ) {
			wooUrls.push( url );
		}
	} );

	await page.goto( '/?p=1' ); /* First post (hello-world in a fresh env). */
	await page.waitForLoadState( 'networkidle' );

	expect( wooUrls ).toEqual( [] );
} );

test( 'home page: global CSS stays under 60% of total CSS', async ( { page } ) => {
	const css = [];

	page.on( 'response', async ( response ) => {
		const url = response.url();
		if ( url.endsWith( '.css' ) || url.includes( '.css?' ) ) {
			css.push( { url, size: ( await response.body() ).length } );
		}
	} );

	await page.goto( '/' );
	await page.waitForLoadState( 'networkidle' );

	expect( css.length ).toBeGreaterThan( 0 );

	const global = css
		.filter( ( sheet ) => /arena\.css/.test( sheet.url ) )
		.reduce( ( sum, sheet ) => sum + sheet.size, 0 );
	const total = css.reduce( ( sum, sheet ) => sum + sheet.size, 0 );

	expect( total ).toBeGreaterThan( 0 );
	expect( global / total ).toBeLessThan( 0.6 );
} );

test( 'shop page loads the WooCommerce module sheet', async ( { page } ) => {
	const wooCss = [];

	page.on( 'response', ( response ) => {
		if ( /arena-woocommerce|woocommerce/.test( response.url() ) && response.url().endsWith( '.css' ) ) {
			wooCss.push( response.url() );
		}
	} );

	await page.goto( '/shop/' );
	await page.waitForLoadState( 'networkidle' );

	expect( wooCss.length ).toBeGreaterThan( 0 );
} );
