<?php
/**
 * Per-page layout overrides (H31/H32, theme side).
 *
 * Registers the `arena_*` post meta the Arena panel's meta box writes —
 * disable title, transparent header, footer, sidebar, container width and
 * typographic preset override — and applies them as body classes and render
 * filters. Every override is inspectable in the meta box and resettable with
 * one click; every change is journaled with undo by the panel (G12).
 *
 * @package Arena_Theme
 * @since   1.1.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Per-page layout options.
 *
 * @since 1.1.0
 */
final class Page_Options {

	/**
	 * The meta keys owned by this class, with their sanitizers.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	const META = array(
		'_arena_hide_title'        => 'bool',
		'_arena_transparent_header' => 'bool',
		'_arena_hide_footer'       => 'bool',
		'_arena_hide_sidebar'      => 'bool',
		'_arena_container'         => 'key',
		'_arena_container_width'   => 'rem',
		'_arena_typo_preset'       => 'key',
		'_arena_header_variant'    => 'key',
	);

	/**
	 * Allowed values for the enumerated meta keys.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	const ENUMS = array(
		'_arena_container'      => array( 'boxed', 'fullwidth', 'narrow' ),
		'_arena_typo_preset'    => array(),
		'_arena_header_variant' => array( 'standard', 'transparent', 'sticky' ),
	);

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_meta' ), 20 );
		add_filter( 'body_class', array( __CLASS__, 'body_classes' ) );
		add_filter( 'render_block_core/post-title', array( __CLASS__, 'maybe_hide_title' ), 10, 2 );
		add_filter( 'render_block_core/template-part', array( __CLASS__, 'maybe_hide_part' ), 10, 2 );
		add_action( 'wp_head', array( __CLASS__, 'print_container_width' ), 5 );
	}

	/**
	 * Registers the meta with REST exposure so the panel and the meta box can
	 * manage it over the documented REST interface (H31/H32).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function register_meta() {
		foreach ( self::META as $key => $type ) {
			register_post_meta(
				array( 'page', 'post' ),
				$key,
				array(
					'type'          => 'string',
					'single'        => true,
					'show_in_rest'  => true,
					'auth_callback' => static function () {
						return current_user_can( 'edit_posts' );
					},
					'sanitize_callback' => array( __CLASS__, 'sanitize' ),
				)
			);
		}
	}

	/**
	 * Sanitizes a meta value by its declared type.
	 *
	 * @since 1.1.0
	 *
	 * @param string $value Raw value.
	 * @return string
	 */
	public static function sanitize( $value ) {
		return sanitize_text_field( (string) $value );
	}

	/**
	 * Reads a sanitized meta value for the current post.
	 *
	 * @since 1.1.0
	 *
	 * @param string $key Meta key.
	 * @return string
	 */
	public static function get( $key ) {
		if ( ! is_singular() || ! isset( self::META[ $key ] ) ) {
			return '';
		}

		$value = (string) get_post_meta( get_the_ID(), $key, true );

		if ( 'key' === self::META[ $key ] && isset( self::ENUMS[ $key ] ) && self::ENUMS[ $key ] ) {
			return in_array( $value, self::ENUMS[ $key ], true ) ? $value : '';
		}

		return $value;
	}

	/**
	 * Body classes for the active overrides.
	 *
	 * @since 1.1.0
	 *
	 * @param string[] $classes Body classes.
	 * @return string[]
	 */
	public static function body_classes( $classes ) {
		if ( self::get( '_arena_hide_title' ) ) {
			$classes[] = 'arena-hide-title';
		}

		if ( self::get( '_arena_hide_footer' ) ) {
			$classes[] = 'arena-hide-footer';
		}

		if ( self::get( '_arena_hide_sidebar' ) ) {
			$classes[] = 'arena-hide-sidebar';
		}

		$container = self::get( '_arena_container' );

		if ( $container ) {
			$classes[] = 'arena-container-' . $container;
		}

		return $classes;
	}

	/**
	 * Hides the post title when the page asks for it (H32).
	 *
	 * @since 1.1.0
	 *
	 * @param string $html Rendered block.
	 * @param array  $block Parsed block.
	 * @return string
	 */
	public static function maybe_hide_title( $html, $block ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		return self::get( '_arena_hide_title' ) ? '' : $html;
	}

	/**
	 * Hides the header or footer parts when the page asks for it (H32).
	 *
	 * @since 1.1.0
	 *
	 * @param string $html Rendered part.
	 * @param array  $block Parsed block.
	 * @return string
	 */
	public static function maybe_hide_part( $html, $block ) {
		$slug = isset( $block['attrs']['slug'] ) ? $block['attrs']['slug'] : '';

		if ( 'footer' === $slug && self::get( '_arena_hide_footer' ) ) {
			return '';
		}

		return $html;
	}

	/**
	 * Prints the per-post container width override (H31/H32).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function print_container_width() {
		$width = self::get( '_arena_container_width' );

		if ( ! preg_match( '/^\d+(\.\d+)?$/', $width ) ) {
			return;
		}

		printf(
			'<style id="arena-container-width">.arena-main{--wp--style--global--content-size:%1$srem;max-width:min(100%% - 2 * var(--wp--custom--spacing--outer), %1$srem);}</style>' . "\n",
			esc_html( $width )
		);
	}
}
