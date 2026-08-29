# Arena — hooks pubblici (per child theme e integrazioni)

Tutti gli hook sono considerati API contrattuali: un child theme può contarci
(H50). La lista completa e aggiornata vive qui.

## Tema (arena-commerce)

| Hook | Tipo | Descrizione |
| --- | --- | --- |
| `arena_theme_bottom_nav_items` | filter | Item della bottom nav (4–5 destinazioni + slot toggle H47). |
| `arena_theme_wishlist_url` | filter | URL dello slot wishlist nell'header (null = slot nascosto, H37). |
| `arena_theme_compare_url` | filter | URL slot confronto prodotti. |
| `arena_theme_checkout_mode` | filter | `standard` \| `distraction-free` (H36). |
| `arena_theme_catalog_mode` | filter | Modalità catalogo: nasconde prezzi e acquisti (H34). |
| `arena_theme_show_bottom_nav` | filter | Disattiva la bottom nav senza toccare codice. |
| `arena_theme_reassurance_items` | filter | Voci di rassicurazione sotto il pulsante acquista. |
| `arena_theme_seo_plugin_active` | filter | Estende la lista di plugin SEO a cui il tema cede il passo (H44). |
| `arena_theme_cart_bar_threshold` | filter | Soglia della barra carrello sticky mobile (H33). |

## Plugin (arena-engine)

| Hook | Tipo | Descrizione |
| --- | --- | --- |
| `arena_engine_modules` | filter | Elenco dei moduli avviati: un child può rimuoverne uno. |
| `arena_engine_journal` | filter | Lettura del journal (capped 200 voci). |
| `arena_engine_undo_<azione>` | filter | Handler di annullamento per ogni azione tracciata (AP9). |
| `arena_engine_compat_active` | action | Segnala gli adapter commerce attivi (H37). |
| `arena_engine_cache_compat_boot` | action | Fired quando WP Rocket / LiteSpeed è attivo (H46). |

## Pattern di override sicuro nel child (H50)

1. **Token**: `assets/css/child.css` con una variabile alla volta.
2. **Hook**: `add_filter( 'arena_theme_bottom_nav_items', … )` ecc.
3. **Part**: copiare un *template part* (`parts/loop-grid.html`), mai una
   classe o un template completo.

Il contrario — copiare le classi del genitore nel child — spezza gli
aggiornamenti ed è l'anti-pattern che lo starter documenta.
