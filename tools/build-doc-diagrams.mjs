#!/usr/bin/env node
/**
 * Annotated SVG wireframes for docs/utente (H49/G17).
 *
 * HONESTY POLICY: these are DIAGRAMS — clearly labelled, schematic
 * wireframes used where a real screenshot cannot be produced in this
 * sandbox. They are not captures and are never presented as evidence of a
 * runtime result; runtime evidence lives in the e2e specs.
 *
 * Usage: node tools/build-doc-diagrams.mjs
 */

import { mkdirSync, writeFileSync } from 'node:fs';
import { join, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join( dirname( fileURLToPath( import.meta.url ) ), '..' );
const out = join( root, 'docs', 'utente', 'diagrammi' );

mkdirSync( out, { recursive: true } );

const FONT = 'font-family="system-ui, -apple-system, Segoe UI, Roboto, sans-serif"';
const MONO = 'font-family="ui-monospace, SFMono-Regular, Menlo, monospace"';

function frame( { title, subtitle, w = 960, h, body } ) {
	return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${ w } ${ h }" width="${ w }" height="${ h }" role="img" aria-label="${ title }">
<rect width="${ w }" height="${ h }" fill="#fdfdfc"/>
<rect x="16" y="16" width="${ w - 32 }" height="${ h - 32 }" fill="#fff" stroke="#c3c4c7" stroke-width="2" rx="12"/>
<text x="40" y="58" ${ FONT } font-size="26" font-weight="800" fill="#1f1f1f">${ title }</text>
<text x="40" y="84" ${ FONT } font-size="14" fill="#50575e">${ subtitle }</text>
<text x="${ w - 40 }" y="${ h - 34 }" text-anchor="end" ${ MONO } font-size="12" fill="#8c8f94">DIAGRAMMA — wireframe schematico, non uno screenshot</text>
${ body }
</svg>
`;
}

const box = ( x, y, w, h, label, fill = '#f0f0f1', stroke = '#c3c4c7', size = 14 ) =>
	`<rect x="${ x }" y="${ y }" width="${ w }" height="${ h }" rx="8" fill="${ fill }" stroke="${ stroke }" stroke-width="1.5"/>
<text x="${ x + w / 2 }" y="${ y + h / 2 + 5 }" text-anchor="middle" ${ FONT } font-size="${ size }" fill="#1f1f1f">${ label }</text>`;

const arrow = ( x1, y1, x2, y2 ) =>
	`<path d="M ${ x1 } ${ y1 } L ${ x2 } ${ y2 }" stroke="#2271b1" stroke-width="2" marker-end="url(#a)"/>`;

const defs = `<defs><marker id="a" markerWidth="8" markerHeight="8" refX="7" refY="4" orient="auto"><path d="M0,0 L8,4 L0,8 z" fill="#2271b1"/></marker></defs>`;

/* 1 — Admin → Arena overview */
writeFileSync( join( out, 'pannello-arena.svg' ), frame( {
	title: 'Admin → Arena',
	subtitle: 'Il pannello: ogni azione tracciata nel Journal e reversibile (H31, AP9)',
	h: 560,
	body: `${ defs }
${ box( 40, 110, 200, 70, 'Starter kit', '#e7f3fa', '#2271b1' ) }
${ box( 260, 110, 200, 70, 'Preset' ) }
${ box( 480, 110, 200, 70, 'Tipografia' ) }
${ box( 700, 110, 200, 70, 'Layout &amp; opzioni' ) }
${ box( 260, 210, 200, 70, 'Journal (annulla)', '#fef8ee', '#d63638' ) }
${ arrow( 460, 540 - 120, 360, 290 ) }
${ box( 40, 320, 860, 90, 'Uploads: preset.json · typography.json · layout.json — fogli di stile tracciati, non opzioni sparse', '#fff', '#8c8f94', 13 ) }
${ box( 40, 440, 860, 70, 'Journal → «Annulla» ripristina lo stato precedente di ogni azione (G12)', '#fff', '#8c8f94', 13 ) }`,
} ) );

/* 2 — Kit import */
writeFileSync( join( out, 'kit-import.svg' ), frame( {
	title: 'Import di un kit in un click',
	subtitle: 'Selettivo, con barra di avanzamento e annullamento totale (H20, G8)',
	h: 620,
	body: `${ defs }
${ box( 40, 110, 280, 190, 'Scheda kit: pagine · famiglia · preset', '#fff' ) }
${ box( 340, 110, 160, 84, 'Import kit', '#e7f3fa', '#2271b1' ) }
${ box( 520, 110, 160, 84, 'Solo una pagina', '#fff' ) }
${ box( 700, 110, 200, 84, 'Sync / Annulla import', '#fef8ee', '#d63638' ) }
${ box( 340, 214, 380, 86, 'Conferma esplicita prima di toccare contenuti esistenti', '#fff', '#8c8f94', 12 ) }
<rect x="40" y="330" width="860" height="18" rx="9" fill="#f0f0f1"/>
<rect x="40" y="330" width="620" height="18" rx="9" fill="#2271b1"/>
<text x="40" y="376" ${ FONT } font-size="13" fill="#50575e">Importazione… (4/7) — menu, prodotti demo, pagine</text>
${ arrow( 90, 420, 90, 480 ) }
${ box( 40, 480, 410, 100, 'Pagine + menu + prodotti creati', '#fff' ) }
${ box( 490, 480, 410, 100, 'Journal: id creati → «Annulla» li elimina tutti', '#fef8ee', '#d63638', 13 ) }`,
} ) );

/* 3 — Meta box per pagina */
writeFileSync( join( out, 'meta-box.svg' ), frame( {
	title: 'Arena: opzioni pagina (meta box)',
	subtitle: 'Override per singola pagina o articolo, con reset in un click (H32)',
	h: 560,
	body: `
${ box( 40, 110, 400, 44, '☐ Nascondi titolo pagina' ) }
${ box( 40, 164, 400, 44, '☐ Header trasparente sull’eroe' ) }
${ box( 40, 218, 400, 44, '☐ Nascondi footer · ☐ Nascondi sidebar' ) }
${ box( 40, 272, 400, 44, 'Contenitore: boxed ▾' ) }
${ box( 40, 326, 400, 44, 'Larghezza contenitore (rem): ____' ) }
${ box( 460, 110, 440, 152, 'Salvato via REST → journal con annullamento', '#e7f3fa', '#2271b1', 14 ) }
${ box( 460, 272, 440, 98, '«Salva override» · «Reset totale (un click)»', '#fff' ) }
${ box( 40, 392, 860, 120, 'Ogni chiave è meta standard esposta in REST: nessun campo proprietario, nessun lock-in', '#fff', '#8c8f94', 13 ) }`,
} ) );

/* 4 — Dark mode */
writeFileSync( join( out, 'modalita-scura.svg' ), frame( {
	title: 'Modalità scura reale',
	subtitle: 'Attributo data-theme, persistenza senza reload, gemelle inverse per ogni preset (H47/H48)',
	h: 600,
	body: `${ defs }
<rect x="40" y="110" width="410" height="420" rx="10" fill="#fff" stroke="#c3c4c7" stroke-width="1.5"/>
<rect x="490" y="110" width="410" height="420" rx="10" fill="#151515" stroke="#3c3c3c" stroke-width="1.5"/>
<text x="245" y="140" text-anchor="middle" ${ FONT } font-size="15" fill="#1f1f1f">Chiaro (default)</text>
<text x="695" y="140" text-anchor="middle" ${ FONT } font-size="15" fill="#eee">Scuro — data-theme="dark"</text>
${ box( 70, 170, 120, 40, 'Toggle ☀︎', '#f0f0f1', '#c3c4c7', 12 ) }
${ box( 520, 170, 120, 40, 'Toggle ☾', '#2b2b2b', '#4a4a4a', 12 ) }
${ box( 70, 230, 350, 70, 'Header + toggle', '#f6f7f7', '#c3c4c7', 12 ) }
${ box( 520, 230, 350, 70, 'Header + toggle', '#202020', '#3c3c3c', 12 ) }
${ box( 70, 320, 350, 100, 'Bottom nav + toggle (H47)', '#f6f7f7', '#c3c4c7', 12 ) }
${ box( 520, 320, 350, 100, 'Bottom nav + toggle', '#202020', '#3c3c3c', 12 ) }
${ box( 70, 440, 350, 60, 'Preferenza salvata: nessun reload', '#f6f7f7', '#c3c4c7', 11 ) }
${ box( 520, 440, 350, 60, 'localStorage → nessun flash', '#202020', '#3c3c3c', 11 ) }`,
} ) );

/* 5 — Bottom nav */
writeFileSync( join( out, 'bottom-nav.svg' ), frame( {
	title: 'Bottom nav mobile (H2) + toggle tema (H47)',
	subtitle: '44px di bersaglio, 4–5 destinazioni + toggle, niente hover',
	h: 620,
	body: `
<rect x="330" y="110" width="300" height="420" rx="24" fill="#fff" stroke="#1f1f1f" stroke-width="3"/>
<rect x="350" y="130" width="260" height="360" rx="8" fill="#f6f7f7" stroke="#c3c4c7"/>
<text x="480" y="440" text-anchor="middle" ${ FONT } font-size="13" fill="#50575e">contenuto della pagina</text>
${ box( 350, 500, 52, 44, '⌂', '#fff', '#c3c4c7', 16 ) }
${ box( 402, 500, 52, 44, '▤', '#fff', '#c3c4c7', 16 ) }
${ box( 454, 500, 52, 44, '⌕', '#fff', '#c3c4c7', 16 ) }
${ box( 506, 500, 52, 44, '☺', '#fff', '#c3c4c7', 16 ) }
${ box( 558, 500, 52, 44, '☾', '#e7f3fa', '#2271b1', 16 ) }
<text x="480" y="566" text-anchor="middle" ${ FONT } font-size="12" fill="#50575e">4 destinazioni + toggle schema = thumb-reach</text>
${ box( 40, 130, 260, 150, 'Le 4–5 destinazioni sono obbligatorie su mobile: la regola è imposta dal codice, non consigliata', '#fff', '#8c8f94', 12 ) }
${ box( 40, 300, 260, 120, 'Il flyout hamburger ACCOMPAGNA la bottom nav (mai la sostituisce)', '#fff', '#8c8f94', 12 ) }`,
} ) );

console.log( '✓ 5 diagrammi scritti in docs/utente/diagrammi/' );
