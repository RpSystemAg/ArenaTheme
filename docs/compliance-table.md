# Arena Prime — compliance table

Updated: 2026-08-28.

Status legend: `PASS` (verified), `FAIL` (verified missing), `PENDING`
(gate/check created but not yet green), `NOT-APPLICABLE`.

This session's primary objective is the enterprise CI matrix. The table below
records the Prime constitution status at the end of that objective. Any FAIL
(or PENDING) is a release blocker per H18.

## Constitution blocks

| ID | Check | Status | Evidence / note |
|---|---|---|---|
| H1 | Mobile-first 360→600→960→1440 | PARTIAL | Playwright smoke test runs at 360×800; theme uses native responsive `@media (width <= 600px)` / `(600px < width <= 960px)`. No exhaustive 4-width regression suite yet. |
| H2 | Bottom navigation ≤600px, 64px + safe-area, 4–5 items, active indicator, hide/show on scroll | FAIL | Not implemented in `parts/`. Current mobile navigation uses the header block navigation. This is a documented release blocker. |
| H3 | Touch targets ≥44×44 | PARTIAL | Claimed by theme/plugin CSS; not yet all templates measured in CI. |
| H4 | No desktop-only feature | PENDING | Not yet systematically verified. |
| H5 | Unique skeleton DOM per template/pattern | PENDING | Requires `variation-matrix.csv` + `tests/php/anti-clone.php`. |
| H6 | `variation-matrix.csv` | FAIL | File not present. |
| H7 | Anti-clone scripted test ≤40% structural overlap | FAIL | No script yet; CI does not yet run it. |
| H8 | Variants change structure (grid/hierarchy/module), not only token swaps | PENDING | Not audited. |
| H9 | ≥12 template families × ≥4 structurally distinct patterns | FAIL | Current theme ships 1 family/11 patterns; target is 48+ structurally distinct patterns. |
| H10 | Spring physics / 200–600ms, motion.dev-style curves | PARTIAL | Carousel/reveal motion is transform/opacity based and `prefers-reduced-motion`-guarded; no motion-budget test exists yet. |
| H11 | FLIP layout, stagger 40–80ms, scroll-driven parallax ≤15%, enter/exit, micro-interactions | FAIL | Not implemented as a set; no tests. |
| H12 | `prefers-reduced-motion` static fallback everywhere | PARTIAL | Implemented in CSS/JS for current motions; no exhaustive audit of every artifact. |
| H13 | Motion cannot delay LCP; INP <100ms; GPU-composited only | PARTIAL | No Lighthouse/INP browser measurement in this environment. |
| H14 | Billboard test (6m / <1s), one dominant hierarchy + message | PENDING | Requires design review protocol. |
| H15 | Family system: ≥5 type levels, palette, grid, photo voice | PARTIAL | Theme has type-scale + palette; no per-family matrix. |
| H16 | Distinctive display + readable body, fluid `clamp()` | PASS | `theme.json` has system families, 10 fluid sizes, no web fonts. |
| H17 | Campaign kit 9:16 / 1:1 / 16:9 | FAIL | `kit-campagna/` not present. |
| H18 | Compliance table per H/AP | PASS | This table. |

## Anti-patterns

| ID | Check | Status | Evidence / note |
|---|---|---|---|
| AP1 | Color/text/font-only variants | PENDING | Needs H5–H9. |
| AP2 | Classic header as only mobile navigation | FAIL | Header navigation is the only mobile navigation; no bottom nav. |
| AP3 | Linear animations, >600ms, no reduced-motion fallback | PASS | Current animation uses transform/opacity and reduced-motion guards. |
| AP4 | Template failing billboard test | PENDING | H14 not yet run. |
| AP5 | Non-reproducible certification numbers | PARTIAL | Cert report cites scripts/artifacts, but Lighthouse numbers are honestly omitted. |
| AP6 | Duplicated pattern without structural rework | PENDING | Needs H5–H7. |
| AP7 | Silent H-derogation | FAIL | H2 and H17 are knowingly unmet in this session. They are declared here instead of silently accepted. |

## Release gates

| Gate | Status | Evidence |
|---|---|---|
| (1) H7 anti-clone green | FAIL | Not implemented. |
| (2) `variation-matrix.csv` complete | FAIL | Not present. |
| (3) Bottom-nav verified at 360px + safe-area | FAIL | Not implemented. |
| (4) axe-core 0 violations on new templates | PARTIAL | Prior cert: 0 on 4 templates. Not run for all templates yet. |
| (5) PHPCS WordPress-VIP-Go 0 errors/0 warnings | PASS | Prior cert report. |
| (6) Lighthouse mobile ≥95 real browser | FAIL | No usable browser in sandbox; scripts exist, numbers not invented. See `docs/certification-report.md`. |
| (7) Campaign kit present | FAIL | Not present. |

## CI gate (this session)

| Check | Status |
|---|---|
| `static-analysis.yml` | PENDING |
| `plugin-check.yml` | PENDING |
| `theme-check.yml` | PENDING |
| `e2e.yml` | PENDING |
| `qit.yml` | N/A without WooCommerce credentials (gated, non-blocking) |
