<?php
/**
 * Mini-cart drawer and commerce shells (H29/H33/H34).
 *
 * Renders the server-side shells — cart drawer, sticky mobile cart bar,
 * quick-view dialog, gallery lightbox and the off-canvas filter drawer — so
 * the markup is in the first HTML paint (SEO, no-JS, assistive technology).
 * The arena-cart / arena-shop script modules fill and drive them through the
 * Store API; the rich panel CSS loads on first open (H45/G15).
 *
 * @package Arena_Theme
 * @since   1.1.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Cart drawer and commerce panel shells.
 *
 * @since 1.1.0
 */
final class Cart {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'wp_footer', array( __CLASS__, 'render_shells' ), 98 );
	}

	/**
	 * Renders the commerce shells when WooCommerce is active.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function render_shells() {
		if ( ! class_exists( 'WooCommerce' ) || is_admin() || wp_doing_ajax() ) {
			return;
		}

		$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout/' );
		$cart_url     = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
		?>
		<div class="arena-cart-drawer" id="arena-cart-drawer" data-arena-dialog hidden aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Shopping cart', 'arena-commerce' ); ?>">
			<div class="arena-cart-drawer__header">
				<h2 class="arena-cart-drawer__title" style="margin:0;"><?php esc_html_e( 'Your cart', 'arena-commerce' ); ?></h2>
				<button type="button" data-arena-dialog-close aria-label="<?php esc_attr_e( 'Close cart', 'arena-commerce' ); ?>">✕</button>
			</div>
			<div class="arena-cart-drawer__body" data-arena-cart-body>
				<p><?php esc_html_e( 'Your cart is empty.', 'arena-commerce' ); ?></p>
			</div>
			<div class="arena-cart-drawer__footer" data-arena-cart-footer></div>
		</div>

		<div class="arena-sticky-cart-bar" aria-label="<?php esc_attr_e( 'Cart actions', 'arena-commerce' ); ?>">
			<a href="<?php echo esc_url( $cart_url ); ?>"><?php esc_html_e( 'View cart', 'arena-commerce' ); ?></a>
			<button type="button" class="wp-element-button" data-arena-cart-open>
				<?php esc_html_e( 'Checkout', 'arena-commerce' ); ?>
			</button>
		</div>

		<div class="arena-quickview" id="arena-quickview" data-arena-dialog hidden aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Quick view', 'arena-commerce' ); ?>"></div>

		<div class="arena-lightbox" id="arena-lightbox" data-arena-dialog hidden aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Image preview', 'arena-commerce' ); ?>"></div>
		<?php

		if ( self::is_filterable_archive() ) {
			?>
			<div class="arena-filters" id="arena-filters" data-arena-dialog hidden aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Filters', 'arena-commerce' ); ?>">
				<div style="display:flex;justify-content:space-between;align-items:center;">
					<h2 style="margin:0;"><?php esc_html_e( 'Filters', 'arena-commerce' ); ?></h2>
					<button type="button" data-arena-dialog-close aria-label="<?php esc_attr_e( 'Close filters', 'arena-commerce' ); ?>">✕</button>
				</div>
				<div data-arena-filters-zone>
					<?php
					/**
					 * Fires inside the off-canvas filter drawer (H34): the Woo
					 * filter blocks (or any widget) can be assigned here.
					 *
					 * @since 1.1.0
					 */
					do_action( 'arena_theme_filters_zone' );
					?>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Whether the current archive renders the filter drawer.
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	private static function is_filterable_archive() {
		return function_exists( 'is_woocommerce' ) && ( is_shop() || is_product_taxonomy() );
	}
}
