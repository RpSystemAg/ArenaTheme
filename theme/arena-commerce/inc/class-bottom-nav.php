<?php
/**
 * Mobile-first bottom navigation (H2).
 *
 * The Block Navigation component is excellent on desktop, but on a 360px
 * viewport the Constitution requires a fixed bottom bar of 4–5 items that:
 *
 * 1. is at least 64px high plus the safe-area inset,
 * 2. keeps every primary action within 44×44px thumb-reach,
 * 3. shows an animated active indicator,
 * 4. hides on scroll-down and reappears on scroll-up,
 * 5. never relies on hover.
 *
 * This component renders server-side so the markup is present in the HTML
 * (SEO, no-JS, and assistive technology all see it), and the stylesheet hides
 * it above 600px. The enhancement script in assets/js/arena.js handles the
 * scroll-direction behaviour and keeps the active state in sync.
 *
 * @package Arena_Theme
 * @since   1.0.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Bottom navigation renderer.
 *
 * @since 1.0.0
 */
final class Bottom_Nav {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'wp_footer', array( __CLASS__, 'render' ), 99 );

		// Allow integrations and headless QA to disable it without editing code.
		add_filter( 'arena_theme_show_bottom_nav', static function ( $show ) {
			return (bool) $show;
		}, 10 );
	}

	/**
	 * Renders the fixed bottom navigation on the front end.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function render() {
		if ( ! apply_filters( 'arena_theme_show_bottom_nav', true ) ) {
			return;
		}

		if ( is_admin() || wp_doing_ajax() ) {
			return;
		}

		$items = self::items();

		if ( count( $items ) < 4 || count( $items ) > 5 ) {
			return;
		}
		?>
		<nav class="arena-bottom-nav" id="arena-bottom-nav" aria-label="<?php esc_attr_e( 'Primary navigation on mobile', 'arena-commerce' ); ?>">
			<ul class="arena-bottom-nav__list">
				<?php
				foreach ( $items as $item ) :
					$current = ! empty( $item['current'] );
					?>
					<li class="arena-bottom-nav__item">
						<a class="arena-bottom-nav__link<?php echo $current ? ' is-current' : ''; ?>" href="<?php echo esc_url( $item['href'] ); ?>"<?php echo $current ? ' aria-current="page"' : ''; ?>>
							<span class="arena-bottom-nav__icon" aria-hidden="true"><?php echo self::icon( $item['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static allow-listed SVGs. ?></span>
							<span class="arena-bottom-nav__label"><?php echo esc_html( $item['label'] ); ?></span>
							<span class="arena-bottom-nav__indicator" aria-hidden="true"></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<?php
	}

	/**
	 * Builds the four-to-five item list for the current request.
	 *
	 * Cart and account links fall back to sensible core URLs when WooCommerce
	 * is absent, so the theme never emits a broken link.
	 *
	 * @since 1.0.0
	 *
	 * @return array[] Item arrays keyed by href, label, icon and current.
	 */
	private static function items() {
		$shop = self::shop_url();
		$cart = self::cart_url();
		$myaccount = self::account_url();

		$items = array(
			array(
				'label'   => __( 'Home', 'arena-commerce' ),
				'href'    => home_url( '/' ),
				'icon'    => 'home',
				'current' => is_front_page() || is_home(),
			),
			array(
				'label'   => __( 'Shop', 'arena-commerce' ),
				'href'    => $shop,
				'icon'    => 'store',
				'current' => function_exists( 'is_shop' ) && is_shop(),
			),
			array(
				'label'   => __( 'Search', 'arena-commerce' ),
				'href'    => home_url( '/?s=' ),
				'icon'    => 'search',
				'current' => is_search(),
			),
			array(
				'label'   => __( 'Account', 'arena-commerce' ),
				'href'    => $myaccount,
				'icon'    => 'user',
				'current' => function_exists( 'is_account_page' ) && is_account_page(),
			),
			array(
				'label'   => __( 'Cart', 'arena-commerce' ),
				'href'    => $cart,
				'icon'    => 'cart',
				'current' => function_exists( 'is_cart' ) && is_cart(),
			),
		);

		/**
		 * Filters the bottom navigation items.
		 *
		 * @since 1.0.0
		 *
		 * @param array[] $items List of {label, href, icon, current}.
		 */
		return apply_filters( 'arena_theme_bottom_nav_items', $items );
	}

	/**
	 * Resolves the shop URL with the best WooCommerce hook available.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private static function shop_url() {
		if ( function_exists( 'wc_get_page_permalink' ) && wc_get_page_permalink( 'shop' ) ) {
			return wc_get_page_permalink( 'shop' );
		}

		if ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0 ) {
			return get_permalink( wc_get_page_id( 'shop' ) );
		}

		return home_url( '/shop/' );
	}

	/**
	 * Resolves the cart URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private static function cart_url() {
		if ( function_exists( 'wc_get_cart_url' ) ) {
			return wc_get_cart_url();
		}

		return home_url( '/cart/' );
	}

	/**
	 * Resolves the account URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private static function account_url() {
		if ( function_exists( 'wc_get_page_permalink' ) && wc_get_page_permalink( 'myaccount' ) ) {
			return wc_get_page_permalink( 'myaccount' );
		}

		return home_url( '/my-account/' );
	}

	/**
	 * Returns one of the allow-listed inline SVG icons.
	 *
	 * The icons are small, monochrome, stroke-based and decorative (aria-hidden).
	 * They follow the viewBox of the core icons shipped in /assets/icons so a
	 * designer can swap the entire set at once without touching this markup.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Icon slug.
	 * @return string
	 */
	private static function icon( $name ) {
		$icons = array(
			'home'   => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 11.5 12 4l8 7.5"/><path d="M6 10v9h12v-9"/><path d="M9 19v-5h6v5"/></svg>',
			'store'  => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 8h16l-1 12H5L4 8z"/><path d="M3 8l2-4h14l2 4"/><path d="M9 20v-6h6v6"/></svg>',
			'search' => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg>',
			'user'   => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c1.5-3.5 4-5 7-5s5.5 1.5 7 5"/></svg>',
			'cart'   => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h2l1.5 10h11L20 8H7"/><circle cx="9" cy="19" r="1"/><circle cx="17" cy="19" r="1"/></svg>',
		);

		return isset( $icons[ $name ] ) ? $icons[ $name ] : $icons['home'];
	}
}
