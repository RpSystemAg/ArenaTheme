# Prestazioni e budget (REGOLA 3, H45, H46, G9, G15)

## La regola che non si negozia

Il budget v2.0 **non è mai stato alzato** (REGOLA 3): quando una funzione
non ci stava, è stata ridisegnata — mai allargato il budget. Esempi v3.1:
i preset sono JSON dichiarativi (zero byte CSS in più), la tipografia è una
variazione e non un foglio custom, i kit sono contenuto e non codice.

## Asset decoupled (H45)

- Un solo foglio globale (`arena.css`, ~15 KB) per ciò che serve ovunque.
- 8 moduli CSS per contesto: blog, carrello, checkout, commerce, dark,
  mega menu, motion, ricerca — caricati solo dove serve.
- Gli asset WooCommerce esistono **solo** nei template WooCommerce: su una
  pagina blog scaricano zero byte Woo (G15).
- Il foglio WooCommerce del plugin è rimpiazzato dal nostro (~2 KB contro
  ~87 KB).
- Niente jQuery, niente build step: script vanilla con `defer`, e i moduli
  Interactivity (carousel/reveal) solo dove ci sono i blocchi corrispondenti.

## Cache plugin (H46)

Con **WP Rocket** o **LiteSpeed** attivi l'adapter del plugin:
- mantiene il nostro script fuori dal delay JS (bottom nav e dark toggle
  devono essere reattivi subito);
- rispetta lazy-load nativo e Speculation Rules;
- il JSON di configurazione runtime viaggia come blocco dati, compatibile
  con l'ottimizzazione HTML.

## Motion e INP (H8/H9)

Solo `transform`/`opacity`, animazioni interrompibili, `prefers-reduced-motion`
rispettato, FLIP per le transizioni di layout: INP target < 100 ms.

## Prove

- Statiche a ogni commit: `lighthouse-budget.test.mjs`, `g15-assets.test.mjs`.
- Runtime: `lighthouse-run.mjs` + `tests/e2e/assets-decoupled.spec.js`
  (network log committato dall'ambiente reale).
