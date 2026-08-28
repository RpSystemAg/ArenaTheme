<?php
/**
 * Template tags used by PHP-rendered output.
 *
 * @package Arena_Theme
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Prints the publication date with a machine-readable datetime.
 *
 * @since 1.0.0
 *
 * @return void
 */
function arena_theme_posted_on() {
	printf(
		'<span class="arena-posted-on">%1$s <time class="arena-entry-date" datetime="%2$s">%3$s</time></span>',
		esc_html__( 'Published', 'arena-commerce' ),
		esc_attr( (string) get_the_date( DATE_W3C ) ),
		esc_html( (string) get_the_date() )
	);
}

/**
 * Prints the author link.
 *
 * @since 1.0.0
 *
 * @return void
 */
function arena_theme_posted_by() {
	printf(
		'<span class="arena-posted-by">%1$s <a class="arena-author-url" href="%2$s">%3$s</a></span>',
		esc_html__( 'by', 'arena-commerce' ),
		esc_url( (string) get_author_posts_url( (int) get_the_author_meta( 'ID' ) ) ),
		esc_html( (string) get_the_author() )
	);
}

/**
 * Prints the entry footer: categories, tags and edit link.
 *
 * @since 1.0.0
 *
 * @return void
 */
function arena_theme_entry_footer() {
	$categories = get_the_category_list( ', ' );
	$tags       = get_the_tag_list( '<span class="arena-tags-label">' . esc_html__( 'Tags:', 'arena-commerce' ) . '</span> ', ', ' );

	if ( $categories ) {
		printf( '<div class="arena-entry-cats">%s</div>', wp_kses_post( $categories ) );
	}

	if ( $tags ) {
		printf( '<div class="arena-entry-tags">%s</div>', wp_kses_post( $tags ) );
	}

	arena_theme_edit_link();
}

/**
 * Prints an edit link for logged-in editors.
 *
 * @since 1.0.0
 *
 * @return void
 */
function arena_theme_edit_link() {
	edit_post_link(
		sprintf(
			/* translators: %s: post title. Only visible to screen readers. */
			wp_kses( __( 'Edit <span class="screen-reader-text">%s</span>', 'arena-commerce' ), array( 'span' => array( 'class' => array() ) ) ),
			get_the_title()
		),
		'<span class="arena-edit-link">',
		'</span>'
	);
}

/**
 * Prints the comment count as an accessible link.
 *
 * @since 1.0.0
 *
 * @return void
 */
function arena_theme_comment_count_link() {
	if ( ! comments_open() && ! get_comments_number() ) {
		return;
	}

	printf(
		'<a class="arena-comment-link" href="%1$s">%2$s</a>',
		esc_url( (string) get_comments_link() ),
		esc_html(
			sprintf(
				/* translators: %s: number of comments. */
				_n( '%s comment', '%s comments', (int) get_comments_number(), 'arena-commerce' ),
				number_format_i18n( get_comments_number() )
			)
		)
	);
}
