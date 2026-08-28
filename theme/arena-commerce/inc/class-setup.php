<?php
/**
 * Theme setup: supports, image sizes, menus and editor wiring.
 *
 * @package Arena_Theme
 * @since   1.0.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Registers everything WordPress needs to know about the theme.
 *
 * @since 1.0.0
 */
final class Setup {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		load_theme_textdomain( ARENA_THEME_DOMAIN, get_theme_file_path( 'languages' ) );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'html5', array( 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets', 'search-form' ) );
		add_theme_support(
			'custom-logo',
			array(
				'height'               => 96,
				'width'                => 320,
				'flex-height'          => true,
				'flex-width'           => true,
				'unlink-homepage-logo' => true,
			)
		);

		add_editor_style( array( 'assets/css/arena-editor.css' ) );

		register_nav_menus(
			array(
				'primary' => __( 'Primary menu', 'arena-commerce' ),
				'footer'  => __( 'Footer menu', 'arena-commerce' ),
			)
		);

		add_action( 'after_setup_theme', array( __CLASS__, 'content_width' ), 0 );
		add_action( 'after_setup_theme', array( __CLASS__, 'image_sizes' ), 11 );

		add_filter( 'image_size_names_choose', array( __CLASS__, 'image_size_labels' ) );
		add_filter( 'body_class', array( __CLASS__, 'body_classes' ) );
		add_filter( 'excerpt_length', array( __CLASS__, 'excerpt_length' ) );
		add_filter( 'excerpt_more', array( __CLASS__, 'excerpt_more' ) );
		add_filter( 'comment_form_defaults', array( __CLASS__, 'comment_form_defaults' ) );
	}

	/**
	 * Sets the content width used by embeds and legacy image markup.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function content_width() {
		$GLOBALS['content_width'] = (int) apply_filters( 'arena_theme_content_width', 1240 );
	}

	/**
	 * Registers commerce-first image sizes.
	 *
	 * Product imagery is 4:5 because that is the ratio marketplaces and social
	 * platforms crop to, and a fixed ratio removes layout shift from grids.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function image_sizes() {
		add_image_size( 'arena-card', 640, 800, true );
		add_image_size( 'arena-card-lg', 960, 1200, true );
		add_image_size( 'arena-hero', 1600, 900, true );
		add_image_size( 'arena-square', 600, 600, true );
		add_image_size( 'arena-story', 1080, 1920, true );
		add_image_size( 'arena-logo', 320, 96, false );
	}

	/**
	 * Makes the custom sizes selectable in the editor.
	 *
	 * @since 1.0.0
	 *
	 * @param array $sizes Existing size labels.
	 * @return array
	 */
	public static function image_size_labels( $sizes ) {
		return array_merge(
			(array) $sizes,
			array(
				'arena-card'    => __( 'Arena card (4:5)', 'arena-commerce' ),
				'arena-card-lg' => __( 'Arena card large (4:5)', 'arena-commerce' ),
				'arena-hero'    => __( 'Arena hero (16:9)', 'arena-commerce' ),
				'arena-square'  => __( 'Arena square (1:1)', 'arena-commerce' ),
				'arena-story'   => __( 'Arena story (9:16)', 'arena-commerce' ),
				'arena-logo'    => __( 'Arena logo', 'arena-commerce' ),
			)
		);
	}

	/**
	 * Adds state classes used by the stylesheet.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public static function body_classes( $classes ) {
		$classes[] = 'arena-commerce';

		if ( is_singular() ) {
			$classes[] = 'arena-singular';
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$classes[] = 'arena-has-woocommerce';
		}

		if ( is_admin_bar_showing() ) {
			$classes[] = 'arena-admin-bar';
		}

		return $classes;
	}

	/**
	 * Caps automatic excerpts so card grids stay visually even.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public static function excerpt_length() {
		return 24;
	}

	/**
	 * Replaces the default ellipsis with a typographic one.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function excerpt_more() {
		return '&hellip;';
	}

	/**
	 * Adds accessible defaults to the comment form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $defaults Comment form defaults.
	 * @return array
	 */
	public static function comment_form_defaults( $defaults ) {
		$defaults['class_form']         = 'arena-form arena-form--comments';
		$defaults['class_submit']       = 'wp-element-button';
		$defaults['title_reply_before'] = '<h2 id="reply-title" class="comment-reply-title">';
		$defaults['title_reply_after']  = '</h2>';

		return $defaults;
	}
}
