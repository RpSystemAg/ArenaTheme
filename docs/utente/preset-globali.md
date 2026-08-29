# Preset globali (H40, G13)

> Admin → Arena → **Preset**. Anche in Aspetto → Editor → Stili.

Un preset è una **combinazione completa**, non un cambio colore: palette
(14 slot semantici), accoppiata tipografica, raggi degli angoli e densità
degli spazi. Otto preset, applicabili con un click.

| Preset | Carattere | Raggi | Densità |
| --- | --- | --- | --- |
| default | neutro di sistema | 6/12/16/24 | 1.00 |
| midnight | scuro di default (v2.0) | — | — |
| editorial | serif, carta calda | 2/4/8/12 | 1.15 |
| commerce | blu negozio, sans netta | 4/8/12/16 | 0.85 |
| magazine | rosso rotocalco, pesante | 0 | 0.90 |
| minimal | bianco e nero, quieto | 2/4/6/8 | 1.00 |
| brutal | giallo pieno, mono, squadrato | 0 | 0.80 |
| soft | viola morbido, aria | 12/16/24/32 | 1.20 |

## Come si applica

Il pulsante **Applica in 1 click** scrive la variazione `preset.json` in
`uploads/arena/` e aggiorna il puntatore `arena_active_preset`. Il tutto è
registrato nel Journal: **Annulla** dalla voce Journal ripristina il preset
precedente byte per byte.

I preset sono anche **variazioni di stile** native: compaiono in
Editor del sito → Stili → Sfoglia, quindi restano utilizzabili anche
senza plugin.

## Regole rispettate

- **Budget mai alzato** (REGOLA 3): un preset è puro JSON dichiarativo
  (< 6 KB), non un foglio di stile aggiuntivo: la pagina non scarica nemmeno
  un byte in più.
- Ogni palette ha la sua **gemella scura** (H47): vedi
  [modalita-scura.md](modalita-scura.md).
- Zero webfont: gli stack sono di sistema (H26).
