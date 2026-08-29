# Pattern e famiglie (H6, H7, H10–H18)

## Il sistema

58 pattern organizzati in **14 famiglie** (Editorial, Discovery, Trust,
Newsletter, Product, Gallery, Hero, Conversion, Social, Support, Service,
Checkout — più le due strutturali Blog e Footer), ciascuna con:

- **asse di scroll** dichiarato (verticale / orizzontale);
- **gerarchia visiva** esplicita (che cosa domina);
- **modulo interattivo** reale (nessun elemento finto);
- **densità** tipica.

Ogni pattern dichiara la famiglia negli attributi `data-arena-*` ed è
verificato dal gate anti-clona (similarità strutturale ≤ 0,40 tra tutte le
coppie) e dal test billboard H14.

## Billboard (H14)

I pattern sopra la piega rispettano la regola dei 6 metri / 1 secondo:
un titolo dominante, al più un paragrafo di supporto, un CTA primario,
massimo due gruppi interattivi, e i cover dichiarano sempre lo scrim
(`dimRatio ≥ 50`).

## Enunciati di valore (H18)

Le fasce di rassicurazione dicono cose verificabili («resi gratuiti 30
giorni», «spedizione tracciata») — mai promesse generiche. Il gate
`h15-family-system` controlla che ogni pattern appartenga a una famiglia e
che i moduli interattivi esistano davvero.

## Dove si usano

- nei template del tema;
- nei kit (inclusi con `{{pattern:slug}}`, risolti a blocchi core reali);
- nell'editor (categorie `arena-commerce`, `arena-motion`).
