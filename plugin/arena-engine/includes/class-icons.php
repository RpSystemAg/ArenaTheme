<?php
/**
 * Registers the Arena Engine commerce icon collection (WordPress 7.1 Icons API).
 *
 * @package Arena_Engine
 * @since   1.0.0
 */

namespace Arena_Engine;

defined( 'ABSPATH' ) || exit;

/**
 * Adds commerce-specific icons to the core Icon block.
 *
 * @since 1.0.0
 */
final class Icons {

	/**
	 * Collection slug.
	 *
	 * @var string
	 */
	const COLLECTION = 'arena-commerce';

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register' ), 15 );
	}

	/**
	 * Registers the collection and every SVG in /assets/icons.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function register() {
		if ( ! function_exists( 'wp_register_icon_collection' ) ) {
			return;
		}

		wp_register_icon_collection(
			self::COLLECTION,
			array(
				'label'       => __( 'Arena commerce', 'arena-engine' ),
				'description' => __( 'Checkout, shipping and trust icons for storefronts.', 'arena-engine' ),
			)
		);

		$files = glob( ARENA_ENGINE_DIR . 'assets/icons/*.svg' );

		if ( ! is_array( $files ) ) {
			return;
		}

		foreach ( $files as $file ) {
			$slug = basename( $file, '.svg' );

			wp_register_icon(
				self::COLLECTION . '/' . $slug,
				array(
					'label'     => ucwords( str_replace( '-', ' ', $slug ) ),
					'file_path' => $file,
				)
			);
		}
	}
}
