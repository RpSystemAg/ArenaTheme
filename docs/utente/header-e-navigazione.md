# Header, navigazione e mega menu (H27, H28, H2)

> Diagramma bottom nav: [diagrammi/bottom-nav.svg](diagrammi/bottom-nav.svg)

## Varianti header (H27)

- **Standard**: barra solida sempre visibile.
- **Trasparente**: si fonde sull'eroe e guadagna sfondo+ombra allo scroll.
- **Sticky**: resta fissa con ombra.

Selezionabile per tutto il sito (varietà header del kit / pannello) **e per
singola pagina** ([meta-per-pagina.md](meta-per-pagina.md)). Il **breakpoint
mobile** (600/782/960) si sceglie dal pannello layout.

## Bottom nav obbligatoria su mobile (H2)

Su schermi stretti la navigazione primaria è la barra fissa in basso:
4–5 destinazioni, bersagli da 44px, indicatore animato sulla voce attiva,
si nasconde scendendo e riappare salendo. La regola «4–5 destinazioni» è
**imposta dal codice**: una lista fuori scala semplicemente non viene
renderizzata.

L'hamburger/flyout **accompagna** la bottom nav, non la sostituisce.

## Mega menu nativo (H28)

Il menu «Arena (mega)» (posizione `arena-mega`) costruisce colonne con link,
immagini e icone (WP 7.1 Icons API), descrizioni e badge, fino a pattern
integrati. Comportamento: focus intrappolato, **ESC** chiude,
`aria-expanded` sempre coerente. Nessuna libreria: CSS + il modulo
`arena-megamenu.js`, caricato solo quando la pagina contiene il markup.
