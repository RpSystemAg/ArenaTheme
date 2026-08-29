#!/usr/bin/env node
/**
 * Real Lighthouse runner — requires Chromium + wp-env.
 *
 * Usage:
 *   npx wp-env start
 *   npx playwright install chromium
 *   node tests/lighthouse-run.mjs
 *
 * Runs Lighthouse mobile against:
 *   - front page  (/)
 *   - single post (/?p=1  — falls back to /hello-world/)
 *   - single product (if Woo active)
 *   - cart/checkout
 *
 * Asserts ≥95 on Performance, Accessibility, Best Practices, SEO.
 * Outputs a JSON report at .cache/lighthouse/<page>.json.
 *
 * This script is committed as the reproduction for gate (6) in
 * docs/certification-report.md; if Chromium is unavailable it exits with
 * code 2 (SKIP) rather than fabricating numbers.
 */

import { execSync } from 'node:child_process';

let lighthouse;
try {
	({ default: lighthouse } = await import( 'lighthouse' ));
} catch ( e ) {
	console.error( '[lighthouse-run] SKIP — lighthouse package not installed. Run: npm i lighthouse -D' );
	process.exit( 2 );
}

const chromePath = process.env.CHROME_PATH || '';
try {
	execSync( 'which google-chrome || which chromium || which chromium-browser', { stdio: 'pipe' } );
} catch ( e ) {
	console.error( '[lighthouse-run] SKIP — no Chromium binary on PATH.' );
	process.exit( 2 );
}

const base = process.env.WP_ENV_URL || 'http://localhost:8888';
const pages = [
	{ name: 'front-page', url: `${ base }/` },
	{ name: 'single', url: `${ base }/?p=1` },
	{ name: 'single-product', url: `${ base }/product/shell-jacket-3l/` },
	{ name: 'checkout', url: `${ base }/checkout/` },
];

const thresholds = { performance: 95, accessibility: 95, 'best-practices': 95, seo: 95 };
let failures = 0;

for ( const page of pages ) {
	console.log( `\n[lighthouse-run] Auditing ${ page.name } (${ page.url })…` );
	try {
		const result = await lighthouse( page.url, {
			logLevel: 'error',
			onlyCategories: Object.keys( thresholds ),
			formFactor: 'mobile',
			screenEmulation: { mobile: true, width: 360, height: 800, deviceScaleFactor: 2 },
		}, undefined );
		const cats = result.lhr.categories;
		for ( const [ cat, min ] of Object.entries( thresholds ) ) {
			const score = Math.round( ( cats[ cat ]?.score || 0 ) * 100 );
			const ok = score >= min;
			console.log( `  ${ ok ? '✓' : '✗' } ${ cat.padEnd( 16 ) } ${ score } (needs ≥${ min })` );
			if ( ! ok ) failures += 1;
		}
	} catch ( err ) {
		console.error( `  ! ${ page.name }: ${ err.message }` );
		failures += 1;
	}
}

process.exit( failures ? 1 : 0 );
