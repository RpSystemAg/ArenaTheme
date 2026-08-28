=== Arena Engine ===
Contributors: arenalabs
Tags: performance, accessibility, woocommerce, blocks, security
Requires at least: 6.9
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html

The runtime half of the Arena Commerce stack: accessibility, performance budget, security headers, Baymard-informed checkout defaults, Interactivity API blocks and WordPress 7.1 Abilities.

== Description ==

Arena Engine is the plugin companion to the Arena Commerce block theme, but it works with any theme.

= What it does =

* **Performance** — strips emoji, oEmbed discovery, wp-embed, jQuery Migrate and logged-out dashicons; tunes the WordPress 7.1 Speculation Rules defaults; adds lazy loading and titles to third-party embeds.
* **Accessibility** — skip links, a polite live region for asynchronous commerce feedback, `aria-current` on the active menu item, 44px touch targets, 3:1 field boundaries and full `prefers-reduced-motion` support.
* **Checkout** — collapses the fields Baymard's research identifies as abandonment triggers, adds correct `autocomplete` tokens, keeps guest checkout enabled and restates the total including tax and shipping immediately above Place order.
* **Security** — `X-Content-Type-Options`, `Referrer-Policy`, `X-Frame-Options`, `Permissions-Policy`, XML-RPC and pingbacks off, generator removed, and asset `?ver=` replaced with a non-disclosing hash.
* **Blocks** — `arena/carousel` and `arena/reveal`, rendered server-side and enhanced through the Interactivity API as script modules.
* **Abilities** — registers `arena-engine/performance-report` and `arena-engine/accessibility-audit` through the WordPress 7.1 Abilities API, with JSON Schema, permission callbacks and declared side-effect annotations, so automation and AI agents can operate on the storefront.
* **Site Health** — an "Arena asset budget" test that fails loudly if the theme's own CSS or JS grows past budget.

= Design rules =

1. Nothing animates without consent. Every motion path checks `prefers-reduced-motion`.
2. No jQuery, no build step required at runtime, no third-party origin contacted.
3. Every module is switchable under Settings → Arena Engine, and reversible.

== Installation ==

1. Upload the `arena-engine` folder to `/wp-content/plugins/`, or upload the ZIP through Plugins → Add New → Upload Plugin.
2. Activate through the Plugins screen.
3. Review Settings → Arena Engine.

== Frequently Asked Questions ==

= Does it require the Arena Commerce theme? =

No. The theme gets the most out of it, but every module degrades gracefully on any block or classic theme.

= Does it require WooCommerce? =

No. The checkout module self-disables when WooCommerce is not present.

= Why is there no Content-Security-Policy by default? =

WordPress core prints an inline skip-link script. A strict policy would break keyboard navigation on sites that have not audited their plugin stack, so CSP is exposed through the `arena_engine_csp` filter instead.

== Changelog ==

= 1.0.0 =
* Initial release.
