<?php
/**
 * Header action slots (H29/H47).
 *
 * One server-rendered block, `arena/header-actions`, that outputs the
 * ready-to-use header slots: live search is already a core block in the part;
 * this adds account, wishlist, mini-cart trigger (Store-API drawer, H33) and
 * the dark-mode toggle. The mobile hamburger lives here too, so the flyout
 * (H27) is reachable at the selected breakpoint.
 *
 * Every slot degrades without JavaScript to a plain link.
 *
 * @package Arena_Theme
 * @since   1.1.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Registers and renders the arena/header-actions block.
 *
 * @since 1.1.0
 */
final class Header_Slots {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_block' ), 20 );
	}

	/**
	 * Registers the server-rendered block.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'arena/header-actions',
			array(
				'api_version'     => 3,
				'name'            => 'arena/header-actions',
				'title'           => __( 'Arena header actions', 'arena-commerce' ),
				'category'        => 'theme',
				'description'     => __( 'Account, wishlist, mini-cart and dark mode toggle slots.', 'arena-commerce' ),
				'textdomain'      => 'arena-commerce',
				'render_callback' => array( __CLASS__, 'render' ),
				'attributes'      => array(
					'showAccount'  => array( 'type' => 'boolean', 'default' => true ),
					'showWishlist' => array( 'type' => 'boolean', 'default' => true ),
					'showCart'     => array( 'type' => 'boolean', 'default' => true ),
					'showTheme'    => array( 'type' => 'boolean', 'default' => true ),
					'showFlyout'   => array( 'type' => 'boolean', 'default' => true ),
				),
			)
		);
	}

	/**
	 * Renders the slots.
	 *
	 * @since 1.1.0
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render( $attributes ) {
		$show_account  = ! isset( $attributes['showAccount'] ) || $attributes['showAccount'];
		$show_wishlist = ! isset( $attributes['showWishlist'] ) || $attributes['showWishlist'];
		$show_cart     = ! isset( $attributes['showCart'] ) || $attributes['showCart'];
		$show_theme    = ! isset( $attributes['showTheme'] ) || $attributes['showTheme'];
		$show_flyout   = ! isset( $attributes['showFlyout'] ) || $attributes['showFlyout'];

		ob_start();
		?>
		<div class="arena-header-actions wp-block-group" style="display:flex;gap:var(--wp--preset--spacing--30);align-items:center;">
			<?php if ( $show_flyout ) : ?>
				<button type="button" class="arena-flyout-toggle" data-arena-dialog-open="arena-flyout" aria-label="<?php esc_attr_e( 'Open menu', 'arena-commerce' ); ?>">
					<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
				</button>
			<?php endif; ?>

			<?php if ( $show_account ) : ?>
				<a class="arena-account-link" href="<?php echo esc_url( self::account_url() ); ?>" aria-label="<?php esc_attr_e( 'My account', 'arena-commerce' ); ?>">
					<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c1.5-3.5 4-5 7-5s5.5 1.5 7 5"/></svg>
				</a>
			<?php endif; ?>

			<?php if ( $show_wishlist && self::wishlist_url() ) : ?>
				<a class="arena-wishlist-link" href="<?php echo esc_url( self::wishlist_url() ); ?>" aria-label="<?php esc_attr_e( 'Wishlist', 'arena-commerce' ); ?>">
					<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20s-7-4.35-9.33-8.55C.9 8.1 2.87 5 6.2 5c2.1 0 3.6 1.1 4.47 2.6h2.66C14.2 6.1 15.7 5 17.8 5c3.33 0 5.3 3.1 3.53 6.45C19 15.65 12 20 12 20z"/></svg>
				</a>
			<?php endif; ?>

			<?php if ( $show_theme ) : ?>
				<?php echo Dark_Mode::render_toggle(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped internally. ?>
			<?php endif; ?>

			<?php if ( $show_cart && class_exists( 'WooCommerce' ) ) : ?>
				<button type="button" class="arena-cart-trigger" data-arena-cart-open aria-label="<?php esc_attr_e( 'Open cart', 'arena-commerce' ); ?>">
					<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h2l1.5 10h11L20 8H7"/><circle cx="9" cy="19" r="1"/><circle cx="17" cy="19" r="1"/></svg>
					<span class="arena-cart-count" data-arena-cart-count><?php echo esc_html( self::cart_count() ); ?></span>
				</button>
			<?php endif; ?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Account URL with a graceful non-Woo fallback.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	private static function account_url() {
		if ( function_exists( 'wc_get_page_permalink' ) && wc_get_page_permalink( 'myaccount' ) ) {
			return wc_get_page_permalink( 'myaccount' );
		}

		return wp_login_url();
	}

	/**
	 * Wishlist URL. Empty until a compatible plugin (YITH, TI, Jetpack…)
	 * registers one through the documented adapter filter (H37).
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	private static function wishlist_url() {
		/**
		 * Filter the wishlist URL rendered in the header slot (H37).
		 *
		 * Adapters in Arena Engine return the plugin URL only while the plugin
		 * is active; the slot stays hidden otherwise.
		 *
		 * @since 1.1.0
		 *
		 * @param string|null $url Wishlist URL or null to hide the slot.
		 */
		$url = apply_filters( 'arena_theme_wishlist_url', null );

		return $url ? (string) $url : '';
	}

	/**
	 * Initial cart count (server-rendered, then kept in sync by the module).
	 *
	 * @since 1.1.0
	 *
	 * @return int
	 */
	private static function cart_count() {
		if ( function_exists( 'WC' ) && WC()->cart ) {
			return WC()->cart->get_cart_contents_count();
		}

		return 0;
	}
}
