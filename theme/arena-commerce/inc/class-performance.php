<?php
/**
 * Performance layer.
 *
 * Rules this file enforces:
 *
 *   1. The LCP image is never lazy-loaded and is marked high priority.
 *   2. No third-party origin is contacted from the front end.
 *   3. Speculative loading is tuned instead of left at core defaults.
 *   4. Security headers are sent on every response.
 *
 * Head-stripping (`remove_action( 'wp_head', ... )`) is deliberately NOT done
 * here: Theme Check classifies it as plugin territory, and the companion
 * arena-engine plugin owns it in `Performance\Optimizer`.
 *
 * @package Arena_Theme
 * @since   1.0.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Applies the theme's performance and hardening defaults.
 *
 * @since 1.0.0
 */
final class Performance {

	/**
	 * Whether the LCP candidate on the current page has been claimed.
	 *
	 * @var bool
	 */
	private static $lcp_claimed = false;

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		add_filter( 'wp_get_attachment_image_attributes', array( __CLASS__, 'image_attributes' ), 20, 3 );
		add_filter( 'wp_lazy_loading_enabled', array( __CLASS__, 'lazy_loading_enabled' ), 10, 3 );
		add_filter( 'wp_speculation_rules_configuration', array( __CLASS__, 'speculation_rules' ) );
		add_action( 'send_headers', array( __CLASS__, 'send_security_headers' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'dequeue_front_end_extras' ), 100 );
		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'pings_open', '__return_false' );
	}

	/**
	 * Optimises every attachment rendered on the front end.
	 *
	 * The first above-the-fold image is treated as the LCP candidate: eager and
	 * high priority. Everything else is lazy and async-decoded.
	 *
	 * @since 1.0.0
	 *
	 * @param array        $attr       Image attributes.
	 * @param \WP_Post     $attachment Attachment post.
	 * @param string|int[] $size       Requested size.
	 * @return array
	 */
	public static function image_attributes( $attr, $attachment, $size ) {
		if ( is_admin() || is_feed() ) {
			return $attr;
		}

		$attr['decoding'] = 'async';

		if ( ! self::$lcp_claimed && self::is_lcp_candidate( $attachment, $size ) ) {
			self::$lcp_claimed     = true;
			$attr['loading']       = 'eager';
			$attr['fetchpriority'] = 'high';
			$attr['decoding']      = 'sync';
		} elseif ( ! isset( $attr['loading'] ) ) {
			$attr['loading'] = 'lazy';
		}

		return $attr;
	}

	/**
	 * Decides whether an image is the likely Largest Contentful Paint element.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Post     $attachment Attachment post.
	 * @param string|int[] $size       Requested size.
	 * @return bool
	 */
	private static function is_lcp_candidate( $attachment, $size ) {
		if ( ! is_singular() && ! is_front_page() ) {
			return false;
		}

		if ( has_post_thumbnail() && (int) get_post_thumbnail_id() === (int) $attachment->ID ) {
			return true;
		}

		return in_array( $size, array( 'arena-hero', 'arena-story', 'arena-card-lg', 'large', 'full' ), true );
	}

	/**
	 * Stops core from lazy-loading the LCP image.
	 *
	 * @since 1.0.0
	 *
	 * @param bool   $default_value  Whether lazy loading is enabled.
	 * @param string $context  Context, e.g. "img".
	 * @param string $tag_name Tag name.
	 * @return bool
	 */
	public static function lazy_loading_enabled( $default_value, $context, $tag_name ) {
		unset( $context, $tag_name );

		if ( self::$lcp_claimed ) {
			return (bool) $default_value;
		}

		return ! ( is_singular() || is_front_page() );
	}

	/**
	 * Tunes the Speculation Rules API introduced and made configurable in 7.1.
	 *
	 * `auto` mode with moderate eagerness prefetches the links a shopper is
	 * most likely to follow next, which is the single cheapest way to cut
	 * perceived navigation latency on a storefront.
	 *
	 * @since 1.0.0
	 *
	 * @param array|null $config Core speculation rules configuration.
	 * @return array|null
	 */
	public static function speculation_rules( $config ) {
		if ( is_array( $config ) && isset( $config['mode'] ) ) {
			return $config;
		}

		return apply_filters(
			'arena_theme_speculation_rules',
			array(
				'mode'      => 'auto',
				'eagerness' => 'moderate',
			)
		);
	}

	/**
	 * Sends a conservative set of security headers.
	 *
	 * A Content-Security-Policy is deliberately not forced: WordPress core
	 * itself prints an inline skip-link script, and a policy that blocks it
	 * would break keyboard navigation across the ecosystem. Use the
	 * `arena_theme_csp` filter to opt in once you have audited your stack.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function send_security_headers() {
		if ( headers_sent() || ! apply_filters( 'arena_theme_security_headers', true ) ) {
			return;
		}

		header( 'X-Content-Type-Options: nosniff' );
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
		header( 'X-Frame-Options: SAMEORIGIN' );
		header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(self)' );
		header( 'Cross-Origin-Opener-Policy: same-origin-allow-popups' );

		$csp = apply_filters( 'arena_theme_csp', '' );

		if ( is_string( $csp ) && '' !== $csp ) {
			header( 'Content-Security-Policy: ' . $csp );
		}
	}

	/**
	 * Drops front-end assets that only exist for classic themes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function dequeue_front_end_extras() {
		if ( is_admin() ) {
			return;
		}

		wp_dequeue_script( 'wp-embed' );
		wp_dequeue_script( 'jquery-migrate' );

		if ( ! is_user_logged_in() ) {
			wp_dequeue_style( 'dashicons' );
		}
	}

	/**
	 * Resets the LCP flag. Exposed for tests.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function reset_lcp_flag() {
		self::$lcp_claimed = false;
	}
}
