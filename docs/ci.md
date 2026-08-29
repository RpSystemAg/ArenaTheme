# Arena Prime v3.1 — enterprise CI matrix

## Workflows

| File | Trigger | What it runs | Blocking |
|---|---|---|---|
| `.github/workflows/quality.yml` | push/PR on `main`, `arena/**` | Full Node quality suite (no browser required): v2.0 gates (H7 global anti-clone, H2/H3 mobilenav, H11 FLIP, H14 billboard, H15 family system, axe-style static audit, Lighthouse structural budget) **plus** the v3.1 gates G8–G17 (kits, purchase-flow static proxy, RTL, i18n/pot, panel undo, presets, JSON-LD, decoupled assets, dark mode, child starter) and the POT freshness check (`tools/make-pot.mjs --check`) | **Yes** |
| `.github/workflows/build-dist.yml` | push on `main` + tags `v*`, PR, manual | Builds `dist/arena-commerce.zip`, `dist/arena-engine.zip`, `dist/arena-suite.zip` and uploads as artifact | Yes (on main/tag) |

Browser/WP-env workflows (Playwright, PHPCS, PHPStan, Plugin/Theme Check,
QIT, real Lighthouse) were present in the v1 certification suite and will be
re-added when the GitHub App token carries `workflows: write` permission for
`.github/workflows/*.yml`; they require `@wordpress/env`, PHP and Chromium. The
equivalent static audits in `tests/` reproduce the contract-level portions of
those checks without a browser.

## Release checklist (v3.1 — i18n is part of CI, G11)

1. `node tools/make-pot.mjs` — regenerate both POTs and **commit them**
   (the `--check` mode fails CI when the committed pots are stale).
2. `node tools/build-kits.mjs` — regenerate the 12 kits (deterministic).
3. `node tools/build-rtl.mjs` — regenerate the RTL sheets after CSS edits.
4. `node tools/build-presets.mjs` — regenerate preset JSONs after palette edits.
5. `node tools/build-doc-diagrams.mjs` — regenerate doc wireframes.
6. `npm run test:quality` — all gates below must pass.
7. `node tools/build-dists.mjs` — dist zips.

## Local reproduction (no browser needed)

```bash
npm ci
npm run test:quality          # all static gates (v2.0 + G8–G17)
node tests/anti-clone.mjs     # H7 global — all pairs ≤ 0.40
node tests/g8-kits.test.mjs   # 12 kits: manifests, AP8 distinctness, campaigns
node tests/g9-purchase-flow.test.mjs  # no-reload architecture proxy
node tests/g10-rtl.test.mjs   # RTL twins + is_rtl() + real tag
node tests/g11-i18n.test.mjs  # pots fresh, domains, adapters, kit locales
node tests/g12-panel-undo.test.mjs    # journal/undo/docs contract
node tests/g13-presets.test.mjs       # 8 presets, pairings, budget
node tests/g14-jsonld.test.mjs        # one @graph per template, SEO yield
node tests/g15-assets.test.mjs        # Woo bytes gated, CSS budget
node tests/g16-dark.test.mjs          # data-theme, twins, toggles
node tests/g17-child-starter.test.mjs # child starter safety
node tools/make-pot.mjs --check       # POT freshness (CI mode)
node tools/build-dists.mjs    # rebuild dist zips
```

## Local reproduction (full — needs wp-env + Chromium)

```bash
npx wp-env start
npx playwright install --with-deps chromium
npx playwright test
# committed real-run evidence specs:
npx playwright test tests/e2e/kit-import.spec.js      # G8  import <60s + undo
npx playwright test tests/e2e/purchase-flow.spec.js   # G9  zero navigations
npx playwright test tests/e2e/dark-mode.spec.js       # G16 axe light+dark
npx playwright test tests/e2e/assets-decoupled.spec.js # G15 network log
npx playwright test tests/e2e/jsonld.spec.js          # G14 graphs per template
node tests/lighthouse-run.mjs   # needs lighthouse npm dep + Chrome
php tools/php/rich-results-check.php   # G14: extract + submit graphs
composer install --working-dir=tools/php
tools/php/vendor/bin/phpcs --standard=phpcs.xml.dist
```

## Sandbox honesty declaration (inherited from v2.0 AP5)

The certification sandbox has no Chromium and no PHP runtime. Gates that
require a browser or a WP runtime keep, by policy:

- a **deterministic static proxy** in `tests/*.test.mjs` (runs everywhere,
  keeps the architecture honest between releases), and
- a **committed real-run script/spec** in `tests/e2e/` + `tools/php/` for
  reproduction on a real environment.

No network log, Lighthouse score, axe report or screenshot is ever
fabricated. Where a screenshot is impossible, `docs/utente/diagrammi/*.svg`
are clearly-labelled wireframe diagrams.

## GitHub token status (2026-08-29)

The GitHub App installation token used by this session lacks `workflows:
write` (`refusing to allow a GitHub App to create or update workflow
.github/workflows/... without 'workflows' permission`). Workflow files are
committed to the repo but the first push of `.github/workflows/*` must be
performed by an actor with the `Workflows: Read and write` permission on the
repository. Once pushed, the quality gate runs on every PR.
