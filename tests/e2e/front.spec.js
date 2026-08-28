/**
 * Front-page smoke test for Arena Commerce.
 *
 * Runs at the mobile-first viewport (360x800) used by the design rules, and
 * asserts the constraints that should never regress: the page renders, no
 * console errors are emitted, jQuery is not loaded, and no web font or
 * third-party font origin is contacted.
 */

const { test, expect } = require( '@playwright/test' );

test( 'front page is healthy at mobile-first viewport', async ( { page, browserName } ) => {
	const consoleErrors = [];
	const pageErrors = [];
	const fontRequests = [];

	page.on( 'console', ( message ) => {
		if ( message.type() === 'error' ) {
			consoleErrors.push( message.text() );
		}
	} );

	page.on( 'pageerror', ( error ) => {
		pageErrors.push( error.message );
	} );

	page.on( 'request', ( request ) => {
		const url = request.url();
		if ( /fonts\.googleapis\.com|fonts\.gstatic\.com|fonts\.shopify\.com|use\.typekit\.net/.test( url ) ) {
			fontRequests.push( url );
		}
	} );

	const response = await page.goto( '/', { waitUntil: 'domcontentloaded' } );

	expect( response, 'homepage should return an HTTP response' ).toBeTruthy();
	expect( response.status() ).toBe( 200 );

	const title = await page.title();
	expect( title.length ).toBeGreaterThan( 0 );

	await expect( page.locator( 'body' ) ).toBeVisible();
	await expect( page.locator( 'main' ).first() ).toBeVisible();

	// The stack is deliberately jQuery-free. The test must query the globals
	// directly, so eslint forbidding properties in production code is relaxed
	// for this file only.
	const hasJQuery = await page.evaluate( () => (
		typeof window.jQuery !== 'undefined' || typeof window.$ !== 'undefined'
	) );
	expect( hasJQuery, 'jQuery must never be loaded' ).toBe( false );

	// No web fonts, no third-party font origin.
	expect( fontRequests, 'web fonts must never be contacted' ).toEqual( [] );

	// Runtime errors must be zero.
	expect( pageErrors, 'no uncaught page errors expected' ).toEqual( [] );
	expect( consoleErrors, 'no console errors expected' ).toEqual( [] );

	// Keep the browser name in the output so failed matrix runs are easy to triage.
	test.info().annotations.push( {
		type: 'browser',
		description: browserName,
	} );
} );
