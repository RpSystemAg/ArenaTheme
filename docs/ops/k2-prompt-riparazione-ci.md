# K2 — Prompt enterprise per la riparazione completa della CI

**Data:** 2026-08-31
**Branch:** `arena/01a056e1-arenatheme` (base `6bff710` «Create php.yml»)
**REQ:** WS-K / REQ-K2 («Dopo il primo run, fissare ogni rosso aprendo un REQ con priorità»)
**Stato:** prompt pronto, non ancora eseguito
**Evidenze a supporto:** `tests/proofs/phpcs-local-repro-source.log`,
`tests/proofs/phpcs-local-repro-summary.log`, `tests/proofs/gw6-budget.log`,
`tests/proofs/ci-token-blocker-k1.log`, `docs/decisions/001-gw6-asset-budget.md`

> **Come usare questo file.** Tutto ciò che segue il separatore è un prompt
> completo e autonomo: copialo integralmente e consegnalo all'agente/team che
> eseguirà i lavori. Contiene contesto, diagnosi misurata, vincoli, piani di
> lavoro, comandi di verifica e riferimenti alla documentazione ufficiale.

---

# PROMPT — Riparazione CI del repository `RpSystemAg/ArenaTheme`

## 0. Ruolo, obiettivo, standard di qualità atteso

Agisci come un **Senior Platform / Release Engineer** incaricato di una
*rimediazione CI* su un repository WordPress di livello enterprise.

**Obiettivo unico:** portare a **verde reale** i workflow GitHub Actions
`quality` e `php` del repository `RpSystemAg/ArenaTheme`, sul branch
`arena/01a056e1-arenatheme`, **senza degradare, disattivare o aggirare alcun
gate**, producendo per ogni cambiamento una prova verificabile e committata.

**Standard atteso (non negoziabile):**

1. Ogni affermazione che fai deve essere **misurata**: un comando, un exit code,
   un log committato. È vietato dichiarare «verde» qualcosa che non hai eseguito:
   se non lo hai eseguito si scrive **“non misurato”**, non “ok”.
2. **Nessun gate numerico viene ri-basato in silenzio.** Se un gate numerico va
   cambiato, si apre una decisione documentata (costituzione v4 §11.4) con le
   due letture a confronto, non si sposta la soglia e basta.
3. Ogni fix è **atomico e tracciabile**: un commit per work package, con il log
   di verifica dentro `tests/proofs/`.
4. Se una correzione richiede di toccare `.github/workflows/*.yml`, la modifica
   si fa **solo** in `ci/workflows/*.yml` e si propaga con
   `node ci/install-workflows.mjs` (il file non modifica lo YAML: copia e
   confronta byte-per-byte). È vietato editare a mano i file in `.github/`.

---

## 1. Contesto di sistema (fatti verificati, non ipotesi)

| Voce | Valore |
|---|---|
| Repository | `RpSystemAg/ArenaTheme` |
| Branch di lavoro | `arena/01a056e1-arenatheme` (creato da `6bff710`) |
| Prodotti versionati | `theme/arena-commerce` (block theme, `theme.json` v3), `theme/arena-commerce-child` (child starter), `plugin/arena-engine` |
| Target di piattaforma | WordPress 7.1 · WooCommerce 11 · PHP 7.4+ (CI 8.3 e 8.5) · Node 22 |
| Workflow presenti | `.github/workflows/quality.yml`, `.github/workflows/php.yml` (installati da `ci/workflows/` tramite `ci/install-workflows.mjs`) |
| Job CI | `quality › static-gates` · `php › phpcs` (matrice 8.3/8.5) · `php › extension-check` |
| Gate statici locali | `npm run test:quality` = 17 gate (`tests/g8…g17`, `h2`, `h11`, `h14`, `h15`, `anti-clone`, `axe-static`) |
| Evidenza committata | `tests/proofs/*.log` (unica directory di log **non** ignorata da `.gitignore`) |
| Documenti di governo | `docs/decisions/001-gw6-asset-budget.md`, `docs/ci.md`, `docs/certification-report-v3.2.md`, `docs/evidence/2026-08-31.md` |

**Ambiente del sandbox che ha prodotto questa analisi** (da dichiarare, perché
limita ciò che è stato misurato): Node 22.22.3 / npm 10.9.8 presenti;
**nessun binario PHP**, nessun Docker, nessun browser; rete limitata a
`github.com`, `api.github.com`, `registry.npmjs.org` (bloccati
`results-receiver.actions.githubusercontent.com`, `*.blob.core.windows.net`,
`repo.packagist.org`, `getcomposer.org`).

---

## 2. Stato reale della CI (run misurate tramite GitHub API)

| Run | Workflow | Commit | Job | Step fallito | Exit |
|---|---|---|---|---|---|
| `33366475340` | `php` | `Create php.yml` | `PHPCS GW4 (PHP 8.5)` | `PHPCS — WordPress-VIP-Go` | **2** |
| `33366475340` | `php` | `Create php.yml` | `PHPCS GW4 (PHP 8.3)` | `PHPCS — WordPress-VIP-Go` | **2** |
| `33366475340` | `php` | `Create php.yml` | `Plugin Check + Theme Check (WP 7.1)` | `Theme Check — arena-commerce` | **1** |
| `33366475274` | `quality` | `Create php.yml` | `Static gates (Node 22)` | `G-W6 asset budget` | **1** |
| `33366425948` | `quality` | `Create quality.yml` | `Static gates (Node 22)` | `G-W6 asset budget` | **1** |

Fonte: `gh api repos/RpSystemAg/ArenaTheme/check-runs/<job-id>/annotations`
(le annotazioni, a differenza dei log zippati, sono raggiungibili dall'API).

**Conseguenze a catena (stato non misurato, da rendere misurato):**

* Nel job `phpcs`, il passo **PHPStan livello 6 è sempre `skipped`**: PHPStan non
  è mai stato eseguito nemmeno una volta. Il suo stato è **sconosciuto**.
* Nel job `quality`, il passo **«Deterministic generators are idempotent» è
  sempre `skipped`** in CI (eseguito localmente in questa sessione: **verde**,
  `git diff --exit-code` pulito).
* **Warning trasversale su tutti i job:** «Node.js 20 is deprecated … actions
  being forced to run on Node.js 24: `actions/setup-node@v4`,
  `actions/upload-artifact@v4`».

---

## 3. Diagnosi per errore (con prova)

### E1 — `php.yml › phpcs` — PHPCS exit 2: **160 errori + 186 warning reali**

**Exit code 2 di PHPCS non è un errore di configurazione.** La semantica
ufficiale dei codici di uscita (vedi §9) è: `0` nessun problema · `1` errori o
warning trovati · `2` errori trovati **con almeno uno auto-correggibile** ·
`3` errore di processazione (ruleset/file non valido). Qui è **2**: il ruleset è
stato caricato e ha prodotto violazioni.

**Riproduzione locale fedele** eseguita in questa sessione con PHPCS 3.13.0 +
WPCS 3.4.1 + VIPCS 3.1.0 + WooCommerce-Core 1.0.1 + PHPCompatibilityWP 2.1.8 +
VariableAnalysis 2.13.0 (le stesse famiglie di versioni che `composer install`
risolve in CI), ruleset `phpcs-gw4.xml.dist`, su `theme/arena-commerce` e
`plugin/arena-engine`:

```
A TOTAL OF 160 ERRORS AND 186 WARNINGS WERE FOUND IN 25 FILES
PHPCBF CAN FIX 304 OF THESE SNIFF VIOLATIONS AUTOMATICALLY
__EXITCODE__=2            ← identico a CI
```

Log committati: `tests/proofs/phpcs-local-repro-summary.log` (per file) e
`tests/proofs/phpcs-local-repro-source.log` (per sniff).
*Nota di metodo:* la riproduzione esclude il solo sniff `Generic.PHP.Syntax`
(che esegue `php -l` in un sottoprocesso, non disponibile nel sandbox): è uno
sniff di lint e in CI contribuisce 0 violazioni. I conteggi possono variare di
poche unità se `composer` risolve versioni diverse dei pacchetti: per questo il
WP-2 impone di **rifare la misura in CI e riconciliare**.

**Distribuzione per sniff (prime 10 voci su 27):**

| Sniff | N | Auto-fix |
|---|---|---|
| `WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned` | 133 | ✅ |
| `WordPress.Arrays.ArrayDeclarationSpacing.AssociativeArrayFound` | 70 | ✅ |
| `Generic.Formatting.MultipleStatementAlignment.NotSameWarning` | 30 | ✅ |
| `PEAR.Functions.FunctionCallSignature.MultipleArguments` | 16 | ✅ |
| `WooCommerce.Commenting.CommentHooks.MissingHookComment` | 15 | ❌ |
| `WordPress.Arrays.ArrayDeclarationSpacing.ArrayItemNoNewLine` | 13 | ✅ |
| `Squiz.Commenting.FunctionComment.SpacingAfterParamType` | 12 | ✅ |
| `WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown` | 10 | ❌ |
| `PEAR.Functions.FunctionCallSignature.CloseBracketLine` | 8 | ✅ |
| `PEAR.Functions.FunctionCallSignature.ContentAfterOpenBracket` | 8 | ✅ |

Restanti: `Universal.Operators.DisallowShortTernary` 6 ·
`Squiz.Commenting.BlockComment.NoNewLine` 4 ·
`Universal.WhiteSpace.PrecisionAlignment` 4 ·
`Generic.Formatting.MultipleStatementAlignment.IncorrectWarning` 2 ·
`WordPress.WP.AlternativeFunctions.parse_url` 2 ·
`WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents` 2 ·
e 11 voci singole, fra cui — le uniche **non** puramente formatiche —
`WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound`,
`WordPress.Security.ValidatedSanitizedInput.InputNotSanitized`,
`WordPress.WP.I18n.NonSingularStringLiteralText`,
`Squiz.PHP.EmbeddedPhp.MultipleStatements`,
`Generic.CodeAnalysis.UnusedFunctionParameter`.

**File più impattati:** `includes/admin/class-rest-panel.php` (80/52) ·
`includes/admin/class-panel.php` (20/9) · `inc/class-assets.php` (2/39) ·
`includes/kits/class-importer.php` (12/15) ·
`includes/admin/class-kits-admin.php` (4/0) · `inc/class-mega-menu.php` (6/5) ·
`inc/class-bottom-nav.php` (5/2) · `inc/class-header-slots.php` (5/0) ·
`inc/class-woocommerce.php` (4/0).

**Natura dell'errore:** ≈ 88 % delle violazioni è **formattazione**
`phpcbf`-correggibile. Il resto è debito semantico reale (hook non documentati,
`file_get_contents` su origini remote, `file_put_contents` non ristretto,
input non sanitizzato, stringhe i18n non letterali, hook name non prefissato).

### E2 — `php.yml › extension-check` — **il comando Theme Check non esiste**

Il workflow esegue:

```yaml
- name: Theme Check — arena-commerce
  run: wp theme check arena-commerce --allow-root 2>&1 | tee "…/ci-theme-check.log"
```

**Il comando `wp theme check` non è registrato da WP-CLI.** Il plugin
`WordPress/theme-check` (clonato da `master`) registra un comando di **primo
livello** con nome `theme-check` e il metodo `run`
(`wp-cli/class-theme-check-cli.php`:

```php
WP_CLI::add_command( 'theme-check', 'Theme_Check_Command' );
```

e il README del plugin documenta `wp theme-check run [<theme>] [--format=<format>]`).

Il router di WP-CLI (`WP_CLI\Runner::find_command_to_run()` →
`CompositeCommand::find_subcommand()`) cerca il sottocomando **per nome esatto o
per `@alias`** e **non esegue alcuna equivalenza trattino/spazio**: `theme` +
`check` non risolve, quindi WP-CLI termina con
`Error: 'check' is not a registered subcommand of 'theme'.` ed **exit code 1**.

> **Corollario importante:** il rosso di `Theme Check` **non è un fallimento del
> tema**. Nessun controllo è stato eseguito. Fino alla correzione del comando,
> lo stato di `arena-commerce` rispetto a Theme Check è **non misurato**, e va
> riportato così in ogni report.

**Comando corretto:**

```bash
wp theme-check run arena-commerce --format=table --allow-root
```

**Rischio collaterale da verificare (E2b).** Nello stesso job:

```bash
wp plugin activate plugin-check theme-check woocommerce --all=false --allow-root || true
```

* `--all` è un **flag**, non accetta `=false`, e viene passato **insieme a slug
  posizionali**: forma sospetta, da validare contro la sintassi di
  `wp plugin activate`.
* `|| true` **maschera l'esito**: se il comando fallisce, `theme-check` (e
  `plugin-check`) restano **non attivi**, e quindi `wp theme-check` non
  esisterebbe nemmeno dopo la correzione di E2 (WP-CLI carica i plugin **attivi**).
* Prova da ispezionare: `tests/proofs/ci-wp-extensions.log` (output di
  `wp plugin list`), artefatto `extension-check-proofs`.

Correzione richiesta: attivare esplicitamente i due plugin **senza `|| true`**,
oppure caricare la CLI senza dipendere dall'attivazione:

```bash
wp --require=/tmp/wp/wp-content/plugins/theme-check/wp-cli/class-theme-check-cli.php \
   theme-check run arena-commerce --format=table --allow-root
```

### E3 — `quality.yml › static-gates` — G-W6 asset budget: **12 breach, rosso atteso e documentato**

Riprodotto localmente in questa sessione (identico a `tests/proofs/gw6-budget.log`):

```
  page      CSS raw      JS raw
  home        32237 B     39017 B
  blog        36758 B     39017 B
  shop        40278 B     43693 B
  product     38799 B     40110 B
  cart        35902 B     30120 B
  checkout    38751 B     33478 B

  engine catalog JS: 4395 B      budgets: CSS ≤ 22528 B · JS ≤ 24576 B · engine ≤ 18432 B
  jQuery references: 0 · third-party origins: 0

G-W6 FAIL — 12 breach(es)
```

**Non è un falso positivo e non va silenziato.** La costituzione v4 §11.4 vieta
di degradare un gate numerico in silenzio. `docs/decisions/001-gw6-asset-budget.md`
ha già scelto **l'Alternativa A** (A1 → A4) e ha già respinto, con motivazione
scritta, l'Alternativa B (budget misurato sul trasferito invece che sul raw).
Il piano e i rischi sono già stimati nel documento: **eseguilo**, non ridiscuterlo.

Sintesi degli interventi (dettaglio e stime in `docs/decisions/001`, §3):

| # | Intervento | Risparmio stimato |
|---|---|---|
| A1 | `arena-dark.css` (7 937 B) dietro cookie `arena-scheme` invece di `css_when => always` (`theme/arena-commerce/inc/class-assets.php:96-101`) | 7 937 B CSS/pagina |
| A2 | Split di `arena-cart.js` (12 649 B) in `arena-cart-bus.js` (~4 KB, `is_woo_active`) + `arena-cart-panel.js` (~8,5 KB, caricato alla prima interazione) | ≈ 8 500 B JS |
| A3 | Estrarre da `arena.css` i componenti non first-paint in `modules/arena-components.css` per contesto | ≥ 3 500 B CSS |
| A4 | Convertire `arena-megamenu.css`, `arena-motion.css/js` in script module via Interactivity API + critical CSS inline | ≈ 12 886 B |

A1+A2+A3 non bastano: **serve anche A4** per rientrare nel budget su tutti i sei
contesti misurati.

**Tutti gli altri passi del job `quality` sono verdi** e verificati localmente in
questa sessione: ESLint 0 problemi · `npm run test:quality` 17/17 PASS ·
`node tools/make-pot.mjs --check` POT freschi (105 tema + 169 plugin) ·
generatori idempotenti senza diff.

### E4 — Debito non bloccante: azioni GitHub su runtime Node 20

`actions/setup-node@v4` e `actions/upload-artifact@v4` dichiarano ancora
`runs.using: node20`; GitHub li sta forzando su Node 24 ed emette un warning su
ogni run. Nessun impatto funzionale **oggi**, ma è debito che diventerà errore.
Aggiornare alle major correnti: `actions/upload-artifact` è arrivato a **v6**
(Node 24 nativo, runner ≥ 2.327.1) e `actions/setup-node` ha **v6** (con v7
disponibile da luglio 2026): **verifica tu stesso l'ultima major al momento
dell'esecuzione e pinna quella**, senza usare `@main`.

### E5 — Igiene di pipeline (non bloccante, ma bloccherà domani)

1. **`tools/php/composer.json` non ha `composer.lock` committato**: ogni run
   risolve versioni diverse → la CI non è riproducibile e il conteggio PHPCS
   può cambiare senza che il codice cambi.
2. **`PHPStan` non è mai stato eseguito** in CI (sempre skipped a causa di E1):
   è un gate non misurato, non un gate verde.
3. **Uso di `|| true` e di `--allow-root` diffusi**: ogni `|| true` va giustificato
   o rimosso; è il meccanismo con cui E2b è diventato invisibile.
4. **Nessun `permissions:` dichiarato** nei workflow: GitHub raccomanda
   `permissions: contents: read` come default minimo (least privilege).
5. **Nessuna concorrenza fra job matrix e nessun `timeout-minutes` su singoli
   step** rischiosi (download da internet, `composer install`); i timeout ci sono
   a livello di job, va bene, ma gli step di rete restano privi di retry.
6. **Nessun workflow `e2e.yml`**: G-W5 (Playwright acquisto → thank-you), G-W1
   (Lighthouse), G-W3 (axe runtime) sono «non misurati» e non hanno nemmeno il
   job che potrebbe misurarli.

---

## 4. Definition of Done (accettazione)

La rimediazione si considera **completa** solo se **tutte** le seguenti sono
vere, dimostrate da una run GitHub Actions reale sul branch:

- [ ] **DoD-1** · `quality.yml › static-gates`: tutti gli step verdi, **incluso
      G-W6** (oppure G-W6 verde su una soglia formalmente ri-basata da una
      nuova `docs/decisions/00X-*.md` approvata che esponga entrambe le letture
      e la motivazione tecnica — §11.4).
- [ ] **DoD-2** · `php.yml › phpcs`: PHPCS **0 errori e 0 warning** su **entrambe**
      le versioni della matrice (8.3 e 8.5), con il ruleset `phpcs-gw4.xml.dist`
      **inalterato nelle sue regole** (è ammesso solo intervenire sul codice,
      su `phpcs.xml.dist` di uso quotidiano, o su proprietà di sniff documentate).
- [ ] **DoD-3** · `php.yml › phpcs`: **PHPStan livello 6 eseguito** e verde
      (oggi è `skipped`: non può più esserlo).
- [ ] **DoD-4** · `php.yml › extension-check`: Theme Check **effettivamente
      eseguito** con `wp theme-check run arena-commerce` e il suo esito
      (verde, oppure elenco dei REQUIRED con relativo REQ di fix) riportato nel
      log committato.
- [ ] **DoD-5** · `php.yml › extension-check`: Plugin Check rieseguito e, nel log,
      evidenza che le categorie **runtime** sono state eseguite (non solo le static).
- [ ] **DoD-6** · Zero warning «Node.js 20 is deprecated» in tutte le run.
- [ ] **DoD-7** · `tools/php/composer.lock` committato e CI deterministica.
- [ ] **DoD-8** · Nessun `|| true` ingiustificato; ogni `|| true` residuo ha un
      commento che dice cosa si sta deliberatamente tollerando.
- [ ] **DoD-9** · Ogni work package ha un commit dedicato e un log in
      `tests/proofs/`; `docs/evidence/<data>.md` aggiornato con la tabella
      prima/dopo e i permalink delle run.

---

## 5. Piani di lavoro

Esegui **nell'ordine**. Ogni WP: modifica → verifica → commit con il log.
Non aprire il WP successivo finché il precedente non è verde in CI.

### WP-1 — Igiene e riproducibilità della pipeline (basso rischio, sblocca tutto)

**Obiettivo.** Rimuovere il debito E4/E5 e rendere i log ispezionabili.

1. In `ci/workflows/quality.yml` e `ci/workflows/php.yml`:
   * Aggiorna le action alle major correnti (`actions/checkout`,
     `actions/setup-node`, `actions/upload-artifact`) **dopo aver verificato
     l'ultima major disponibile**; non usare `@main`.
   * Aggiungi in cima a ogni workflow `permissions: contents: read`.
   * Sostituisci `actions/upload-artifact` con nomi di artefatto univoci per job
     (già fatto) e `if-no-files-found: error` **anche** in `php.yml`
     (oggi è `warn`, quindi un artefatto mancante passa inosservato).
   * Rimuovi o giustifica ogni `|| true`.
2. Genera e committa `tools/php/composer.lock`
   (`cd tools/php && composer update --lock` oppure `composer install` dopo
   averlo generato), così la CI smette di risolvere versioni fluttuanti.
3. Verifica: `node ci/install-workflows.mjs` stampa «2 workflow(s) installed» e
   i due `diff` sono puliti.

**DoD:** run senza warning Node 20; `composer.lock` in Git; nessun `|| true`
ingiustificato.

### WP-2 — PHPCS a zero (il lavoro più grande: 346 violazioni, 304 auto-fixabili)

**Ordine di esecuzione obbligatorio:**

1. **Meccanico prima, semantico dopo.** Esegui `phpcbf` con lo stesso ruleset e
   **solo** sugli sniff auto-correggibili, poi rilancia `phpcs` e committa il
   delta. Non mescolare mai, nello stesso commit, `phpcbf` e rifattorizzazioni
   manuali: rende impossibile la review.
   ```bash
   tools/php/vendor/bin/phpcbf --standard=phpcs-gw4.xml.dist \
     --basepath=. --extensions=php theme/arena-commerce plugin/arena-engine
   tools/php/vendor/bin/phpcs  --standard=phpcs-gw4.xml.dist --report=full -s \
     --report-checkstyle=tests/proofs/phpcs-checkstyle.xml
   ```
2. **Allineamento array / chiamate di funzione** (~282 violazioni):
   `WordPress.Arrays.MultipleStatementAlignment` (DoubleArrowNotAligned,
   NotSameWarning, IncorrectWarning) e
   `PEAR.Functions.FunctionCallSignature` (MultipleArguments, CloseBracketLine,
   ContentAfterOpenBracket). Sono quasi interamente risolte da `phpcbf`; dove
   `phpcbf` non arriva, allinea manualmente `=>` e gli argomenti su colonna.
3. **Commenti di documentazione** (`WooCommerce.Commenting.CommentHooks.MissingHookComment`
   15, `Squiz.Commenting.FunctionComment.SpacingAfterParamType` 12,
   `Squiz.Commenting.BlockComment.NoNewLine` 4,
   `Generic.Commenting.DocComment.ShortNotCapital` 1,
   `Squiz.Commenting.FunctionComment.ParamCommentFullStop` 1):
   aggiungi i docblock mancanti seguendo gli standard inline di WordPress;
   ogni `do_action`/`apply_filters` **deve** avere il proprio commento
   (è un requisito WooCommerce, non estetica).
4. **Violazioni semantiche VIP/WP** (da trattare una per una, con giudizio):
   * `WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown` (10):
     sostituisci le `file_get_contents()` su origini remote con l'HTTP API di
     WordPress (`wp_remote_get()` + `wp_remote_retrieve_body()`), con transient
     cache e gestione dell'errore; se un uso è strettamente locale,
     **giustificalo** con un
     `// phpcs:ignore … -- motivo` e la motivazione nel commit.
   * `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents` (2):
     valuta `WP_Filesystem`; se non applicabile, whitelist motivata nel file di
     configurazione PHPCS, **non** nel codice sparso.
   * `WordPress.WP.AlternativeFunctions.parse_url_parse_url` (2): usa
     `wp_parse_url()`.
   * `Universal.Operators.DisallowShortTernary` (6):
     `?:` → `isset() ? … : …` esplicito (i valori `0`/`''`/`null` non sono equivalenti).
   * `WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound` (1):
     prefissa il nome dell'hook con `arena_`/`arena_engine_`.
   * `WordPress.Security.ValidatedSanitizedInput.InputNotSanitized` (1):
     Sanitize → Validate → Escape, nell'ordine.
   * `WordPress.WP.I18n.NonSingularStringLiteralText` (1): la stringa passata
     alle funzioni i18n deve essere un letterale, non una variabile.
   * `Squiz.PHP.EmbeddedPhp.MultipleStatements` (1) e
     `Generic.CodeAnalysis.UnusedFunctionParameter` (1): rifattorizza o
     documenta la decisione.
5. **Blocca la regressione:** `phpcs.xml.dist` (uso quotidiano) e
   `phpcs-gw4.xml.dist` (gate) restano la fonte di verità. Se uno sniff è
   strutturalmente inapplicabile, si configura la **proprietà dello sniff** nel
   ruleset con un commento che spiega il perché — **non** si aggiungono
   `phpcs:ignore` a pioggia nel codice.

**Verifica locale (se PHP è disponibile):** i comandi sopra.
**Verifica in CI:** job `phpcs` verde su 8.3 **e** 8.5.
**DoD:** `0 errors / 0 warnings` e, nel log, la riga di riepilogo che lo attesta.

### WP-3 — Theme Check: correggere il comando (E2)

1. Sostituisci lo step con:
   ```bash
   wp theme-check run arena-commerce --format=table --allow-root 2>&1 \
     | tee "$GITHUB_WORKSPACE/tests/proofs/ci-theme-check.log"
   ```
2. Prima di Theme Check, attiva i plugin **senza `|| true`**:
   ```bash
   wp plugin activate plugin-check --allow-root
   wp plugin activate theme-check  --allow-root
   wp plugin list --allow-root | tee "…/ci-wp-extensions.log"
   ```
   (oppure, alternativa robusta, `--require=…/theme-check/wp-cli/class-theme-check-cli.php`).
3. Esegui e **leggi l'esito**: `wp theme-check run` restituisce `0` solo se
   **tutti** i controlli passano; ogni REQUIRED mancato porta a exit 1
   (`checkbase.php::run_themechecks()` fa `$pass = $pass & $check->check(...)`,
   e la CLI fa `WP_CLI::halt( $success ? 0 : 1 )`).
4. Se emergono REQUIRED: **aprire un REQ per ciascuno**, con il messaggio
   esatto di Theme Check, e fissarli (o motivare formalmente l'eccezione).

**DoD:** il log `ci-theme-check.log` contiene una tabella di risultati reale
(non un errore «not a registered subcommand»), e l'esito è documentato.

### WP-4 — G-W6 asset budget (E3)

Esegui **A1 → A2 → A3 → A4** di `docs/decisions/001-gw6-asset-budget.md`,
un commit ciascuno, rigenerando e committando il log a ogni passo:

```bash
node tests/gw6-budget.test.mjs | tee tests/proofs/gw6-budget.log
```

Vincoli:

* Nessuna nuova origine; nessun jQuery; nessuna dipendenza di terze parti
  (oggi: `jQuery references: 0 · third-party origins: 0` — va mantenuto).
* Ogni passo deve tenere verdi: `npm run test:quality` (17 gate, in particolare
  G15 «Woo bytes gated to Woo templates» e G16 «dark mode reale»),
  `npm run test:axe`, `tests/e2e/dark-mode.spec.js`,
  `tests/e2e/purchase-flow.spec.js` (zero-reload + undo).
* Se dopo A1–A4 il gap residuo resta > 0, **non abbassare la soglia**: apri
  `docs/decisions/002-*` con le due letture (raw vs transferito), i numeri
  misurati, i rischi, e la proposta di ri-baseline, poi applicala solo se
  approvata. §11.4: un gate si cambia **in chiaro**, mai in silenzio.

**DoD:** `node tests/gw6-budget.test.mjs` exit 0 (oppure decisione 002 approvata
e referenziata dal workflow).

### WP-5 — PHPStan livello 6 (oggi non misurato)

Con WP-2 chiuso, il passo PHPStan smette di essere `skipped`. Preparati:

* `phpstan.neon.dist` punta a `tools/php/vendor/php-stubs/wordpress-stubs` e
  `woocommerce-stubs`; `tools/php/phpstan-bootstrap.php` definisce le costanti
  del progetto.
* Al primo verde di PHPCS, esegui PHPStan, raccogli gli errori e aprili come
  work package (livello 6 richiede type hint completi su parametri, proprietà e
  ritorni). Se il debito è grande e motivato, introduzione **controllata** di un
  baseline **con scadenza e piano di smaltimento** — non come via di fuga
  permanente.

**DoD:** PHPStan livello 6 eseguito e verde, o baseline temporaneo con REQ di
smaltimento e data.

### WP-6 — Evidenza, documenti, chiusura REQ

1. Aggiorna `docs/evidence/<data>.md` con: tabella gate prima/dopo, exit code,
   permalink delle run, elenco dei REQ aperti.
2. Aggiorna `docs/ci.md` (oggi dice «G-W6 exit 1» come stato atteso) quando
   G-W6 cambia stato.
3. Aggiorna `docs/certification-report-v3.2.md`: distingui sempre
   **verde misurato** da **non misurato**. Un gate non eseguito non è verde.
4. Proponi (come REQ separato, non in questo giro) un workflow `e2e.yml` per
   G-W1 (Lighthouse), G-W3 (axe runtime light/dark/RTL) e G-W5 (Playwright
   acquisto → thank-you), usando `wp-env` e Playwright.

---

## 6. Regole non negoziabili (guardrail)

1. **Non commentare, non `continue-on-error`, non `|| true`-izzare** uno step
   rosso: la risposta corretta a un rosso è un REQ (§11.4).
2. **Non modificare i file in `.github/workflows/` a mano.** Solo
   `ci/workflows/*.yml` + `node ci/install-workflows.mjs`.
3. **Non aggiungere `phpcs:ignore` a pioggia.** Ogni ignore ha una motivazione
   scritta accanto.
4. **Non introdurre dipendenze runtime** (jQuery, CDN di terze parti): il
   conteggio `third-party origins: 0` è un requisito di prodotto.
5. **Ogni commit porta la sua prova** in `tests/proofs/`.
6. **Non pushare con `--force`**, e non cambiare branch: il branch è
   `arena/01a056e1-arenatheme`.
7. **Dichiara sempre cosa non hai potuto misurare.** «Non misurato» è uno stato
   legittimo e obbligatorio; «verde» senza run non lo è.

---

## 7. Sequenza di commit

```
WP-1  ci(WP-1): action a runtime Node 24, permissions minime, composer.lock
WP-2a style(WP-2): phpcbf — 304 violazioni auto-correggibili
WP-2b docs(WP-2): docblock hook e commenti (WooCommerce.Commenting + Squiz)
WP-2c fix(WP-2): violazioni semantiche VIP/WP (HTTP API, wp_parse_url, sanitize, hook prefix)
WP-3  fix(WP-3): `wp theme-check run` invece di `wp theme check`; attivazione plugin senza || true
WP-4a perf(A1): arena-dark.css dietro cookie arena-scheme
WP-4b perf(A2): split arena-cart.js (bus + panel on interaction)
WP-4c perf(A3): modules/arena-components.css per contesto
WP-4d perf(A4): megamenu/motion come script module Interactivity API
WP-5  fix(WP-5): PHPStan livello 6 verde
WP-6  docs(WP-6): evidence log + certificazione aggiornata
```

Ogni commit: `git push origin arena/01a056e1-arenatheme`, attendi la run,
allega il permalink nel messaggio del commit successivo **se il commit tocca
workflow ricordati la regola 2**.

---

## 8. Verifica finale (da eseguire e allegare)

```bash
# ── Locale (gira ovunque: Node 22) ───────────────────────────────────────────
npm ci --no-audit --no-fund
npx eslint . --format stylish                 # 0 problemi
npm run test:quality                          # 17 gate, exit 0
node tools/make-pot.mjs --check               # POT freschi
node tests/gw6-budget.test.mjs                # exit 0 solo dopo A4
node tools/build-kits.mjs && node tools/build-rtl.mjs && node tools/build-presets.mjs
git diff --exit-code --stat -- plugin theme ':(exclude)*/languages/*.pot'

# ── Locale (serve PHP 8.3 + composer) ────────────────────────────────────────
cd tools/php && composer install --no-interaction --no-progress --prefer-dist
tools/php/vendor/bin/phpcs --standard=phpcs-gw4.xml.dist --report=full -s   # 0/0
tools/php/vendor/bin/phpcbf --standard=phpcs-gw4.xml.dist                   # solo in WP-2a
tools/php/vendor/bin/phpstan analyse -c phpstan.neon.dist --no-progress     # 0 errori

# ── CI (unica fonte di verità) ───────────────────────────────────────────────
gh run list --workflow=php
gh run list --workflow=quality
gh api repos/RpSystemAg/ArenaTheme/check-runs/<job-id>/annotations   # 0 failure
gh run download <run-id>                                             # tests/proofs/*
```

---

## 9. Documentazione ufficiale di riferimento (usala, non improvvisare)

**GitHub Actions**

* Documentazione: <https://docs.github.com/en/actions>
* Sintassi dei workflow: <https://docs.github.com/en/actions/reference/workflow-syntax-for-github-actions>
* Comandi dei workflow (`::error::`, `$GITHUB_STEP_SUMMARY`, mascheramento): <https://docs.github.com/en/actions/reference/workflows-and-actions/workflow-commands>
* Matrici, `fail-fast`, concorrenza: <https://docs.github.com/en/actions/writing-workflows/choosing-what-your-workflow-does/using-a-matrix-for-your-jobs>
* Hardening delle Actions / permessi del token: <https://docs.github.com/en/actions/security-for-github-actions/security-hardening-for-github-actions>
* Permessi dei workflow e impostazioni del repository: <https://docs.github.com/en/repositories/managing-your-repositorys-settings-and-features/enabling-features-for-your-repository/managing-github-actions-settings-for-a-repository>
* Deprecazione di Node 20 sui runner (changelog ufficiale): <https://github.blog/changelog/2025-09-19-deprecation-of-node-20-on-github-actions-runners/>
* `actions/upload-artifact` (v6 = Node 24 nativo): <https://github.com/actions/upload-artifact>
* `actions/setup-node`: <https://github.com/actions/setup-node>
* `actions/checkout`: <https://github.com/actions/checkout>
* `shivammathur/setup-php`: <https://github.com/shivammathur/setup-php>

**PHPCS e standard di codice**

* PHP_CodeSniffer (repo ufficiale, ex `squizlabs`): <https://github.com/PHPCSStandards/PHP_CodeSniffer>
* Utilizzo e **codici di uscita**: <https://github.com/PHPCSStandards/PHP_CodeSniffer/wiki/Usage>
* Correzione automatica (`phpcbf`): <https://github.com/PHPCSStandards/PHP_CodeSniffer/wiki/Fixing-Errors-Automatically>
* Ruleset annotato: <https://github.com/PHPCSStandards/PHP_CodeSniffer/wiki/Annotated-ruleset.xml>
* Proprietà configurabili degli sniff: <https://github.com/PHPCSStandards/PHP_CodeSniffer/wiki/Customisable-Sniff-Properties>
* WordPress Coding Standards (WPCS): <https://github.com/WordPress/WordPress-Coding-Standards> · handbook: <https://developer.wordpress.org/coding-standards/wordpress-coding-standards/>
* Standard inline di documentazione PHP: <https://developer.wordpress.org/coding-standards/wordpress-coding-standards/inline-documentation-standards/php/>
* Automattic VIP Coding Standards: <https://github.com/Automattic/VIP-Coding-Standards>
* WooCommerce Sniffs: <https://github.com/woocommerce/woocommerce-sniffs>
* PHPCompatibilityWP: <https://github.com/PHPCompatibility/PHPCompatibilityWP>
* Sicurezza WordPress (sanitize/validate/escape): <https://developer.wordpress.org/apis/security/>
* Internazionalizzazione: <https://developer.wordpress.org/apis/internationalization/>
* HTTP API (`wp_remote_get`): <https://developer.wordpress.org/apis/http-api/>
* Filesystem API: <https://developer.wordpress.org/apis/filesystem/>
* WooCommerce per sviluppatori: <https://developer.woocommerce.com/docs/>

**Analisi statica e dipendenze PHP**

* PHPStan — guida introduttiva: <https://phpstan.org/user-guide/getting-started>
* PHPStan — riferimento di configurazione: <https://phpstan.org/config-reference>
* PHPStan — baseline (da usare solo con piano di smaltimento): <https://phpstan.org/user-guide/baseline>
* Composer — utilizzo di base: <https://getcomposer.org/doc/01-basic-usage.md>
* Composer — vincoli di versione (`^`): <https://getcomposer.org/doc/articles/versions.md>

**Controlli ufficiali WordPress**

* Plugin Check (repo): <https://github.com/WordPress/plugin-check> · pagina plugin: <https://wordpress.org/plugins/plugin-check/>
* Theme Check (repo, README con la sintassi CLI corretta): <https://github.com/WordPress/theme-check>
* Requisiti obbligatori per i temi (Theme Review Handbook): <https://make.wordpress.org/themes/handbook/review/required/>
* WP-CLI: <https://developer.wordpress.org/cli/> · <https://developer.wordpress.org/cli/commands/theme/>
* WP-CLI Commands Cookbook (registrazione dei comandi): <https://make.wordpress.org/cli/handbook/commands-cookbook/>
* `@wordpress/env` (wp-env): <https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/>

**Performance front-end (serve a WP-4)**

* Riferimento `theme.json`: <https://developer.wordpress.org/block-editor/reference-guides/theme-json-reference/>
* Interactivity API: <https://developer.wordpress.org/block-editor/reference-guides/packages/packages-interactivity/>
* `wp_enqueue_script` e script module: <https://developer.wordpress.org/reference/functions/wp_enqueue_script/>
* Prestazioni per sviluppatori WordPress: <https://developer.wordpress.org/apis/performance/>
* Playwright (per l'e2e da aggiungere): <https://playwright.dev/docs/intro>

---

## 10. Formato del resoconto finale

Restituisci:

1. **Tabella gate** `prima → dopo`, con exit code, comando e log committato.
2. **Elenco delle modifiche per commit**, con il permalink della run che le ha
   validato.
3. **Elenco di ciò che resta «non misurato»**, con il motivo tecnico e il REQ
   che lo sbloccherà (es. G-W1/G-W2/G-W3/G-W5 senza runner browser).
4. **Eventuale proposta di ri-baseline** (solo per G-W6) in forma di
   `docs/decisions/00X-*.md`, con entrambe le letture e i numeri.
5. **Nessuna frase del tipo «tutto verde»** se esiste anche un solo gate non
   eseguito.

---

*Fine del prompt.*
