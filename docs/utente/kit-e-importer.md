# Kit e importer (H19–H23, G8)

> Admin → Arena → **Kit**. Diagramma: [diagrammi/kit-import.svg](diagrammi/kit-import.svg)

Un **kit** è un sito completo pronto all'uso: home + 6 pagine interne, menu,
contenuti dimostrativi e il proprio preset cromatico. Sono 12, distribuite
sulle famiglie di pattern commerciali (H19), tutte bilingue en_US + it_IT
(H42).

## Import in un click (H20)

1. Scegli la **lingua** di import (Italiano o Inglese).
2. **Import kit** per tutto, oppure **Importa una sola pagina** per scegliere
   dalla lista.
3. La barra di avanzamento mostra il passo corrente (menu → prodotti →
   pagine).
4. Finito: il kit è installato e la scheda mostra **Sync** e **Annulla import**.

### Cosa NON viene mai toccato senza conferma

- Le pagine con lo stesso slug vengono **saltate**, non sovrascritte.
- Il menu occupa la posizione primaria solo se libera (o con conferma).
- La home diventa pagina iniziale **solo** se accetti la conferma esplicita.

L'annullamento elimina **esattamente** gli oggetti creati dall'import (pagine,
prodotti demo, menu) identificati nel Journal: null'altro.

## Sync senza re-import (H22)

Quando una nuova versione del kit esce, **Sync** aggiorna solo i contenuti:
le pagine che hai modificato dopo l'import non vengono toccate e vengono
segnalate come conflitti. Anche il sync è annullabile.

## Zero lock-in (H23)

Le pagine dei kit sono **blocchi core** + pattern del tema. Dopo l'import:

- nessuno shortcode proprietario da cui dipendere;
- nessuna tabella custom da bonificare;
- puoi disattivare il plugin e il sito resta in piedi, modificabile
  con l'editor a blocchi.

## Campagna inclusa (H21)

Ogni kit porta tre asset SVG vettoriali pronti per la promozione —
story 9:16, feed 1:1, banner 16:9 — generati dal preset del kit stesso
(`kits/<slug>/campaign/`). Si modificano in qualsiasi editor vettoriale.

## I 12 kit

| Kit | Famiglia | Preset | Uso tipico |
| --- | --- | --- | --- |
| Atelier Mode | Editorial | editorial | moda su misura, atelier |
| Nordic Furniture | Discovery | minimal | arredo flat-pack |
| Supplement Lab | Trust | commerce | nutrizione sportiva certificata |
| Specialty Coffee | Newsletter | editorial | torrefazione in abbonamento |
| Tech Refurb Depot | Product | commerce | elettronica ricondizionata |
| Ceramic Studio | Gallery | soft | ceramica artigianale |
| Outdoor Depot | Hero | commerce | attrezzatura outdoor |
| Beauty Ritual | Conversion | soft | skincare rituale |
| Independent Bookshop | Other | magazine | libreria di quartiere |
| Pet Supply Co. | Support | brutal | pet food «senza nascondino» |
| Bike Service Workshop | Service | minimal | officina prenotabile |
| Digital Assets Store | Checkout | minimal | beni digitali a consegna istantanea |

Le case non sono ricolorazioni: la struttura della home è diversa per
costruzione (AP8) e il gate G8 misura la distanza strutturale tra tutte le
coppie (soglia Jaccard ≤ 0,40; attuale: 0,125).
