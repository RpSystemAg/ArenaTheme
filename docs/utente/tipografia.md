# Tipografia (H24–H26)

> Admin → Arena → **Tipografia**

## Livelli semantici (H24)

La tabella del pannello espone i livelli che il tema usa davvero —
`display, h1…h6, body, caption, price, quote` — perché ogni slot tipografico
è *semantico*: il prezzo non è «un testo grande» ma **price**, la didascalia
non è «testo piccolo» ma **caption**. I blocchi ereditano i livelli dal
`theme.json`; nessun blocco incolla a mano una famiglia o una misura.

## Cosa si regola per livello (H25)

- **famiglia** (stack di sistema: sans / serif / mono);
- **peso**;
- **interlinea**;
- **spaziatura tra lettere**;
- **trasformazione** (maiuscole, minuscole, capitalizzato).

## Scale separate mobile/desktop

Due fattori indipendenti (0,75–1,5 mobile; 0,9–2 desktop): la scala mobile
può comprimersi senza sacrificare il ritmo desktop. Il tema genera
dimensioni **fluide** con `min`/`max`, quindi niente salti tra breakpoint.

## Dove finisce la modifica

Il salvataggio scrive `uploads/arena/typography.json` — una variazione di
stile tracciata, non una manciata di opzioni — e il Journal conserva lo
stato precedente per l'annullamento (AP9/G12).

## Webfont (H26)

Di default **zero font scaricati**: gli stack sono di sistema, quindi zero
richieste, zero FOIT/CLS. Se un kit di marca esige un font, la politica è
documentata: solo self-hosted, con `font-display: swap`, subset e fallback di
sistema, entro 80 KB gzip per famiglia. Nessun preset li usa.
