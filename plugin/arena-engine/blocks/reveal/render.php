<?php
/**
 * Server-side render for the Arena Reveal block.
 *
 * @package Arena_Engine
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$arena_variant = isset( $attributes['variant'] ) ? (string) $attributes['variant'] : 'rise';
$arena_delay   = isset( $attributes['delay'] ) ? max( 0, min( 1200, (int) $attributes['delay'] ) ) : 0;

if ( ! in_array( $arena_variant, array( 'fade', 'rise', 'scale', 'blur' ), true ) ) {
	$arena_variant = 'rise';
}

$arena_wrapper = get_block_wrapper_attributes(
	array(
		'class'               => 'arena-reveal',
		'data-arena-reveal'   => $arena_variant,
		'data-wp-interactive' => 'arena/reveal',
		'style'               => $arena_delay > 0 ? sprintf( '--arena-reveal-delay:%dms', $arena_delay ) : '',
	)
);

// Inner blocks are rendered through the Interactivity API so the directives
// below are processed by core rather than by a bespoke runtime.
$arena_inner = do_blocks( (string) ( $content ?? '' ) );

if ( function_exists( 'wp_interactivity_process_directives' ) ) {
	$arena_inner = wp_interactivity_process_directives( $arena_inner );
}
?>
<div <?php echo $arena_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by get_block_wrapper_attributes(). ?>>
	<?php echo $arena_inner; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block inner content. ?>
</div>
