/**
 * Arena Prime H2/H3 — mobile bottom navigation and touch targets.
 *
 * At the mandatory 360px viewport the test asserts:
 *   - the bottom bar is rendered and visible,
 *   - it contains 4–5 items,
 *   - items are ≥64px high and ≥44px wide (thumb-reach),
 *   - the bar carries a safe-area bottom padding,
 *   - it hides on scroll-down and reappears on scroll-up,
 *   - it stays available when prefers-reduced-motion is emulated.
 */

const { test, expect } = require( '@playwright/test' );

test( 'bottom nav is present and thumb-reachable at 360px', async ( { page } ) => {
	const response = await page.goto( '/', { waitUntil: 'domcontentloaded' } );

	expect( response.status() ).toBe( 200 );

	const nav = page.locator( '#arena-bottom-nav' );
	await expect( nav ).toBeVisible();

	const items = nav.locator( '.arena-bottom-nav__link' );
	const count = await items.count();

	expect( count, 'H2 requires 4-5 items' ).toBeGreaterThanOrEqual( 4 );
	expect( count, 'H2 allows a maximum of 5 items' ).toBeLessThanOrEqual( 5 );

	const sizes = await items.evaluateAll( ( nodes ) => nodes.map( ( node ) => {
		const box = node.getBoundingClientRect();
		return { width: box.width, height: box.height };
	} ) );

	for ( const size of sizes ) {
		expect( size.height, 'H2 min-height 64px' ).toBeGreaterThanOrEqual( 64 );
		expect( size.width, 'H3 min-width 44px' ).toBeGreaterThanOrEqual( 44 );
	}

	const style = await nav.evaluate( ( node ) => {
		const css = getComputedStyle( node );
		return {
			position: css.position,
			paddingBottom: css.paddingBottom,
		};
	} );

	expect( style.position ).toBe( 'fixed' );
	expect( style.paddingBottom ).toContain( 'safe-area-inset-bottom' );
} );

test( 'bottom nav hides on scroll-down and returns on scroll-up', async ( { page } ) => {
	await page.goto( '/', { waitUntil: 'domcontentloaded' } );
	await page.evaluate( () => document.body.style.height = '6000px' );

	await page.mouse.wheel( 0, 800 );
	await page.waitForTimeout( 350 );

	await expect( page.locator( '#arena-bottom-nav' ) ).toHaveClass( /arena-bottom-nav--hidden/ );

	await page.mouse.wheel( 0, -400 );
	await page.waitForTimeout( 350 );

	await expect( page.locator( '#arena-bottom-nav' ) ).not.toHaveClass( /arena-bottom-nav--hidden/ );
} );

test( 'bottom nav remains available under prefers-reduced-motion', async ( { page } ) => {
	await page.emulateMedia( { reducedMotion: 'reduce' } );
	await page.goto( '/', { waitUntil: 'domcontentloaded' } );

	await expect( page.locator( '#arena-bottom-nav' ) ).toBeVisible();
	await expect( page.locator( '#arena-bottom-nav' ).first() ).toBeEnabled();
} );
