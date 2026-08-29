# Modalità scura (H47, H48, G16)

> Diagramma: [diagrammi/modalita-scura.svg](diagrammi/modalita-scura.svg)

## Com'è fatta davvero

- L'interruttore imposta l'attributo **`data-theme`** sull'elemento radice:
  un vero cambio di palette, **non** un filtro `invert()` (che violerebbe
  il contrasto — AP13).
- La preferenza si salva in `localStorage` e uno script inline la applica
  **prima del primo paint**: nessun flash, nessun reload.
- Senza preferenza salvata vince `prefers-color-scheme` di sistema.
- Ogni palette ha la **gemella inversa**: le otto palette (inclusa
  midnight, scura di natura) sono coperte in `arena-dark.css`.

## Dove sta l'interruttore (H47)

**Due posti**: nelle azioni dell'header e nella bottom nav mobile (bersaglio
44px). Entrambi flippano lo stesso attributo.

## Contrasto (H48)

Entrambi gli schemi rispettano WCAG 2.2 AA e dichiarano `color-scheme`
per i controlli nativi; sono supportati anche `prefers-contrast` e
`forced-colors` (modalità alto contrasto / Windows).

La prova runtime (axe con 0 violazioni in chiaro **e** in scuro,
persistenza dopo reload) è lo spec committato
`tests/e2e/dark-mode.spec.js`; il contratto statico è
`tests/g16-dark.test.mjs`.
