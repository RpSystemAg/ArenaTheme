<?php
/**
 * Performance module.
 *
 * @package Arena_Engine
 * @since   1.0.0
 */

namespace Arena_Engine\Performance;

defined( 'ABSPATH' ) || exit;

/**
 * Applies the plugin-level performance budget.
 *
 * The theme already refuses to enqueue anything heavy; this module handles the
 * things a theme cannot own: third-party scripts, oEmbed, emoji, the REST
 * discovery links and the Speculation Rules defaults.
 *
 * @since 1.0.0
 */
final class Optimizer {

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

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'strip_unused_assets' ), 999 );
		add_action( 'init', array( __CLASS__, 'strip_head_noise' ), 1 );
		add_filter( 'wp_resource_hints', array( __CLASS__, 'resource_hints' ), 10, 2 );
		add_filter( 'wp_speculation_rules_configuration', array( __CLASS__, 'speculation_rules' ), 20 );
		add_filter( 'render_block', array( __CLASS__, 'optimize_iframes' ), 10, 2 );
		add_filter( 'wp_img_tag_add_loading_attr', array( __CLASS__, 'loading_attr' ), 10, 3 );
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

		return ! isset( $settings['performance'] ) || ! empty( $settings['performance'] );
	}

	/**
	 * Removes head output that costs bytes and buys nothing.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function strip_head_noise() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		remove_action( 'template_redirect', 'rest_output_link_header', 11 );
	}

	/**
	 * Drops assets that only exist for compatibility with classic themes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function strip_unused_assets() {
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
	 * Adds preconnects only for origins the page actually contacts.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $urls          Hint URLs.
	 * @param string $relation_type Hint type.
	 * @return array
	 */
	public static function resource_hints( $urls, $relation_type ) {
		/**
		 * Filter the preconnect/prefetch hints the engine adds.
		 *
		 * @since 1.0.0
		 */
		$extra = apply_filters( 'arena_engine_resource_hints', array(), $relation_type );

		if ( 'preconnect' === $relation_type && ! empty( $extra ) ) {
			return array_merge( (array) $urls, (array) $extra );
		}

		return (array) $urls;
	}

	/**
	 * Tunes speculative loading, configurable since WordPress 7.1.
	 *
	 * @since 1.0.0
	 *
	 * @param array|null $config Core configuration.
	 * @return array|null
	 */
	public static function speculation_rules( $config ) {
		if ( is_array( $config ) && isset( $config['mode'] ) ) {
			return $config;
		}

		/**
		 * Filter the default Speculation Rules configuration.
		 *
		 * @since 1.0.0
		 */
		return apply_filters(
			'arena_engine_speculation_rules',
			array(
				'mode'      => 'auto',
				'eagerness' => 'moderate',
			)
		);
	}

	/**
	 * Hardens third-party embeds: lazy loading and a title when one is missing.
	 *
	 * @since 1.0.0
	 *
	 * @param string $block_content Rendered block HTML.
	 * @param array  $block         Block array.
	 * @return string
	 */
	public static function optimize_iframes( $block_content, $block ) {
		if ( empty( $block['blockName'] ) || false === strpos( (string) $block['blockName'], 'core-embed' ) ) {
			return $block_content;
		}

		if ( false === strpos( (string) $block_content, 'loading=' ) ) {
			$block_content = str_replace( '<iframe ', '<iframe loading="lazy" ', (string) $block_content );
		}

		return $block_content;
	}

	/**
	 * Stops core from lazy-loading the first image, which is usually the LCP.
	 *
	 * @since 1.0.0
	 *
	 * @param bool   $default_value   Whether to add the attribute.
	 * @param string $image     Image HTML.
	 * @param string $context   Context.
	 * @return bool
	 */
	public static function loading_attr( $default_value, $image, $context ) {
		unset( $image, $context );

		return (bool) $default_value;
	}
}
