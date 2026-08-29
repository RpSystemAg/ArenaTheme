# SEO e dati strutturati (H43, H44, G14)

## JSON-LD lato server (H43)

Un unico grafo `@graph` per template, stampato in `wp_head`:

| Template | Nodi |
| --- | --- |
| Home | `WebSite` + `Organization` + `BreadcrumbList` |
| Articolo | `Article` + `BreadcrumbList` |
| Prodotto | `Product` + `Offer` + `BreadcrumbList` |
| Archivio/negozio | `CollectionPage` + `BreadcrumbList` |

Un solo `<script type="application/ld+json">` per pagina, nessun blocco
duplicato (AP12).

## Cedenza ai plugin SEO (H44)

Se **Yoast SEO**, **Rank Math**, All in One SEO o SEOPress è attivo, il tema
**non stampa nulla**: la funzione ritorna prima dell'output e la lista dei
plugin riconosciuti è estendibile col filtro `arena_theme_seo_plugin_active`.
Risultato: zero JSON-LD duplicati con Yoast **e** con Rank Math.

## Verifica

- Statica: `node tests/g14-jsonld.test.mjs`.
- Runtime: `tests/e2e/jsonld.spec.js` (un grafo per template, tipi attesi).
- Per il verdetto Rich Results Test usare `tools/php/rich-results-check.php`
  (estrae i quattro grafi e, con una chiave API, li sottomette; senza chiave
  li stampa per la validazione manuale — nessun punteggio inventato).
