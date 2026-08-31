# Arena Prime v3.2 — CI: stato reale, non dichiarato

Ultima verifica: **2026-08-29**, branch `arena/01a04d66-arenatheme`,
commit `c5b8336`. Ogni affermazione qui sotto ha un comando o un log in
`tests/proofs/`.

## 1. Il blocco: i workflow non sono pushabili da questa sessione

Il token dell'App GitHub non ha il permesso `workflows`. Verificato, non
ipotizzato — `tests/proofs/ci-token-blocker.log`:

```
$ git push origin arena/01a04d66-arenatheme
 ! [remote rejected] HEAD -> arena/01a04d66-arenatheme (refusing to allow a
   GitHub App to create or update workflow `.github/workflows/php.yml`
   without `workflows` permission)
error: failed to push some refs to 'https://github.com/RpSystemAg/ArenaTheme.git'
exit=1
```

Conseguenza: **non esiste alcuna run di GitHub Actions per questo repo** e
nessun badge può essere esposto. Chiunque scriva il contrario sta inventando.

Il report v2.0 aveva lasciato i workflow fuori da Git ("i file esistono nel
working tree"): `git log --all -- .github` era vuoto, cioè nel repo non
c'era niente. Ora sono versionati in un percorso che il token *può* scrivere.

## 2. Attivazione (un comando, da un actor con `Workflows: Read and write`)

```bash
node ci/install-workflows.mjs          # copia ci/workflows/*.yml → .github/workflows/ e verifica byte-per-byte
git add .github && git commit -m "ci: activate workflows" && git push
```

`ci/install-workflows.mjs` non modifica lo YAML: copia e confronta. Verificato
localmente — entrambi i file si installano e si parsano.

## 3. Che cosa contiene ciascun workflow

| File | Job | Che cosa esegue | Verificato qui? |
|---|---|---|---|
| `ci/workflows/quality.yml` | `static-gates` | ESLint · i 17 gate di `npm run test:quality` · `make-pot.mjs --check` · **gate G-W6** · idempotenza dei generatori (`build-kits`/`build-rtl`/`build-presets` + `git diff --exit-code`) | **Sì**, passo per passo: `tests/proofs/ci-quality-rehearsal.log` |
| `ci/workflows/php.yml` | `phpcs` (matrice PHP 8.3 + 8.5) | PHPCS con `phpcs-gw4.xml.dist` (WordPress-VIP-Go + WooCommerce-Core + Core/Docs + PHPCompatibilityWP) · PHPStan livello 6 | **No** — nel sandbox non esiste PHP |
| `ci/workflows/php.yml` | `extension-check` | WordPress 7.1 reale + MySQL · Plugin Check ufficiale (static **e** runtime) su `arena-engine` · Theme Check su `arena-commerce` | **No** — né PHP né Docker |

Il passo "generatori idempotenti" è nuovo e ha già dimostrato il suo valore:
escludendo i POT (che riscrivono il `POT-Creation-Date` a ogni run) prende
esattamente la classe di bug committata in `arena-dark-rtl.css`, vecchio di 18
righe rispetto al proprio generatore.

## 4. Che cosa NON può girare in questo sandbox

Verificato, non presunto:

| Limite | Comando che lo dimostra |
|---|---|
| Nessun PHP | `command -v php` → vuoto; `apt-get install php-cli` → `Unable to locate package` |
| Nessuna rete verso i repo Debian | `sudo apt-get update` → `Failed to fetch http://deb.debian.org/debian/dists/bookworm/InRelease — Connection failed` |
| Nessun Docker (niente wp-env) | `command -v docker` → vuoto |
| Nessun browser | nessuna binaria Chromium/Chrome/Firefox; `cdn.playwright.dev` non raggiungibile |

Quindi **G-W1 (Lighthouse), G-W2 (RUM), G-W3 (axe runtime), G-W5 (Playwright)
e G-W4 (PHPCS/Plugin Check) non sono misurati in questa sessione.** Non sono
"verdi": sono non misurati. La differenza è riportata così com'è nel report di
certificazione.

## 5. Riproduzione locale (ciò che gira ovunque)

```bash
npm ci
npx eslint . --format stylish      # 0 problemi
npm run test:quality               # 17 gate, exit 0
node tools/make-pot.mjs --check    # POT freschi
node tests/gw6-budget.test.mjs     # G-W6: exit 1 — docs/decisions/001-gw6-asset-budget.md
node tests/anti-clone.mjs          # H7: 1653 coppie, peggio 0.344 ≤ 0.40
node tests/anti-clone.mjs --pair footer-3-columns footer-5-columns   # diagnostica di una coppia
```

## 6. Release checklist

1. `node tools/make-pot.mjs` — rigenera i POT e **committali**.
2. `node tools/build-kits.mjs` · `build-rtl.mjs` · `build-presets.mjs` ·
   `build-doc-diagrams.mjs`.
3. `npm run test:quality` — tutti i gate verdi.
4. `node tests/gw6-budget.test.mjs` — finché è rosso, il REQ che tocca gli
   asset non è chiudibile (vedi `docs/decisions/001-gw6-asset-budget.md`).
5. `node tools/build-dists.mjs` — zip in `dist/`.
