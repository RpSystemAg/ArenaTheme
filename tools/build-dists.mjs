#!/usr/bin/env node
/**
 * Build release zip archives for Arena Prime v2.0 via system `zip`.
 *
 * Produces:
 *   dist/arena-commerce.zip   — the WordPress theme
 *   dist/arena-engine.zip     — the companion plugin
 *   dist/arena-suite.zip      — theme + plugin bundled
 */

import { mkdirSync, rmSync, statSync, existsSync, cpSync } from 'node:fs';
import { execSync } from 'node:child_process';
import { join, resolve } from 'node:path';
import { tmpdir } from 'node:os';
import { mkdtempSync } from 'node:fs';

const ROOT = resolve( process.cwd() );
const DIST = join( ROOT, 'dist' );
const TMP = mkdtempSync( join( tmpdir(), 'arena-dist-' ) );

const EXCLUDES = [
	'*/node_modules/*',
	'*/.git/*',
	'*/.github/*',
	'*/.cache/*',
	'*/tests/*',
	'*/tools/*',
	'*/vendor/bin/*',
	'*/.eslintrc*',
	'*/.wp-env*',
	'*/playwright.config*',
	'*/package*.json',
	'*/phpcs*',
	'*/phpstan*',
];

function sh( cmd, opts = {} ) {
	console.log( '  $ ' + cmd );
	execSync( cmd, { stdio: 'pipe', ...opts } );
}

mkdirSync( DIST, { recursive: true } );

// Stage theme.
const themeStage = join( TMP, 'arena-commerce' );
cpSync( join( ROOT, 'theme', 'arena-commerce' ), themeStage, { recursive: true } );

// Stage plugin.
const pluginStage = join( TMP, 'arena-engine' );
cpSync( join( ROOT, 'plugin', 'arena-engine' ), pluginStage, { recursive: true } );

function zipDir( stageDir, outFile ) {
	if ( existsSync( outFile ) ) rmSync( outFile );
	const exArgs = EXCLUDES.map( ( x ) => `-x '${ x }'` ).join( ' ' );
	// cd into staged parent and zip the subdirectory.
	sh( `cd ${ TMP } && zip -rq ${ outFile } ${ stageDir.split( '/' ).pop() } ${ exArgs }` );
}

console.log( '[dist] Building arena-commerce.zip …' );
zipDir( themeStage, join( DIST, 'arena-commerce.zip' ) );
const ts = statSync( join( DIST, 'arena-commerce.zip' ) ).size;
console.log( `  → ${ ts } bytes` );

console.log( '[dist] Building arena-engine.zip …' );
zipDir( pluginStage, join( DIST, 'arena-engine.zip' ) );
const ps = statSync( join( DIST, 'arena-engine.zip' ) ).size;
console.log( `  → ${ ps } bytes` );

// Suite: re-zip both directories together.
console.log( '[dist] Building arena-suite.zip …' );
if ( existsSync( join( DIST, 'arena-suite.zip' ) ) ) rmSync( join( DIST, 'arena-suite.zip' ) );
const exArgs = EXCLUDES.map( ( x ) => `-x '${ x }'` ).join( ' ' );
sh( `cd ${ TMP } && zip -rq ${ join( DIST, 'arena-suite.zip' ) } arena-commerce arena-engine ${ exArgs }` );
const ss = statSync( join( DIST, 'arena-suite.zip' ) ).size;
console.log( `  → ${ ss } bytes` );

console.log( '\n[dist] Done. Build staging at:', TMP );
