/**
 * Flat ESLint config for Arena Commerce.
 *
 * The WordPress and WooCommerce plugins ship flat configs in ESLint v9+/v10.
 * This file keeps the project on the current config format while the
 * `.eslintrc.json` file remains as the legacy reference for tooling that
 * still reads `.eslintrc.*`.
 */
const woocommerce = require( '@woocommerce/eslint-plugin' );

module.exports = [
	...woocommerce.configs.recommended,
	{
		files: [ '**/*.js', '**/*.jsx', '**/*.mjs', '**/*.cjs' ],
		languageOptions: {
			globals: {
				wp: 'readonly',
				/* Browser globals the hand-written runtime scripts use. Every
				   one of them is feature-detected before use. */
				IntersectionObserver: 'readonly',
				MutationObserver: 'readonly',
				ResizeObserver: 'readonly',
				DOMParser: 'readonly',
				NodeFilter: 'readonly',
				requestAnimationFrame: 'readonly',
				cancelAnimationFrame: 'readonly',
				getComputedStyle: 'readonly',
				matchMedia: 'readonly',
				localStorage: 'readonly',
				sessionStorage: 'readonly',
				fetch: 'readonly',
				URL: 'readonly',
				URLSearchParams: 'readonly',
				AbortController: 'readonly',
				customElements: 'readonly',
				history: 'readonly',
				navigator: 'readonly',
				performance: 'readonly',
				PerformanceObserver: 'readonly',
				FormData: 'readonly',
				HTMLFormElement: 'readonly',
			},
		},
		rules: {
			// Arena deliberately keeps ES5-style runtime scripts (no build
			// step, no transpiler) and does not adopt a Prettier formatter.
			'prettier/prettier': 'off',
			'no-var': 'off',
			// CommonJS config files and Node-powered Playwright tests.
			'@typescript-eslint/no-require-imports': 'off',
			// `useBlockProps()` from the block editor is not a React Hook.
			'react-hooks/rules-of-hooks': 'off',
			// The focus dialog needs `document.activeElement`, which is the
			// documented WordPress pattern for WCAG 2.4.3 focus management.
			'@wordpress/no-global-active-element': 'off',
			'no-restricted-globals': [
				'error',
				{ name: '$', message: 'jQuery is forbidden in Arena Commerce.' },
				{ name: 'jQuery', message: 'jQuery is forbidden in Arena Commerce.' },
			],
			'no-restricted-properties': [
				'error',
				{ object: 'window', property: 'jQuery', message: 'jQuery is forbidden in Arena Commerce.' },
				{ object: 'document', property: 'jQuery', message: 'jQuery is forbidden in Arena Commerce.' },
			],
			'@wordpress/i18n-text-domain': [
				'error',
				{
					allowedTextDomain: [ 'arena-commerce', 'arena-engine' ],
				},
			],
			/* Function declarations are hoisted by the language; the hazard this
			   rule exists for (using a `const` before its initialiser) stays on. */
			'@typescript-eslint/no-use-before-define': [
				'error',
				{ functions: false, classes: true, variables: true, typedefs: true, enums: true },
			],
			'@typescript-eslint/no-unused-vars': [
				'error',
				{
					varsIgnorePattern: '^_',
					argsIgnorePattern: '^_',
				},
			],
		},
	},
	{
		files: [ 'tests/e2e/**/*.js' ],
		rules: {
			// The smoke test intentionally probes for `window.jQuery` and
			// `window.$`; it must be allowed to reference the forbidden globals.
			'no-restricted-properties': 'off',
			'yoda': 'off',
		},
	},
	/*
	 * `tools/**` and the `tests/*.mjs` gates are Node CLIs: `console` IS their
	 * output channel and CRC32 needs bitwise operators. Scoped here, never
	 * globally, so shipped front-end code keeps the strict rules.
	 */
	{
		files: [ 'tools/**/*.mjs', 'tests/**/*.mjs' ],
		languageOptions: {
			globals: {
				process: 'readonly',
				console: 'readonly',
				Buffer: 'readonly',
				__dirname: 'readonly',
				__filename: 'readonly',
				module: 'readonly',
				require: 'readonly',
				exports: 'writable',
				setTimeout: 'readonly',
				URL: 'readonly',
			},
		},
		rules: {
			'no-console': 'off',
			'jsdoc/require-jsdoc': 'off',
			'@typescript-eslint/no-require-imports': 'off',
		},
	},
	{
		files: [ 'tools/zip-utils.mjs', 'tests/anti-clone.mjs', 'tests/anti-clone-global.mjs' ],
		rules: {
			/* CRC32 (zip) and the FNV-1a hash in the anti-clone auditors are
			   defined with shifts and XOR. */
			'no-bitwise': 'off',
		},
	},
	{
		/* The kit importer and panel confirm destructive actions the way
		   WordPress core does; `alert` stays banned everywhere else. */
		files: [ 'plugin/arena-engine/assets/js/admin-arena.js' ],
		rules: {
			'no-alert': 'off',
		},
	},
	{
		/* `motif()` derives cx/cy/r once and returns one SVG branch per family;
		   hoisting the three constants above the switch is the point. */
		files: [ 'tools/kits/campaign.mjs', 'tools/stamp-pattern-signatures.mjs' ],
		rules: {
			'@wordpress/no-unused-vars-before-return': 'off',
		},
	},
	{
		ignores: [
			'dist/**',
			'node_modules/**',
			'vendor/**',
			'coverage/**',
		],
	},
];
