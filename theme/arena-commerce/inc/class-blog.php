<?php
/**
 * Blog & archives system (H38/H39).
 *
 * One loop, four structurally distinct layouts — grid / list / fullwidth /
 * masonry (CSS-native columns) — shipped as swappable template parts
 * (parts/loop-*.html). The index, archive and search templates reference the
 * same `loop` part, so nothing is duplicated: the selected layout is resolved
 * at render time from the tracked layout option (H31) and can be swapped in
 * the Site Editor like any part.
 *
 * Options (all tracked variations with undo, set from the Arena panel):
 *   arena_blog_layout    grid|list|fullwidth|masonry
 *   arena_blog_sidebar   none|left|right
 *   arena_blog_content   excerpt|full
 *   arena_blog_ratio     4-3|16-9|1-1|3-2
 *   arena_post_meta      ordered list of enabled meta entries
 *
 * @package Arena_Theme
 * @since   1.1.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Blog layouts, sidebar, meta and post formats.
 *
 * @since 1.1.0
 */
final class Blog {

	/**
	 * Loop layouts shipped as template parts.
	 *
	 * @since 1.1.0
	 *
	 * @var string[]
	 */
	const LAYOUTS = array( 'grid', 'list', 'fullwidth', 'masonry' );

	/**
	 * Meta entries the theme can render, in their default order (H39).
	 *
	 * @since 1.1.0
	 *
	 * @var string[]
	 */
	const META_KEYS = array( 'author', 'date', 'categories', 'tags', 'comments', 'reading-time' );

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_theme_support( 'post-formats', array( 'gallery', 'quote', 'video' ) );

		add_filter( 'body_class', array( __CLASS__, 'body_classes' ) );
		add_filter( 'pre_render_block', array( __CLASS__, 'swap_loop_part' ), 10, 2 );
		add_filter( 'render_block_core/post-excerpt', array( __CLASS__, 'maybe_full_content' ), 10, 2 );
		add_action( 'init', array( __CLASS__, 'register_blocks' ), 20 );
	}

	/**
	 * The selected loop layout.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public static function layout() {
		$layout = (string) get_option( 'arena_blog_layout', 'grid' );

		if ( ! in_array( $layout, self::LAYOUTS, true ) ) {
			$layout = 'grid';
		}

		/**
		 * Filter the blog loop layout (H38).
		 *
		 * @since 1.1.0
		 *
		 * @param string $layout grid|list|fullwidth|masonry.
		 */
		return apply_filters( 'arena_theme_blog_layout', $layout );
	}

	/**
	 * The selected sidebar position.
	 *
	 * @since 1.1.0
	 *
	 * @return string none|left|right
	 */
	public static function sidebar() {
		$sidebar = (string) get_option( 'arena_blog_sidebar', 'right' );

		if ( ! in_array( $sidebar, array( 'none', 'left', 'right' ), true ) ) {
			$sidebar = 'right';
		}

		return apply_filters( 'arena_theme_blog_sidebar', $sidebar );
	}

	/**
	 * The configured ordered meta entries (H39).
	 *
	 * @since 1.1.0
	 *
	 * @return string[] Ordered subset of META_KEYS.
	 */
	public static function meta_keys() {
		$meta = get_option( 'arena_post_meta', self::META_KEYS );

		if ( ! is_array( $meta ) ) {
			$meta = self::META_KEYS;
		}

		$meta = array_values( array_intersect( array_filter( array_map( 'strval', $meta ) ), self::META_KEYS ) );

		/**
		 * Filter the rendered post-meta entries and their order (H39).
		 *
		 * @since 1.1.0
		 *
		 * @param string[] $meta Ordered subset of META_KEYS.
		 */
		return apply_filters( 'arena_theme_post_meta', $meta );
	}

	/**
	 * Body classes carrying the loop configuration.
	 *
	 * @since 1.1.0
	 *
	 * @param string[] $classes Body classes.
	 * @return string[]
	 */
	public static function body_classes( $classes ) {
		if ( is_home() || is_archive() || is_search() ) {
			$classes[] = 'arena-blog';
			$classes[] = 'arena-loop--ratio-' . self::ratio();
		}

		return $classes;
	}

	/**
	 * The featured-image aspect ratio option.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public static function ratio() {
		$ratio = (string) get_option( 'arena_blog_ratio', '4-3' );

		if ( ! in_array( $ratio, array( '4-3', '16-9', '1-1', '3-2' ), true ) ) {
			$ratio = '4-3';
		}

		return apply_filters( 'arena_theme_blog_ratio', $ratio );
	}

	/**
	 * Swaps the `loop` template part to the selected layout part (H38).
	 *
	 * The layout parts live in parts/loop-<layout>.html and are regular
	 * template parts, so the Site Editor can swap them manually too.
	 *
	 * @since 1.1.0
	 *
	 * @param string|null $pre_render Pre-render override.
	 * @param array       $parsed_block Parsed block.
	 * @return string|null
	 */
	public static function swap_loop_part( $pre_render, $parsed_block ) {
		if ( ! isset( $parsed_block['blockName'] ) || 'core/template-part' !== $parsed_block['blockName'] ) {
			return $pre_render;
		}

		$slug = isset( $parsed_block['attrs']['slug'] ) ? $parsed_block['attrs']['slug'] : '';

		if ( 'loop' !== $slug ) {
			return $pre_render;
		}

		$layout  = self::layout();
		$sidebar = self::sidebar();
		$part    = 'loop';

		if ( in_array( $layout, self::LAYOUTS, true ) && is_readable( get_theme_file_path( 'parts/loop-' . $layout . '.html' ) ) ) {
			$part = 'loop-' . $layout;
		}

		$loop_markup = do_blocks(
			'<!-- wp:template-part {"slug":"' . $part . '"} /-->'
		);

		if ( 'none' === $sidebar || ! is_readable( get_theme_file_path( 'parts/sidebar.html' ) ) ) {
			return '<div class="arena-loop-area">' . $loop_markup . '</div>';
		}

		$sidebar_markup = do_blocks( '<!-- wp:template-part {"slug":"sidebar"} /-->' );
		$classes        = 'arena-with-sidebar' . ( 'left' === $sidebar ? ' arena-with-sidebar--left' : '' );

		return '<div class="' . esc_attr( $classes ) . '">'
			. '<div class="arena-loop-area">' . $loop_markup . '</div>'
			. $sidebar_markup
			. '</div>';
	}

	/**
	 * Replaces the excerpt with the full content when the option says so.
	 *
	 * @since 1.1.0
	 *
	 * @param string $html Rendered block.
	 * @param array  $block Parsed block.
	 * @return string
	 */
	public static function maybe_full_content( $html, $block ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		if ( 'full' !== (string) get_option( 'arena_blog_content', 'excerpt' ) ) {
			return $html;
		}

		$content = get_the_content( null, false, get_post() );
		$content = strip_shortcodes( $content );
		$content = do_blocks( $content );

		return '<div class="arena-prose arena-loop__full">' . $content . '</div>';
	}

	/**
	 * Registers the post-meta block (H39).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function register_blocks() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'arena/post-meta',
			array(
				'api_version'     => 3,
				'name'            => 'arena/post-meta',
				'title'           => __( 'Arena post meta', 'arena-commerce' ),
				'category'        => 'theme',
				'description'     => __( 'Configurable, ordered post meta (author, date, terms, comments, reading time).', 'arena-commerce' ),
				'textdomain'      => 'arena-commerce',
				'render_callback' => array( __CLASS__, 'render_meta' ),
				'attributes'      => array(
					'context' => array( 'type' => 'string', 'default' => 'single' ),
				),
			)
		);
	}

	/**
	 * Renders the configured post meta (H39).
	 *
	 * @since 1.1.0
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render_meta( $attributes ) {
		$items = array();

		foreach ( self::meta_keys() as $key ) {
			$item = self::meta_item( $key );

			if ( $item ) {
				$items[] = '<li class="arena-post-meta__item">' . $item . '</li>';
			}
		}

		if ( empty( $items ) ) {
			return '';
		}

		return '<ul class="arena-post-meta">' . implode( '', $items ) . '</ul>';
	}

	/**
	 * Renders a single meta entry.
	 *
	 * @since 1.1.0
	 *
	 * @param string $key Meta key.
	 * @return string
	 */
	private static function meta_item( $key ) {
		switch ( $key ) {
			case 'author':
				$author = get_userdata( (int) get_post_field( 'post_author' ) );

				return $author
					? '<span>' . esc_html__( 'By', 'arena-commerce' ) . '</span> <a href="' . esc_url( get_author_posts_url( $author->ID ) ) . '">' . esc_html( $author->display_name ) . '</a>'
					: '';

			case 'date':
				return '<time datetime="' . esc_attr( get_the_date( DATE_W3C ) ) . '">' . esc_html( get_the_date() ) . '</time>';

			case 'categories':
				return get_the_category_list( ', ', '', get_the_ID() );

			case 'tags':
				return get_the_tag_list( '', ', ', '', get_the_ID() );

			case 'comments':
				$count = (int) get_comments_number();

				return $count
					/* translators: %s: number of comments. */
					? '<a href="' . esc_url( get_comments_link() ) . '">' . esc_html( sprintf( _n( '%s comment', '%s comments', $count, 'arena-commerce' ), number_format_i18n( $count ) ) ) . '</a>'
					: '';

			case 'reading-time':
				$words = str_word_count( wp_strip_all_tags( (string) get_post_field( 'post_content' ) ) );

				/* translators: %s: number of minutes. */
				return '<span>' . esc_html( sprintf( _n( '%s min read', '%s min read', max( 1, (int) ceil( $words / 220 ) ), 'arena-commerce' ), number_format_i18n( max( 1, (int) ceil( $words / 220 ) ) ) ) ) . '</span>';
		}

		return '';
	}
}
