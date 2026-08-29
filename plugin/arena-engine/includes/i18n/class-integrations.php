<?php
/**
 * i18n plugin adapters (H42): WPML and Polylang.
 *
 * Registered ONLY when the plugin is active (checked at boot). Each adapter:
 *   - registers the theme/plugin strings that live outside gettext (the
 *     runtime-config labels consumed by JS modules);
 *   - keeps kit imports locale-aware (the importer resolves {{t:}} against
 *     the current admin locale).
 *
 * Verified statically by tests/g11-i18n.test.mjs and documented in
 * docs/dev/i18n.md.
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\I18n;

defined( 'ABSPATH' ) || exit;

/**
 * WPML / Polylang integration.
 *
 * @since 1.1.0
 */
final class Integrations {

	/**
	 * Attaches the hooks (guarded by plugin presence — H37/H42 style).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		if ( ! self::wpml_active() && ! self::polylang_active() ) {
			return;
		}

		add_action( 'init', array( __CLASS__, 'register_strings' ), 20 );
	}

	/**
	 * Whether WPML is active.
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public static function wpml_active() {
		return did_action( 'wpml_loaded' ) || defined( 'ICL_SITEPRESS_VERSION' );
	}

	/**
	 * Whether Polylang is active.
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public static function polylang_active() {
		return function_exists( 'PLL' ) || defined( 'POLYLANG_VERSION' );
	}

	/**
	 * The non-gettext strings the JS modules consume at runtime.
	 *
	 * @since 1.1.0
	 *
	 * @return string[] label => default (English).
	 */
	public static function runtime_strings() {
		return array(
			'arena.cart.empty'     => 'Your cart is empty.',
			'arena.cart.total'     => 'Total',
			'arena.cart.checkout'  => 'Checkout',
			'arena.cart.added'     => 'Added to your cart.',
			'arena.cart.removed'   => 'Item removed.',
			'arena.cart.undo'      => 'Undo',
			'arena.search.noResults' => 'No results.',
			'arena.shop.loadMore'  => 'Load more',
			'arena.theme.dark'     => 'Dark mode on',
			'arena.theme.light'    => 'Light mode on',
		);
	}

	/**
	 * Registers the runtime strings with the active i18n plugin.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function register_strings() {
		$strings = self::runtime_strings();

		if ( self::wpml_active() && has_filter( 'wpml_register_single_string' ) ) {
			foreach ( $strings as $key => $value ) {
				do_action( 'wpml_register_single_string', 'arena-engine', $key, $value );
			}

			return;
		}

		if ( self::polylang_active() && function_exists( 'pll_register_string' ) ) {
			foreach ( $strings as $key => $value ) {
				pll_register_string( $key, $value, 'arena-engine', false );
			}
		}
	}
}
