# Compatibilità plugin (H37, H46)

## Principio

Nessun adapter viene caricato «in previsione»: ogni classe di compatibilità
controlla se il plugin è attivo e **solo allora** si registra. Se il plugin
non c'è, il costo è zero.

## Wishlist e confronto (H37)

| Plugin | Cosa succede |
| --- | --- |
| YITH WooCommerce Wishlist | Lo slot header wishlist punta alla pagina YITH; il pulsante per prodotto usa lo shortcode ufficiale. |
| TI WooCommerce Wishlist | Come sopra con la pagina/pulsante TI. |
| Jetpack | URL confronto estendibile via filtro. |

Lo slot wishlist resta **nascosto** finché nessun adapter restituisce un
URL (`arena_theme_wishlist_url`): mai icone morte.

## Plugin SEO (H44)

Yoast / Rank Math / AIOSEO / SEOPress: il tema cede il passo sui dati
strutturati (vedi [seo-e-dati-strutturati.md](seo-e-dati-strutturati.md)).

## Cache (H46)

WP Rocket e LiteSpeed: esclusioni documentate per gli script critici e
rispetto di lazy-load/Speculation Rules (vedi
[prestazioni.md](prestazioni.md) e `docs/dev/hooks.md`).

## WooCommerce

Il tema dichiara il supporto e ridefinisce il foglio di stile; tutte le
funzioni Woo sono guardate da `class_exists('WooCommerce')`: senza Woo il
tema è un tema blog perfettamente funzionante.
