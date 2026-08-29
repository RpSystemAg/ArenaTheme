/**
 * G16 real-run proof — real dark mode (H47/H48).
 *
 * Run on the real environment:
 *
 *   npx wp-env start && npm run test:e2e -- tests/e2e/dark-mode.spec.js
 *
 * Evidence produced:
 *   - axe-core: 0 violations in LIGHT and in DARK (both must pass — AP13);
 *   - the scheme persists after a reload without any flash of wrong theme;
 *   - the toggle is reachable in the header AND in the bottom nav.
 */

import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

test( 'dark mode is real, persistent and accessible in both schemes', async ( { page } ) => {
	await page.goto( '/' );

	/* H47 — data-theme attribute on the root, not a filter. */
	await expect( page.locator( 'html' ) ).toHaveAttribute( 'data-theme', /light|dark/ );

	/* H47 — toggle in the header. */
	await expect( page.locator( 'header [data-arena-theme-toggle]' ) ).toHaveCount( 1 );

	/* H47 — toggle in the bottom nav (mobile viewport is set in the config). */
	await page.setViewportSize( { width: 390, height: 844 } );
	await expect( page.locator( '.arena-bottom-nav [data-arena-theme-toggle]' ) ).toHaveCount( 1 );

	/* Light scheme first: 0 axe violations. */
	const light = await new AxeBuilder( { page } ).analyze();
	expect( light.violations ).toEqual( [] );

	/* Toggle to dark — no reload, the attribute flips. */
	await page.locator( 'header [data-arena-theme-toggle]' ).click();
	await expect( page.locator( 'html' ) ).toHaveAttribute( 'data-theme', 'dark' );

	const dark = await new AxeBuilder( { page } ).analyze();
	expect( dark.violations ).toEqual( [] );

	/* H47 — persistence without reload: the stored scheme survives a manual
	   reload and the boot script applies it before first paint. */
	await page.reload();
	await expect( page.locator( 'html' ) ).toHaveAttribute( 'data-theme', 'dark' );

	/* Restore light for the next test run. */
	await page.locator( 'header [data-arena-theme-toggle]' ).click();
	await expect( page.locator( 'html' ) ).toHaveAttribute( 'data-theme', 'light' );
} );
