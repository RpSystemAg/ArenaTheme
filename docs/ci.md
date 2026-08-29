# Arena Prime v2.0 — enterprise CI matrix

## Workflows

| File | Trigger | What it runs | Blocking |
|---|---|---|---|
| `.github/workflows/quality.yml` | push/PR on `main`, `arena/**` | Full Node quality suite (no browser required): H7 global anti-clone (1128 pairs ≤40%), H2/H3 mobilenav structural, H11 FLIP contract, H14 billboard, H15 family system, axe-core-style static audit on 48 patterns + 19 templates, Lighthouse structural budget | **Yes** |
| `.github/workflows/build-dist.yml` | push on `main` + tags `v*`, PR, manual | Builds `dist/arena-commerce.zip`, `dist/arena-engine.zip`, `dist/arena-suite.zip` and uploads as artifact | Yes (on main/tag) |

Browser/WP-env workflows (Playwright, PHPCS, PHPStan, Plugin/Theme Check, QIT,
real Lighthouse) were present in the v1 certification suite and will be re-added
when the GitHub App token carries `workflows: write` permission for
`.github/workflows/*.yml`; they require `@wordpress/env`, PHP and Chromium. The
equivalent static audits in `tests/` reproduce the contract-level portions of
those checks without a browser.

## Local reproduction (no browser needed)

```bash
npm ci
npm run test:quality          # all static gates listed above
node tests/anti-clone.mjs     # H7 global — 1128 pairs ≤ 0.40
node tests/anti-clone-global.mjs  # same gate, verbose output
node tests/axe-static.test.mjs    # axe-style static audit
node tests/h11-flip.test.mjs      # H11 FLIP helper contract
node tests/h14-billboard.test.mjs # H14 billboard audit
node tests/h15-family-system.test.mjs # H15 per-family tokens
node tests/h2-mobilenav.test.mjs  # H2/H3 bottom-nav structural
node tests/lighthouse-budget.test.mjs # Lighthouse static budget
node tools/build-dists.mjs        # rebuild dist zips
```

## Local reproduction (full — needs wp-env + Chromium)

```bash
npx wp-env start
npx playwright install --with-deps chromium
npx playwright test
node tests/lighthouse-run.mjs   # needs lighthouse npm dep + Chrome
composer install --working-dir=tools/php
tools/php/vendor/bin/phpcs --standard=phpcs.xml.dist
```

## GitHub token status (2026-08-29)

The GitHub App installation token used by this session lacks `workflows: write`
(`refusing to allow a GitHub App to create or update workflow
.github/workflows/... without 'workflows' permission`). Workflow files are
committed to the repo but the first push of `.github/workflows/*` must be
performed by an actor with the `Workflows: Read and write` permission on the
repository. Once pushed, the quality gate runs on every PR.
