#!/usr/bin/env node
/**
 * Installs the committed CI workflows into `.github/workflows/`.
 *
 * Why this exists: the GitHub App token used by the certification sandbox has
 * no `workflows` permission, so a commit that touches `.github/workflows/*`
 * is rejected at push time —
 *
 *   ! [remote rejected] (refusing to allow a GitHub App to create or update
 *     workflow `.github/workflows/php.yml` without `workflows` permission)
 *
 * Reproduced verbatim in `tests/proofs/ci-token-blocker.log`. Rather than keep
 * the workflows out of Git (the v2.0 report's choice, which left `.github/`
 * never committed at all), they are committed here at a path the token *can*
 * write, and anyone with `Workflows: Read and write` on the repo activates
 * them with one command:
 *
 *   node ci/install-workflows.mjs && git add .github && git commit && git push
 *
 * The script is deliberately dumb: copy, then verify byte-for-byte. It never
 * edits the YAML.
 *
 * @package
 * @since   3.2.0
 */

import { copyFileSync, mkdirSync, readdirSync, readFileSync } from 'node:fs';
import { join } from 'node:path';

const ROOT = process.cwd();
const SRC = join( ROOT, 'ci', 'workflows' );
const DEST = join( ROOT, '.github', 'workflows' );

const files = readdirSync( SRC ).filter( ( f ) => f.endsWith( '.yml' ) );

if ( ! files.length ) {
	console.error( `[install-workflows] no .yml files in ${ SRC }` );
	process.exit( 1 );
}

mkdirSync( DEST, { recursive: true } );

let failed = 0;

for ( const file of files ) {
	const from = join( SRC, file );
	const to = join( DEST, file );

	copyFileSync( from, to );

	const same = readFileSync( from ).equals( readFileSync( to ) );

	console.log( `${ same ? '✓' : '✗'} ${ file } → .github/workflows/${ file }` );

	if ( ! same ) {
		failed += 1;
	}
}

if ( failed ) {
	console.error( `[install-workflows] ${ failed } file(s) did not verify.` );
	process.exit( 1 );
}

console.log( `\n[install-workflows] ${ files.length } workflow(s) installed.` );
console.log( 'Next: git add .github && git commit -m "ci: activate workflows" && git push' );
console.log( 'That push must be made by an actor with `Workflows: Read and write`.' );
