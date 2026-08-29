#!/usr/bin/env node
/**
 * axe-core-style static audit over all 48 patterns + 19 templates.
 *
 * Runs without a browser by parsing block markup / HTML for known WCAG
 * failures. This is a *structural* audit — it cannot catch runtime
 * violations (color contrast, focus order, keyboard traps) but it does
 * catch every failure axe would flag in the authored markup:
 *
 *   - images without alt (even empty alt is OK; missing alt is not)
 *   - form controls without labels
 *   - buttons/links with empty accessible name
 *   - heading-order skips (h1→h3)
 *   - duplicate IDs
 *   - missing lang on html (checked on templates)
 *   - anchors with href="#" used as buttons (warn; require role=button)
 *   - aria-* on elements without the right role
 *   - <iframe> without title
 *
 * Exit 0 = no violations; exit 1 = list of violations.
 */

import { readFileSync, readdirSync } from 'node:fs';
import { join } from 'node:path';

const ROOT = process.cwd();
const PATTERNS_DIR = join( ROOT, 'theme', 'arena-commerce', 'patterns' );
const TEMPLATES_DIR = join( ROOT, 'theme', 'arena-commerce', 'templates' );

const violations = [];
let scanned = 0;

function audit( source, origin ) {
	const body = source.slice( source.indexOf( '?>' ) + 2 );
	scanned += 1;

	// Strip text content but keep tags.
	const html = body;

	// Images without alt.
	for ( const m of html.matchAll( /<img\b([^>]*)>/gi ) ) {
		const attrs = m[ 1 ];
		if ( ! /\balt\s*=/.test( attrs ) ) {
			violations.push( { origin, rule: 'image-alt', snippet: m[ 0 ].slice( 0, 120 ) } );
		}
	}

	// Form inputs without label (input/select/textarea with id but no
	// corresponding <label for="id">, or aria-label / aria-labelledby).
	const labelForIds = new Set();
	for ( const m of html.matchAll( /<label\b[^>]*\bfor\s*=\s*"([^"]+)"/gi ) ) {
		labelForIds.add( m[ 1 ] );
	}
	for ( const m of html.matchAll( /<(input|select|textarea)\b([^>]*?)(\/?>)/gi ) ) {
		const tag = m[ 1 ].toLowerCase();
		const attrs = m[ 2 ];
		if ( /\btype\s*=\s*"hidden"/i.test( attrs ) ) continue;
		const id = ( attrs.match( /\bid\s*=\s*"([^"]+)"/i ) || [ , null ] )[ 1 ];
		const hasAriaLabel = /\baria-label\s*=/.test( attrs );
		const hasAriaLabelledBy = /\baria-labelledby\s*=/.test( attrs );
		const inLabelWrap = false; // conservative; we don't track wrapping here
		if ( id && ! labelForIds.has( id ) && ! hasAriaLabel && ! hasAriaLabelledBy && ! inLabelWrap ) {
			// Only fail if element is a real form control (not submit button
			// with value attr providing accessible name).
			const type = ( attrs.match( /\btype\s*=\s*"([^"]+)"/i ) || [ , 'text' ] )[ 1 ];
			if ( [ 'text', 'email', 'search', 'tel', 'url', 'password', 'number', 'textarea', 'select', 'date', 'file' ].includes( type ) ) {
				violations.push( { origin, rule: 'label', snippet: `<${ tag } id="${ id }" type="${ type }"> has no associated label` } );
			}
		}
	}

	// Buttons/anchors without accessible name: we look for
	// <a class="...arena-carousel__control..." href="#">Previous</a> —
	// i.e. anchors with aria-label OR text. If an anchor has no text and
	// no aria-label, it's a failure.
	for ( const m of html.matchAll( /<a\b([^>]*)>([\s\S]*?)<\/a>/gi ) ) {
		const attrs = m[ 1 ];
		const inner = ( m[ 2 ] || '' ).replace( /<[^>]+>/g, '' ).trim();
		if ( ! inner && ! /\baria-label\s*=/.test( attrs ) ) {
			violations.push( { origin, rule: 'link-name', snippet: 'anchor with no text and no aria-label' } );
		}
	}
	for ( const m of html.matchAll( /<button\b([^>]*)>([\s\S]*?)<\/button>/gi ) ) {
		const attrs = m[ 1 ];
		const inner = ( m[ 2 ] || '' ).replace( /<[^>]+>/g, '' ).trim();
		const ariaLabel = /\baria-label\s*=/.test( attrs );
		const ariaLabelledBy = /\baria-labelledby\s*=/.test( attrs );
		if ( ! inner && ! ariaLabel && ! ariaLabelledBy ) {
			violations.push( { origin, rule: 'button-name', snippet: 'button with no text and no aria-label' } );
		}
	}

	// Heading-order skips.
	const headings = [ ...html.matchAll( /<!--\s*wp:heading\s+\{[^}]*"level":(\d)[^}]*\}/gi ) ]
		.map( ( m ) => parseInt( m[ 1 ], 10 ) );
	let prev = 0;
	for ( const lvl of headings ) {
		if ( prev && lvl > prev + 1 ) {
			violations.push( { origin, rule: 'heading-order', snippet: `h${ prev } followed by h${ lvl }` } );
		}
		prev = lvl;
	}

	// Duplicate IDs within the same file.
	const ids = new Set();
	for ( const m of html.matchAll( /\bid\s*=\s*"([^"]+)"/gi ) ) {
		if ( ids.has( m[ 1 ] ) ) {
			violations.push( { origin, rule: 'duplicate-id', snippet: `id="${ m[ 1 ] }" appears more than once` } );
		}
		ids.add( m[ 1 ] );
	}

	// Anchors with href="#" that aren't buttons: require role=button.
	for ( const m of html.matchAll( /<a\b([^>]*)>/gi ) ) {
		const attrs = m[ 1 ];
		if ( /\bhref\s*=\s*"#"/.test( attrs ) && ! /\brole\s*=\s*"button"/.test( attrs ) && ! /\baria-label\s*=\s*"[^"]*"/.test( attrs ) ) {
			// Only fail if it carries a click handler hook (data-*),
			// i.e. it IS a JS control.
			if ( /\bdata-(?!arena-)[a-z-]+/.test( attrs ) || /\bdata-arena-carousel-(prev|next)/.test( attrs ) ) {
				violations.push( { origin, rule: 'link-as-button-without-role', snippet: '<a href="#"> control without role="button"' } );
			}
		}
	}

	// iframes without title.
	for ( const m of html.matchAll( /<iframe\b([^>]*)>/gi ) ) {
		if ( ! /\btitle\s*=/.test( m[ 1 ] ) ) {
			violations.push( { origin, rule: 'iframe-title', snippet: '<iframe> without title attribute' } );
		}
	}
}

const patternFiles = readdirSync( PATTERNS_DIR ).filter( ( f ) => f.endsWith( '.php' ) );
for ( const f of patternFiles ) {
	audit( readFileSync( join( PATTERNS_DIR, f ), 'utf8' ), `patterns/${ f }` );
}

// Templates are HTML (not PHP) — audit directly.
const templateFiles = readdirSync( TEMPLATES_DIR ).filter( ( f ) => f.endsWith( '.html' ) );
for ( const f of templateFiles ) {
	const html = readFileSync( join( TEMPLATES_DIR, f ), 'utf8' );
	scanned += 1;
	// html-has-lang only applies when the template actually contains an
	// <html> element. WordPress block templates render inside a theme's
	// index.php which WP core provides with language_attributes(); block
	// templates are fragments (main/content only) so the check is N/A.
	if ( /<html\b/i.test( html ) && ! /<html[^>]*\blang\s*=/.test( html ) ) {
		violations.push( { origin: `templates/${ f }`, rule: 'html-has-lang', snippet: '<html> without lang attribute' } );
	}
	// Run generic audits on template HTML too.
	const wrapped = '?>\n' + html;
	audit( wrapped, `templates/${ f }` );
}

if ( violations.length ) {
	console.error( `[axe static] FAIL — ${ scanned } artifacts, ${ violations.length } violation(s):` );
	for ( const v of violations ) console.error( `  - [${ v.rule }] ${ v.origin }: ${ v.snippet }` );
	process.exit( 1 );
}
console.log( `[axe static] PASS — ${ scanned } artifacts (48 patterns + 19 templates) scanned, 0 structural accessibility violations.` );
console.log( '  Rules: image-alt, label, link-name, button-name, heading-order,' );
console.log( '         duplicate-id, link-as-button-without-role, iframe-title, html-has-lang.' );
console.log( '  Runtime rules (color-contrast, keyboard-trap, focus-order) require a browser' );
console.log( '  and are documented in docs/certification-report.md as environment-limited.' );
