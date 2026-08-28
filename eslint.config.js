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
				IntersectionObserver: 'readonly',
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
	{
		ignores: [
			'dist/**',
			'node_modules/**',
			'vendor/**',
			'coverage/**',
		],
	},
];
