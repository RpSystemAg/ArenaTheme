# Blog e journal del sito (H38, H39)

## Layout via template-part (H38)

Il loop è un *template part* (`parts/loop.html`) con varianti
`loop-grid`, `loop-list`, `loop-fullwidth`, `loop-masonry`: il tema cambia
layout **senza duplicare i template** — la scelta sta nel pannello, il
markup no. Sidebar destra/sinistra/nessuna, estratto o testo completo,
rapporto dell'immagine in evidenza selezionabile.

## Meta configurabili (H39)

Autore, data, categorie, tag, commenti e tempo di lettura: presenza e
**ordine** liberamente impostabili dal pannello layout. Il rendering è il
pattern `post-meta`, quindi vale anche per i kit e per i child.

## Navigazione e correlati

- Navigazione precedente/successiva (`post-navigation`);
- box autore (`author-box`);
- articoli correlati (`related-posts`) come pattern, non come query
  nascosta.

## Formati

I formati standard/gallery/quote/video hanno pattern dedicati
(`post-format-*`), tutti blocchi core.

## Accessibilità

Eredità v2.0: gerarchia dei titoli coerente, annunci live sulla paginazione,
focus visibile, rispetto di `prefers-reduced-motion`.
