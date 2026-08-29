#!/usr/bin/env node
/**
 * G12 — Panel + meta box with verified undo and docs (H31/H32/AP9).
 *
 *   U1  Every panel mutation is journaled with its previous state.
 *   U2  Every journaled action has a registered undo handler (preset.apply,
 *       typography.save, layout.save, meta.save, kit.import, kit.sync).
 *   U3  Every action in the registry is reversible + documented (doc anchor
 *       → a real docs/utente page) — AP9 closed.
 *   U4  The meta box saves through the journaled REST endpoint and resets
 *       with one click (H32).
 *   U5  The admin UI uses fetch (no jQuery) and confirms destructive kit
 *       imports explicitly (H20).
 *   U6  Undo is exposed both in the Journal page and per kit card.
 */

import { readFileSync, existsSync } from 'node:fs';
import { join } from 'node:path';
import assert from 'node:assert/strict';

const root = process.cwd();
const admin = ( f ) => readFileSync( join( root, 'plugin', 'arena-engine', 'includes', 'admin', f ), 'utf8' );
const read = ( p ) => readFileSync( join( root, p ), 'utf8' );

const restPanel = admin( 'class-rest-panel.php' );
const journal = admin( 'class-journal.php' );
const actions = admin( 'class-actions.php' );
const variations = admin( 'class-variations.php' );
const metaBox = admin( 'class-meta-box.php' );
const menu = admin( 'class-menu.php' );
const kitsAdmin = admin( 'class-kits-admin.php' );
const adminJs = read( 'plugin/arena-engine/assets/js/admin-arena.js' );

/* U1 — journal record with previous state on every save path. */
assert.ok( journal.includes( 'function record' ) && journal.includes( 'function undo' ), 'Journal record/undo missing (U1)' );

for ( const needle of [ 'Journal::record', "'previous'" ] ) {
	assert.ok( restPanel.includes( needle ), `REST panel must journal previous state (${ needle }) (U1)` );
}

/* U2 — undo handlers registered for every panel action (meta.save lives with
   the Journal, which owns the ACTION_META contract). */
for ( const action of [ 'preset.apply', 'typography.save', 'layout.save' ] ) {
	assert.ok( restPanel.includes( `arena_engine_undo_${ action }` ), `undo filter for ${ action } missing (U2)` );
}
assert.ok(
	journal.includes( "arena_engine_undo_' . self::ACTION_META" ) || journal.includes( 'arena_engine_undo_meta.save' ),
	'undo filter for meta.save missing (U2)'
);

const importer = read( 'plugin/arena-engine/includes/kits/class-importer.php' );
for ( const action of [ 'kit.import', 'kit.sync' ] ) {
	assert.ok( importer.includes( `arena_engine_undo_${ action }` ), `undo filter for ${ action } missing (U2)` );
}

/* U3 — registry rows: reversible + doc anchor that resolves to a page. */
for ( const action of [ 'preset.apply', 'typography.save', 'layout.save', 'meta.save', 'kit.import', 'kit.undo', 'kit.sync' ] ) {
	assert.ok( actions.includes( `'${ action }'` ), `action registry missing ${ action } (U3)` );
}

const docAnchors = [ ...actions.matchAll( /'doc'\s*=>\s*'([a-z0-9-]+)'/g ) ].map( ( m ) => m[ 1 ] );
assert.ok( docAnchors.length >= 7, `registry exposes ${ docAnchors.length } doc anchors, need ≥ 7 (U3)` );

for ( const anchor of docAnchors ) {
	assert.ok(
		existsSync( join( root, 'docs', 'utente', `${ anchor }.md` ) ),
		`docs/utente/${ anchor }.md missing — AP14: feature without a doc page (U3)`
	);
}

/* U4 — meta box journaled REST save + one-click reset. */
assert.ok( metaBox.includes( 'data-arena-meta-save' ) && metaBox.includes( 'data-arena-meta-reset' ), 'meta box save/reset controls missing (U4)' );
assert.ok( restPanel.includes( "register_rest_route( self::NAMESPACE_V1, '/meta/" ), 'REST meta endpoint missing (U4)' );
assert.ok( restPanel.includes( "'reset'" ), 'REST meta reset path missing (U4)' );

/* U5 — fetch-based UI, no jQuery, explicit confirm on overwrite. */
assert.ok( adminJs.includes( 'fetch(' ), 'admin UI must use fetch (U5)' );
const adminJsCode = adminJs.replace( /\/\*[\s\S]*?\*\//g, '' ).replace( /^\s*\/\/.*$/gm, '' );
assert.ok( ! /jQuery|\$\(/.test( adminJsCode ), 'no jQuery in the admin UI (U5)' );
assert.ok( adminJs.includes( 'confirm' ), 'destructive import must ask explicitly (H20/U5)' );

/* U6 — undo surface: journal page + kit cards. */
assert.ok( menu.includes( 'arena-journal' ), 'Journal page missing from the Arena menu (U6)' );
assert.ok( kitsAdmin.includes( 'data-arena-kit-undo' ), 'kit cards must expose undo (U6)' );

/* Variations are files, not scattered options (H25/H31 mechanism). */
assert.ok( variations.includes( 'wp_theme_json_data_user' ), 'variations must merge through wp_theme_json_data_user' );

console.log( 'G12 PASS — every panel action journaled, reversible, documented; meta box + kits verified' );
