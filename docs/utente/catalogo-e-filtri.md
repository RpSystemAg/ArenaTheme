# Catalogo, filtri, galleria prodotto (H34, H35)

## Loop del catalogo (H34)

- **Load-more / scroll infinito**: scelta del visitatore, ricordata in
  `localStorage` (nessuna scrittura sul server).
- **Filtri off-canvas** nel drawer laterale, con **chip attive** e conteggi;
- **Quick view** in modale con galleria;
- **Badge saldi** in tre varianti (bolla / nastro / etichetta) scelte dal
  pannello layout;
- **modalità catalogo**: prezzi e acquisti nascosti (vetrine, B2B).

## Galleria prodotto nativa (H35)

Il blocco `arena/product-gallery` sostituisce l'immagine prodotto:
slider + zoom + lightbox **senza librerie** (pointer events, focus
management e tastiera in `arena-cart.js`/`assets/js` del tema).

## Tab riordinabili

L'ordine di descrizione / informazioni aggiuntive / recensioni si imposta
dal pannello (lista separata da virgole), senza toccare template.

## Correlati e up-sell come pattern (H35/H6)

Sia i correlati sia la fascia «Completa il kit» sono pattern nativi
(`related-posts`, `product-editorial-grid`…): si sostituiscono
dall'editor, restano coerenti alla famiglia e passano il test
anti-clona/anti-finto (nessun modulo finto).
