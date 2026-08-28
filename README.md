# Arena — theme + engine for WordPress 7.1 and WooCommerce 11

Two installable components that together form the "Arena Commerce" stack:

| Component | Path | Install |
|---|---|---|
| **Arena Commerce** (block theme) | `theme/arena-commerce/` | `dist/arena-commerce.zip` → Appearance → Themes → Add New → Upload |
| **Arena Engine** (plugin) | `plugin/arena-engine/` | `dist/arena-engine.zip` → Plugins → Add New → Upload |
| **Suite bundle** | `dist/arena-suite.zip` | contains both + install notes (WP installs theme and plugin separately) |

The theme is a full-site-editing theme with a design-token `theme.json`, 19 templates, 11
patterns and the WordPress 7.1 Icons API. The plugin is theme-independent and works with any
theme; everything it does is switchable and reversible under **Settings → Arena Engine**.

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
- **Abilities** — `arena-engine/performance-report` and `arena-engine/accessibility-audit`
  registered through the WordPress 7.1 Abilities API for automation and AI agents.

## Certification

See [`docs/certification-report.md`](docs/certification-report.md) for the real test results
on WordPress 7.1 / PHP 8.5.8: 0 axe-core violations, Theme Check **PASS**, PHPCS
`WordPress-Extra` + `PHPCompatibility 7.4-` and `WordPress-VIP-Go` at 0 errors / 0 warnings,
and the official Plugin Check PHPCS ruleset clean. The report also states plainly which
checks could not run in this sandbox (Lighthouse / browser capture — no usable browser).

## Layout

```
theme/arena-commerce/      block theme (theme.json, templates/, patterns/, parts/, inc/, assets/)
plugin/arena-engine/       plugin (includes/, blocks/carousel, blocks/reveal, assets/)
dist/                      installable zips
docs/                      certification report
```

## Development notes

- No build step is required at runtime; CSS/JS are hand-tuned and versioned by `Version:`.
- PHP must pass `WordPress-VIP-Go` while staying installable on ordinary WordPress: the code
  guards every VIP-restricted call so a non-VIP install simply skips the enhancement.
- The test harness lives outside the repo snapshot in `.cache/arena-lab/` (685 MB of tooling)
  and is documented in the certification report.
