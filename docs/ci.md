# Arena Commerce — enterprise CI matrix

> **Publication blocker (2026-08-28).** The GitHub App token connected to the
> sandbox does not have the `Workflows: Read and write` permission, so GitHub
> refuses to push `.github/workflows/*`. The five workflow files below are
> fully written, locally validated, and present in the repo checkout
> (`git status` shows them untracked under `.github/`), but they could not be
> pushed by this session. PR #2 deliberately carries the reproducible configs,
> tests and docs so the gates can be merged independently; when a token with
> `workflows: write` is available, add `.github/workflows/` and the matrix will
> turn green.

This repository ships a local, blocking GitHub Actions suite for every push or
pull request touching `main` or `arena/**`.

## Workflows

| Workflow | Trigger | What it runs | Blocking |
|---|---|---|---|
| `static-analysis.yml` | push / PR on `main`, `arena/**` | `php-lint` (PHP 7.4→8.4), `phpcs` (WordPress-Core + WordPress-Docs + PHPCompatibilityWP on the repo, WooCommerce-Core on `plugin/arena-engine`), `phpstan` (level 6 + `johnbillion/wp-compat`, `requiresAtLeast: 6.9`), `eslint` (WordPress + WooCommerce, jQuery banned), `abilities` (`tests/php/abilities-contract-check.php`) | Yes |
| `plugin-check.yml` | push / PR on `main`, `arena/**` | wp-env on WordPress 7.1 + WooCommerce 11, official Plugin Check, `wp plugin check arena-engine` | Yes |
| `theme-check.yml` | push / PR on `main`, `arena/**` | wp-env on WordPress 7.1, `wp theme activate arena-commerce`, official Theme Check, `wp theme-check run arena-commerce` | Yes |
| `e2e.yml` | push / PR on `main`, `arena/**` | wp-env + Playwright at 360×800 on PHP 7.4→8.4, `tests/e2e/front.spec.js` | Yes |
| `qit.yml` | push / PR on `main`, `arena/**` | WooCommerce QIT compatibility. Gated on `secrets.WCCOM_USERNAME`/`WCCOM_CONSUMER_TOKEN`/`WCCOM_CONSUMER_SECRET`; skipped (never blocking) when credentials are absent | Only when credentials exist |

## Local reproduction

```bash
npm ci
npx wp-env start
npx wp-env run cli wp plugin check arena-engine
npx wp-env run cli wp theme-check run arena-commerce
npx playwright install --with-deps chromium
npx playwright test

php tests/php/abilities-contract-check.php
composer install --working-dir=tools/php
tools/php/vendor/bin/phpcs --standard=phpcs.xml.dist
tools/php/vendor/bin/phpcs --standard=plugin/arena-engine/phpcs-woo.xml
tools/php/vendor/bin/phpstan analyse --configuration=phpstan.neon.dist --no-progress --memory-limit=1G
```

## Hard requirements enforced by CI

- No jQuery anywhere in runtime JavaScript (ESLint and the Playwright smoke test).
- No web fonts or third-party font origins (Playwright smoke test).
- PHP 7.4+ syntax/lint across theme, plugin and tooling (PHP matrix).
- WordPress `WordPress-Core` / `WordPress-Docs` / `PHPCompatibilityWP` clean,
  and WooCommerce-Core clean on the plugin.
- WordPress 7.1 + WooCommerce 11 through `@wordpress/env`.
- Abilities API registry contract: `wp_abilities_api_init`, output schema,
  orthogonal permission/execute callbacks, readonly and non-destructive
  annotations.

## H7 anti-clone workflow

`.github/workflows/anti-clone.yml` runs on every push/PR to `main` and
`arena/**`:

| Job | Command | Blocking |
|---|---|---|
| `anti-clone` | `node tests/anti-clone.mjs` | Yes |

Local reproduction:

```bash
npm run test:anti-clone
```

The workflow fails when any *within-family* pair of the 48 patterns exceeds
the 40% structural-overlap ceiling (H9 family reading). The global
"ogni coppia" reading is documented as an explicit AP7 conflict in
[`docs/compliance-table.md`](compliance-table.md).
