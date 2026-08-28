<?php
/**
 * Site Health integration.
 *
 * @package Arena_Engine
 * @since   1.0.0
 */

namespace Arena_Engine\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Surfaces the Arena budget inside Site Health so regressions are visible.
 *
 * @since 1.0.0
 */
final class Health {

	/**
	 * CSS budget in bytes for the theme's own stylesheet.
	 *
	 * @var int
	 */
	const CSS_BUDGET = 16384;

	/**
	 * JS budget in bytes for the theme's own script.
	 *
	 * @var int
	 */
	const JS_BUDGET = 12288;

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		add_filter( 'site_status_tests', array( __CLASS__, 'tests' ) );
	}

	/**
	 * Registers the test.
	 *
	 * @since 1.0.0
	 *
	 * @param array $tests Existing tests.
	 * @return array
	 */
	public static function tests( $tests ) {
		$tests['direct']['arena_asset_budget'] = array(
			'label' => __( 'Arena asset budget', 'arena-engine' ),
			'test'  => array( __CLASS__, 'run' ),
		);

		return $tests;
	}

	/**
	 * Runs the budget test.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function run() {
		$result = array(
			'label'       => __( 'Arena assets are within budget', 'arena-engine' ),
			'status'      => 'good',
			'badge'       => array(
				'label' => __( 'Performance', 'arena-engine' ),
				'color' => 'blue',
			),
			'description' => '',
			'actions'     => '',
			'test'        => 'arena_asset_budget',
		);

		$css = get_theme_file_path( 'assets/css/arena.css' );
		$js  = get_theme_file_path( 'assets/js/arena.js' );
		$css_size = is_readable( $css ) ? (int) filesize( $css ) : 0;
		$js_size  = is_readable( $js ) ? (int) filesize( $js ) : 0;

		if ( ! is_readable( $css ) ) {
			$result['status']      = 'recommended';
			$result['label']       = __( 'Arena theme stylesheet not found', 'arena-engine' );
			$result['description'] = '<p>' . esc_html__( 'The active theme is not Arena Commerce, so the budget test is skipped.', 'arena-engine' ) . '</p>';

			return $result;
		}

		if ( $css_size > self::CSS_BUDGET || $js_size > self::JS_BUDGET ) {
			$result['status'] = 'critical';
			$result['label']  = __( 'Arena assets exceed the performance budget', 'arena-engine' );
		}

		$result['description'] = sprintf(
			'<p>%s</p>',
			esc_html(
				sprintf(
					/* translators: 1: CSS bytes, 2: JS bytes. */
					__( 'Theme CSS: %1$d bytes. Theme JS: %2$d bytes.', 'arena-engine' ),
					$css_size,
					$js_size
				)
			)
		);

		return $result;
	}
}
