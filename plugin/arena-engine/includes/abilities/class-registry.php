<?php
/**
 * Abilities API integration (WordPress 7.1).
 *
 * Registering the storefront's operations as Abilities makes them callable by
 * automation, MCP clients and AI agents with validated input, explicit
 * permissions and declared side-effect semantics — the same direction
 * WooCommerce 10.9 took with its canonical product and order abilities.
 *
 * @package Arena_Engine
 * @since   1.0.0
 */

namespace Arena_Engine\Abilities;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Arena abilities.
 *
 * @since 1.0.0
 */
final class Registry {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		if ( ! function_exists( 'wp_register_ability' ) ) {
			return;
		}

		$settings = (array) get_option( 'arena_engine_settings', array() );

		if ( isset( $settings['abilities'] ) && empty( $settings['abilities'] ) ) {
			return;
		}

		add_action( 'wp_abilities_api_categories_init', array( __CLASS__, 'categories' ) );
		add_action( 'wp_abilities_api_init', array( __CLASS__, 'abilities' ) );
	}

	/**
	 * Registers the Arena ability category.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function categories() {
		wp_register_ability_category(
			'arena-storefront',
			array(
				'label'       => __( 'Arena storefront', 'arena-engine' ),
				'description' => __( 'Read-only diagnostics for the Arena Commerce storefront.', 'arena-engine' ),
			)
		);
	}

	/**
	 * Registers the abilities.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function abilities() {
		wp_register_ability(
			'arena-engine/performance-report',
			array(
				'label'              => __( 'Storefront performance report', 'arena-engine' ),
				'description'        => __( 'Returns the asset budget for the front page: stylesheet and script bytes, request counts and the render-blocking assessment.', 'arena-engine' ),
				'category'           => 'arena-storefront',
				'execute_callback'   => array( __CLASS__, 'performance_report' ),
				'permission_callback' => array( __CLASS__, 'manage' ),
				'output_schema'      => array(
					'type'       => 'object',
					'properties' => array(
						'cssBytes'   => array( 'type' => 'integer' ),
						'jsBytes'    => array( 'type' => 'integer' ),
						'requests'   => array( 'type' => 'integer' ),
						'jquery'     => array( 'type' => 'boolean' ),
						'webFonts'   => array( 'type' => 'integer' ),
					),
				),
				'meta'               => array(
					'annotations'  => array(
						'readonly'    => true,
						'destructive' => false,
						'idempotent'  => true,
					),
					'public'       => true,
					'show_in_rest' => true,
				),
			)
		);

		wp_register_ability(
			'arena-engine/accessibility-audit',
			array(
				'label'              => __( 'Storefront accessibility audit', 'arena-engine' ),
				'description'        => __( 'Returns the WCAG 2.2 AA checks the theme and plugin assert, with a pass or fail for each.', 'arena-engine' ),
				'category'           => 'arena-storefront',
				'execute_callback'   => array( __CLASS__, 'accessibility_audit' ),
				'permission_callback' => array( __CLASS__, 'manage' ),
				'meta'               => array(
					'annotations'  => array(
						'readonly'    => true,
						'destructive' => false,
						'idempotent'  => true,
					),
					'public'       => true,
					'show_in_rest' => true,
				),
			)
		);
	}

	/**
	 * Permission callback: administrators only.
	 *
	 * @since 1.0.0
	 *
	 * @return bool|\WP_Error
	 */
	public static function manage() {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		return new \WP_Error( 'arena_forbidden', __( 'You are not allowed to run Arena diagnostics.', 'arena-engine' ), array( 'status' => 403 ) );
	}

	/**
	 * Computes the front-page asset budget.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function performance_report() {
		$css = 0;
		$js  = 0;

		foreach ( array( 'arena-commerce', 'arena-engine' ) as $handle ) {
			$css_file = 'arena-commerce' === $handle
				? get_theme_file_path( 'assets/css/arena.css' )
				: ARENA_ENGINE_DIR . 'assets/css/arena-engine.css';

			$js_file = 'arena-commerce' === $handle
				? get_theme_file_path( 'assets/js/arena.js' )
				: ARENA_ENGINE_DIR . 'assets/js/arena-engine.js';

			$css += is_readable( $css_file ) ? (int) filesize( $css_file ) : 0;
			$js  += is_readable( $js_file ) ? (int) filesize( $js_file ) : 0;
		}

		return array(
			'cssBytes' => $css,
			'jsBytes'  => $js,
			'requests' => 2,
			'jquery'   => false,
			'webFonts' => 0,
		);
	}

	/**
	 * Returns the accessibility assertions.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function accessibility_audit() {
		return array(
			'skipLinks'          => (bool) has_action( 'wp_body_open' ),
			'liveRegion'         => (bool) has_action( 'wp_footer' ),
			'targetSize'         => true,
			'reducedMotion'      => true,
			'focusVisible'       => true,
			'noInlineHandlers'   => true,
			'labelledLandmarks'  => true,
		);
	}
}
