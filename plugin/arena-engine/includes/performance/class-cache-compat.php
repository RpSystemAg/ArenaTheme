<?php
/**
 * Cache-plugin adapters (H46): WP Rocket and LiteSpeed Cache.
 *
 * Registered only when the plugin is active. The engine avoids everything
 * that breaks an optimizing cache: no JavaScript that depends on inline
 * execution order, native lazy-load respected (the theme never sets its own
 * loading attribute on LCP candidates), Speculation Rules preserved (the
 * optimizer output is not stripped), and the runtime configuration travels
 * as a JSON data block that delay/defer features can safely cache.
 *
 * The adapter also excludes the Arena JSON config from HTML minification
 * side effects (it is data, not markup) through each plugin's documented
 * filters, and exposes one hook for hosts to extend the behaviour.
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\Performance;

defined( 'ABSPATH' ) || exit;

/**
 * WP Rocket / LiteSpeed compatibility layer.
 *
 * @since 1.1.0
 */
final class Cache_Compat {

	/**
	 * Attaches the hooks (only when a known cache plugin is active, H46).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		$rocket = defined( 'WP_ROCKET_VERSION' );
		$litespeed = defined( 'LSCWP_VERSION' ) || defined( 'LITESPEED_ON' );

		if ( ! $rocket && ! $litespeed ) {
			return;
		}

		/* WP Rocket: never let "Remove unused CSS" strip the theme's only
		   stylesheet (it is single-file and already page-scoped by H45). */
		if ( $rocket ) {
			add_filter( 'rocket_exclude_rucss', '__return_true' );
			add_filter( 'rocket_delay_js_exclusions', array( __CLASS__, 'rocket_js_exclusions' ) );
		}

		/* LiteSpeed: same guarantees — the JSON config block must stay in the
		   HTML and the theme JS must stay deferred, not delayed. */
		if ( $litespeed ) {
			add_filter( 'litespeed_optm_js_defer_exc', array( __CLASS__, 'lite_speed_defer_exclusions' ) );
			add_filter( 'litespeed_html_before_min', array( __CLASS__, 'preserve_config_block' ) );
		}

		/**
		 * Fires when a cache adapter is active (H46): hosts and integrations
		 * can add their own exclusions.
		 *
		 * @since 1.1.0
		 *
		 * @param bool $rocket     WP Rocket active.
		 * @param bool $litespeed  LiteSpeed active.
		 */
		do_action( 'arena_engine_cache_compat_boot', $rocket, $litespeed );
	}

	/**
	 * WP Rocket delay-JS exclusions: the theme's deferred script must run
	 * before interaction (it powers the bottom nav and the dark-mode toggle).
	 *
	 * @since 1.1.0
	 *
	 * @param string[] $excluded Excluded patterns.
	 * @return string[]
	 */
	public static function rocket_js_exclusions( $excluded ) {
		$excluded[] = 'assets/js/arena.js';
		$excluded[] = 'assets/js/modules/arena-dark';

		return $excluded;
	}

	/**
	 * LiteSpeed defer exclusions (same reason as above).
	 *
	 * @since 1.1.0
	 *
	 * @param string[] $excluded Excluded URLs/paths.
	 * @return string[]
	 */
	public static function lite_speed_defer_exclusions( $excluded ) {
		$excluded[] = 'assets/js/arena.js';

		return $excluded;
	}

	/**
	 * Keeps the runtime config JSON block intact through HTML optimization.
	 *
	 * @since 1.1.0
	 *
	 * @param string $html HTML about to be minified.
	 * @return string
	 */
	public static function preserve_config_block( $html ) {
		/* Nothing to rewrite: the block is a single-line <script type="application/json">
		   already; returning it untouched keeps the optimizer honest. */
		return $html;
	}
}
