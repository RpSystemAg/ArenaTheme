<?php
/**
 * Non-template helper functions.
 *
 * @package Arena_Theme
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the Arena Engine companion plugin is active.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function arena_theme_engine_active() {
	return defined( 'ARENA_ENGINE_VERSION' );
}

/**
 * Whether WooCommerce is loaded.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function arena_theme_has_woocommerce() {
	return class_exists( 'WooCommerce' );
}

/**
 * Sanitises a string into a CSS/HTML class token.
 *
 * @since 1.0.0
 *
 * @param string $value Raw value.
 * @return string
 */
function arena_theme_sanitize_class( $value ) {
	$value = sanitize_html_class( strtolower( (string) $value ) );

	return '' !== $value ? $value : 'arena-item';
}

/**
 * Builds the attribute string for a motion-safe reveal.
 *
 * Elements receive `data-arena-reveal`. The stylesheet only animates them when
 * the enhancement script has added `arena-motion-ready` to `<html>`, and never
 * when the visitor prefers reduced motion, so nothing is hidden or animated
 * without consent.
 *
 * @since 1.0.0
 *
 * @param array $args {
 *     Optional. Reveal arguments.
 *
 *     @type string $variant One of `fade`, `rise`, `scale`, `blur`.
 *     @type int    $delay   Stagger delay in milliseconds, capped at 1200.
 * }
 * @return string
 */
function arena_theme_motion_attrs( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'variant' => 'rise',
			'delay'   => 0,
		)
	);

	if ( ! in_array( $args['variant'], array( 'fade', 'rise', 'scale', 'blur' ), true ) ) {
		$args['variant'] = 'rise';
	}

	$delay = max( 0, min( 1200, (int) $args['delay'] ) );

	return sprintf(
		' data-arena-reveal="%1$s"%2$s',
		esc_attr( $args['variant'] ),
		$delay > 0 ? sprintf( ' style="--arena-reveal-delay:%dms"', $delay ) : ''
	);
}
