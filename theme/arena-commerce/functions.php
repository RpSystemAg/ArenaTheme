<?php
/**
 * Arena Commerce theme bootstrap.
 *
 * Deliberately small: every feature lives in its own class under /inc so the
 * load order is explicit and each file can be linted and reviewed in isolation.
 *
 * @package Arena_Theme
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/** Theme version, used to cache-bust every enqueued asset. */
define( 'ARENA_THEME_VERSION', '1.0.0' );

/** Lowest WordPress release supported. Mirrors style.css "Requires at least". */
define( 'ARENA_THEME_MIN_WP', '7.0' );

/** Lowest PHP release supported. Mirrors style.css "Requires PHP". */
define( 'ARENA_THEME_MIN_PHP', '7.4' );

/** Text domain, kept in one place so no string literal can drift. */
define( 'ARENA_THEME_DOMAIN', 'arena-commerce' );

/**
 * PSR-0-ish autoloader for the Arena_Theme namespace.
 *
 * `Arena_Theme\WooCommerce` maps to `inc/class-arena-woocommerce.php`, which
 * satisfies the WordPress file-naming convention while keeping the code
 * namespaced.
 *
 * @since 1.0.0
 *
 * @param string $class_name Fully qualified class name.
 * @return void
 */
spl_autoload_register(
	static function ( $class_name ) {
		$prefix = 'Arena_Theme\\';

		if ( 0 !== strpos( $class_name, $prefix ) ) {
			return;
		}

		$parts = explode( '\\', substr( $class_name, strlen( $prefix ) ) );
		$name  = array_pop( $parts );
		$path  = __DIR__ . '/inc';

		foreach ( $parts as $part ) {
			$path .= '/' . strtolower( str_replace( '_', '-', $part ) );
		}

		$path .= '/class-' . strtolower( str_replace( '_', '-', $name ) ) . '.php';

		if ( is_readable( $path ) ) {
			require_once $path;
		}
	}
);

require_once get_theme_file_path( 'inc/template-functions.php' );
require_once get_theme_file_path( 'inc/template-tags.php' );

/**
 * Boots a component if it exposes a static init() method.
 *
 * @since 1.0.0
 *
 * @param string ...$classes Fully qualified class names.
 * @return void
 */
function arena_theme_boot( ...$classes ) {
	foreach ( $classes as $class_name ) {
		if ( class_exists( $class_name ) && method_exists( $class_name, 'init' ) ) {
			$class_name::init();
		}
	}
}

add_action(
	'after_setup_theme',
	static function () {
		arena_theme_boot(
			'Arena_Theme\Setup',
			'Arena_Theme\Icons',
			'Arena_Theme\Block_Patterns',
			'Arena_Theme\Accessibility'
		);

		arena_theme_boot( 'Arena_Theme\Bottom_Nav' );
	},
	5
);

add_action(
	'init',
	static function () {
		arena_theme_boot( 'Arena_Theme\Performance', 'Arena_Theme\WooCommerce' );
	},
	5
);

add_action(
	'wp_enqueue_scripts',
	static function () {
		arena_theme_boot( 'Arena_Theme\Assets' );
	},
	5
);
