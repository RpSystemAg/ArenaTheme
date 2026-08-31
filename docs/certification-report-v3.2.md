# Arena World Class Enterprise — certification report v3.2 (intermedio)

**Data:** 2026-08-31 (kickoff mandato rev. 2, WS-K giorno 1)
**Branch:** `arena/01a05686-arenatheme`
**Commit base:** `a6409b1` (merge PR #6)
**Stato:** 🟡 CI installata e verificata localmente, in attesa di push da actor con permesso Workflows. **Nessun gate viene dichiarato verde senza una run linkata.**

## 1. Matrice dei gate — situazione reale

Legenda: ✅ verde con prova committata / 🟡 verde in sandbox, non ancora su runner / ❌ rosso / ⏳ non misurato / ⛔ bloccato da permessi

### Gate World Class (G-W1..G-W8) — eredità mandato v1

| Gate | Nome | Stato | Prova / Note |
|---|---|---|---|
| G-W1 | Lighthouse CI browser reale (p90 mobile) | ⏳ | Nessun Chromium nel sandbox; workflow quality.yml ha lo step ma deve ancora girare su runner |
| G-W2 | RUM pubblico 28 giorni (LCP ≤1.8s / INP ≤150ms / CLS ≤0.05) | ⏳ | Beacon non installato (WS-K3) |
| G-W3 | axe funnel light+dark+RTL (0 violazioni) | ⏳ | Necessita Playwright+Chromium; gira su runner dopo push |
| G-W4 | PHPCS matrice 8.3/8.5 (VIP-Go + Woo-Core + Core/Docs + PHPCompatWP) + PHPStan lv6 + Plugin Check + Theme Check WP 7.1/MySQL | ⏳ | php.yml installato; sandbox non ha PHP |
| G-W5 | Playwright E2E acquisto→thank-you | ⏳ | Necessita browser + wp-env |
| G-W6 | Asset budget per pagina (CSS ≤22 KB, JS ≤24 KB, engine ≤18 KB raw) | ❌ | 12 breach (CSS peggiore +17.7 KB, JS peggiore +19.1 KB). Documentato in `docs/decisions/001`, piano A1→A4. Lo step è **lasciato blocking** nel workflow, non silenziato. Log: `tests/proofs/gw6-budget.log` |
| G-W7 | (a.k.a. anti-clone H7) | ✅ | 1653 coppie, worst 0.344 ≤0.40 |
| G-W8 | POT freschi + domini i18n puliti | ✅ | 105+169 stringhe; log `tests/proofs/ci-pot-check.log` |

### Gate Enterprise (G-E1..G-E8) — rev. 2

| Gate | Nome | Stato | Nota |
|---|---|---|---|
| G-E1 | CI viva (≥3 run verdi consecutive su main, badge README) | ⛔ | Workflow installati in `.github/workflows/`, push bloccato da token. Vedi `docs/ops/k1-unblock-ci.md`. |
| G-E2 | Gate runtime eseguiti (G-W1/G-W3/G-W4/G-W5 con log linkati) | ⏳ | Dipende da G-E1 |
| G-E3 | RUM 28 giorni, p75 LCP/INP/CLS, dashboard pubblica | ⏳ | Settimana 3+ |
| G-E4 | Canale WordPress.org (tema+plugin, 10 pattern, translate.w.org) | ⏳ | WS-G, settimane 3–6 |
| G-E5 | Arena One subscription + Stripe E2E + refund 30gg | ⏳ | WS-H, settimane 7–12 |
| G-E6 | Arena Blocks v1 (12 blocchi) | ⏳ | WS-I, settimane 7–12 |
| G-E7 | Head-to-head vs Astra/GP+GB/Blocksy, 8/8 vinto o pareggiato | ⏳ | WS-L, settimane 13–16 |
| G-E8 | Scala (30 kit, preview cloud, en/it + es/de/fr ≥98%) | ⏳ | WS-L |

## 2. Gate statici (verdi oggi in sandbox, ma non in CI)

Comando: `npm ci && npm run test:quality`

| # | Gate | Esito |
|---|---|---|
| 1 | anti-clone (1653 coppie ≤0.40) | ✅ |
| 2 | axe static (96 artifact) | ✅ |
| 3 | H14 billboard (11 pattern) | ✅ |
| 4 | H15 family system (14 famiglie × token) | ✅ |
| 5 | H11 FLIP | ✅ |
| 6 | H2/H3 mobile bottom nav | ✅ |
| 7 | Lighthouse budget strutturale | ✅ |
| 8 | G8 kits (12 kit, worst sim 0.125) | ✅ |
| 9 | G9 purchase flow (static proxy) | ✅ |
| 10 | G10 RTL (8 moduli + globale, is_rtl) | ✅ |
| 11 | G11 i18n (POT, domini, adattatori) | ✅ |
| 12 | G12 panel undo (journal + revert) | ✅ |
| 13 | G13 presets (8 preset, dark twin) | ✅ |
| 14 | G14 JSON-LD (@graph per template) | ✅ |
| 15 | G15 assets (Woo per-contesto) | ✅ |
| 16 | G16 dark mode (data-theme, persistenza) | ✅ |
| 17 | G17 child starter (hook + token) | ✅ |

Comando: `npx eslint .` → **0 errori, 0 warnings**.
Comando: `node tools/make-pot.mjs --check` → **POT freschi**.
Comando: `node tools/build-kits.mjs && node tools/build-rtl.mjs && node tools/build-presets.mjs && git diff --exit-code` → **idempotente**.

## 3. Numeri verificati (31/08/2026)

| Metrica | Valore |
|---|---|
| Pattern | 58 in 14 famiglie |
| Template | 19 |
| Starter kit | 12 (7 pagine + 4–5 prodotti, bilingui en/it) |
| Preset | 8 (7 generati + `midnight` dark-first) |
| Stringhe i18n | 105 (tema) + 169 (plugin) |
| CSS globale `arena.css` | 15 249 B raw / 4 461 B gzip |
| JS globale `arena.js` | 14 457 B raw / 4 505 B gzip |
| Engine catalog JS | 4 395 B (arena-engine.js + arena-interactivity.js) |
| jQuery / terze parti sul front-end | 0 / 0 |
| Fluid font sizes in theme.json | 15 |

## 4. Decisioni aperte

- **001** — G-W6 asset budget: alternativa A (A1→A4) scelta, non ancora eseguita. G-W6 rimane rosso e bloccante.
- **002** — Split free/Pro: non ancora redatta. Obbligatoria prima di qualunque codice di licensing (§03.5).

## 5. Prove disponibili in `tests/proofs/`

| File | Contenuto |
|---|---|
| `ci-eslint.log` | ESLint run del 31/08 |
| `ci-test-quality.log` | 17 gate del 31/08 |
| `ci-pot-check.log` | POT freshness 31/08 |
| `gw6-budget.log` | G-W6 12 breach |
| `ci-token-blocker.log` | Blocco permessi originale (28/08) |
| `ci-token-blocker-k1.log` | Blocco permessi replicato sul commit f8990c5 |
| `anti-clone.log` | Run dettagliata anti-clone |
| `ci-quality-rehearsal.log` | Rehearsal pre-push |

## 6. Cosa manca per dichiarare la CI viva (G-E1)

1. **Azione umana** (30 s, una tantum): sequenza in `docs/ops/k1-unblock-ci.md`.
2. Dopo il push: aprire `https://github.com/RpSystemAg/ArenaTheme/actions` e
   verificare che `quality` e `php` partano.
3. Linkare i permalink delle prime tre run verdi in questo report e
   aggiornare i badge in `README.md` (i placeholder sono già in cima).
4. Creare un PR da `arena/01a05686-arenatheme` → `main` e far girare la CI
   sulla PR; a merge i badge punteranno a `main`.

## 7. Dichiarazione di onestà

Non esistono run verdi su GitHub Actions per questo repository alla data
del 31/08/2026. Nessun badge in README punta a un URL funzionante finché
l'azione K1 non è completata da un attore con permesso Workflows. Ogni
numero in questo report è prodotto da uno script committato e
riproducibile; i numeri non ancora misurati sono scritti come ⏳, non come
verdi.
