<?php
/**
 * Security module.
 *
 * @package Arena_Engine
 * @since   1.0.0
 */

namespace Arena_Engine\Security;

defined( 'ABSPATH' ) || exit;

/**
 * Applies conservative hardening that never breaks the ecosystem.
 *
 * @since 1.0.0
 */
final class Hardening {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		if ( ! self::enabled() ) {
			return;
		}

		add_action( 'send_headers', array( __CLASS__, 'headers' ) );
		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'pings_open', '__return_false' );
		add_filter( 'wp_headers', array( __CLASS__, 'remove_generator' ) );
		add_filter( 'the_generator', '__return_empty_string' );
		add_filter( 'style_loader_src', array( __CLASS__, 'strip_version' ), 10, 2 );
		add_filter( 'script_loader_src', array( __CLASS__, 'strip_version' ), 10, 2 );
	}

	/**
	 * Whether the module is switched on.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private static function enabled() {
		$settings = (array) get_option( 'arena_engine_settings', array() );

		return ! isset( $settings['security'] ) || ! empty( $settings['security'] );
	}

	/**
	 * Sends a conservative header set.
	 *
	 * A Content-Security-Policy is offered through a filter rather than forced:
	 * WordPress core itself prints an inline skip-link script, and a strict
	 * policy would break keyboard navigation on sites that have not audited it.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function headers() {
		if ( headers_sent() ) {
			return;
		}

		header( 'X-Content-Type-Options: nosniff' );
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
		header( 'X-Frame-Options: SAMEORIGIN' );
		header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(self)' );
		header( 'Cross-Origin-Opener-Policy: same-origin-allow-popups' );

		$csp = apply_filters( 'arena_engine_csp', '' );

		if ( is_string( $csp ) && '' !== $csp ) {
			header( 'Content-Security-Policy: ' . $csp );
		}
	}

	/**
	 * Removes the version header WordPress sends on every response.
	 *
	 * @since 1.0.0
	 *
	 * @param array $headers Response headers.
	 * @return array
	 */
	public static function remove_generator( $headers ) {
		if ( isset( $headers['X-Powered-By'] ) ) {
			unset( $headers['X-Powered-By'] );
		}

		return $headers;
	}

	/**
	 * Replaces the WordPress version in asset query strings with a stable hash.
	 *
	 * Core appends `?ver=6.9.7`, which publishes the exact patch level of every
	 * install to anyone who views source.
	 *
	 * @since 1.0.0
	 *
	 * @param string $src    Asset URL.
	 * @param string $handle Asset handle.
	 * @return string
	 */
	public static function strip_version( $src, $handle ) {
		unset( $handle );

		if ( false === strpos( (string) $src, 'ver=' ) ) {
			return $src;
		}

		return (string) preg_replace( '/ver=[0-9][^&]*/', 'ver=' . substr( md5( (string) wp_salt( 'auth' ) ), 0, 10 ), (string) $src );
	}
}
