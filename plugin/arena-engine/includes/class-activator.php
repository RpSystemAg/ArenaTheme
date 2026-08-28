<?php
/**
 * Activation and deactivation routines.
 *
 * @package Arena_Engine
 * @since   1.0.0
 */

namespace Arena_Engine;

defined( 'ABSPATH' ) || exit;

/**
 * Stores the version and default settings on activation.
 *
 * @since 1.0.0
 */
final class Activator {

	/**
	 * Runs on activation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function activate() {
		if ( false === get_option( 'arena_engine_settings' ) ) {
			add_option( 'arena_engine_settings', self::defaults(), '', false );
		}

		update_option( 'arena_engine_version', ARENA_ENGINE_VERSION, false );
		update_option( 'arena_engine_installed', time(), false );

		/*
		 * No flush_rewrite_rules() here. The plugin registers no post types,
		 * taxonomies or rewrite rules, so there is nothing to flush — and
		 * flushing on activation is explicitly disallowed on WordPress VIP
		 * because it invalidates the whole site's rewrite cache on a single
		 * request.
		 */
	}

	/**
	 * Default settings. Everything is on, because everything is reversible.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function defaults() {
		return array(
			'performance'    => true,
			'accessibility'  => true,
			'security'       => true,
			'checkout'       => true,
			'abilities'      => true,
			'motion_default' => 'respect-os',
			'audit_bar'      => false,
		);
	}
}
