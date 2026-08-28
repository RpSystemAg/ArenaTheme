# Arena Commerce — certification report

Run against a live **WordPress 7.1** install (tag `7.1`, `$wp_version = '7.1'`) on **PHP 8.5.8**
via `@php-wasm/node`, and against the real rendered HTML of four core templates
(front page, single post, 404, search).

Every number below is reproduced from the machine-readable artifacts in
`.cache/arena-lab/out/` (`integration-report.json`, `axe-report.json`,
`theme-check.json`, `theme-check.txt`) and is re-runnable with the scripts in
`.cache/arena-lab/bin/`.

## 1. Integration (real WordPress 7.1)

| Check | Result |
|---|---|
| Theme active / version | `arena-commerce` / `1.0.0` |
| Plugin active | `arena-engine` yes |
| Block references scanned | **550** across **34** template/pattern files |
| Unknown core blocks | **0** |
| WooCommerce blocks (resolve only with Woo active) | 64 |
| `arena/carousel`, `arena/reveal` registered | yes |
| Icons API `wp_get_icon('arena/cart')` | renders, `role="img"` + `aria-label`, `aria-hidden` when decorative |
| Abilities API registered | `arena-engine/performance-report`, `arena-engine/accessibility-audit` |
| Global CSS | 30 070 B with `@media (width <= 600px)`, `@media (600px < width <= 960px)`, `:focus-visible`, `current-menu-item` |

## 2. Accessibility — axe-core 4.x

Rule tags: `wcag2a`, `wcag2aa`, `wcag21a`, `wcag21aa`, `wcag22aa`, `best-practice`,
run on the rendered HTML via jsdom.

| Page | passes | violations |
|---|---|---|
| front-page | 40 | **0** |
| single-post | 43 | **0** |
| not-found | 39 | **0** |
| search | 39 | **0** |

Real fixes made while chasing this: heading order (`trust-bar` h3→h2, search card h3→h2),
duplicate landmark names (distinct `aria-label` on footer menus + search forms via a
`render_block` filter), invalid `<ul>` nesting (explicit `navigation-link` items instead of
the `page-list` fallback inside `core/navigation`), duplicate skip links (plugin now defers
to `arena_theme_skip_links_printed`).

## 3. Static analysis

| Tool / ruleset | Scope | Errors | Warnings |
|---|---|---|---|
| PHPCS `WordPress-Extra` + `PHPCompatibility` (testVersion 7.4-) | theme + plugin | **0** | **0** |
| PHPCS `WordPress-VIP-Go` | theme + plugin | **0** | **0** |
| Plugin Check official ruleset (`plugin-check.ruleset.xml`) | plugin | **0** | **0** |
| Theme Check (full suite) | theme | **PASS** | 2 INFO |

The two Theme Check INFO notes are: a reminder that the `accessibility-ready` tag implies a
manual accessibility review, and confirmation that a single text-domain matches the slug.

## 4. Performance budget

| Asset | raw | gzip |
|---|---|---|
| `assets/css/arena.css` | 12 491 B | ~3.6 KB |
| `assets/js/arena.js` | 8 958 B | ~2.8 KB |
| `arena-engine` css | 2 117 B | <1 KB |
| `arena-engine` js | 2 386 B | <1 KB |

Zero web fonts, zero jQuery, zero third-party origins; global styles are inlined by core so
there is no render-blocking stylesheet beyond the single preloaded theme CSS. The plugin's
interactivity ships as a WordPress script module loaded only when a block uses it.

## 5. Things that could NOT be run in this sandbox (honest gaps)

* **Lighthouse / browser-captured screenshot.** There is no usable browser: Chromium's shared
  libraries cannot be installed because the Debian mirror is unreachable from this sandbox
  (`deb.debian.org` times out). The theme's `screenshot.png` is therefore a **design mock**
  rendered from the theme's real tokens and copy via satori → SVG → resvg
  (`lighthouse/make-screenshot.mjs`), not a browser capture. Lighthouse numbers were not
  fabricated; instead the measurable proxies above (asset weight, no web fonts, no
  render-blocking scripts) are reported.
* **Plugin Check runtime checks.** The WP-CLI entrypoint of Plugin Check needs a native PHP
  binary; the sandbox only has WASM PHP, so only the official Plugin Check *PHPCS* ruleset
  (its static category) was executed.

Both gaps are environment limitations, not skipped work; the scripts that would close them
(`render-page.php`, `serve.mjs`, `screenshot.mjs`, `make-screenshot.mjs`) are committed so a
machine with a browser can reproduce the remaining checks.
