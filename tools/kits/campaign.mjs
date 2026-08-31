/**
 * Campaign asset generator (H17/H21): three SVG compositions per kit
 * (9×16, 1×1, 16×9), derived from the kit's own preset palette and family
 * motif. Pure vector, no fonts embedded, system stack declared — the files
 * are honest diagrams/ads built from text and shapes, editable in any
 * vector tool.
 */

import { readFileSync } from 'node:fs';
import { join } from 'node:path';

const esc = ( s ) => String( s )
	.replace( /&/g, '&amp;' )
	.replace( /</g, '&lt;' )
	.replace( />/g, '&gt;' )
	.replace( /"/g, '&quot;' )
	.replace( /'/g, '&apos;' );

/** Wrap a title into ≤3 balanced lines of ~26 chars. */
function wrap( text, max = 26, maxLines = 3 ) {
	const words = String( text ).split( /\s+/ );
	const lines = [];
	let line = '';

	for ( const word of words ) {
		if ( ( line + ' ' + word ).trim().length > max && line ) {
			lines.push( line.trim() );
			line = word;
		} else {
			line = ( line + ' ' + word ).trim();
		}
	}

	if ( line ) {
		lines.push( line.trim() );
	}

	return lines.slice( 0, maxLines );
}

/**
 * Reads the preset palette from the theme styles directory.
 *
 * @param {string} preset   Preset slug.
 * @param {string} themeDir Theme directory.
 * @return {{base:string, contrast:string, accent:string, soft:string}} Resolved palette slots with safe fallbacks.
 */
export function palette( preset, themeDir ) {
	const file = join( themeDir, 'styles', `${ preset }.json` );
	const json = JSON.parse( readFileSync( file, 'utf8' ) );
	const colors = json.settings?.color?.palette ?? [];

	const bySlug = Object.fromEntries( colors.map( ( c ) => [ c.slug, c.color ] ) );

	return {
		base: bySlug.base || '#ffffff',
		contrast: bySlug.contrast || '#1f1f1f',
		accent: bySlug.accent || bySlug.primary || '#004eeb',
		soft: bySlug.soft || bySlug.accent2 || bySlug.base2 || '#f2f0eb',
	};
}

/** Family motif → decorative shapes for the given viewport. */
function motif( family, w, h, pal ) {
	const cx = w / 2;
	const cy = h / 2;
	const r = Math.min( w, h );

	switch ( family ) {
		case 'Editorial':
			return `<rect x="0" y="${ h * 0.72 }" width="${ w }" height="${ h * 0.28 }" fill="${ pal.contrast }" opacity="0.06"/>
<rect x="${ w * 0.08 }" y="${ h * 0.66 }" width="${ w * 0.2 }" height="2" fill="${ pal.accent }"/>`;
		case 'Discovery':
			return `<circle cx="${ cx }" cy="${ cy * 0.86 }" r="${ r * 0.3 }" fill="none" stroke="${ pal.accent }" stroke-width="3"/>
<circle cx="${ cx + r * 0.3 }" cy="${ cy * 0.86 }" r="${ r * 0.3 }" fill="${ pal.soft }"/>`;
		case 'Trust':
			return `<path d="M ${ w * 0.1 } ${ h * 0.78 } L ${ w * 0.42 } ${ h * 0.7 } L ${ w * 0.6 } ${ h * 0.76 } L ${ w * 0.9 } ${ h * 0.64 }" fill="none" stroke="${ pal.accent }" stroke-width="4" stroke-linecap="round"/>`;
		case 'Newsletter':
			return `<rect x="${ w * 0.12 }" y="${ h * 0.7 }" width="${ w * 0.76 }" height="${ h * 0.09 }" rx="${ h * 0.045 }" fill="${ pal.soft }"/>
<rect x="${ w * 0.12 }" y="${ h * 0.7 }" width="${ w * 0.5 }" height="${ h * 0.09 }" rx="${ h * 0.045 }" fill="${ pal.accent }"/>`;
		case 'Product':
			return `<rect x="${ w * 0.1 }" y="${ h * 0.68 }" width="${ w * 0.34 }" height="${ h * 0.14 }" rx="8" fill="${ pal.soft }"/>
<rect x="${ w * 0.5 }" y="${ h * 0.68 }" width="${ w * 0.4 }" height="${ h * 0.14 }" rx="8" fill="${ pal.accent }" opacity="0.2"/>`;
		case 'Gallery':
			return `<rect x="${ w * 0.12 }" y="${ h * 0.66 }" width="${ w * 0.24 }" height="${ h * 0.18 }" fill="${ pal.soft }"/>
<rect x="${ w * 0.4 }" y="${ h * 0.72 }" width="${ w * 0.2 }" height="${ h * 0.12 }" fill="${ pal.accent }" opacity="0.5"/>
<rect x="${ w * 0.64 }" y="${ h * 0.66 }" width="${ w * 0.24 }" height="${ h * 0.18 }" fill="${ pal.soft }"/>`;
		case 'Hero':
			return `<polygon points="${ w * 0.12 },${ h * 0.82 } ${ cx },${ h * 0.62 } ${ w * 0.88 },${ h * 0.82 }" fill="${ pal.accent }" opacity="0.18"/>`;
		case 'Conversion':
			return `<rect x="${ w * 0.12 }" y="${ h * 0.72 }" width="${ w * 0.76 }" height="${ h * 0.1 }" rx="${ h * 0.05 }" fill="${ pal.accent }"/>`;
		case 'Other':
			return `<rect x="${ w * 0.12 }" y="${ h * 0.68 }" width="${ w * 0.16 }" height="4" fill="${ pal.accent }"/>
<rect x="${ w * 0.32 }" y="${ h * 0.68 }" width="${ w * 0.16 }" height="4" fill="${ pal.contrast }"/>
<rect x="${ w * 0.52 }" y="${ h * 0.68 }" width="${ w * 0.16 }" height="4" fill="${ pal.accent }" opacity="0.5"/>`;
		case 'Support':
			return `<circle cx="${ cx }" cy="${ h * 0.76 }" r="${ r * 0.16 }" fill="none" stroke="${ pal.accent }" stroke-width="4"/>
<path d="M ${ cx - r * 0.05 } ${ h * 0.76 } L ${ cx } ${ h * 0.76 + r * 0.05 } L ${ cx + r * 0.07 } ${ h * 0.76 - r * 0.05 }" fill="none" stroke="${ pal.accent }" stroke-width="4" stroke-linecap="round"/>`;
		case 'Service':
			return `<rect x="${ w * 0.12 }" y="${ h * 0.7 }" width="${ w * 0.36 }" height="${ h * 0.12 }" rx="6" fill="${ pal.soft }"/>
<rect x="${ w * 0.52 }" y="${ h * 0.7 }" width="${ w * 0.36 }" height="${ h * 0.12 }" rx="6" fill="none" stroke="${ pal.accent }" stroke-width="2"/>`;
		case 'Checkout':
			return `<rect x="${ w * 0.12 }" y="${ h * 0.72 }" width="${ w * 0.76 }" height="${ h * 0.08 }" rx="4" fill="${ pal.soft }"/>
<rect x="${ w * 0.12 }" y="${ h * 0.72 }" width="${ w * 0.3 }" height="${ h * 0.08 }" rx="4" fill="${ pal.contrast }"/>`;
		default:
			return `<circle cx="${ cx }" cy="${ cy }" r="${ r * 0.2 }" fill="${ pal.accent }" opacity="0.15"/>`;
	}
}

/**
 * Builds one campaign SVG.
 *
 * @param {Object} kit    Kit spec.
 * @param {Object} pal    Palette.
 * @param {Object} opts   {w, h, label}
 * @param {string} locale Locale.
 * @return {string} SVG markup.
 */
export function campaignSvg( kit, pal, opts, locale = 'en_US' ) {
	const { w, h, label } = opts;
	const title = locale === 'it_IT' ? kit.home.title.it_IT : kit.home.title.en_US;
	const cta = locale === 'it_IT' ? kit.campaign.cta.it_IT : kit.campaign.cta.en_US;
	const name = locale === 'it_IT' ? kit.name.it_IT : kit.name.en_US;
	const lines = wrap( title );
	const titleSize = Math.round( Math.min( w, h ) * ( w > h ? 0.085 : 0.075 ) );
	const leading = titleSize * 1.08;
	const startY = h * 0.42;
	const mono = "'SF Mono', 'Cascadia Mono', ui-monospace, Menlo, Consolas, monospace";
	const display = w > h && kit.family === 'Editorial' ? "'Iowan Old Style', Georgia, 'Times New Roman', serif" : "system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif";

	return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${ w } ${ h }" role="img" aria-labelledby="t d" width="${ w }" height="${ h }">
<title id="t">${ esc( name ) } — ${ esc( cta ) }</title>
<desc id="d">${ esc( `${ name }: ${ title }` ) }</desc>
<rect width="${ w }" height="${ h }" fill="${ pal.base }"/>
${ motif( kit.family, w, h, pal ) }
<text x="${ w * 0.12 }" y="${ h * 0.18 }" font-family="${ mono }" font-size="${ Math.round( titleSize * 0.34 ) }" letter-spacing="4" fill="${ pal.accent }" text-transform="uppercase">${ esc( ( name + ' · ' + label ).toUpperCase() ) }</text>
${ lines
	.map(
		( line, i ) => `<text x="${ w * 0.12 }" y="${ startY + i * leading }" font-family="${ display }" font-size="${ titleSize }" font-weight="800" fill="${ pal.contrast }">${ esc( line ) }</text>`
	)
	.join( '\n' ) }
<text x="${ w * 0.12 }" y="${ h * 0.6 }" font-family="${ display }" font-size="${ Math.round( titleSize * 0.4 ) }" fill="${ pal.contrast }" opacity="0.72">${ esc( locale === 'it_IT' ? 'Pronto in un click.' : 'Ready in one click.' ) }</text>
<rect x="${ w * 0.12 }" y="${ h * 0.78 }" width="${ Math.min( w * 0.5, 420 ) }" height="${ h * 0.08 }" rx="${ h * 0.04 }" fill="${ pal.accent }"/>
<text x="${ w * 0.12 + 28 }" y="${ h * 0.78 + h * 0.052 }" font-family="${ display }" font-size="${ Math.round( titleSize * 0.38 ) }" font-weight="700" fill="#fff">${ esc( cta ) }</text>
<text x="${ w * 0.12 }" y="${ h * 0.94 }" font-family="${ mono }" font-size="${ Math.round( titleSize * 0.26 ) }" fill="${ pal.contrast }" opacity="0.6">arena-commerce · kit/${ esc( kit.slug ) }</text>
</svg>
`;
}

/** The three mandated ratios (H21). */
export const RATIOS = [
	{ key: '9x16', w: 1080, h: 1920, label: 'story' },
	{ key: '1x1', w: 1080, h: 1080, label: 'feed' },
	{ key: '16x9', w: 1920, h: 1080, label: 'banner' },
];
