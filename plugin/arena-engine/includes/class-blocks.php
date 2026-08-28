<?php
/**
 * Block registration.
 *
 * Blocks are registered from block.json and rendered server-side through the
 * Interactivity API, so behaviour ships as a script module instead of a bundle:
 * the browser only downloads the store it actually uses.
 *
 * @package Arena_Engine
 * @since   1.0.0
 */

namespace Arena_Engine;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Arena blocks.
 *
 * @since 1.0.0
 */
final class Blocks {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register' ), 20 );
	}

	/**
	 * Registers every block directory under /blocks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function register() {
		$dirs = glob( ARENA_ENGINE_DIR . 'blocks/*', GLOB_ONLYDIR );

		if ( ! is_array( $dirs ) ) {
			return;
		}

		foreach ( $dirs as $dir ) {
			if ( is_readable( $dir . '/block.json' ) ) {
				register_block_type( $dir );
			}
		}

		if ( function_exists( 'wp_register_script_module' ) ) {
			wp_register_script_module(
				'@arena/interactivity',
				ARENA_ENGINE_URL . 'assets/js/arena-interactivity.js',
				array(
					'@wordpress/interactivity' => array( 'version' => ARENA_ENGINE_VERSION ),
				),
				ARENA_ENGINE_VERSION
			);
		}
	}
}
