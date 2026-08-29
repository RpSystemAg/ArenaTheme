# Checkout (H36)

## Modalità distraction-free

Dal pannello layout: **Arena → Layout & opzioni → Checkout**.

Quando attiva, la pagina checkout (e la ringraziamento) elimina tutto ciò
che distrae: colonne footer e navigazione bottom vengono nascoste via
`body.arena-distraction-free` (regole dedicate in `arena-checkout.css`) e
restano solo brand, progress e modulo d'ordine.

## Progress dei passi

Il pattern `checkout-steps` mostra dove si è nel percorso
(carrello → dati → pagamento → conferma) e l'ordine di conferma mantiene la
coerenza visiva del percorso. I passi sono link reali dove ha senso:
niente finti moduli.

## My-account

Le schede del mio-account sono riordinabili (opzione tracciata) e la pagina
usa la stessa disciplina di focus/annunci del resto del tema.

## Autofill e accessibilità

Il tema mappa i token `autocomplete` corretti (nome, email, indirizzi,
pagamento) e il modulo è navigabile da tastiera con annunci live sugli
errori: questo eredita la certificazione accessibilità v2.0.
