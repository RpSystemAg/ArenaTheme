<?php
/**
 * Plugin bootstrap and module registry.
 *
 * @package Arena_Engine
 * @since   1.0.0
 */

namespace Arena_Engine;

defined( 'ABSPATH' ) || exit;

/**
 * Boots every Arena Engine module exactly once.
 *
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Returns the shared instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * The modules to boot, in dependency order.
	 *
	 * @since 1.0.0
	 *
	 * @return string[]
	 */
	public static function modules() {
		return array(
			'Arena_Engine\Icons',
			'Arena_Engine\Blocks',
			'Arena_Engine\Performance\Optimizer',
			'Arena_Engine\Performance\Cache_Compat',
			'Arena_Engine\Accessibility\Enhancer',
			'Arena_Engine\Commerce\Checkout',
			'Arena_Engine\Commerce\Compat',
			'Arena_Engine\Security\Hardening',
			'Arena_Engine\Abilities\Registry',
			'Arena_Engine\Admin\Dashboard',
			'Arena_Engine\Admin\Health',
			'Arena_Engine\Admin\Menu',
			'Arena_Engine\Admin\Journal',
			'Arena_Engine\Admin\Variations',
			'Arena_Engine\Admin\Meta_Box',
			'Arena_Engine\Admin\REST_Panel',
			'Arena_Engine\I18n\Integrations',
			'Arena_Engine\Kits\REST_Kits',
			'Arena_Engine\Kits\Importer',
		);
	}

	/**
	 * Boots every registered module.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function boot() {
		/**
		 * Filter the module list so host projects can add or remove engine modules.
		 *
		 * @since 1.0.0
		 */
		foreach ( apply_filters( 'arena_engine_modules', self::modules() ) as $module ) {
			if ( class_exists( $module ) && method_exists( $module, 'init' ) ) {
				$module::init();
			}
		}
	}

	/**
	 * Private constructor: use ::instance().
	 */
	private function __construct() {}
}
