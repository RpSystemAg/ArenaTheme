<?php
/**
 * Accessibility layer.
 *
 * Arena targets WCAG 2.2 level AA. WordPress core already prints a
 * "Skip to content" link for block themes (see
 * `wp_enqueue_block_template_skip_link()`, which targets the first `<main>` on
 * the page), so this class adds only what core does not: extra skip targets, a
 * live region for asynchronous commerce feedback, and accessible defaults for
 * forms and pagination.
 *
 * @package Arena_Theme
 * @since   1.0.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Adds the accessibility primitives every Arena template relies on.
 *
 * @since 1.0.0
 */
final class Accessibility {

	/**
	 * ID of the polite live region printed in the footer.
	 *
	 * @var string
	 */
	const LIVE_REGION_ID = 'arena-live-region';

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'wp_body_open', array( __CLASS__, 'skip_links' ), 5 );
		add_action( 'wp_footer', array( __CLASS__, 'live_region' ), 5 );
		add_filter( 'the_content_more_link', array( __CLASS__, 'more_link' ), 10, 2 );
		add_filter( 'wp_link_pages_args', array( __CLASS__, 'pagination_args' ) );
		add_filter( 'comment_form_field_comment', array( __CLASS__, 'comment_field' ) );
		add_filter( 'render_block', array( __CLASS__, 'landmark_labels' ), 10, 2 );
	}

	/**
	 * Gives every navigation and search landmark a distinct accessible name.
	 *
	 * Neither `core/navigation` nor `core/search` exposes an `ariaLabel`
	 * attribute in WordPress 7.1, and two landmarks with the same role and no
	 * name are indistinguishable to assistive technology (WCAG 1.3.6 /
	 * 2.4.1). The name is derived from the block's `className`, so it stays
	 * editable in the editor without inventing block attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param string $block_content Rendered block HTML.
	 * @param array  $block         Block array.
	 * @return string
	 */
	public static function landmark_labels( $block_content, $block ) {
		if ( empty( $block['blockName'] ) || ! is_string( $block_content ) || '' === $block_content ) {
			return $block_content;
		}

		if ( 'core/navigation' === $block['blockName'] ) {
			$tag = 'nav';
		} elseif ( 'core/search' === $block['blockName'] ) {
			$tag = 'form';
		} else {
			return $block_content;
		}

		$class = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';
		$label = self::landmark_label( $class, $tag );

		if ( '' === $label ) {
			return $block_content;
		}

		$processor = new \WP_HTML_Tag_Processor( $block_content );

		if ( $processor->next_tag( array( 'tag_name' => $tag ) ) ) {
			$processor->set_attribute( 'aria-label', $label );

			return $processor->get_updated_html();
		}

		return $block_content;
	}

	/**
	 * Resolves the accessible name for a landmark from its class list.
	 *
	 * The map is ordered most-specific first so that modifier classes win over
	 * the base class.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class Block className.
	 * @param string $tag   Landmark tag name.
	 * @return string
	 */
	private static function landmark_label( $class, $tag ) {
		if ( '' === trim( $class ) ) {
			return '';
		}

		$map = 'nav' === $tag
			? array(
				'arena-footer__menu--shop'    => __( 'Shop menu', 'arena-commerce' ),
				'arena-footer__menu--support' => __( 'Support menu', 'arena-commerce' ),
				'arena-nav'                   => __( 'Main menu', 'arena-commerce' ),
			)
			: array(
				'arena-search--404'   => __( 'Search the store', 'arena-commerce' ),
				'arena-search--inline' => __( 'Search again', 'arena-commerce' ),
				'arena-search'        => __( 'Search products', 'arena-commerce' ),
			);

		foreach ( $map as $needle => $label ) {
			if ( false !== strpos( ' ' . $class . ' ', ' ' . $needle . ' ' ) ) {
				return $label;
			}
		}

		return '';
	}

	/**
	 * Prints supplementary skip links.
	 *
	 * Keyboard, switch and screen-magnifier users can jump straight to the
	 * primary navigation or to search instead of tabbing through the header.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function skip_links() {
		$links = apply_filters(
			'arena_theme_skip_links',
			array(
				'#arena-primary-navigation' => __( 'Skip to navigation', 'arena-commerce' ),
				'#arena-search'             => __( 'Skip to search', 'arena-commerce' ),
			)
		);

		if ( empty( $links ) || ! is_array( $links ) ) {
			return;
		}

		echo '<nav class="arena-skip-links" aria-label="' . esc_attr__( 'Skip links', 'arena-commerce' ) . '">';

		foreach ( $links as $target => $label ) {
			printf(
				'<a class="arena-skip-link screen-reader-text" href="%1$s">%2$s</a>',
				esc_url( (string) $target ),
				esc_html( (string) $label )
			);
		}

		echo '</nav>';

		/**
		 * Fires after the theme has printed its own skip links.
		 *
		 * @since 1.0.0
		 */
		do_action( 'arena_theme_skip_links_printed' );
	}

	/**
	 * Prints an empty polite live region.
	 *
	 * Asynchronous events (mini-cart updates, AJAX validation, carousel state)
	 * write into this node so screen readers announce the change without focus
	 * being stolen from the control the user is operating.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function live_region() {
		printf(
			'<div id="%1$s" class="screen-reader-text" role="status" aria-live="polite" aria-atomic="true"></div>',
			esc_attr( self::LIVE_REGION_ID )
		);
	}

	/**
	 * Gives the "read more" link an unambiguous accessible name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $link           Generated markup.
	 * @param string $more_link_text Link text.
	 * @return string
	 */
	public static function more_link( $link, $more_link_text ) {
		unset( $link );

		return sprintf(
			'<a class="arena-more-link" href="%1$s">%2$s<span class="screen-reader-text"> %3$s</span></a>',
			esc_url( (string) get_permalink() ),
			esc_html( $more_link_text ),
			esc_html( (string) get_the_title() )
		);
	}

	/**
	 * Turns post pagination into a labelled navigation landmark.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Pagination arguments.
	 * @return array
	 */
	public static function pagination_args( $args ) {
		$args['before'] = '<nav class="arena-pagination" aria-label="' . esc_attr__( 'Post pagination', 'arena-commerce' ) . '">';
		$args['after']  = '</nav>';

		return $args;
	}

	/**
	 * Keeps browser autofill from fighting the comment textarea.
	 *
	 * @since 1.0.0
	 *
	 * @param string $field Comment textarea markup.
	 * @return string
	 */
	public static function comment_field( $field ) {
		return str_replace( '<textarea ', '<textarea autocomplete="off" ', (string) $field );
	}
}
