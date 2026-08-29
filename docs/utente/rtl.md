# RTL — lingue da destra a sinistra (H41, G10)

## Cosa succede attivando una lingua RTL

1. `is_rtl()` è vero → il tema carica i fogli **`-rtl.css`** al posto degli
   LTR (globale + 8 moduli). L'enqueue condizionale è nel codice
   (`class-assets.php`), verificabile, non un'ipotesi.
2. Il tag `rtl-language-support` in `style.css` è **reale**: i file ci sono.

## Come è costruito

- Il CSS del tema è **logical-first**: `margin-inline`, `padding-inline`,
  `inset-inline`, `border-inline`… — si specchia da solo in RTL.
- Le dichiarazioni **fisiche** residue (i pochi `left/right` letterali)
  vengono specchiate dal generatore `tools/build-rtl.mjs`, committato con i
  file generati: ogni dichiarazione fisica ha il suo mirror nel foglio RTL.
- I moduli usati dai template principali — blog/loop, commerce/prodotto,
  carrello, checkout, ricerca, dark — hanno tutti la gemella `-rtl`.

## Verifica

`node tests/g10-rtl.test.mjs` (copertura mirror + enqueue condizionale +
tag reale). Prova manuale: impostare la lingua del sito su una lingua RTL e
confrontare catalogo, articolo, checkout, ricerca.
