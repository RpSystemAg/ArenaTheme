#!/usr/bin/env node
/**
 * Arena Prime v3.1 — POT generator (H42/G11).
 *
 * Extracts every gettext call from the theme and the plugin PHP sources and
 * writes languages/arena-commerce.pot and languages/arena-engine.pot. Run at
 * every release (documented in docs/ci.md); the pots are committed.
 *
 * Supports: __, _e, esc_html__, esc_html_e, esc_attr__, esc_attr_e, _x, _ex,
 * esc_html_x, esc_attr_x, _n, _nx — with the arena-commerce / arena-engine
 * text domains only.
 *
 * Usage: node tools/make-pot.mjs [--check]
 *   --check: exit 1 if the committed pots are stale (CI mode, G11).
 */

import { readFileSync, writeFileSync, existsSync, readdirSync } from 'node:fs';
import { join, dirname, relative } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join( dirname( fileURLToPath( import.meta.url ) ), '..' );
const check = process.argv.includes( '--check' );

const PROJECTS = [
	{
		name: 'arena-commerce',
		domain: 'arena-commerce',
		dir: join( root, 'theme', 'arena-commerce' ),
		out: join( root, 'theme', 'arena-commerce', 'languages', 'arena-commerce.pot' ),
	},
	{
		name: 'arena-engine',
		domain: 'arena-engine',
		dir: join( root, 'plugin', 'arena-engine' ),
		out: join( root, 'plugin', 'arena-engine', 'languages', 'arena-engine.pot' ),
	},
];

const FUNCTIONS = [
	{ fn: '__', args: [ 'msgid' ] },
	{ fn: '_e', args: [ 'msgid' ] },
	{ fn: 'esc_html__', args: [ 'msgid' ] },
	{ fn: 'esc_html_e', args: [ 'msgid' ] },
	{ fn: 'esc_attr__', args: [ 'msgid' ] },
	{ fn: 'esc_attr_e', args: [ 'msgid' ] },
	{ fn: '_x', args: [ 'msgid', 'msgctxt' ] },
	{ fn: '_ex', args: [ 'msgid', 'msgctxt' ] },
	{ fn: 'esc_html_x', args: [ 'msgid', 'msgctxt' ] },
	{ fn: 'esc_attr_x', args: [ 'msgid', 'msgctxt' ] },
	{ fn: '_n', args: [ 'msgid', 'msgid_plural' ] },
	{ fn: '_nx', args: [ 'msgid', 'msgid_plural', null, 'msgctxt' ] },
];

/** Collect PHP files recursively. */
function phpFiles( dir ) {
	const out = [];
	const stack = [ dir ];
	const skip = new Set( [ 'node_modules', '.git', 'kits', 'vendor' ] );

	while ( stack.length ) {
		const current = stack.pop();
		for ( const entry of readdirSync( current, { withFileTypes: true } ) ) {
			const path = join( current, entry.name );
			if ( entry.isDirectory() ) {
				if ( ! skip.has( entry.name ) ) {
					stack.push( path );
				}
			} else if ( entry.name.endsWith( '.php' ) ) {
				out.push( path );
			}
		}
	}

	return out.sort();
}

/** Parse a PHP single- or double-quoted string literal at position i. */
function parseString( code, i ) {
	const quote = code[ i ];
	if ( quote !== "'" && quote !== '"' ) {
		return null;
	}

	let value = '';
	let j = i + 1;

	while ( j < code.length ) {
		const ch = code[ j ];
		if ( ch === '\\' ) {
			const next = code[ j + 1 ];
			if ( next === 'n' ) value += '\n';
			else if ( next === 't' ) value += '\t';
			else if ( next === "'" ) value += "'";
			else if ( next === '"' ) value += '"';
			else if ( next === '\\' ) value += '\\';
			else value += next;
			j += 2;
			continue;
		}
		if ( ch === quote ) {
			return { value, end: j + 1 };
		}
		value += ch;
		j += 1;
	}

	return null;
}

/** Split a call's argument list into string literals (null where not a literal). */
function parseArgs( code, openParen ) {
	const args = [];
	let depth = 0;
	let i = openParen;

	while ( i < code.length ) {
		const ch = code[ i ];
		if ( ch === '(' ) depth++;
		else if ( ch === ')' ) {
			depth--;
			if ( depth === 0 ) return { args, end: i };
		} else if ( ( ch === "'" || ch === '"' ) && depth === 1 ) {
			const parsed = parseString( code, i );
			if ( parsed ) {
				args.push( parsed.value );
				i = parsed.end;
				continue;
			}
		} else if ( ch === ',' && depth === 1 ) {
			args.push( null );
			// trim consecutive placeholders handled by index
		}
		i++;
	}

	return { args: args.filter( ( a, idx ) => idx < 8 ), end: i };
}

/** Extract entries from one file. */
function extract( file, domain, baseDir ) {
	const code = readFileSync( file, 'utf8' );
	const entries = new Map();

	for ( const { fn, args: argSpec } of FUNCTIONS ) {
		const re = new RegExp( `(?<![\\w$])${ fn }\\s*\\(`, 'g' );
		let match;

		while ( ( match = re.exec( code ) ) ) {
			const { args } = parseArgs( code, match.index + match[ 0 ].length - 1 );

			/* The text domain must be the LAST literal argument. */
			if ( ! args.length || args[ args.length - 1 ] !== domain ) {
				continue;
			}

			const msgid = args[ 0 ];
			if ( typeof msgid !== 'string' || ! msgid ) {
				continue;
			}

			const msgctxt = argSpec.includes( 'msgctxt' ) ? args[ argSpec.indexOf( 'msgctxt' ) ] : null;
			const plural = argSpec.includes( 'msgid_plural' ) ? args[ 1 ] : null;
			const line = code.slice( 0, match.index ).split( '\n' ).length;
			const key = ( msgctxt ? msgctxt + '\u0004' : '' ) + msgid + ( plural ? '\u0005' + plural : '' );

			if ( ! entries.has( key ) ) {
				entries.set( key, { msgid, msgctxt, plural, refs: [] } );
			}

			entries.get( key ).refs.push( `${ relative( baseDir, file ).replace( /\\/g, '/' ) }:${ line }` );
		}
	}

	return entries;
}

function potHeader( project ) {
	const now = new Date().toISOString().slice( 0, 10 );
	const hour = new Date().toISOString().slice( 11, 16 );

	return `# Arena ${ project.name === 'arena-commerce' ? 'theme' : 'engine plugin' } — generated POT (H42/G11).
# Generated by tools/make-pot.mjs on ${ now } ${ hour }+00:00.
msgid ""
msgstr ""
"Project-Id-Version: ${ project.name } 1.1.0\\n"
"Report-Msgid-Bugs-To: https://github.com/RpSystemAg/ArenaTheme/issues\\n"
"POT-Creation-Date: ${ now } ${ hour }+0000\\n"
"MIME-Version: 1.0\\n"
"Content-Type: text/plain; charset=UTF-8\\n"
"Content-Transfer-Encoding: 8bit\\n"
"X-Generator: make-pot.mjs\\n"
"X-Domain: ${ project.domain }\\n"
`;
}

function potBody( entries ) {
	const chunks = [];
	const keys = [ ...entries.keys() ].sort( ( a, b ) => a.localeCompare( a ), 'en' );

	const sorted = [ ...entries.entries() ].sort( ( [ a ], [ b ] ) => a < b ? -1 : a > b ? 1 : 0 );

	for ( const [ , entry ] of sorted ) {
		const lines = [];

		if ( entry.refs.length ) {
			lines.push( `#: ${ [ ...new Set( entry.refs ) ].slice( 0, 8 ).join( ' ' ) }` );
		}

		if ( entry.msgctxt ) {
			lines.push( `msgctxt ${ potString( entry.msgctxt ) }` );
		}

		lines.push( `msgid ${ potString( entry.msgid ) }` );

		if ( entry.plural ) {
			lines.push( `msgid_plural ${ potString( entry.plural ) }` );
			lines.push( 'msgstr[0] ""' );
			lines.push( 'msgstr[1] ""' );
		} else {
			lines.push( 'msgstr ""' );
		}

		chunks.push( lines.join( '\n' ) );
	}

	void keys;

	return chunks.join( '\n' );
}

function potString( value ) {
	const escaped = value
		.replace( /\\/g, '\\\\' )
		.replace( /"/g, '\\"' )
		.replace( /\n/g, '\\n' )
		.replace( /\t/g, '\\t' );

	return `"${ escaped }"`;
}

let stale = 0;

for ( const project of PROJECTS ) {
	const entries = new Map();

	for ( const file of phpFiles( project.dir ) ) {
		for ( const [ key, entry ] of extract( file, project.domain, project.dir ) ) {
			if ( ! entries.has( key ) ) {
				entries.set( key, entry );
			} else {
				entries.get( key ).refs.push( ...entry.refs );
			}
		}
	}

	const pot = potHeader( project ) + '\n' + potBody( entries ) + '\n';

	if ( check ) {
		const existing = existsSync( project.out ) ? readFileSync( project.out, 'utf8' ) : '';
		/* Compare ignoring the timestamp lines (header date varies). */
		const normalize = ( s ) => s.replace( /^.*(?:Generated by|POT-Creation-Date).*$/gm, '' );
		if ( normalize( existing ) !== normalize( pot ) ) {
			console.error( `✗ ${ project.out } is stale — run: node tools/make-pot.mjs` );
			stale++;
		} else {
			console.log( `✓ ${ project.out } up to date (${ entries.size } strings)` );
		}
	} else {
		writeFileSync( project.out, pot );
		console.log( `✓ ${ project.out } — ${ entries.size } strings` );
	}
}

if ( stale ) {
	process.exit( 1 );
}
