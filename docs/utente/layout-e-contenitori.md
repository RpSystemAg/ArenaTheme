# Layout e contenitori (H31)

> Admin → Arena → **Layout & opzioni**

## Contenitore

- **Contenitore predefinito**: boxed / fullwidth / narrow.
- **Larghezza** personalizzata in rem (30–90): scritta come variazione
  `layout.json` (`contentSize`/`wideSize`), quindi l'editor e tutti i
  blocchi la rispettano davvero.

## Navigazione e breadcrumb (H30)

- Posizione del breadcrumb: sopra l'header, sotto l'header, dentro il
  contenuto, oppure nascosto. La traccia visibile e il JSON-LD
  `BreadcrumbList` usano la stessa sorgente.
- **Breakpoint mobile** (600 / 782 / 960): il punto in cui header e bottom
  nav cambiano pelle (H27).

## Blog (H38/H39)

- Layout del loop: griglia / lista / fullwidth / masonry (CSS nativo).
- Sidebar: destra / sinistra / nessuna.
- Contenuto nel loop: estratto / testo completo.
- Rapporto immagini in evidenza: 4:3, 16:9, 1:1, 3:2.
- **Ordine dei meta** (autore, data, categorie, tag, commenti, tempo di
  lettura): libera presenza e ordine, separati da virgole.

## Commercio (H33–H36)

- Modalità checkout: standard / **distraction-free**.
- Badge saldi: bolla / nastro / etichetta.
- Modalità catalogo: nasconde prezzi e pulsanti d'acquisto.
- Ordine delle tab prodotto (description, additional_information, reviews…).

## Annullamento

Ogni salvataggio è una singola azione `layout.save` nel Journal con l'intero
stato precedente: **Annulla** ripristina opzioni e variazione insieme
(G12).
