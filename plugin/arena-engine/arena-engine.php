<?php
/**
 * Plugin Name:       Arena Engine
 * Plugin URI:        https://github.com/RpSystemAg/ArenaTheme
 * Description:       The runtime half of the Arena Commerce stack. Ships the accessibility, performance, security and Baymard-informed checkout layers, Interactivity API blocks, a WordPress 7.1 icon collection and registered Abilities so automation and AI agents can operate the storefront. Works with any block theme and with or without WooCommerce.
 * Version:           1.0.0
 * Requires at least: 6.9
 * Requires PHP:      7.4
 * Author:            Arena Labs
 * Author URI:        https://github.com/RpSystemAg
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain:       arena-engine
 * Domain Path:       /languages
 *
 * @package Arena_Engine
 */

defined( 'ABSPATH' ) || exit;

define( 'ARENA_ENGINE_VERSION', '1.0.0' );
define( 'ARENA_ENGINE_FILE', __FILE__ );
define( 'ARENA_ENGINE_DIR', plugin_dir_path( __FILE__ ) );
define( 'ARENA_ENGINE_URL', plugin_dir_url( __FILE__ ) );
define( 'ARENA_ENGINE_DOMAIN', 'arena-engine' );
define( 'ARENA_ENGINE_MIN_WP', '6.9' );
define( 'ARENA_ENGINE_MIN_PHP', '7.4' );

/**
 * PSR-0-ish autoloader for the Arena_Engine namespace.
 *
 * @since 1.0.0
 *
 * @param string $class Fully qualified class name.
 * @return void
 */
spl_autoload_register(
	static function ( $class ) {
		$prefix = 'Arena_Engine\\';

		if ( 0 !== strpos( $class, $prefix ) ) {
			return;
		}

		$parts = explode( '\\', substr( $class, strlen( $prefix ) ) );
		$name  = array_pop( $parts );
		$path  = ARENA_ENGINE_DIR . 'includes';

		foreach ( $parts as $part ) {
			$path .= '/' . strtolower( str_replace( '_', '-', $part ) );
		}

		$path .= '/class-' . strtolower( str_replace( '_', '-', $name ) ) . '.php';

		if ( is_readable( $path ) ) {
			require_once $path;
		}
	}
);

register_activation_hook( __FILE__, array( 'Arena_Engine\Activator', 'activate' ) );

/*
 * Text domain loading is intentionally omitted: since WordPress 4.6 the
 * platform loads the plugin's language pack automatically on wordpress.org,
 * and calling load_plugin_textdomain() is flagged by Plugin Check.
 */
add_action(
	'plugins_loaded',
	static function () {
		Arena_Engine\Plugin::instance()->boot();
	},
	5
);
