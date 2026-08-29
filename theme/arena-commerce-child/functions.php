<?php
/**
 * Arena Commerce Child — starter functions (H50).
 *
 * Three child-safe ways to extend the parent, in the order you should reach
 * for them:
 *
 *   1. TOKENS  — override CSS custom properties / theme.json values in
 *                assets/css/child.css (one variable at a time, nothing else).
 *   2. HOOKS   — use the documented filters below; they exist precisely so a
 *                child never has to replace a parent class.
 *   3. PARTS   — copy a template PART (e.g. parts/header.html) into the child
 *                only when a hook genuinely cannot express the change.
 *
 * Copying whole parent templates/classes into a child is the one anti-pattern
 * this starter exists to prevent: it forks maintenance forever.
 *
 * @package Arena_Child
 * @since   1.1.0
 */

namespace Arena_Child;

defined( 'ABSPATH' ) || exit;

/**
 * Boot the child: parent stylesheet first, token overrides second.
 *
 * The parent's arena.css is enqueued by the parent theme (handle
 * 'arena-commerce'); the child sheet loads after it and changes tokens only.
 */
function boot() {
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\styles', 11 );
	add_filter( 'arena_theme_bottom_nav_items', __NAMESPACE__ . '\\bottom_nav' );
	add_filter( 'arena_theme_wishlist_url', __NAMESPACE__ . '\\wishlist' );
}

/**
 * Enqueues the child token-override sheet AFTER the parent cascade.
 *
 * @since 1.1.0
 *
 * @return void
 */
function styles() {
	wp_enqueue_style(
		'arena-commerce-child',
		get_stylesheet_directory_uri() . '/assets/css/child.css',
		array( 'arena-commerce' ),
		(string) wp_get_theme()->get( 'Version' )
	);
}

/**
 * Example hook 1 — add or reorder bottom-nav destinations (H2/H47).
 *
 * The parent renders 4–5 destinations plus the scheme toggle; the filter
 * receives the whole list so a child can add a shop item, drop the account
 * link for a catalogue-only store, etc. Keep 4–5 destinations: the parent
 * will not render the bar otherwise (the rule is enforced, not advisory).
 *
 * @since 1.1.0
 *
 * @param array[] $items Bottom-nav items.
 * @return array[]
 */
function bottom_nav( $items ) {
	/* Example, commented: replace the search destination with a WhatsApp link.
	 *
	 * foreach ( $items as $index => $item ) {
	 *     if ( 'search' === $item['icon'] ) {
	 *         $items[ $index ] = array(
	 *             'label' => 'WhatsApp',
	 *             'href'  => 'https://wa.me/390200000000',
	 *             'icon'  => 'search',
	 *         );
	 *     }
	 * }
	 */

	return $items;
}

/**
 * Example hook 2 — give the header wishlist slot a URL (H29/H37).
 *
 * The slot stays hidden until something returns a URL here: point it at your
 * wishlist plugin's page, or at any page you like.
 *
 * @since 1.1.0
 *
 * @param string|null $url Default null (slot hidden).
 * @return string|null
 */
function wishlist( $url ) {
	/* Example, commented:
	 *
	 * return get_permalink( 123 ); // Your wishlist page ID.
	 */

	return $url;
}

boot();
