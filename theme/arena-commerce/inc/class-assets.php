<?php
/**
 * Front-end asset pipeline, decoupled per module (H45).
 *
 * The strategy stays deliberately boring because boring is fast: one preloaded
 * global stylesheet, one deferred core script, and component CSS/JS that load
 * only when the page actually renders that component. No jQuery, no icon
 * font, no third-party origin is contacted.
 *
 * Module registry (v3.1):
 *   core        arena.css + arena.js                    every page
 *   dark        modules/arena-dark.css                   every page (H47)
 *   motion      modules/arena-motion.{css,js}            carousels / marquees only
 *   commerce    modules/arena-commerce.css, arena-cart.js WooCommerce active
 *   cart        modules/arena-cart.css                   on first panel open (H45/G15)
 *   shop        modules/arena-shop.js                    shop / product archives
 *   blog        modules/arena-blog.css                   blog, archive, search, single
 *   megamenu    modules/arena-megamenu.{css,js}          when the header uses it (H28)
 *   search      modules/arena-search.{css,js}            when a search block exists (H29)
 *   checkout    modules/arena-checkout.css, arena-account.js  checkout / account (H36)
 *
 * WooCommerce's own stylesheets are dequeued on non-Woo pages so a blog post
 * ships zero commerce bytes (G15).
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
	 * Per-request cache of the scanned page markup.
	 *
	 * @var string|null
	 */
	private static $page_markup = null;

	/**
	 * Modules already loaded during this request.
	 *
	 * @var string[]
	 */
	private static $loaded = array();

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

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_modules' ), 20 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'dequeue_off_context_woo' ), 99 );
		add_action( 'wp_head', array( __CLASS__, 'print_runtime_config' ), 8 );
	}

	/**
	 * The module registry. Each module declares its CSS/JS files and the
	 * conditions under which each asset type loads.
	 *
	 * Conditions are method names on this class receiving the module slug.
	 *
	 * @since 1.1.0
	 *
	 * @return array[]
	 */
	public static function modules() {
		$modules = array(
			'dark'     => array(
				'css'       => array( 'modules/arena-dark.css' ),
				'js'        => array(),
				'css_when'  => 'always',
				'js_when'   => 'never',
			),
			'motion'   => array(
				'css'       => array( 'modules/arena-motion.css' ),
				'js'        => array( 'modules/arena-motion.js' ),
				'css_when'  => 'has_motion_markup',
				'js_when'   => 'has_motion_markup',
			),
			'blog'     => array(
				'css'       => array( 'modules/arena-blog.css' ),
				'js'        => array(),
				'css_when'  => 'is_blog_context',
				'js_when'   => 'never',
			),
			'commerce' => array(
				'css'       => array( 'modules/arena-commerce.css' ),
				'js'        => array( 'modules/arena-cart.js' ),
				'css_when'  => 'is_woo_page',
				'js_when'   => 'is_woo_active',
			),
			'shop'     => array(
				'css'       => array(),
				'js'        => array( 'modules/arena-shop.js' ),
				'css_when'  => 'never',
				'js_when'   => 'is_shop_context',
			),
			'megamenu' => array(
				'css'       => array( 'modules/arena-megamenu.css' ),
				'js'        => array( 'modules/arena-megamenu.js' ),
				'css_when'  => 'has_megamenu_markup',
				'js_when'   => 'has_megamenu_markup',
			),
			'search'   => array(
				'css'       => array( 'modules/arena-search.css' ),
				'js'        => array( 'modules/arena-search.js' ),
				'css_when'  => 'has_search_block',
				'js_when'   => 'has_search_block',
			),
			'checkout' => array(
				'css'       => array( 'modules/arena-checkout.css' ),
				'js'        => array( 'modules/arena-account.js' ),
				'css_when'  => 'is_checkout_or_account',
				'js_when'   => 'is_checkout_or_account',
			),
			'cart'     => array(
				/* Loaded at runtime on first panel open; url printed in config. */
				'css'       => array( 'modules/arena-cart.css' ),
				'js'        => array(),
				'css_when'  => 'never',
				'js_when'   => 'never',
			),
		);

		/**
		 * Filter the asset module registry (child themes can add modules).
		 *
		 * @since 1.1.0
		 *
		 * @param array[] $modules Module definitions.
		 */
		return apply_filters( 'arena_theme_asset_modules', $modules );
	}

	/**
	 * Registers the global stylesheet and marks it for preload.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function register_styles() {
		$file = get_theme_file_path( 'assets/css/arena.css' );

		wp_register_style( self::STYLE_HANDLE, self::style_src( 'assets/css/arena.css' ), array(), self::asset_version( $file ) );
		wp_style_add_data( self::STYLE_HANDLE, 'preload', true );
		wp_enqueue_style( self::STYLE_HANDLE );
	}

	/**
	 * Registers the progressive-enhancement core script.
	 *
	 * `strategy => defer` (not `in_footer`) keeps the file discoverable during
	 * parsing while never blocking rendering.
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

		/* Script modules (on-demand, H45): registered up-front, enqueued by
		   condition so the browser only fetches what the page renders. */
		foreach ( self::modules() as $slug => $module ) {
			foreach ( $module['js'] as $index => $path ) {
				wp_register_script_module(
					'arena-' . $slug . ( $index ? '-' . $index : '' ),
					get_theme_file_uri( 'assets/js/' . $path ),
					array(),
					self::asset_version( get_theme_file_path( 'assets/js/' . $path ) )
				);
			}
		}
	}

	/**
	 * Loads the modules whose conditions match the current request (H45).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function enqueue_modules() {
		foreach ( self::modules() as $slug => $module ) {
			if ( 'never' !== $module['css_when'] && self::condition( $module['css_when'], $slug ) ) {
				self::load_module_styles( $slug );
			}

			if ( 'never' !== $module['js_when'] && self::condition( $module['js_when'], $slug ) ) {
				self::load_module_scripts( $slug );
			}
		}
	}

	/**
	 * Loads a module's stylesheets by slug (public so parts can opt in).
	 *
	 * @since 1.1.0
	 *
	 * @param string $slug Module slug.
	 * @return void
	 */
	public static function load_module_styles( $slug ) {
		$module = isset( self::modules()[ $slug ] ) ? self::modules()[ $slug ] : null;

		if ( ! $module ) {
			return;
		}

		foreach ( $module['css'] as $index => $path ) {
			$handle = 'arena-' . $slug . ( $index ? '-' . $index : '' );
			$file   = get_theme_file_path( 'assets/css/' . $path );

			wp_enqueue_style( $handle, self::style_src( $path ), array(), self::asset_version( $file ) );
		}

		self::$loaded[] = $slug . ':css';
	}

	/**
	 * Loads a module's scripts by slug.
	 *
	 * @since 1.1.0
	 *
	 * @param string $slug Module slug.
	 * @return void
	 */
	public static function load_module_scripts( $slug ) {
		$module = isset( self::modules()[ $slug ] ) ? self::modules()[ $slug ] : null;

		if ( ! $module ) {
			return;
		}

		foreach ( $module['js'] as $index => $path ) {
			wp_enqueue_script_module( 'arena-' . $slug . ( $index ? '-' . $index : '' ) );
		}

		self::$loaded[] = $slug . ':js';
	}

	/**
	 * Evaluates a named loading condition.
	 *
	 * @since 1.1.0
	 *
	 * @param string $condition Condition name (a method on this class).
	 * @param string $slug      Module slug being evaluated.
	 * @return bool
	 */
	public static function condition( $condition, $slug ) {
		if ( method_exists( __CLASS__, $condition ) ) {
			return (bool) self::$condition( $slug );
		}

		return false;
	}

	/* ---------------------------------------------------------- Conditions */

	/**
	 * Always true.
	 *
	 * @return bool
	 */
	private static function always() {
		return true;
	}

	/**
	 * Never true.
	 *
	 * @return bool
	 */
	private static function never() {
		return false;
	}

	/**
	 * The page renders a carousel or a marquee.
	 *
	 * @return bool
	 */
	private static function has_motion_markup() {
		return (bool) preg_match( '/data-arena-carousel|arena-carousel|data-arena-marquee|arena-marquee/', self::page_markup() );
	}

	/**
	 * Blog, archive, search or single post view.
	 *
	 * @return bool
	 */
	private static function is_blog_context() {
		return is_home() || is_archive() || is_search() || ( is_singular() && 'post' === get_post_type() );
	}

	/**
	 * WooCommerce is active (cart JS is needed wherever the header drawer or
	 * an add-to-cart button may appear).
	 *
	 * @return bool
	 */
	private static function is_woo_active() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * A WooCommerce template is being rendered.
	 *
	 * @return bool
	 */
	private static function is_woo_page() {
		return self::is_woo_active()
			&& function_exists( 'is_woocommerce' )
			&& ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_product() );
	}

	/**
	 * Shop, product taxonomy or product page.
	 *
	 * @return bool
	 */
	private static function is_shop_context() {
		return self::is_woo_active()
			&& function_exists( 'is_woocommerce' )
			&& ( is_shop() || is_product_taxonomy() || is_product() );
	}

	/**
	 * Checkout or my-account page (H36).
	 *
	 * @return bool
	 */
	private static function is_checkout_or_account() {
		return self::is_woo_active()
			&& function_exists( 'is_checkout' )
			&& ( is_checkout() || is_account_page() );
	}

	/**
	 * The header renders the mega menu block or the mobile flyout (H28).
	 *
	 * @return bool
	 */
	private static function has_megamenu_markup() {
		return (bool) preg_match( '/arena\/mega-menu|data-arena-mega-menu|arena-flyout/', self::page_markup() );
	}

	/**
	 * A search block is rendered (live search, H29).
	 *
	 * @return bool
	 */
	private static function has_search_block() {
		return false !== strpos( self::page_markup(), 'wp:search' );
	}

	/* ------------------------------------------------------------- Helpers */

	/**
	 * Resolves a stylesheet URL, swapping in the generated RTL build when the
	 * locale is right-to-left (H41: explicit is_rtl() conditional enqueue).
	 *
	 * @since 1.1.0
	 *
	 * @param string $path Path relative to the theme root.
	 * @return string
	 */
	public static function style_src( $path ) {
		if ( is_rtl() ) {
			$rtl = preg_replace( '/\.css$/', '-rtl.css', $path );

			if ( is_readable( get_theme_file_path( $rtl ) ) ) {
				return get_theme_file_uri( $rtl );
			}
		}

		return get_theme_file_uri( $path );
	}

	/**
	 * Collects the markup of the current page for module detection: the block
	 * template, the header/footer parts, and the queried post content, with
	 * pattern references resolved to their real markup.
	 *
	 * Best-effort by design: a failed scan only means a module stays unloaded
	 * and its feature degrades to the no-JS baseline.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	private static function page_markup() {
		if ( null !== self::$page_markup ) {
			return self::$page_markup;
		}

		$markup = '';

		/* The resolved block template, set by locate_block_template(). */
		if ( isset( $GLOBALS['_wp_current_template_content'] ) ) {
			$markup .= (string) $GLOBALS['_wp_current_template_content'];
		}

		/* Header and footer parts are rendered on (almost) every page. */
		foreach ( array( 'header', 'footer' ) as $part ) {
			$content = '';

			if ( function_exists( 'get_block_template' ) ) {
				$found = get_block_template( get_stylesheet() . '//' . $part, 'wp_template_part' );

				if ( $found && ! empty( $found->content ) ) {
					$content = $found->content;
				}
			}

			if ( '' === $content && is_readable( get_theme_file_path( 'parts/' . $part . '.html' ) ) ) {
				$content = (string) file_get_contents( get_theme_file_path( 'parts/' . $part . '.html' ) );
			}

			$markup .= $content;
		}

		/* Singular content (patterns inserted in the editor are real markup). */
		$queried = get_queried_object();

		if ( $queried instanceof \WP_Post ) {
			$markup .= $queried->post_content;
		}

		$markup = self::resolve_pattern_refs( $markup );

		self::$page_markup = $markup;

		return $markup;
	}

	/**
	 * Expands `<!-- wp:pattern {"slug": "..."} -->` references into the real
	 * pattern markup so marker detection sees the rendered blocks.
	 *
	 * @since 1.1.0
	 *
	 * @param string $markup Combined markup.
	 * @return string
	 */
	private static function resolve_pattern_refs( $markup ) {
		if ( false === strpos( $markup, 'wp:pattern' ) ) {
			return $markup;
		}

		return preg_replace_callback(
			'/<!-- wp:pattern (\{[^\n]*?"slug"\s*:\s*"([^"]+)"[^\n]*?\}) \/-->/',
			static function ( $matches ) {
				$file = get_theme_file_path( 'patterns/' . str_replace( 'arena-commerce/', '', $matches[2] ) . '.php' );

				if ( ! is_readable( $file ) ) {
					return '';
				}

				$raw     = (string) file_get_contents( $file );
				$header  = strpos( $raw, '?>' );
				$content = false === $header ? $raw : substr( $raw, $header + 2 );

				return $content . '<!-- /wp:pattern -->';
			},
			$markup
		);
	}

	/**
	 * Dequeues WooCommerce's own assets on non-Woo pages (H45/G15).
	 *
	 * The Arena cart drawer is theme-owned and Store-API-driven, so the Woo
	 * bundles are pure waste outside Woo templates.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function dequeue_off_context_woo() {
		if ( ! class_exists( 'WooCommerce' ) || self::is_woo_page() ) {
			return;
		}

		/**
		 * Filter whether to dequeue WooCommerce assets off WooCommerce pages.
		 *
		 * @since 1.1.0
		 *
		 * @param bool $dequeue Default true.
		 */
		if ( ! apply_filters( 'arena_theme_dequeue_woo_off_context', true ) ) {
			return;
		}

		foreach ( array( 'woocommerce-layout', 'woocommerce-smallscreen', 'woocommerce-general', 'woocommerce', 'wc-add-to-cart', 'wc-cart-fragments', 'wc-blocks-style' ) as $handle ) {
			wp_dequeue_style( $handle );
			wp_dequeue_script( $handle );
		}
	}

	/**
	 * Prints the front-end runtime configuration.
	 *
	 * A JSON data block is used instead of `wp_localize_script()`: a
	 * `type="application/json"` element is never executed, so the theme stays
	 * compatible with a Content-Security-Policy that forbids inline scripts,
	 * and `wp-i18n` (~13 KB) never has to load just to translate labels.
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
				'version'   => ARENA_THEME_VERSION,
				'i18n'      => array(
					'nextSlide'     => __( 'Next slide', 'arena-commerce' ),
					'previousSlide' => __( 'Previous slide', 'arena-commerce' ),
					'pauseCarousel' => __( 'Pause automatic rotation', 'arena-commerce' ),
					'playCarousel'  => __( 'Start automatic rotation', 'arena-commerce' ),
					'closeDialog'   => __( 'Close', 'arena-commerce' ),
					'darkModeOn'    => __( 'Dark mode on', 'arena-commerce' ),
					'darkModeOff'   => __( 'Light mode on', 'arena-commerce' ),
				),
				'selectors' => array(
					'carousel' => '[data-arena-carousel]',
					'dialog'   => '[data-arena-dialog]',
					'marquee'  => '[data-arena-marquee]',
				),
				'cart'      => self::cart_config(),
				'search'    => array(
					'products' => class_exists( 'WooCommerce' ),
					'endpoint' => rest_url( 'wc/store/v1' ),
					'i18n'     => array(
						'noResults' => __( 'No results.', 'arena-commerce' ),
					),
				),
				'shop'      => array(
					'i18n' => array(
						'loadMore' => __( 'Load more', 'arena-commerce' ),
						'loading'  => __( 'Loading…', 'arena-commerce' ),
					),
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
	 * Store API configuration for the cart module (H33).
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	private static function cart_config() {
		$base = array(
			'enabled'     => false,
			'endpoint'    => rest_url( 'wc/store/v1' ),
			'cssUrl'      => self::style_src( 'assets/css/modules/arena-cart.css' ),
			'checkoutUrl' => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout/' ),
			'nonce'       => '',
			'i18n'        => array(
				'empty'     => __( 'Your cart is empty.', 'arena-commerce' ),
				'total'     => __( 'Total', 'arena-commerce' ),
				'checkout'  => __( 'Checkout', 'arena-commerce' ),
				'added'     => __( 'Added to your cart.', 'arena-commerce' ),
				'removed'   => __( 'Item removed.', 'arena-commerce' ),
				'undo'      => __( 'Undo', 'arena-commerce' ),
				'quantity'  => __( 'Quantity', 'arena-commerce' ),
				'increase'  => __( 'Increase quantity', 'arena-commerce' ),
				'decrease'  => __( 'Decrease quantity', 'arena-commerce' ),
				'remove'    => __( 'Remove item', 'arena-commerce' ),
				'addToCart' => __( 'Add to cart', 'arena-commerce' ),
			),
		);

		if ( ! class_exists( 'WooCommerce' ) ) {
			return $base;
		}

		$base['enabled'] = true;
		$base['nonce']   = wp_create_nonce( 'wc_store_api' );

		return $base;
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
