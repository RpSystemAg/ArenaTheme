# Child theme starter (H50, G17)

> `theme/arena-commerce-child/` nel repository: copialo così com'è e
> attivalo.

## Perché esiste

Un child theme ben fatto **estende** il genitore senza duplicarne i file.
Lo starter dimostra i tre modi sicuri, nell'ordine in cui vanno usati:

### 1. Token (il 90% dei casi)

`assets/css/child.css` cambia **una variabile alla volta**:

```css
:root { --wp--preset--color--accent: #b3541e; }
```

Tutto ciò che usa quel token (pulsanti, link, badge, focus) segue. Il file
carica **dopo** la cascata del genitore ed è volutamente minuscolo.

### 2. Hook

`functions.php` mostra i due filtri più richiesti già pronti (commentati):

- `arena_theme_bottom_nav_items` — destinazioni della barra mobile;
- `arena_theme_wishlist_url` — attiva lo slot wishlist nell'header.

La lista completa: `docs/dev/hooks.md`. Gli hook sono API contrattuali.

### 3. Template part

Solo se un hook davvero non basta: copia `parts/loop-grid.html` (un *part*,
non un template completo) nel child e modificalo.

## L'anti-pattern

Copiare classi o template interi del genitore nel child: funziona oggi,
spezza ogni aggiornamento domani. Lo starter non contiene una riga
duplicata dal genitore — ed è così che resta (G17 lo verifica).

## Attivazione

1. Copia `theme/arena-commerce-child` in `wp-content/themes/`.
2. Aspetto → Temi → attiva **Arena Commerce Child** (il genitore resta
   installato).
3. Il child dichiara `Template: arena-commerce`, il proprio text domain e
   un `theme.json` che eredita tutto.
