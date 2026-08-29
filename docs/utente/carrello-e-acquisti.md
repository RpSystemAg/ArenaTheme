# Carrello e acquisti senza ricaricamenti (H29, H33, G9)

> Il flusso d'acquisto non ricarica mai la pagina: aggiungi → drawer →
> quantità → annulla rimozione → checkout.

## Slot header ajax (H29)

- **Ricerca live** con debounce: risultati mentre digiti.
- **Account** e **wishlist** (quest'ultima compare solo se un plugin la
  fornisce — H37).
- **Mini-carrello drawer** che si apre sull'evento di aggiunta, senza
  navigazione.

## Aggiunta al carrello ajax (H33)

Dalla scheda catalogo o dalla pagina prodotto: `fetch()` verso la Store
API, drawer aperto, **annuncio live** per screen reader. Il fallback senza
JavaScript resta il submit classico del form (progressive enhancement).

## Nel drawer

- **Stepper quantità** ± senza reload;
- **rimozione con annullo**: la voce eliminata torna con un click dal toast
  «Annulla»;
- totale e button checkout sempre visibili.

## Barra carrello sticky mobile

Quando il carrello non è vuoto compare la barra sticky in basso con totale
e «Vai al checkout»: il pollice non deve cercare nulla.

## Prove

- Architettura verificata a ogni commit dal proxy statico
  `tests/g9-purchase-flow.test.mjs`.
- Prova runtime (network log committato, zero navigazioni nel flusso):
  `tests/e2e/purchase-flow.spec.js` su wp-env — vedere `docs/ci.md`.
