# Il Journal: ogni azione è reversibile (AP9, G12)

> Admin → Arena → **Journal**

Il Journal è la risposta a un'anti-pattern classico dei temi commerciali:
*opzioni che si salvano ma non si annullano e non si spiegano* (AP9).

## Cosa registra

Ogni azione del pannello e degli importer, dalla più recente:

| Colonna | Contenuto |
| --- | --- |
| Quando | Data e ora. |
| Azione | Etichetta umana + codice azione (es. `preset.apply`). |
| Documentazione | Generata dal registry azioni + link alla pagina della guida. |
| Annulla | Il pulsante, quando l'azione è reversibile. |

Vengono registrate: `preset.apply`, `typography.save`, `layout.save`,
`meta.save`, `kit.import`, `kit.sync`, `kit.undo`. Il registro è limitato
alle 200 azioni più recenti.

## Come funziona l'annullamento

Ogni azione salva nel payload **lo stato precedente**. «Annulla» invoca
l'handler registrato per quell'azione (`arena_engine_undo_<azione>`), che
ripristina esattamente quello stato:

- i file di variazione tornano al contenuto precedente;
- le opzioni tornano ai valori precedenti;
- gli import eliminano solo gli oggetti creati (mai contenuti tuoi).

Le azioni già annullate sono mostrate come tali e non si annullano due
volte.

## Per gli sviluppatori

Registry e handler: `plugin/arena-engine/includes/admin/class-actions.php`,
`class-journal.php`, `class-rest-panel.php`. API: `GET /arena/v1/actions`,
`POST /arena/v1/actions/<id>/undo` (vedi `docs/dev/kits-api.md`).
