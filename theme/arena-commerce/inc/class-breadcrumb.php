<?php
/**
 * Breadcrumb (H30).
 *
 * Renders the breadcrumb template part at a selectable position — above the
 * header, below the header or inside the content — and feeds the
 * BreadcrumbList JSON-LD emitted by Schema (H43). Position is a tracked
 * layout option (H31) with a documented undo, never a duplicated template.
 *
 * @package Arena_Theme
 * @since   1.1.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Breadcrumb renderer.
 *
 * @since 1.1.0
 */
final class Breadcrumb {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		$position = self::position();

		if ( 'hidden' === $position ) {
			return;
		}

		$hook = 'wp_body_open';

		if ( 'below-header' === $position ) {
			$hook = 'arena_theme_after_header';
		} elseif ( 'in-content' === $position ) {
			$hook = 'arena_theme_before_content';
		}

		add_action( $hook, array( __CLASS__, 'render' ) );
	}

	/**
	 * The configured breadcrumb position (H30).
	 *
	 * @since 1.1.0
	 *
	 * @return string above-header|below-header|in-content|hidden
	 */
	public static function position() {
		$position = (string) get_option( 'arena_breadcrumb_position', 'in-content' );

		if ( ! in_array( $position, array( 'above-header', 'below-header', 'in-content', 'hidden' ), true ) ) {
			$position = 'in-content';
		}

		/**
		 * Filter the breadcrumb position (H30).
		 *
		 * @since 1.1.0
		 *
		 * @param string $position above-header|below-header|in-content|hidden.
		 */
		return apply_filters( 'arena_theme_breadcrumb_position', $position );
	}

	/**
	 * Renders the breadcrumb trail.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function render() {
		if ( is_front_page() ) {
			return;
		}

		$trail = self::trail();

		if ( count( $trail ) < 2 ) {
			return;
		}

		echo '<nav class="arena-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'arena-commerce' ) . '"><ol class="arena-breadcrumbs__list">';

		$last = count( $trail ) - 1;

		foreach ( $trail as $index => $crumb ) {
			echo '<li class="arena-breadcrumbs__item">';

			if ( $index < $last && $crumb['url'] ) {
				echo '<a href="' . esc_url( $crumb['url'] ) . '">' . esc_html( $crumb['title'] ) . '</a>';
			} else {
				echo '<span aria-current="page">' . esc_html( $crumb['title'] ) . '</span>';
			}

			echo '</li>';
		}

		echo '</ol></nav>';
	}

	/**
	 * Builds the breadcrumb trail: home → (shop) → ancestors → current.
	 *
	 * @since 1.1.0
	 *
	 * @return array[] {title, url} pairs.
	 */
	public static function trail() {
		$trail = array(
			array(
				'title' => __( 'Home', 'arena-commerce' ),
				'url'   => home_url( '/' ),
			),
		);

		if ( function_exists( 'is_woocommerce' ) && ( is_shop() || is_product() || is_product_taxonomy() ) ) {
			$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '';

			if ( $shop && ! is_shop() ) {
				$trail[] = array(
					'title' => get_the_title( function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0 ),
					'url'   => $shop,
				);
			}
		}

		if ( is_singular() ) {
			$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );

			foreach ( $ancestors as $ancestor ) {
				$trail[] = array(
					'title' => get_the_title( $ancestor ),
					'url'   => get_permalink( $ancestor ),
				);
			}

			if ( is_singular( 'post' ) ) {
				$blog_id = (int) get_option( 'page_for_posts' );

				if ( $blog_id ) {
					$trail[] = array(
						'title' => get_the_title( $blog_id ),
						'url'   => get_permalink( $blog_id ),
					);
				}
			}

			$trail[] = array(
				'title' => get_the_title(),
				'url'   => '',
			);
		} elseif ( is_archive() ) {
			$trail[] = array(
				'title' => wp_strip_all_tags( get_the_archive_title() ),
				'url'   => '',
			);
		} elseif ( is_search() ) {
			$trail[] = array(
				'title' => sprintf( /* translators: %s: search query. */ __( 'Search results for “%s”', 'arena-commerce' ), get_search_query() ),
				'url'   => '',
			);
		}

		/**
		 * Filter the breadcrumb trail (H30).
		 *
		 * @since 1.1.0
		 *
		 * @param array[] $trail {title, url} pairs.
		 */
		return apply_filters( 'arena_theme_breadcrumb_trail', $trail );
	}
}
