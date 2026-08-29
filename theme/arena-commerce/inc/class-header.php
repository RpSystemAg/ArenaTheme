<?php
/**
 * Header system (H27).
 *
 * Three header variants, selectable globally and per page:
 *   standard    the part as authored (sticky-ready, shadow on scroll);
 *   transparent starts transparent over the hero, turns solid on scroll;
 *   sticky      pinned with shadow-on-scroll.
 *
 * On mobile the fixed bottom nav (H2) stays mandatory; the hamburger opens a
 * flyout canvas for deep navigation and AFFIANCA it — never replaces it.
 *
 * The class also renders the header action slots (H29): live search, account,
 * wishlist, mini-cart trigger and the dark-mode toggle (H47).
 *
 * @package Arena_Theme
 * @since   1.1.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Header variants, slots and mobile flyout.
 *
 * @since 1.1.0
 */
final class Header {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_filter( 'body_class', array( __CLASS__, 'body_classes' ) );
		add_action( 'wp_body_open', array( __CLASS__, 'render_flyout' ) );
		add_filter( 'render_block_core/template-part', array( __CLASS__, 'inject_variant' ), 10, 3 );
	}

	/**
	 * The active header variant for the current request.
	 *
	 * Priority: per-page meta (H32) → global option (H31) → standard.
	 *
	 * @since 1.1.0
	 *
	 * @return string standard|transparent|sticky
	 */
	public static function variant() {
		$variant = 'standard';

		if ( is_singular() ) {
			$meta = get_post_meta( get_the_ID(), '_arena_header_variant', true );

			if ( in_array( $meta, array( 'standard', 'transparent', 'sticky' ), true ) ) {
				$variant = $meta;
			}
		}

		/**
		 * Filter the active header variant (H27).
		 *
		 * @since 1.1.0
		 *
		 * @param string $variant standard|transparent|sticky.
		 */
		$variant = apply_filters( 'arena_theme_header_variant', $variant );

		return in_array( $variant, array( 'standard', 'transparent', 'sticky' ), true ) ? $variant : 'standard';
	}

	/**
	 * The selected mobile breakpoint for the bottom nav + hamburger (H27).
	 *
	 * @since 1.1.0
	 *
	 * @return int 600|782|960
	 */
	public static function mobile_breakpoint() {
		$bp = (int) get_option( 'arena_mobile_breakpoint', 600 );

		if ( ! in_array( $bp, array( 600, 782, 960 ), true ) ) {
			$bp = 600;
		}

		/**
		 * Filter the mobile navigation breakpoint (H27).
		 *
		 * @since 1.1.0
		 *
		 * @param int $bp 600|782|960.
		 */
		return (int) apply_filters( 'arena_theme_mobile_breakpoint', $bp );
	}

	/**
	 * Body classes carrying the header state and the selected breakpoint.
	 *
	 * @since 1.1.0
	 *
	 * @param string[] $classes Body classes.
	 * @return string[]
	 */
	public static function body_classes( $classes ) {
		$variant = self::variant();

		if ( 'transparent' === $variant ) {
			$classes[] = 'arena-header-transparent';
		} elseif ( 'sticky' === $variant ) {
			$classes[] = 'arena-header-sticky';
		}

		$classes[] = 'arena-bp-' . self::mobile_breakpoint();

		return $classes;
	}

	/**
	 * Marks the header group with the variant class so the CSS states apply
	 * without duplicating the header template part.
	 *
	 * @since 1.1.0
	 *
	 * @param string    $html  Rendered part.
	 * @param array     $block Parsed block.
	 * @param \WP_Block $instance Block instance.
	 * @return string
	 */
	public static function inject_variant( $html, $block, $instance ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		$slug = isset( $block['attrs']['slug'] ) ? $block['attrs']['slug'] : '';

		if ( 'header' !== $slug || false === strpos( $html, 'arena-header' ) ) {
			return $html;
		}

		$variant = self::variant();
		$classes = 'arena-header--' . $variant;

		if ( 'sticky' === $variant ) {
			$classes .= ' arena-header--sticky';
		}

		$html = preg_replace( '/(class="[^"]*arena-header)/', '$1 ' . esc_attr( $classes ), $html, 1 );

		return $html;
	}

	/**
	 * Renders the mobile flyout canvas (H27): deep navigation, account and the
	 * dark-mode toggle, reachable from the bottom-nav "More" entry (H47).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function render_flyout() {
		if ( is_admin() || wp_doing_ajax() ) {
			return;
		}

		$items = self::flyout_items();
		?>
		<div class="arena-flyout" id="arena-flyout" data-arena-dialog hidden aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Site menu', 'arena-commerce' ); ?>">
			<div class="arena-flyout__header">
				<p class="arena-flyout__title" style="margin:0;font-weight:700;"><?php esc_html_e( 'Menu', 'arena-commerce' ); ?></p>
				<button type="button" class="arena-flyout__close" data-arena-dialog-close aria-label="<?php esc_attr_e( 'Close menu', 'arena-commerce' ); ?>">✕</button>
			</div>
			<ul class="arena-flyout__nav">
				<?php foreach ( $items as $item ) : ?>
					<li>
						<a href="<?php echo esc_url( $item['href'] ); ?>"<?php echo ! empty( $item['current'] ) ? ' aria-current="page"' : ''; ?>>
							<?php echo esc_html( $item['label'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="arena-flyout__tools">
				<button type="button" class="arena-theme-toggle" data-arena-theme-toggle>
					<?php echo Dark_Mode::icon_moon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static allow-listed SVG. ?>
					<?php echo Dark_Mode::icon_sun(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static allow-listed SVG. ?>
					<span><?php esc_html_e( 'Toggle dark mode', 'arena-commerce' ); ?></span>
				</button>
			</div>
		</div>
		<?php
	}

	/**
	 * The deep-navigation links for the flyout: the assigned primary menu, or
	 * the page list as a fallback so the flyout is never empty.
	 *
	 * @since 1.1.0
	 *
	 * @return array[]
	 */
	private static function flyout_items() {
		$items = array();

		if ( has_nav_menu( 'primary' ) ) {
			$nav = wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'echo'           => false,
					'depth'          => 1,
					'items_wrap'     => '%3$s',
					'fallback_cb'    => false,
				)
			);

			if ( preg_match_all( '/<a[^>]*href="([^"]+)"[^>]*>(.*?)<\/a>/i', (string) $nav, $matches, PREG_SET_ORDER ) ) {
				foreach ( $matches as $match ) {
					$items[] = array(
						'label'   => wp_strip_all_tags( $match[2] ),
						'href'    => $match[1],
						'current' => untrailingslashit( $match[1] ) === untrailingslashit( (string) parse_url( home_url( add_query_arg( array() ) ), PHP_URL_PATH ) ),
					);
				}
			}
		}

		if ( empty( $items ) ) {
			$pages = get_pages( array( 'sort_column' => 'menu_order', 'number' => 8 ) );

			foreach ( $pages as $page ) {
				$items[] = array(
					'label'   => get_the_title( $page ),
					'href'    => get_permalink( $page ),
					'current' => is_page( $page->ID ),
				);
			}
		}

		/**
		 * Filter the mobile flyout links (H27).
		 *
		 * @since 1.1.0
		 *
		 * @param array[] $items {label, href, current} entries.
		 */
		return apply_filters( 'arena_theme_flyout_items', $items );
	}
}
