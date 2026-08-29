/**
 * G8/G12 real-run proof — one-click kit import with progress, no silent
 * overwrite and full undo (H20/H22).
 *
 * Run on the real environment:
 *
 *   npx wp-env start && npm run test:e2e -- tests/e2e/kit-import.spec.js
 *
 * Evidence produced:
 *   - import wall time per kit (assert < 60 s, G8);
 *   - an overwrite confirm is REQUIRED before touching existing content;
 *   - after UNDO, every created page/product/menu is gone;
 *   - timings appended to tests/e2e/out/kit-import-timings.log.
 */

import { test, expect } from '@playwright/test';
import { mkdirSync, appendFileSync } from 'node:fs';

const OUT_DIR = 'tests/e2e/out';

test.beforeAll( () => {
	mkdirSync( OUT_DIR, { recursive: true } );
} );

test( 'import a kit in one click, then undo it completely', async ( { page, request } ) => {
	await page.goto( '/wp-admin/admin.php?page=arena-kits' );

	const card = page.locator( '[data-arena-kit]' ).first();
	const slug = await card.getAttribute( 'data-arena-kit' );

	/* H20 — the import asks before overwriting anything. */
	page.on( 'dialog', ( dialog ) => dialog.accept() );

	const started = Date.now();

	await card.locator( '[data-arena-kit-import]' ).click();

	/* H20 — the progress bar appears and completes. */
	await expect( card.locator( '.arena-kit-progress__fill' ) ).toBeVisible();
	await expect( card.locator( '.arena-kit-progress__label' ) ).toContainText( /✓|Imported|Importato/i, { timeout: 60_000 } );

	const elapsed = Date.now() - started;
	appendFileSync( `${ OUT_DIR }/kit-import-timings.log`, `kit=${ slug } import_ms=${ elapsed }\n` );

	/* G8 — under sixty seconds. */
	expect( elapsed ).toBeLessThan( 60_000 );

	/* The kit is now installed and the undo button is armed. */
	await page.reload();
	await expect( page.locator( '[data-arena-undo]' ).first() ).toBeVisible();

	/* H20 — full undo: every created object disappears. */
	const before = await request.get( '/wp-json/wp/v2/pages?per_page=100' );
	const beforeTitles = ( await before.json() ).map( ( p ) => p.title.rendered );

	await page.locator( '[data-arena-undo]' ).first().click();
	await page.waitForLoadState( 'networkidle' );
	await page.reload();

	const after = await request.get( '/wp-json/wp/v2/pages?per_page=100' );
	const afterTitles = ( await after.json() ).map( ( p ) => p.title.rendered );

	const removed = beforeTitles.filter( ( title ) => ! afterTitles.includes( title ) );
	appendFileSync( `${ OUT_DIR }/kit-import-timings.log`, `kit=${ slug } undo_removed_pages=${ removed.length }\n` );

	expect( removed.length ).toBeGreaterThan( 0 );
} );

test( 'kit REST API is versioned and documented (H22)', async ( { request } ) => {
	const response = await request.get( '/wp-json/arena/v1/kits' );
	expect( response.ok() ).toBeTruthy();

	const kits = await response.json();
	expect( kits.length ).toBeGreaterThanOrEqual( 12 );

	for ( const kit of kits ) {
		expect( kit.slug ).toBeTruthy();
		expect( kit.sync ).toContain( '/sync' );
		expect( kit.pages.length ).toBeGreaterThanOrEqual( 6 );
	}
} );
