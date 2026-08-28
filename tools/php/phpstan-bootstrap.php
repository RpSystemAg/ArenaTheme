<?php
/**
 * PHPStan bootstrap for the Arena Commerce stack.
 *
 * WordPress stubs do not declare the plugin/theme constants, so this file
 * provides the small set of constants PHPStan needs to analyse the code
 * paths without executing WordPress itself.
 *
 * @package Arena_Quality
 */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', '/tmp/wordpress/' );
}

if ( ! defined( 'ARENA_ENGINE_VERSION' ) ) {
	define( 'ARENA_ENGINE_VERSION', '1.0.0' );
}

if ( ! defined( 'ARENA_ENGINE_FILE' ) ) {
	define( 'ARENA_ENGINE_FILE', __DIR__ . '/../../plugin/arena-engine/arena-engine.php' );
}

if ( ! defined( 'ARENA_ENGINE_DIR' ) ) {
	define( 'ARENA_ENGINE_DIR', __DIR__ . '/../../plugin/arena-engine/' );
}

if ( ! defined( 'ARENA_ENGINE_URL' ) ) {
	define( 'ARENA_ENGINE_URL', 'https://example.test/wp-content/plugins/arena-engine/' );
}

if ( ! defined( 'ARENA_ENGINE_DOMAIN' ) ) {
	define( 'ARENA_ENGINE_DOMAIN', 'arena-engine' );
}

if ( ! defined( 'ARENA_ENGINE_MIN_WP' ) ) {
	define( 'ARENA_ENGINE_MIN_WP', '6.9' );
}

if ( ! defined( 'ARENA_ENGINE_MIN_PHP' ) ) {
	define( 'ARENA_ENGINE_MIN_PHP', '7.4' );
}

if ( ! defined( 'ARENA_THEME_VERSION' ) ) {
	define( 'ARENA_THEME_VERSION', '1.0.0' );
}

if ( ! defined( 'ARENA_THEME_MIN_WP' ) ) {
	define( 'ARENA_THEME_MIN_WP', '7.0' );
}

if ( ! defined( 'ARENA_THEME_MIN_PHP' ) ) {
	define( 'ARENA_THEME_MIN_PHP', '7.4' );
}

if ( ! defined( 'ARENA_THEME_DOMAIN' ) ) {
	define( 'ARENA_THEME_DOMAIN', 'arena-commerce' );
}
