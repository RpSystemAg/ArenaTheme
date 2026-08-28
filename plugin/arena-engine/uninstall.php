<?php
/**
 * Removes every option the plugin created. Nothing else is touched, so a
 * merchant can reinstall without losing store data.
 *
 * @package Arena_Engine
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

$arena_engine_options = array(
	'arena_engine_version',
	'arena_engine_settings',
	'arena_engine_installed',
	'arena_engine_audit_last',
);

foreach ( $arena_engine_options as $arena_engine_option ) {
	delete_option( $arena_engine_option );
	delete_site_option( $arena_engine_option );
}
