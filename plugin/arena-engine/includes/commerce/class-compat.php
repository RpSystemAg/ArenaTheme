<?php
/**
 * Wishlist & compare adapters (H37).
 *
 * Registered ONLY when the target plugin is active (YITH Wishlist, TI
 * WooCommerce Wishlist, Jetpack-related wishlists). The theme renders the
 * header wishlist slot only when an adapter returns a URL through the
 * `arena_theme_wishlist_url` filter; adapters never load when the plugin is
 * absent.
 *
 * Documented hooks (docs/dev/hooks.md):
 *   arena_theme_wishlist_url      — header slot URL (null hides the slot)
 *   arena_theme_compare_url       — compare page URL (null hides the slot)
 *   arena_theme_wishlist_button   — per-product button markup
 *   arena_engine_compat_active    — reports the active adapter slug
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\Commerce;

defined( 'ABSPATH' ) || exit;

/**
 * Wishlist / compare compatibility layer.
 *
 * @since 1.1.0
 */
final class Compat {

	/**
	 * Attaches the adapters, each guarded by plugin presence (H37).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		$active = array();

		if ( self::yith_active() ) {
			add_filter( 'arena_theme_wishlist_url', array( __CLASS__, 'yith_url' ) );
			add_filter( 'arena_theme_wishlist_button', array( __CLASS__, 'yith_button' ), 10, 2 );
			$active[] = 'yith-wishlist';
		}

		if ( self::ti_active() ) {
			add_filter( 'arena_theme_wishlist_url', array( __CLASS__, 'ti_url' ) );
			add_filter( 'arena_theme_wishlist_button', array( __CLASS__, 'ti_button' ), 10, 2 );
			$active[] = 'ti-wishlist';
		}

		if ( defined( 'JETPACK__VERSION' ) ) {
			add_filter( 'arena_theme_compare_url', array( __CLASS__, 'jetpack_compare_url' ) );
			$active[] = 'jetpack';
		}

		if ( ! $active ) {
			return;
		}

		/**
		 * Reports the active commerce adapters (H37).
		 *
		 * @since 1.1.0
		 *
		 * @param string[] $active Adapter slugs (yith-wishlist, ti-wishlist, jetpack…).
		 */
		do_action( 'arena_engine_compat_active', $active );
	}

	/**
	 * YITH WooCommerce Wishlist active?
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public static function yith_active() {
		return defined( 'YITH_WCWL' ) || defined( 'YITH_WCWL_VERSION' );
	}

	/**
	 * TI WooCommerce Wishlist active?
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public static function ti_active() {
		return defined( 'TINVWL_VERSION' ) || class_exists( 'TInvWL' );
	}

	/**
	 * YITH wishlist page URL.
	 *
	 * @since 1.1.0
	 *
	 * @param string|null $url Default null (slot hidden).
	 * @return string
	 */
	public static function yith_url( $url ) {
		return function_exists( 'yith_wcwl_object_count' ) || shortcode_exists( 'yith_wcwl_wishlist' )
			? get_permalink( (int) get_option( 'yith_wcwl_wishlist_page_id' ) )
			: $url;
	}

	/**
	 * TI wishlist page URL.
	 *
	 * @since 1.1.0
	 *
	 * @param string|null $url Default null (slot hidden).
	 * @return string
	 */
	public static function ti_url( $url ) {
		$page_id = (int) get_option( 'tinvwl_page_wishlist', 0 );

		return $page_id ? get_permalink( $page_id ) : $url;
	}

	/**
	 * YITH per-product wishlist button.
	 *
	 * @since 1.1.0
	 *
	 * @param string     $markup  Default empty.
	 * @param \WC_Product $product Product.
	 * @return string
	 */
	public static function yith_button( $markup, $product ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		return shortcode_exists( 'yith_wcwl_add_to_wishlist' )
			? do_shortcode( '[yith_wcwl_add_to_wishlist product_id="' . ( $product ? $product->get_id() : 0 ) . '"]' )
			: $markup;
	}

	/**
	 * TI per-product wishlist button.
	 *
	 * @since 1.1.0
	 *
	 * @param string     $markup  Default empty.
	 * @param \WC_Product $product Product.
	 * @return string
	 */
	public static function ti_button( $markup, $product ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		return shortcode_exists( 'ti_wishlist_products_loop' )
			? do_shortcode( '[ti_wishlist_products_loop product_id="' . ( $product ? $product->get_id() : 0 ) . '"]' )
			: $markup;
	}

	/**
	 * Jetpack compare URL placeholder (Jetpack has no native compare; hosts
	 * can override through the same filter).
	 *
	 * @since 1.1.0
	 *
	 * @param string|null $url Default null.
	 * @return string|null
	 */
	public static function jetpack_compare_url( $url ) {
		return apply_filters( 'arena_theme_compare_url_custom', $url );
	}
}
