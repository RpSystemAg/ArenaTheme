# Arena Engine — REST API dei kit e del pannello (H20/H22/H31)

> Namespace versionato: **`arena/v1`**. Ogni modifica all'API è una *version
> bump* del kit (`kit.json → version`), mai un cambiamento silenzioso dei campi.

## Autenticazione

Tutti gli endpoint richiedono `manage_options`; l'autenticazione è la
cookie-auth standard di WordPress con il nonce REST:

```
X-WP-Nonce: <wp_create_nonce('wp_rest')>
```

La UI di amministrazione (`assets/js/admin-arena.js`) usa solo `fetch()`:
zero jQuery, zero pagine ricaricate.

## Kit

| Metodo | Endpoint | Descrizione |
| --- | --- | --- |
| `GET` | `/arena/v1/kits` | Elenco dei kit installabili con stato (`installed`, versione, `upToDate`). |
| `GET` | `/arena/v1/kits/<slug>` | Manifest completo di un kit. |
| `POST` | `/arena/v1/kits/<slug>/import` | Importa il kit o una singola pagina. |
| `POST` | `/arena/v1/kits/<slug>/undo` | Annulla l'importazione referenziata dal journal. |
| `POST` | `/arena/v1/kits/<slug>/sync` | Aggiorna i contenuti del kit senza re-importare (H22). |

### `POST /kits/<slug>/import`

Body (JSON):

```json
{
  "scope": "full | page",
  "page": "lookbook",
  "confirm_overwrite": false,
  "locale": "en_US | it_IT"
}
```

Regole di sicurezza (H20 — mai sovrascrivere senza conferma esplicita):

- una pagina con lo slug esistente viene **saltata** a meno che
  `confirm_overwrite` non sia `true`;
- il menu non tocca la posizione primaria se occupata, salvo conferma;
- la home page diventa front page **solo** con `confirm_overwrite: true`;
- ogni import crea una voce di **journal** con gli id creati: l'annullamento
  (`undo`) elimina esattamente quegli oggetti, nessun altro.

Risposta: `{ journal, report: { created[], skipped[], conflicts[] } }`.

### Sync senza re-import (H22)

`POST /kits/<slug>/sync` aggiorna i contenuti delle pagine del kit alla
versione spedizione corrente. Le pagine che il negoziante ha modificato dopo
l'import (`modified_gmt` oltre 60 s dall'import) **non vengono toccate** e
vengono riportate come conflitti. Il sync è a sua volta annullabile.

## Pannello (H31/H32)

| Metodo | Endpoint | Descrizione |
| --- | --- | --- |
| `GET/POST` | `/arena/v1/typography` | Livelli tipografici + scale mobile/desktop → `uploads/arena/typography.json`. |
| `GET/POST` | `/arena/v1/presets` | Preset correnti / attivazione in un click → `preset.json` + puntatore `arena_active_preset`. |
| `GET/POST` | `/arena/v1/layout` | Contenitore, sidebar, breadcrumb, breakpoint, blog, checkout, catalogo → opzioni tracciate + `layout.json`. |
| `GET/POST` | `/arena/v1/meta/<id>` | Meta box per pagina (H32): lettura, salvataggio, `reset: true` one-click. |
| `GET` | `/arena/v1/actions` | Journal completo (nuovi prima). |
| `POST` | `/arena/v1/actions/<id>/undo` | Annulla un'azione tracciata. |

Ogni `POST` risponde con l'id `journal` dell'azione: l'annullamento è sempre
disponibile dalla pagina **Arena → Journal**.

## Journal (AP9)

Ogni azione scrive in `arena_engine_journal` (cap 200 voci): azione, etichetta,
payload con lo **stato precedente**, riferimento doc. L'annullamento avviene
tramite il filtro `arena_engine_undo_<azione>`:

- `preset.apply` — ripristina il file preset precedente + il puntatore;
- `typography.save` — ripristina `typography.json` byte per byte;
- `layout.save` — ripristina ogni opzione precedente + `layout.json`;
- `meta.save` — ripristina i meta per pagina;
- `kit.import` / `kit.sync` — elimina/ripristina esattamente gli oggetti creati.

## Formato manifest del kit

Vedi `plugin/arena-engine/kits/<slug>/kit.json`:

- `slug`, `name`, `version`, `description`, `family`, `preset`, `headerVariant`;
- `campaign`: i tre SVG (9×16, 1×1, 16×9) dell'asset H21;
- `menu.items[]`: `label_key` + `href`;
- `pages[]` (6–9, home inclusa): `slug`, `file`, `title_key`, `template`, `is_front`;
- `products[]`: `name_key`, `price`, `category_key`, `description_key`;
- `i18n`: mappe complete `en_US` + `it_IT`;
- `sync`: endpoint + strategia; `source: "core-blocks"` (H23 — zero lock-in).

Le pagine usano i token `{{t:chiave}}` (traduzioni) e `{{pattern:slug}}`
(inclusioni di pattern del tema, risolte a blocchi core reali all'import).
