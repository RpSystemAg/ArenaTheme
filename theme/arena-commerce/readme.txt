=== Arena Commerce ===
Contributors: arenalabs
Tags: block-styles, block-patterns, full-site-editing, wide-blocks, editor-style, one-column, custom-colors, custom-menu, custom-logo, featured-images, rtl-language-support, sticky-post, threaded-comments, translation-ready, e-commerce, accessibility-ready
Requires at least: 7.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A performance-first block theme for WordPress 7.1 and WooCommerce 11.

== Description ==

Arena Commerce is built around three commitments: a measurable performance budget, WCAG 2.2 AA accessibility by default, and commerce templates informed by published Baymard Institute research.

= WordPress 7.1 features used =

* Native responsive styles through `@mobile` and `@tablet` keys in theme.json.
* Custom breakpoints via `settings.viewport` (600px mobile, 960px tablet).
* Pseudo-state styling for `core/button` and `core/navigation-link` (`:hover`, `:focus`, `:focus-visible`, `:active`).
* Custom block states: `-current` styles the active navigation item.
* The Icons API: 18 icons registered as the `arena` collection for the core Icon block.
* Speculation Rules tuned through `wp_speculation_rules_configuration`.
* The core Accordion block for FAQs, so disclosure needs no JavaScript.

= Performance =

* One stylesheet (about 12 KB, under 4 KB gzipped), preloaded.
* One deferred script (about 9 KB, under 3 KB gzipped), no dependencies, no jQuery.
* No web fonts. The system font stack means zero font requests and zero CLS from font swap.
* theme.json global styles are inlined by core, so there is no second render-blocking stylesheet.

= Accessibility =

* Palette verified against WCAG 1.4.3: every text pair passes AA, the lowest being accent on surface at 4.6:1.
* Form field borders use a token at 4.74:1 against canvas, satisfying WCAG 1.4.11.
* 44px minimum target size, against the 24px AA minimum.
* Skip links, labelled landmarks, a polite live region, visible `:focus-visible` rings and `scroll-padding-top` so focus is never obscured.
* `prefers-reduced-motion`, `prefers-contrast: more` and `forced-colors` are all handled.

= Commerce =

* Templates for shop, single product, category, tag, product search, cart, checkout, my account and order confirmation.
* Reassurance copy under the add-to-cart button, cost transparency at checkout, autofill tokens on every address field.

== Changelog ==

= 1.0.0 =
* Initial release.
