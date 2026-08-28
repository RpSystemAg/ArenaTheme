/**
 * Playwright configuration for the Arena Commerce front end.
 *
 * @type {import('@playwright/test').PlaywrightTestConfig}
 */
const { defineConfig, devices } = require( '@playwright/test' );

module.exports = defineConfig( {
	testDir: './tests/e2e',
	timeout: 60 * 1000,
	expect: {
		timeout: 10 * 1000,
	},
	fullyParallel: false,
	retries: process.env.CI ? 0 : 0,
	reporter: 'list',
	use: {
		baseURL: process.env.WP_ENV_URL || 'http://localhost:8888',
		viewport: {
			width: 360,
			height: 800,
		},
		trace: 'retain-on-failure',
		screenshot: 'only-on-failure',
	},
	projects: [
		{
			name: 'chromium',
			use: {
				...devices[ 'Desktop Chrome' ],
				viewport: {
					width: 360,
					height: 800,
				},
			},
		},
	],
} );
