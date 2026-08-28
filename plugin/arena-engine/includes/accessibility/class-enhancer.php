<?php
/**
 * Accessibility module.
 *
 * @package Arena_Engine
 * @since   1.0.0
 */

namespace Arena_Engine\Accessibility;

defined( 'ABSPATH' ) || exit;

/**
 * Adds the accessibility primitives that must exist regardless of theme.
 *
 * A plugin cannot assume the active theme does the right thing, so this module
 * ships the pieces that make WCAG 2.2 AA achievable anywhere: skip links, a
 * live region, focus-visible fallbacks and honest form markup.
 *
 * @since 1.0.0
 */
final class Enhancer {

	/**
	 * ID of the polite live region.
	 *
	 * @var string
	 */
	const LIVE_REGION_ID = 'arena-engine-live-region';

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		if ( ! self::enabled() ) {
			return;
		}

		add_action( 'wp_body_open', array( __CLASS__, 'skip_links' ), 20 );
		add_action( 'wp_footer', array( __CLASS__, 'live_region' ), 4 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 20 );
		add_filter( 'nav_menu_link_attributes', array( __CLASS__, 'menu_link_attributes' ), 10, 2 );
	}

	/**
	 * Whether the module is switched on.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private static function enabled() {
		$settings = (array) get_option( 'arena_engine_settings', array() );

		return ! isset( $settings['accessibility'] ) || ! empty( $settings['accessibility'] );
	}

	/**
	 * Prints supplementary skip links.
	 *
	 * Only printed when the theme has not already provided them, detected by
	 * looking for the conventional `arena-skip-links` wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function skip_links() {
		if ( did_action( 'arena_theme_skip_links_printed' ) ) {
			return;
		}

		$links = apply_filters(
			'arena_engine_skip_links',
			array(
				'#content' => __( 'Skip to content', 'arena-engine' ),
				'#arena-primary-navigation' => __( 'Skip to navigation', 'arena-engine' ),
			)
		);

		if ( empty( $links ) || ! is_array( $links ) ) {
			return;
		}

		echo '<nav class="arena-skip-links" aria-label="' . esc_attr__( 'Skip links', 'arena-engine' ) . '">';

		foreach ( $links as $target => $label ) {
			printf(
				'<a class="arena-skip-link screen-reader-text" href="%1$s">%2$s</a>',
				esc_url( (string) $target ),
				esc_html( (string) $label )
			);
		}

		echo '</nav>';
	}

	/**
	 * Prints the polite live region used for asynchronous feedback.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function live_region() {
		printf(
			'<div id="%1$s" class="screen-reader-text" role="status" aria-live="polite" aria-atomic="true"></div>',
			esc_attr( self::LIVE_REGION_ID )
		);
	}

	/**
	 * Enqueues the small enhancement script and stylesheet.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function enqueue() {
		wp_register_style(
			'arena-engine',
			ARENA_ENGINE_URL . 'assets/css/arena-engine.css',
			array(),
			ARENA_ENGINE_VERSION
		);
		wp_enqueue_style( 'arena-engine' );

		wp_register_script(
			'arena-engine',
			ARENA_ENGINE_URL . 'assets/js/arena-engine.js',
			array(),
			ARENA_ENGINE_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => false,
			)
		);
		wp_enqueue_script( 'arena-engine' );
	}

	/**
	 * Marks the current menu item for assistive technology.
	 *
	 * @since 1.0.0
	 *
	 * @param array    $atts Menu link attributes.
	 * @param \WP_Post $item Menu item.
	 * @return array
	 */
	public static function menu_link_attributes( $atts, $item ) {
		if ( ! empty( $item->current ) ) {
			$atts['aria-current'] = 'page';
		}

		return $atts;
	}
}
