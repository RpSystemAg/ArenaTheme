<?php
/**
 * Front-end asset pipeline.
 *
 * The strategy is deliberately boring because boring is fast: one preloaded
 * stylesheet, one deferred module-free script, and everything else coming from
 * theme.json, which core inlines into the HTML. No jQuery, no icon font, no
 * third-party origin is contacted.
 *
 * @package Arena_Theme
 * @since   1.0.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Registers and optimises the theme's own assets.
 *
 * @since 1.0.0
 */
final class Assets {

	/**
	 * Handle of the main stylesheet.
	 *
	 * @var string
	 */
	const STYLE_HANDLE = 'arena-commerce';

	/**
	 * Handle of the main script.
	 *
	 * @var string
	 */
	const SCRIPT_HANDLE = 'arena-commerce';

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		self::register_styles();
		self::register_scripts();

		add_action( 'wp_head', array( __CLASS__, 'print_runtime_config' ), 8 );
	}

	/**
	 * Registers the stylesheet and marks it for preload.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function register_styles() {
		$file = get_theme_file_path( 'assets/css/arena.css' );

		wp_register_style( self::STYLE_HANDLE, get_theme_file_uri( 'assets/css/arena.css' ), array(), self::asset_version( $file ) );
		wp_style_add_data( self::STYLE_HANDLE, 'preload', true );
		wp_enqueue_style( self::STYLE_HANDLE );
	}

	/**
	 * Registers the progressive-enhancement script.
	 *
	 * `strategy => defer` (not `in_footer`) keeps the file discoverable during
	 * parsing while never blocking rendering, which is what INP and LCP both
	 * want.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function register_scripts() {
		$file = get_theme_file_path( 'assets/js/arena.js' );

		wp_register_script(
			self::SCRIPT_HANDLE,
			get_theme_file_uri( 'assets/js/arena.js' ),
			array(),
			self::asset_version( $file ),
			array(
				'strategy'  => 'defer',
				'in_footer' => false,
			)
		);

		wp_enqueue_script( self::SCRIPT_HANDLE );
	}

	/**
	 * Prints the front-end runtime configuration.
	 *
	 * A JSON data block is used instead of `wp_localize_script()`: a
	 * `type="application/json"` element is never executed, so the theme stays
	 * compatible with a Content-Security-Policy that forbids inline scripts, and
	 * `wp-i18n` (~13 KB) never has to load just to translate five labels.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function print_runtime_config() {
		if ( ! wp_script_is( self::SCRIPT_HANDLE, 'enqueued' ) && ! wp_script_is( self::SCRIPT_HANDLE, 'done' ) ) {
			return;
		}

		$config = apply_filters(
			'arena_theme_runtime_config',
			array(
				'version' => ARENA_THEME_VERSION,
				'i18n'    => array(
					'nextSlide'     => __( 'Next slide', 'arena-commerce' ),
					'previousSlide' => __( 'Previous slide', 'arena-commerce' ),
					'pauseCarousel' => __( 'Pause automatic rotation', 'arena-commerce' ),
					'playCarousel'  => __( 'Start automatic rotation', 'arena-commerce' ),
					'closeDialog'   => __( 'Close', 'arena-commerce' ),
				),
				'selectors' => array(
					'carousel' => '[data-arena-carousel]',
					'dialog'   => '[data-arena-dialog]',
					'marquee'  => '[data-arena-marquee]',
				),
			)
		);

		echo wp_get_inline_script_tag( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON data block escaped by core.
			(string) wp_json_encode( $config ),
			array(
				'type' => 'application/json',
				'id'   => 'arena-commerce-config',
			)
		);
	}

	/**
	 * Derives a cache-busting version from the file's modification time.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file Absolute path.
	 * @return string
	 */
	private static function asset_version( $file ) {
		$mtime = is_readable( $file ) ? filemtime( $file ) : false;

		return $mtime ? (string) $mtime : ARENA_THEME_VERSION;
	}
}
