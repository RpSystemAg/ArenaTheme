# 001 — G-W6 asset budget vs. the v3.1 always-on module set

**Date:** 2026-08-29
**Status:** OPEN — escalated per constitution §11.4 (a REQ collides with a numeric gate)
**Opened by:** measurement, not opinion — `node tests/gw6-budget.test.mjs`
**Affects:** G-W6 (CSS ≤ 22 KB · JS tema ≤ 24 KB · JS engine ≤ 18 KB, raw, per page)

## 1. The collision

G-W6 in the v4 constitution says *"CSS tema ≤ 22KB raw (inclusi stili checkout
blocks), JS tema ≤ 24KB raw, JS engine ≤ 18KB sulle pagine catalogo"*. The v3.1
report states the stack already ships "CSS 15.9 KB raw / JS 17.2 KB raw totali",
and the constitution preamble calls the 33 KB budget a moat.

Both statements measure only the **global** sheet and script. Measuring what a
browser actually downloads per page context — the module sets resolved from
`theme/arena-commerce/inc/class-assets.php::modules()`, which is the shipping
source of truth — gives:

```
$ node tests/gw6-budget.test.mjs

  page      CSS raw      JS raw       CSS files
  home        32237 B     39045 B    arena.css + arena-dark.css + arena-motion.css + arena-megamenu.css + arena-search.css
  blog        36758 B     39045 B    arena.css + arena-dark.css + arena-motion.css + arena-blog.css + arena-megamenu.css + arena-search.css
  shop        40278 B     43721 B    arena.css + arena-dark.css + arena-motion.css + arena-commerce.css + arena-megamenu.css + arena-search.css + arena-woocommerce.css
  product     38799 B     40138 B    arena.css + arena-dark.css + arena-motion.css + arena-commerce.css + arena-megamenu.css + arena-woocommerce.css
  cart        35902 B     30148 B    arena.css + arena-dark.css + arena-commerce.css + arena-megamenu.css + arena-woocommerce.css
  checkout    38751 B     33506 B    arena.css + arena-dark.css + arena-commerce.css + arena-megamenu.css + arena-checkout.css + arena-woocommerce.css

  engine catalog JS: 4395 B (arena-engine.js + arena-interactivity.js)
  budgets: CSS ≤ 22528 B · JS ≤ 24576 B · engine ≤ 18432 B
  jQuery references: 0 · third-party origins: 0 (across 32 front-end files)

G-W6 FAIL — 12 breach(es)
```

Verified parts of the DNA that **do** hold: 0 jQuery references and 0
third-party origins across all 32 shipped front-end files, and engine catalog JS
at 4 395 B (≤ 18 KB, the loosest of the three).

The two sub-budgets that fail:

| Leg | Worst case | Gate | Gap |
|---|---|---|---|
| CSS per page | 40 278 B (`/shop`) | 22 528 B | **+17 750 B** |
| JS per page | 43 721 B (`/shop`) | 24 576 B | **+19 145 B** |
| Engine catalog JS | 4 395 B | 18 432 B | −14 037 B ✅ |

### Where the bytes are

| File | Raw | Loaded when |
|---|---|---|
| `arena.css` | 15 249 B | every page (core) |
| `modules/arena-dark.css` | 7 937 B | **every page** (`css_when => always`) |
| `arena.js` | 14 457 B | every page (core) |
| `modules/arena-cart.js` | 12 649 B | **every page while Woo is active** (`js_when => is_woo_active`) |
| `modules/arena-megamenu.css` | 4 675 B | header contains a mega menu |
| `modules/arena-motion.js` | 5 314 B | carousel/marquee markup |
| `modules/arena-shop.js` | 4 676 B | shop / product / taxonomy |

Two lines alone are worth 20 586 B on pages that never need them:

1. `arena-dark.css` is enqueued unconditionally (`'css_when' => 'always'`,
   `class-assets.php:96-101`). A visitor who never switches scheme downloads
   7 937 B of dark rules on every page.
2. `arena-cart.js` is enqueued on `class_exists( 'WooCommerce' )`
   (`class-assets.php:345-347`, condition `is_woo_active`), so a blog post or a
   landing page on any Woo-active store ships 12 649 B of cart/drawer/stepper
   code.

## 2. Why this is escalated and not silently re-based

`tests/lighthouse-budget.test.mjs` (v2.0) asserts CSS ≤ 32 KB / JS ≤ 18 KB for
the global sheet only and passes. It would have been trivial to keep pointing
G-W6 at that same proxy and declare the gate green. That would have been a
silent degradation of a numeric gate, which §11.4 forbids. The measured gate is
therefore committed **blocking**, and this document records the shortfall.

## 3. Two alternatives

### Alternative A — shrink the shipped set (recommended, keeps the gate intact)

Two structural changes, both already idiomatic in this codebase:

| # | Change | Bytes saved on light/first-visit pages | Risk | Verification |
|---|---|---|---|---|
| A1 | Gate `arena-dark.css` behind a `arena-scheme` cookie written by the existing toggle, and keep shipping it whenever the cookie is absent or `dark`. Returning light visitors stop downloading it. | 7 937 B CSS on every page for returning visitors | Low — no state is possible where `data-theme="dark"` renders without the sheet | `tests/e2e/dark-mode.spec.js` + new cookie assertion |
| A2 | Split `arena-cart.js` into `arena-cart-bus.js` (ajax add-to-cart + header count, ~4 KB, keeps `is_woo_active`) and `arena-cart-panel.js` (drawer, stepper, removal undo, sticky bar, ~8.5 KB, loaded on first cart interaction — the pattern the registry already uses for `modules/arena-cart.css`, whose URL is printed in the runtime config). | ≈ 8 500 B JS on home/blog/shop | Medium — first interaction must capture, load and replay the click | `tests/e2e/purchase-flow.spec.js` (zero-reload + undo) |
| A3 | Move non-first-paint components (forms, tables, block-library overrides, print) out of `arena.css` into a `modules/arena-components.css` loaded per context. | target ≥ 3 500 B CSS | Medium — global-sheet refactor, needs visual regression pass | axe funnel + Lighthouse CLS |

Projected worst case after A1+A2: `/shop` CSS 32 341 B, JS 35 221 B; after A3
`/shop` CSS ≈ 28 800 B, JS ≈ 35 200 B. **A1–A3 alone do not reach G-W6.** The
residual ≈ 6 600 B CSS / 10 600 JS gap needs A4.

| # | Change | Bytes | Note |
|---|---|---|---|
| A4 | Convert the two remaining always-on modules (`arena-megamenu.css` 4 675 B, `arena-motion.css` 2 897 B + `arena-motion.js` 5 314 B) to Interactivity-API-driven script modules, and inline the ≤ 2 KB of critical CSS they need above the fold. | ≈ 12 886 B | Reaches G-W6 on every measured context; requires the WP 7.1 Interactivity API only, no new origin |

### Alternative B — re-state G-W6 as a per-page *transport* budget

Measure the same set after Brotli on the wire (the theme ships `arena.css` at
4 461 B gzip today) and hold 22 KB **transferred** rather than raw. Today's
`/shop` set is ≈ 11 KB gzip, so the gate would be green immediately with no code
change.

**Rejected as the primary reading.** "raw" is what the constitution writes, a
transport budget hides regressions behind a compressor setting the theme does
not control, and it would be a re-definition of the gate rather than a fix.
Recorded here only so the choice is auditable.

## 4. Decision taken now

- **Alternative A**, executed in numbered steps A1 → A4, one commit each, each
  one re-running `tests/gw6-budget.test.mjs` and committing the new log to
  `tests/proofs/gw6-budget.log`.
- Until A4 lands, `G-W6` is reported **RED** in `docs/certification-report-v3.2.md`
  with the numbers above. No badge, README line or report sentence claims it.
- The blocking step stays in `.github/workflows/quality.yml`, so the gap is
  visible on every run instead of being tracked in prose.

## 5. Reproduce

```bash
node tests/gw6-budget.test.mjs   # exit 1 until A4 lands; prints every file and byte
```
