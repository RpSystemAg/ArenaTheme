# Traduzioni (H42, G11)

## Il flusso di rilascio

A ogni release i template POT si rigenerano e si committano:

```bash
node tools/make-pot.mjs           # theme/arena-commerce/languages + plugin/arena-engine/languages
node tools/make-pot.mjs --check   # CI: i pot committati devono essere freschi
```

Zero stringhe visibili fuori da gettext: il controllo è parte del gate G11
(domain corretti per ogni chiamata, pot non stantii).

## Contenuti dei kit

Ogni kit porta **en_US + it_IT completi** con parità di chiavi: alla
creazione del sito si sceglie la lingua di import dal selettore nella
scheda del kit. Non sono traduzioni runtime: è contenuto demo *datato per
lingua*, quindi anche i prezzi/o le descrizioni possono differire
deliberatamente.

## WPML e Polylang

Quando WPML o Polylang è attivo, il plugin registra le stringhe usate a
runtime dai moduli JS (carrello, ricerca, dark mode, load-more) nel gruppo
**arena-engine**, così diventano traducibili come normali stringhe tema.
L'adapter si carica **solo** se il plugin esiste (zero costo altrimenti).

### Verifica suggerita

1. Attiva WPML (o Polylang) con una seconda lingua.
2. Stringhe tema → gruppo `arena-engine`: le 10 chiavi runtime devono
   esserci.
3. Traducine una: il drawer carrello la usa alla lingua attiva.

Dettagli tecnici: `docs/dev/i18n.md`.
