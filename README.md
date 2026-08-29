# Arena — theme + engine for WordPress 7.1 and WooCommerce 11 (v3.1)

Two installable components that together form the "Arena Commerce" stack:

| Component | Path | Install |
|---|---|---|
| **Arena Commerce** (block theme) | `theme/arena-commerce/` | `dist/arena-commerce.zip` → Appearance → Themes → Add New → Upload |
| **Arena Engine** (plugin) | `plugin/arena-engine/` | `dist/arena-engine.zip` → Plugins → Add New → Upload |
| **Suite bundle** | `dist/arena-suite.zip` | contains both + install notes (WP installs theme and plugin separately) |

The theme is a full-site-editing theme with a design-token `theme.json`, 19 templates, 58
patterns (14 families) and the WordPress 7.1 Icons API. The plugin is theme-independent and
works with any theme; everything it does is switchable and reversible under
**Admin → Arena** (kits, presets, typography, layout, journal) and
**Settings → Arena Engine**.

## What's in the box

- **Performance** — single preloaded stylesheet and one deferred, dependency-free script; no
  web fonts, no jQuery, no third-party origins; Speculation Rules tuned; `wp-embed`, jQuery
  Migrate, emoji and oEmbed discovery stripped; LCP image never lazy-loaded.
- **Accessibility** — WCAG 2.2 AA palette and targets (44 px), skip links, live region,
  labelled landmarks, full `prefers-reduced-motion` / `prefers-contrast` / `forced-colors`
  support, 0 axe-core violations across the front page, single post, 404 and search.
- **Commerce** — Baymard-informed checkout (collapsed secondary fields, correct `autocomplete`
  tokens, guest checkout on, cost transparency above Place order), reassurance under
  add-to-cart, full WooCommerce templates (shop, product, cart, checkout, account, order).
- **Security** — conservative headers, XML-RPC/pingbacks off, generator hidden, `?ver=`
  de-identified; CSP is offered via a filter because core's inline skip-link script would
  otherwise break under a strict policy.
- **Motion** — `arena/carousel` and `arena/reveal` blocks rendered server-side and enhanced
  through the Interactivity API as script modules; nothing animates without consent.
- **Mobile-first (Arena Prime H2/H3)** — fixed bottom navigation on ≤600px (64px + safe area,
  5 items, animated active indicator, hide-on-scroll-down / reappear-on-scroll-up), 44px touch
  targets, live-region announcements. Desktop keeps the classic header.
- **Pattern library (H9/H15)** — 14 families (12 commercial × ≥4 structurally distinct
  patterns + Blog/Footer), each pattern with its own grid, hierarchy, density and module.
- **Abilities** — `arena-engine/performance-report` and `arena-engine/accessibility-audit`
  registered through the WordPress 7.1 Abilities API for automation and AI agents.

## What's new in v3.1 (Arena Prime expansion)

- **12 starter kits (H19–H23)** — complete one-click sites (home + 6 pages, menu, demo
  content, own preset, en_US + it_IT, campaign SVGs in 3 ratios) in `plugin/arena-engine/kits/`;
  selective import with progress bar, explicit overwrite confirm, full undo and versioned
  REST sync (`arena/v1`).
- **Arena panel (H31/H32, AP9)** — presets, typography (per-level + separate mobile/desktop
  scales), layout and per-page meta box: every action journaled with previous state, undo and
  a generated documentation link (`Admin → Arena → Journal`).
- **8 presets (H40)** — palette + type pairing + radius + density as tracked style variations,
  each with an inverted dark twin (H47).
- **Real dark mode (H47/H48)** — `data-theme` root attribute, persistence without reload,
  toggle in the header **and** the bottom nav, WCAG 2.2 AA in both schemes.
- **Commerce depth (H33–H37)** — ajax add-to-cart + drawer + stepper + removal undo + sticky
  mobile cart bar, load-more/infinite scroll with saved preference, off-canvas filters,
  quick view, native product gallery (zero libs), distraction-free checkout, wishlist/compare
  and cache-plugin adapters that load only when the target plugin is active.
- **Blog system (H38/H39)** — layout swaps via template parts (grid/list/fullwidth/masonry),
  configurable post-meta order, post-format patterns, author box, related posts.
- **RTL (H41)** — generated `-rtl.css` twins for the global sheet and 8 modules with
  `is_rtl()` conditional enqueue; **i18n (H42)** — committed POTs, `tools/make-pot.mjs`,
  WPML/Polylang adapters, bilingual kits.
- **SEO (H43/H44)** — server-side JSON-LD `@graph` per template; the theme yields entirely
  to Yoast/Rank Math when active. **Performance (H45/H46)** — per-context module enqueues
  (zero WooCommerce bytes on non-Woo pages) and WP Rocket/LiteSpeed compatibility.
- **Docs & starter (H49/H50)** — `docs/utente/` (one annotated article per feature,
  H1–H50 mapped, no orphans) and a child-theme starter that extends through tokens and hooks.
- **Gates G8–G17** — all green as static contracts in `npm run test:quality`; runtime proofs
  are committed Playwright specs (`tests/e2e/`) for reproduction on a real wp-env.

## Arena Prime release artifacts

- `variation-matrix.csv` — H6 matrix for 58 patterns + 19 templates.
- `tests/anti-clone.mjs` — H7 scripted 40% structural-overlap gate (`npm run test:anti-clone`).
- `kit-campagna/` — H17 campaign kit: 9:16 stories, 1:1 feed, 16:9 display (each starter kit
  also ships its own 3-ratio SVG campaign set, H21).
- `tests/g8-kits.test.mjs` … `tests/g17-child-starter.test.mjs` — v3.1 gates (kits,
  purchase-flow proxy, RTL, i18n, panel undo, presets, JSON-LD, assets, dark, child starter).
- `tests/e2e/*.spec.js` — committed real-run proofs (kit import + undo, zero-reload purchase
  flow, axe light+dark, decoupled assets network log, JSON-LD graphs).
- `tools/` — build-dists, build-kits, build-presets, build-rtl, build-doc-diagrams, make-pot.
- `docs/utente/` — user guide, one page per feature (H49); `docs/dev/` — REST/i18n/hooks API.
- `theme/arena-commerce-child/` — child-theme starter (H50).

## Certification

See [`docs/certification-report.md`](docs/certification-report.md) for the real test results
on WordPress 7.1 / PHP 8.5.8: 0 axe-core violations, Theme Check **PASS**, PHPCS
`WordPress-Extra` + `PHPCompatibility 7.4-` and `WordPress-VIP-Go` at 0 errors / 0 warnings,
and the official Plugin Check PHPCS ruleset clean. The report also states plainly which
checks could not run in this sandbox (Lighthouse / browser capture — no usable browser).

## Layout

```
theme/arena-commerce/        block theme (theme.json, templates/, patterns/, parts/, inc/, assets/)
theme/arena-commerce-child/  child-theme starter (H50)
plugin/arena-engine/         plugin (includes/, blocks/, assets/, kits/ — 12 starter kits)
dist/                        installable zips
docs/                        certification report, compliance table, user guide (utente/), dev API docs
tests/                       static gates (v2.0 + G8–G17) + e2e specs
tools/                       deterministic generators (kits, presets, rtl, pot, dists, diagrams)
```

## Development notes

- No build step is required at runtime; CSS/JS are hand-tuned and versioned by `Version:`.
- PHP must pass `WordPress-VIP-Go` while staying installable on ordinary WordPress: the code
  guards every VIP-restricted call so a non-VIP install simply skips the enhancement.
- The test harness lives outside the repo snapshot in `.cache/arena-lab/` (685 MB of tooling)
  and is documented in the certification report.
