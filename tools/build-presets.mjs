#!/usr/bin/env node
/**
 * Arena Prime v3.1 — H40 global presets builder.
 *
 * Generates the 8 one-click style variations in theme/arena-commerce/styles/:
 *   default, midnight, editorial, commerce, magazine, minimal, brutal, soft.
 *
 * Every preset = palette + typographic pairing + radius scale + density
 * (spacing scale), expressed as a theme.json variation so it applies in one
 * click from the Site Editor Styles panel AND from the Arena admin panel
 * (which mirrors the choice through the tracked `arena_active_preset`
 * variation). Fonts stay system stacks: the default ships zero web fonts
 * (H26) and no preset adds one.
 *
 * The dark twin of every palette lives in
 * assets/css/modules/arena-dark.css (H47); tests/g13-presets.test.mjs and
 * tests/g16-dark-a11y.test.mjs verify the pairs for both this output and the
 * dark CSS, so no preset can ship a non-AA scheme.
 *
 * Run: node tools/build-presets.mjs
 */

import { writeFileSync, mkdirSync } from 'node:fs';
import { join } from 'node:path';

const ROOT = process.cwd();
const OUT = join( ROOT, 'theme', 'arena-commerce', 'styles' );
mkdirSync( OUT, { recursive: true } );

const SANS = '-apple-system, BlinkMacSystemFont, "Segoe UI Variable Text", "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif';
const SERIF = 'ui-serif, Georgia, Cambria, "Times New Roman", serif';
const MONO = 'ui-monospace, SFMono-Regular, "SF Mono", Menlo, Consolas, "Liberation Mono", monospace';

/** Base spacing scale (theme.json slugs 10–110). */
const BASE_SPACING = [
	[ '4xs', '0.125rem', '10' ],
	[ '3xs', '0.25rem', '20' ],
	[ '2xs', '0.5rem', '30' ],
	[ 'xs', '0.75rem', '40' ],
	[ 'sm', '1rem', '50' ],
	[ 'md', '1.5rem', '60' ],
	[ 'lg', '2rem', '70' ],
	[ 'xl', '3rem', '80' ],
	[ '2xl', '4rem', '90' ],
	[ '3xl', '6rem', '100' ],
	[ '4xl', '8rem', '110' ],
];

function spacing( factor ) {
	return BASE_SPACING.map( ( [ name, size, slug ] ) => ( {
		name,
		slug,
		size: `${ ( parseFloat( size ) * factor ).toFixed( 3 ).replace( /\.?0+$/, '' ) }rem`,
	} ) );
}

function radius( sm, md, lg, xl ) {
	return {
		none: '0',
		sm: `${ sm }px`,
		md: `${ md }px`,
		lg: `${ lg }px`,
		xl: `${ xl }px`,
		full: '999px',
	};
}

/** Palette rows: [slug, name, light]. Dark twins live in arena-dark.css. */
const PRESETS = {
	default: {
		title: 'Default',
		palette: {
			base: '#FFFFFF', surface: '#F4F5F7', 'surface-alt': '#E7EAEE',
			foreground: '#0B1017', muted: '#55606F', primary: '#0B4F6C',
			'primary-hover': '#08394F', accent: '#B45309', 'accent-soft': '#FEF3E2',
			success: '#15803D', warning: '#A16207', danger: '#B91C1C',
			'border-strong': '#6B7480', outline: '#C6CBD3',
		},
		names: { base: 'Canvas', surface: 'Surface', 'surface-alt': 'Surface strong', foreground: 'Ink', muted: 'Ink muted', primary: 'Primary', 'primary-hover': 'Primary hover', accent: 'Accent', 'accent-soft': 'Accent soft', success: 'Success', warning: 'Warning', danger: 'Danger', 'border-strong': 'Border strong', outline: 'Border' },
		headings: SANS, body: SANS, headingWeight: '800',
		radius: radius( 6, 12, 16, 24 ),
		density: 1,
		description: 'The Arena baseline: calm ink-and-teal commerce palette, system sans, medium radius.',
	},
	commerce: {
		title: 'Commerce',
		palette: {
			base: '#FFFFFF', surface: '#F5F7FA', 'surface-alt': '#E8EDF4',
			foreground: '#101828', muted: '#475467', primary: '#004EEB',
			'primary-hover': '#0040B8', accent: '#B42318', 'accent-soft': '#FEE4E2',
			success: '#067647', warning: '#B54708', danger: '#B42318',
			'border-strong': '#667085', outline: '#D0D5DD',
		},
		names: { base: 'Canvas', surface: 'Surface', 'surface-alt': 'Surface strong', foreground: 'Ink', muted: 'Ink muted', primary: 'Primary', 'primary-hover': 'Primary hover', accent: 'Accent', 'accent-soft': 'Accent soft', success: 'Success', warning: 'Warning', danger: 'Danger', 'border-strong': 'Border strong', outline: 'Border' },
		headings: SANS, body: SANS, headingWeight: '700',
		radius: radius( 4, 8, 12, 16 ),
		density: 0.85,
		description: 'High-contrast corporate blue for high-volume catalogues: compact density, small radius.',
	},
	editorial: {
		title: 'Editorial',
		palette: {
			base: '#FDFAF5', surface: '#F6F1E7', 'surface-alt': '#EDE5D6',
			foreground: '#1C1917', muted: '#57534E', primary: '#7C2D12',
			'primary-hover': '#5C1F0D', accent: '#92400E', 'accent-soft': '#FDF0E0',
			success: '#166534', warning: '#854D0E', danger: '#B91C1C',
			'border-strong': '#57534E', outline: '#DDD6C9',
		},
		names: { base: 'Paper', surface: 'Surface', 'surface-alt': 'Surface strong', foreground: 'Ink', muted: 'Ink muted', primary: 'Primary', 'primary-hover': 'Primary hover', accent: 'Accent', 'accent-soft': 'Accent soft', success: 'Success', warning: 'Warning', danger: 'Danger', 'border-strong': 'Border strong', outline: 'Border' },
		headings: SERIF, body: SANS, headingWeight: '700',
		radius: radius( 2, 4, 8, 12 ),
		density: 1.15,
		description: 'Paper-warm story-first palette, serif display, airy density and near-sharp corners.',
	},
	magazine: {
		title: 'Magazine',
		palette: {
			base: '#FFFFFF', surface: '#F7F7F8', 'surface-alt': '#EAECEE',
			foreground: '#111318', muted: '#4B5563', primary: '#A50034',
			'primary-hover': '#7A0026', accent: '#B45309', 'accent-soft': '#FEF3E2',
			success: '#15803D', warning: '#A16207', danger: '#B91C1C',
			'border-strong': '#4B5563', outline: '#D6D9DE',
		},
		names: { base: 'Canvas', surface: 'Surface', 'surface-alt': 'Surface strong', foreground: 'Ink', muted: 'Ink muted', primary: 'Primary', 'primary-hover': 'Primary hover', accent: 'Accent', 'accent-soft': 'Accent soft', success: 'Success', warning: 'Warning', danger: 'Danger', 'border-strong': 'Border strong', outline: 'Border' },
		headings: SANS, body: SANS, headingWeight: '900',
		radius: radius( 0, 0, 0, 0 ),
		density: 0.9,
		description: 'Black-and-crimson newsstand energy: black-weight sans, zero radius, tight rhythm.',
	},
	minimal: {
		title: 'Minimal',
		palette: {
			base: '#FFFFFF', surface: '#F6F6F6', 'surface-alt': '#EBEBEB',
			foreground: '#111111', muted: '#595959', primary: '#1F1F1F',
			'primary-hover': '#000000', accent: '#595959', 'accent-soft': '#EDEDED',
			success: '#15803D', warning: '#A16207', danger: '#B91C1C',
			'border-strong': '#595959', outline: '#DCDCDC',
		},
		names: { base: 'Canvas', surface: 'Surface', 'surface-alt': 'Surface strong', foreground: 'Ink', muted: 'Ink muted', primary: 'Primary', 'primary-hover': 'Primary hover', accent: 'Accent', 'accent-soft': 'Accent soft', success: 'Success', warning: 'Warning', danger: 'Danger', 'border-strong': 'Border strong', outline: 'Border' },
		headings: SANS, body: SANS, headingWeight: '600',
		radius: radius( 2, 4, 6, 8 ),
		density: 1,
		description: 'Monochrome quiet: one ink, one grey ramp, whisper-small radius.',
	},
	brutal: {
		title: 'Brutal',
		palette: {
			base: '#FFEB00', surface: '#FFF350', 'surface-alt': '#F5D900',
			foreground: '#0A0A0A', muted: '#4A4A00', primary: '#0A0A0A',
			'primary-hover': '#262626', accent: '#7A0000', 'accent-soft': '#F5D900',
			success: '#0B6B2E', warning: '#7A4A00', danger: '#8B0000',
			'border-strong': '#3A3A00', outline: '#B8A800',
		},
		names: { base: 'Highlighter', surface: 'Surface', 'surface-alt': 'Surface strong', foreground: 'Ink', muted: 'Ink muted', primary: 'Primary', 'primary-hover': 'Primary hover', accent: 'Accent', 'accent-soft': 'Accent soft', success: 'Success', warning: 'Warning', danger: 'Danger', 'border-strong': 'Border strong', outline: 'Border' },
		headings: MONO, body: SANS, headingWeight: '700',
		radius: radius( 0, 0, 0, 0 ),
		density: 0.8,
		description: 'Highlighter-yellow poster energy: mono headings, zero radius, dense grid.',
	},
	soft: {
		title: 'Soft',
		palette: {
			base: '#FCFBFF', surface: '#F5F2FB', 'surface-alt': '#ECE7F7',
			foreground: '#221F33', muted: '#5B5670', primary: '#5B3DF5',
			'primary-hover': '#4328D4', accent: '#7C3AED', 'accent-soft': '#F1EAFE',
			success: '#047857', warning: '#92400E', danger: '#BE123C',
			'border-strong': '#64607A', outline: '#E2DDEC',
		},
		names: { base: 'Canvas', surface: 'Surface', 'surface-alt': 'Surface strong', foreground: 'Ink', muted: 'Ink muted', primary: 'Primary', 'primary-hover': 'Primary hover', accent: 'Accent', 'accent-soft': 'Accent soft', success: 'Success', warning: 'Warning', danger: 'Danger', 'border-strong': 'Border strong', outline: 'Border' },
		headings: SANS, body: SANS, headingWeight: '700',
		radius: radius( 12, 16, 24, 32 ),
		density: 1.2,
		description: 'Lavender calm with generous curves: the friendliest corner of the Arena.',
	},
};

function build( slug, spec ) {
	const variation = {
		$schema: 'https://schemas.wp.org/trunk/theme.json',
		version: 3,
		title: spec.title,
		description: spec.description,
		settings: {
			color: {
				palette: Object.entries( spec.palette ).map( ( [ key, color ] ) => ( {
					slug: key,
					color,
					name: spec.names[ key ],
				} ) ),
			},
			typography: {
				fontFamilies: [
					{ fontFamily: spec.headings, name: 'Display', slug: 'display' },
					{ fontFamily: spec.body, name: 'Body', slug: 'body' },
					{ fontFamily: MONO, name: 'Mono', slug: 'mono' },
				],
			},
			spacing: { spacingSizes: spacing( spec.density ) },
			custom: { radius: spec.radius },
		},
		styles: {
			typography: { fontFamily: `var(--wp--preset--font-family--body)` },
			elements: Object.fromEntries(
				[ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'heading' ].map( ( tag ) => [
					tag,
					{
						typography: {
							fontFamily: 'var(--wp--preset--font-family--display)',
							fontWeight: spec.headingWeight,
						},
					},
				] )
			),
		},
	};

	return JSON.stringify( variation, null, '\t' ) + '\n';
}

for ( const [ slug, spec ] of Object.entries( PRESETS ) ) {
	const file = join( OUT, `${ slug }.json` );
	writeFileSync( file, build( slug, spec ) );
	console.log( `[presets] wrote styles/${ slug }.json` );
}

console.log( `\n[presets] 7 generated + styles/midnight.json (dark-first, v2.0) = 8 presets (H40).` );
console.log( '[presets] Dark twins: assets/css/modules/arena-dark.css (H47) — verified by tests/g13 + tests/g16.' );
