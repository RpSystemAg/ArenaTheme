# Il pannello Arena (H31)

> Admin → **Arena**. Sei voci: Panoramica, Kit, Preset, Tipografia,
> Layout & opzioni, Journal. Diagramma: [diagrammi/pannello-arena.svg](diagrammi/pannello-arena.svg)

## Cosa fa

Il pannello è l'unico punto in cui un negozio si configura **senza aprire un
file**: contenitore, larghezza per articolo, sidebar, breadcrumb, breakpoint
mobile, opzioni blog e commercio.

## Come funziona davvero (e perché è diverso)

Ogni azione del pannello produce una **variazione di stile** in
`uploads/arena/` (`preset.json`, `typography.json`, `layout.json`) o
un'**opzione tracciata**, e ogni salvataggio è **registrato nel Journal con
lo stato precedente**. Questo chiude l'anti-pattern AP9 («opzioni senza
annullamento né documentazione»):

- niente opzioni sparse nel database senza traccia;
- niente CSS inline generato che nessuno può verificare;
- ogni voce del Journal ha il suo pulsante **Annulla** e un link alla
  documentazione generata dal registry azioni.

## Le pagine

| Voce | Cosa ci trovi |
| --- | --- |
| Panoramica | Stato del sistema: kit, preset, azioni tracciate. |
| Kit | I siti pronti: vedi [kit-e-importer.md](kit-e-importer.md). |
| Preset | Le otto combinazioni crometiche: vedi [preset-globali.md](preset-globali.md). |
| Tipografia | Livelli e scale: vedi [tipografia.md](tipografia.md). |
| Layout & opzioni | Contenitore, breadcrumb, blog, commercio: vedi [layout-e-contenitori.md](layout-e-contenitori.md). |
| Journal | Cronologia e annullamenti: vedi [journal.md](journal.md). |

## API

Tutto il pannello parla con la REST API versionata `arena/v1`
(`docs/dev/kits-api.md`): la UI usa `fetch()`, zero jQuery, zero ricaricamenti
di pagina per salvare.
