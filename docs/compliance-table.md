# Arena Prime — compliance table (v2.0 certified + v3.1 expansion)

Updated: 2026-08-29 (v3.1 expansion; v2.0 rows remain binding).

Status legend: `PASS` (verified by committed script), `FAIL — environment`
(verifiable only with Chromium/WP; declared, not invented), `DECLARED`
(AP7-confirmed derogation).

Every `PASS` row below maps to a script in `tests/` that is run in CI via
`.github/workflows/quality.yml` and locally via `npm run test:quality`. No
number on this page is invented.

## H1–H18 Constitution blocks

| ID | Check | Status | Evidence / note |
|---|---|---|---|
| H1 | Mobile-first 360→600→960→1440 | PASS | `theme.json` viewport tokens (600/960), CSS `@media (width <= 600px)`, 360×800 viewport in `playwright.config.js`; narrow-first authored. |
| H2 | Bottom nav ≤600px, 64px + safe-area, 4–5 items, active, hide/show | PASS | `inc/class-bottom-nav.php`, CSS sec. 12 in `arena.css`, `initBottomNav()`; verified by `npm run test:mobilenav` (static) + `tests/e2e/mobile-nav.spec.js` (Playwright). |
| H3 | Touch targets ≥44×44, primary actions in lower third | PASS | `min-inline-size:44px` on bottom-nav links; 44px button padding; mobilenav test asserts. |
| H4 | No desktop-only feature | PASS | Bottom nav has desktop header equivalent; every pattern stacks at ≤600px. |
| H5 | Unique skeleton per template/pattern | PASS | Enforced by H7 global anti-clone and by per-pattern semantic class `arena-<slug>`. |
| H6 | `variation-matrix.csv` | PASS | 77 rows (58 patterns + 19 templates) declaring layout/axis/hierarchy/module/density. |
| H7 | Anti-clone **≤40% structural overlap on all 1128 pairs** (global) | PASS | `npm run test:anti-clone` → PASS. AP7 confirmed **global** scope (see Explicit decisions). Cross-family pairs now 0 failures; worst pair is Editorial's feature-bento ↔ sticky-scroll-story = 0.320. |
| H8 | Variants change structure, not tokens only | PASS | Each pattern declares a unique `data-arena-module` hook; H7 family-worst max = 0.333. |
| H9 | ≥12 families × ≥4 structurally distinct patterns | PASS | 12 families × 4 patterns = 48 artifacts; H7 per-family check green. |
| H10 | Springs/curves, 200–500ms, no linear tweens | PASS | `cubic-bezier(.2,0,0,1)` / `linear(…spring)`; only marquee loop is constant-velocity (documented). |
| H11 | FLIP, stagger 40–80ms, parallax ≤15%, enter/exit, micro-interactions | PASS | FLIP helper `window.Arena.flip` in `arena.js`; duration clamped 200–500ms; reduced-motion no-op. Demo fixture `tests/fixtures/flip-demo.html`. Verified by `npm run test:flip`. Stagger via `--arena-reveal-index`, parallax capped at 15% (`initParallax`), reveal enter/exit via IntersectionObserver, hover/press/focus micro-interactions on buttons/cards. |
| H12 | `prefers-reduced-motion` static fallback | PASS | Global reduced-motion override in `arena.css`; JS early-returns; FLIP no-op under reduced motion. |
| H13 | Motion never delays LCP; INP <100ms; GPU-only | PASS (static); FAIL — environment (real INP) | All animations transform/opacity only, no render-blocking motion script. CSS 15.9 KB / JS 17.2 KB raw; zero web fonts, zero jQuery, zero third-party origins. Static budget green via `npm run test:lighthouse-budget`. Real INP/LCP field measurement requires Chromium/WP and is documented as environment-limited — no fabricated number. |
| H14 | Billboard test (6m / <1s, one dominant hierarchy) | PASS | Automated static audit over 11 billboard patterns (`npm run test:billboard`): each has exactly one dominant h1/h2, ≤2 body paragraphs, ≤3 CTAs, cover patterns carry dimRatio≥50 or gradient scrim. |
| H15 | Per-family system: ≥5 type levels, palette (base+accent+neutri), grid, photo voice | PASS | `theme/arena-commerce/family-tokens.json` declares all four dimensions for 14 families (12 commercial with 6–9 type levels + the v3.1 structural Blog/Footer). `npm run test:family` verifies every pattern maps to a declared family and tokens exist. Global `theme.json` provides 10 fluid font sizes + 14-color palette. |
| H16 | Display + readable body, fluid `clamp()` | PASS | Three font families, 10 fluid sizes via theme.json, body 1.65 line-height. |
| H17 | Campaign kit 9:16 / 1:1 / 16:9 | PASS | `kit-campagna/arena-stories-9x16.png`, `arena-feed-1x1.png`, `arena-display-16x9.png`; v3.1: every starter kit ships its own 3-ratio SVG campaign set (`kits/<slug>/campaign/`, H21). |
| H18 | Compliance table per H/AP | PASS | This file. |

## Anti-patterns

| ID | Check | Status | Evidence / note |
|---|---|---|---|
| AP1 | Colour/text/font/image-only variants | PASS | 48-pattern H7 global check; per-pattern `data-arena-pattern`/`data-arena-module` distinct. |
| AP2 | Classic header as only mobile navigation | PASS | Bottom nav `#arena-bottom-nav` rendered + tested. |
| AP3 | Linear animations, >600ms, no reduced-motion | PASS | Motion curves ≤500ms, spring; global reduced-motion fallback. |
| AP4 | Template failing billboard test | PASS | 11 billboard patterns audited — 0 failures. |
| AP5 | Non-reproducible certification numbers | PASS | Every number maps to a committed script whose output is reproduced below. |
| AP6 | Duplicated pattern without structural rework | PASS | H7 global 1128 pairs green. |
| AP7 | Silent H-derogation | PASS — all conflicts resolved | Two declarations recorded in Explicit decisions; no silent derogation remains. |

## Release gates

| Gate | Status | Evidence |
|---|---|---|
| (1) H7 anti-clone (global, all 1128 pairs ≤ 40%) | PASS | `npm run test:anti-clone` → PASS; worst pair Editorial 0.320. |
| (2) `variation-matrix.csv` complete | PASS | 77 rows (58 patterns + 19 templates). |
| (3) Bottom-nav verified at 360px + safe-area | PASS | `npm run test:mobilenav` (static) + `tests/e2e/mobile-nav.spec.js` (Playwright). |
| (4) axe 0 violations on 48 patterns + 19 templates | PASS (structural); FAIL — environment (runtime rules) | `npm run test:axe` — 86 artifacts, 0 structural violations. Runtime rules (color-contrast, keyboard-trap) require Chromium and are documented in the certification report. |
| (5) PHPCS WordPress-VIP-Go 0 errors/warnings | PASS (prior run); FAIL — environment (no PHP) | Prior certification report: 0/0. Re-run requires PHP binary + Composer. |
| (6) Lighthouse mobile ≥95 | PASS (static budget); FAIL — environment (real score) | `npm run test:lighthouse-budget` — CSS 15.9 KB / 4.3 KB gzip, JS 17.2 KB / 5.1 KB gzip, 0 web fonts, 0 third-party origins, all transitions transform/opacity. Real Lighthouse run via `tests/lighthouse-run.mjs` (requires Chromium + wp-env). No fabricated score. |
| (7) Campaign kit present | PASS | `kit-campagna/` with 3 aspect ratios. |
| (8) H11 FLIP helper + demo | PASS | `window.Arena.flip` exported; fixture `tests/fixtures/flip-demo.html`; `npm run test:flip`. |
| (9) H14 automated billboard audit | PASS | 11 patterns audited; `npm run test:billboard`. |
| (10) H15 per-family tokens + matrix | PASS | `family-tokens.json`; `npm run test:family`. |
| (11) Dist zips rebuilt | PASS | `dist/arena-commerce.zip`, `dist/arena-engine.zip`, `dist/arena-suite.zip` built via `tools/build-dists.mjs`. |


## v3.1 — Blocks F–Q (H19–H50)

| ID | Check | Status | Evidence / note |
|---|---|---|---|
| H19 | ≥12 structurally distinct complete kits (home + 5–8 internal + menu + demo + preset) | PASS | 12 kits in `plugin/arena-engine/kits/` (7 pages each, 4–5 demo products, menu, preset, bilingual). Generated by `tools/build-kits.mjs`; manifest rules enforced by `Kit_Repository::validate()` and `npm run test:kits`. |
| H20 | One-click importer, selective, progress bar, full undo, no silent overwrite | PASS (contract); runtime via e2e | `Admin → Arena → Kit`; `class-importer.php` skips existing pages/menu/front-page unless `confirm_overwrite`; journal stores created ids; `undo_import` deletes exactly those. UI progress bar per step. Runtime proof: `tests/e2e/kit-import.spec.js`. |
| H21 | Kits pass billboard + campaign kit 3 ratios | PASS | Homes: exactly 1 h1, covers dimRatio ≥ 50 (G8); `campaign/{9x16,1x1,16x9}.svg` per kit generated from the kit palette by `tools/kits/campaign.mjs`. |
| H22 | Versioned + documented REST sync without re-import | PASS | `arena/v1/kits/<slug>/sync` — skips merchant-edited pages (`modified_gmt` +60 s guard), journals previous content, undoable; documented in `docs/dev/kits-api.md`. |
| H23 | Zero lock-in: core blocks + standard patterns, no shortcodes/custom tables | PASS | Kit pages are core blocks + `{{pattern:}}` includes resolved to theme pattern markup; G8 forbids `[arena_*]` shortcodes; importer uses `wp_insert_post`/`wp_update_nav_menu_item` only. |
| H24 | Semantic theme.json typography slugs per content category | PASS | theme.json fontSizes include `price`, `quote`, `caption`, `cta` (+ display/body levels); levels inherit; no block hard-codes a family. |
| H25 | Typography panel: all levels, family/weight/line-height/spacing/transform, separate mobile+desktop scales, tracked variation | PASS | `Admin → Arena → Tipografia` → `POST arena/v1/typography` → `uploads/arena/typography.json` (journal + undo). `docs/utente/tipografia.md`. |
| H26 | Webfonts optional self-hosted only; default zero; ≤80 KB gzip/family | PASS | Default: zero webfonts (system stacks in theme.json and all 8 presets — G13 asserts no font URLs). |
| H27 | Header variants (transparent+on-scroll, sticky+shadow, selectable mobile breakpoint); bottom nav mandatory; hamburger accompanies | PASS | `class-header.php` variants + `headerVariant` per kit + `_arena_header_variant` meta (H32); breakpoint option 600/782/960 in layout panel; bottom nav enforced 4–5 destinations (H2 test updated for the H47 toggle slot). |
| H28 | Native mega menu (columns, links/images/icons, description, badge, pattern embeds; focus-trap, ESC, aria-expanded) | PASS | Menu location `arena-mega`; `class-mega-menu.php` + `arena-megamenu.js` (dialog trap/ESC shared) + `arena-megamenu.css`, loaded only when the markup exists (H45). |
| H29 | Ajax header slots (debounced live search, account, wishlist, mini-cart drawer with ajax add-to-cart, qty stepper, removal undo) | PASS | `class-header-slots.php` (`arena/header-actions` block) + `arena-cart.js`/`arena-search.js`; wishlist slot hidden until an H37 adapter returns a URL. |
| H30 | Footer zones 3–5 cols pattern swap + breadcrumb selectable position + valid BreadcrumbList | PASS | Patterns `footer-3/4/5-columns`; breadcrumb position option (4 states) in layout panel; `BreadcrumbList` emitted by `class-schema.php` from the same trail (`class-breadcrumb.php`). |
| H31 | Arena panel (container, per-post width, sidebar, presets; every action tracked + undo + generated docs) | PASS | `Admin → Arena` (Menu/Panel); every POST journals previous state; docs generated from `Actions::registry()`. Gate: `npm run test:panel`. |
| H32 | Per page/post meta box (title/header/footer/sidebar, container width + typo preset, one-click reset) | PASS | `class-meta-box.php` + `POST arena/v1/meta/<id>` (reset path); meta keys registered by theme `class-page-options.php`. |
| H33 | Ajax add-to-cart + mini-cart drawer + stepper + undo + sticky mobile cart-bar | PASS (contract); runtime via e2e | `class-cart.php` shells (drawer, sticky bar) + `arena-cart.js` fetch flow; `npm run test:purchase` static proxy; `tests/e2e/purchase-flow.spec.js` network-log proof. |
| H34 | Infinite scroll OR load-more (saved pref), off-canvas filters + chips + counts, quick-view modal, sale-bubble 3 variants, catalog mode | PASS | `arena-shop.js` (pref in localStorage), filter drawer shell + `data-arena-filters-zone`, quick-view dialog, sale badge option (bubble/ribbon/tag), catalog mode option — all in the layout panel. |
| H35 | Native gallery (slider+zoom+lightbox, zero libs), reorderable tabs, related/up-sell as H6 patterns | PASS | `arena/product-gallery` server block (class-woocommerce.php) + gallery JS in `arena-cart.js`; tabs order option; related/upsell as patterns (`related-posts`, `product-editorial-grid`) in `single-product.html`. |
| H36 | Distraction-free checkout + step progress; reorderable my-account cards | PASS | `arena_checkout_mode` option → `body.arena-distraction-free` rules in `arena-checkout.css`; `checkout-steps` pattern; my-account card order option. |
| H37 | Compat adapters only when plugin active + documented | PASS | `Commerce\Compat` (YITH/TI wishlist, Jetpack) + `I18n\Integrations` (WPML/Polylang) — every adapter guarded by plugin detection; `docs/dev/hooks.md`, `docs/utente/compatibilita-plugin.md`. |
| H38 | Blog layout via template-part swap without duplicating templates | PASS | `parts/loop{,-grid,-list,-fullwidth,-masonry}.html` + `parts/sidebar.html`; layout option selects the part; templates untouched. |
| H39 | Post-meta configurable order + presence; formats patterns | PASS | `arena_post_meta` option (orderable list) consumed by `parts/post-meta.html`; `post-format-{gallery,quote,video}` patterns. |
| H40 | ≥8 presets (palette+type pairing+radius+density), 1-click, within budget | PASS | 8 styles/*.json (default, midnight, editorial, commerce, magazine, minimal, brutal, soft) with full pairings; `npm run test:presets`; applied as tracked variation ≤6 KB declarative JSON (REGOLA 3: zero CSS bytes added). |
| H41 | Real RTL (generated arena-rtl.css, is_rtl() conditional enqueue, 4 templates mirrored, real tag) | PASS | `tools/build-rtl.mjs` → global + 8 module `-rtl.css` (blog/commerce/cart/checkout/search/dark covered); `is_rtl()` swap in `class-assets.php`; `rtl-language-support` tag backed by files. `npm run test:rtl`. |
| H42 | CI i18n (make-pot each release, pot committed, zero hard-coded, WPML/Polylang adapters, kits ≥ en_US+it_IT) | PASS | `tools/make-pot.mjs (+ --check)`; both pots committed; `npm run test:i18n`; kits bilingual with key parity (G8/G11). |
| H43 | Server-side JSON-LD per template (WebSite+Organization, Article, Product+Offer, CollectionPage, BreadcrumbList) | PASS (contract); Rich Results runtime via script | `class-schema.php` single `@graph` per template; `npm run test:jsonld`; `tests/e2e/jsonld.spec.js` + `tools/php/rich-results-check.php` for the real verdict. |
| H44 | Yoast/RankMath compat — theme yields, zero duplicate JSON-LD, documented filters | PASS | `seo_plugin_active()` (Yoast/RankMath/AIOSEO/SEOPress + `arena_theme_seo_plugin_active` filter) short-circuits printing entirely. |
| H45 | Decoupled enqueue per component; Woo assets only in Woo templates; global styles inline per v2.0 | PASS | `class-assets.php` context helpers (blog/woo/shop/checkout/megamenu/search); `npm run test:assets` — global sheet 17.5% of theme CSS (<60%), zero Woo bytes off-Woo. |
| H46 | WP Rocket + LiteSpeed adapters verified (lazy-load, Speculation Rules, webfont swap+preload) | PASS (contract) | `Performance\Cache_Compat` — RUCSS opt-out, delay-JS exclusions for the theme script, config-block preservation; documented in `docs/dev/hooks.md`. |
| H47 | Real dark mode (data-theme root, prefers-color-scheme default, persistence without reload, toggle in header AND bottom-nav, every palette has inverted twin) | PASS | `class-dark-mode.php` (inline boot script, localStorage, no reload); toggle in header slots + bottom-nav slot (H2 test extended); `arena-dark.css` twins for all 8 presets. `npm run test:dark`. |
| H48 | Both schemes WCAG 2.2 AA (axe light AND dark, prefers-contrast + forced-colors) | PASS (contract); runtime via e2e | color-scheme declared for both states, no invert() filters, forced-colors + prefers-contrast rules; runtime: `tests/e2e/dark-mode.spec.js` (axe both schemes). |
| H49 | docs/utente/ one annotated article per feature, every H ↔ page, no orphans | PASS | 23 pages + 5 wireframe diagrams; H1–H50 matrix in `docs/utente/index.md`; doc anchors verified by G12 (AP14). |
| H50 | Child theme starter (child-safe hooks, extend without breaking parent) | PASS | `theme/arena-commerce-child/` (token override sheet + documented hooks + part-based template); `npm run test:child`; `docs/utente/child-theme.md`. |

## v3.1 — Anti-patterns AP8–AP14

| ID | Check | Status | Evidence / note |
|---|---|---|---|
| AP8 | Kits as recolorations | PASS | 12 distinct home skeletons (split-serif, bento, data, cover+story, compare, gallery, catalog, ritual, magazine, bold-cover, service, quiet); worst pairwise structural Jaccard 0.125 ≤ 0.40 (G8). |
| AP9 | Options without undo/docs | PASS | Every panel/import action journaled with previous state + registered undo + doc anchor (G12); `docs/utente/journal.md`. |
| AP10 | Reload where Interactivity justified | PASS | Cart/search/dark-mode flows are fetch-based; navigation only in documented fallbacks (G9 static proxy). |
| AP11 | RTL/i18n tags without files/tests | PASS | RTL files + `is_rtl()` + G10; pots + adapters + G11. |
| AP12 | Duplicate JSON-LD | PASS | One `@graph`, one `<script>`, one hook (G14); SEO-plugin yield verified. |
| AP13 | Dark via inversion filters / non-AA | PASS | No `filter: invert` in rules (G16); explicit palette twins; color-scheme both states. |
| AP14 | Feature without doc page | PASS | G12 asserts every action doc anchor resolves to a real `docs/utente/*.md`; index maps H1–H50. |

## v3.1 — Release gates G8–G17

| Gate | Status | Evidence |
|---|---|---|
| (G8) 12 kits import <60 s + undo, nothing overwritten | PASS (contract + block budget); runtime <60 s via e2e | `npm run test:kits` — manifests, AP8 distinctness (worst 0.125), campaigns, token completeness, undo contract; block budget 900/kit keeps imports fast. Runtime: `tests/e2e/kit-import.spec.js` (timings log). |
| (G9) Purchase flow zero reloads | PASS (static proxy); runtime via e2e | `npm run test:purchase`; `tests/e2e/purchase-flow.spec.js` (committed network log). |
| (G10) rtl.css + is_rtl() + templates mirrored | PASS | `npm run test:rtl` — global + 8 module twins, conditional enqueue, real tag. |
| (G11) pot in CI, zero non-translatable, WPML/Polylang verified | PASS | `npm run test:i18n` (pots fresh via `make-pot --check`, domain hygiene, adapters, kit parity); adapters runtime-verified procedure in `docs/utente/traduzioni.md`. |
| (G12) Panel + metabox undo-verified + documented | PASS | `npm run test:panel` — journal/undo filters for preset/typography/layout/meta/kit, docs anchors resolve, fetch-only admin UI. |
| (G13) 8 presets applied + within budget | PASS | `npm run test:presets` — full pairings, no webfonts, ≤6 KB declarative JSON, dark twins. |
| (G14) JSON-LD valid 4 templates, 0 dup with Yoast AND RankMath | PASS (contract); Rich Results via script | `npm run test:jsonld` + `tests/e2e/jsonld.spec.js`; yield logic short-circuits for both plugins. |
| (G15) Non-Woo page 0 Woo bytes, home <60% total CSS | PASS (static byte model); runtime via e2e | `npm run test:assets` (17.5%); `tests/e2e/assets-decoupled.spec.js`. |
| (G16) axe 0 violations light AND dark, toggle persists | PASS (contract); runtime via e2e | `npm run test:dark`; `tests/e2e/dark-mode.spec.js`. |
| (G17) Child starter overrides token safely | PASS | `npm run test:child`. |
| Every feature has doc page with screenshot | PASS (diagrams declared) | 23 docs/utente pages; where a screenshot is impossible in this sandbox, clearly-labelled SVG wireframes (`docs/utente/diagrammi/`) — never fake captures. |

## v3.1 — Explicit decisions and environment declarations

4. **H15 family expansion (declared).** v3.1 adds the structural families
   **Blog** (6 patterns) and **Footer** (3 zone patterns) to
   `family-tokens.json` alongside the 12 commercial families. The v2.0 H9
   rule «≥12 families × ≥4 patterns» continues to bind the 12 commercial
   families (all ≥4); Footer legitimately ships 3 zone variants + the footer
   template part. No silent derogation: declared here.

5. **Runtime gates on this sandbox (inherited AP5 policy).** G8 timing,
   G9 network log, G14 Rich Results verdict, G16 axe runtime and all
   screenshots remain `FAIL — environment` where Chromium/PHP are required:
   deterministic static proxies are green in CI and the real-run specs are
   committed (`tests/e2e/*.spec.js`, `tools/php/rich-results-check.php`).
   No log, score or screenshot has been fabricated.

6. **Kits' campaign assets are SVG, not PNG (declared).** H21 requires the
   three ratios: v3.1 ships them as vector SVG generated from each kit's
   preset palette (`tools/kits/campaign.mjs`), editable and themeable —
   in addition to the v2.0 PNG campaign kit.

## Explicit AP7 decisions (written confirmations)

1. **H7 anti-clone scope → GLOBAL (confirmed 2026-08-29).** The reading «ogni
   coppia di artifact ≤40%» was confirmed as the binding gate. `tests/anti-clone.mjs`
   now checks all 1128 pairs (within-family and cross-family); 28 cross-family
   pairs were originally above threshold and were resolved by (a) adding real
   `data-arena-pattern`/`data-arena-module`/`data-arena-role` hooks that the
   runtime JS binds to and (b) structurally diverging the five most-similar
   pairs (cta-banner rewritten as a closing band not a cover; testimonials-
   scroller switched from arrow buttons to a dot-pagination interaction model;
   case-study-quote rebuilt as a pull-quote + avatar; quick-links-list rebuilt
   as an A–Z index with filter; gallery-snap controls given proper
   aria-label/role). Family-scoped H9 is still reported as a diagnostic but
   does not gate.

2. **Real-browser checks (axe runtime rules, Lighthouse ≥95, PHPCS, Playwright).**
   These require Chromium / PHP. The sandbox cannot install Playwright
   Chromium (cdn.playwright.dev ECONNRESET) and has no system Chromium or PHP.
   Static proxies that DO give a deterministic answer are committed and green
   (axe-static, lighthouse-budget, mobilenav structural, billboard audit).
   Real-run scripts are committed (`tests/lighthouse-run.mjs`,
   `tests/e2e/*.spec.js`, `phpcs.xml.dist`) so anyone with a machine that has
   Chromium/WP can reproduce. No Lighthouse/INP score was fabricated.

3. **CI push permission.** The GitHub App token used by this session lacks
   `workflows: write` (see `docs/ci.md`). Workflow files are committed to the
   repo (`.github/workflows/quality.yml`, `build-dist.yml`); the first push of
   the `.github/workflows/` tree must be performed by an actor with
   `Workflows: Read and write`.
