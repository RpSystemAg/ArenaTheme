# K1 — Sblocco CI: sequenza per l'actor con permesso Workflows

**Data:** 2026-08-31
**REQ:** WS-K / REQ-K1
**Stato:** preparato dall'agente, attende attore umano con permesso `Workflows: Read and write`
**Prova del blocco:** `tests/proofs/ci-token-blocker-k1.log`

## Contesto

Il token GitHub App usato dalla sessione sandbox non ha il permesso `workflows`.
Tentando di pushare un commit che tocca `.github/workflows/*`, GitHub rifiuta:

```
! [remote rejected] arena/01a05686-arenatheme -> arena/01a05686-arenatheme
  (refusing to allow a GitHub App to create or update workflow
   `.github/workflows/php.yml` without `workflows` permission)
```

Il commit `f8990c5` sul branch `arena/01a05686-arenatheme` contiene già
`.github/workflows/quality.yml` e `.github/workflows/php.yml`, copiati
byte-per-byte da `ci/workflows/` tramite `node ci/install-workflows.mjs`.
Il push di questo commit è fermo sul sandbox.

## Sequenza esatta da eseguire (umano, 30 secondi)

Prerequisito: accesso a `github.com/RpSystemAg/ArenaTheme` come utente o App
con permesso **Workflows: Read and write** (settings repo → Actions → General
→ Workflow permissions, oppure PAT con scope `workflow`).

```bash
# 1. Prendi il branch della sessione
git clone https://github.com/RpSystemAg/ArenaTheme.git
cd ArenaTheme
git checkout arena/01a05686-arenatheme
git pull origin arena/01a05686-arenatheme

# 2. Verifica che i workflow siano presenti e integri
node ci/install-workflows.mjs     # deve stampare 2 ✓ e "2 workflow(s) installed"
diff ci/workflows/quality.yml .github/workflows/quality.yml && echo OK
diff ci/workflows/php.yml     .github/workflows/php.yml     && echo OK

# 3. Commit (se non già presente — il commit f8990c5 lo contiene) e push
git add .github
git status                        # deve mostrare i 2 file nuovi/modificati
git commit -m "ci(REQ-K1): activate workflows — needs Workflows permission"
git push origin arena/01a05686-arenatheme
```

## 4. Verifica dopo il push

1. Apri https://github.com/RpSystemAg/ArenaTheme/actions — devono comparire
   due workflow: **quality** e **php**.
2. Apri la run di `quality` sul branch. Atteso sul primo giro:
   - **ESLint** ✅
   - **Quality suite (17 gates)** ✅
   - **POT freshness** ✅
   - **G-W6 asset budget** ❌ (12 breach documentati in `docs/decisions/001`)
   - **Generatori idempotenti** ✅
   Il rosso su G-W6 è atteso e documentato: non è un falso positivo.
3. Apri la run di `php`. Atteso:
   - Job `phpcs` (matrice PHP 8.3 + 8.5) — eseguirà PHPCS WordPress-VIP-Go
     + WooCommerce-Core + PHPStan lv6.
   - Job `extension-check` — installerà WP 7.1 + MySQL, WooCommerce, copierà
     `plugin/arena-engine` e `theme/arena-commerce`, lancerà Plugin Check
     (static + runtime) e Theme Check.
4. Copia i permalink delle tre run (quality, phpcs-8.3, extension-check)
   nel prossimo evidence log `docs/evidence/2026-09-04.md` (venerdì) e
   aggiorna `docs/certification-report.md`.

## 5. Se il push viene rifiutato di nuovo

- Controlla che l'utente/App abbia effettivamente `Workflows: Read and write`
  (non basta `Contents: Read and write`).
- Se sul repo è attivo il branch protection su `main`, il push va fatto su
  `arena/01a05686-arenatheme` e poi aperta una PR verso `main` — la CI girerà
  sulla PR. Il badge si può esporre solo quando i workflow sono nel branch
  di default, quindi la PR verso `main` è il passo successivo dopo il verde
  sul branch arena.

## 6. Cosa NON fare

- Non editare i file `.github/workflows/*.yml` a mano. Qualsiasi modifica
  va fatta in `ci/workflows/*.yml` e poi propagata con
  `node ci/install-workflows.mjs`.
- Non silenziare G-W6 commentando lo step. Vedi §11.4: i gate non si
  degradano in silenzio. La risposta corretta a un rosso è un REQ.
- Non pushare con `--force`. La regola «token senza permesso = sicurezza,
  non burocrazia» si applica anche all'umano: il permesso Workflows va
  dato intenzionalmente.
