<?php
/**
 * Registers the Arena icon collection with the WordPress 7.1 Icons API.
 *
 * Since 7.1 core exposes `wp_register_icon_collection()` / `wp_register_icon()`,
 * so icons become first-class editor citizens (`core/icon`) instead of an icon
 * font or a hand-rolled sprite. Icons are stored as individual SVG files under
 * /assets/icons and registered by `file_path`, which keeps this class free of
 * markup and lets the editor read each icon's intrinsic viewBox.
 *
 * @package Arena_Theme
 * @since   1.0.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Arena icon collection.
 *
 * @since 1.0.0
 */
final class Icons {

	/**
	 * Collection slug used by the `core/icon` block.
	 *
	 * @var string
	 */
	const COLLECTION = 'arena';

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
	 * Registers the collection and every icon in /assets/icons.
	 *
	 * Icons are discovered from disk rather than hard-coded, so a child theme or
	 * a merchant can drop a file in and have it appear in the inserter.
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
				'label'       => __( 'Arena', 'arena-commerce' ),
				/* translators: %s: theme name. */
				'description' => sprintf( __( 'Commerce icons shipped with %s.', 'arena-commerce' ), 'Arena Commerce' ),
			)
		);

		$dir   = get_theme_file_path( 'assets/icons' );
		$files = glob( $dir . '/*.svg' );

		if ( ! is_array( $files ) ) {
			return;
		}

		foreach ( $files as $file ) {
			$slug = basename( $file, '.svg' );
			$name = self::COLLECTION . '/' . $slug;

			wp_register_icon(
				$name,
				array(
					'label'     => ucwords( str_replace( '-', ' ', $slug ) ),
					'file_path' => $file,
				)
			);
		}
	}

	/**
	 * Renders a registered icon.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug  Icon slug without the collection prefix.
	 * @param array  $args  Arguments forwarded to `wp_get_icon()`.
	 * @return string SVG markup, or an empty string when unavailable.
	 */
	public static function render( $slug, $args = array() ) {
		if ( ! function_exists( 'wp_get_icon' ) ) {
			return '';
		}

		return wp_get_icon( self::COLLECTION . '/' . $slug, $args );
	}
}
