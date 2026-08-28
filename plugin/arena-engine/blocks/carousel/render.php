<?php
/**
 * Server-side render for the Arena Carousel block.
 *
 * @package Arena_Engine
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$arena_attributes = $attributes;
$arena_context    = isset( $context ) ? $context : array();
$arena_content    = isset( $content ) ? $content : '';

$arena_per_view = max( 1, min( 8, (int) ( $arena_attributes['perView'] ?? 3 ) ) );
$arena_gap      = (string) ( $arena_attributes['gap'] ?? '1rem' );
$arena_label    = (string) ( $arena_attributes['label'] ?? '' );
$arena_controls = ! empty( $arena_attributes['showControls'] );
$arena_progress = ! empty( $arena_attributes['showProgress'] );

$arena_state = wp_json_encode(
	array(
		'index'   => 0,
		'total'   => 0,
		'playing' => false,
	)
);

$arena_wrapper = get_block_wrapper_attributes(
	array(
		'class'                => 'arena-carousel',
		'data-arena-carousel'  => 'true',
		'role'                 => 'group',
		'aria-roledescription' => 'carousel',
		'aria-label'           => '' !== $arena_label ? $arena_label : __( 'Carousel', 'arena-engine' ),
		'data-wp-interactive'  => 'arena/carousel',
		'data-wp-context'      => $arena_state,
	)
);

$arena_viewport_style = sprintf(
	'--arena-carousel-gap:%1$s;--arena-carousel-per-view:%2$d;',
	esc_attr( wp_strip_all_tags( $arena_gap ) ),
	$arena_per_view
);
?>
<div <?php echo $arena_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by get_block_wrapper_attributes(). ?>>
	<div class="arena-carousel__viewport" style="<?php echo esc_attr( $arena_viewport_style ); ?>" tabindex="0">
		<?php echo $arena_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block inner content. ?>
	</div>

	<?php if ( $arena_controls ) : ?>
		<div class="arena-carousel__controls">
			<button type="button" class="arena-carousel__control" data-arena-carousel-prev aria-label="<?php esc_attr_e( 'Previous slide', 'arena-engine' ); ?>">
				<span aria-hidden="true">&larr;</span>
			</button>
			<button type="button" class="arena-carousel__control" data-arena-carousel-next aria-label="<?php esc_attr_e( 'Next slide', 'arena-engine' ); ?>">
				<span aria-hidden="true">&rarr;</span>
			</button>
		</div>
	<?php endif; ?>

	<?php if ( $arena_progress ) : ?>
		<div class="arena-carousel__progress">
			<span class="arena-carousel__progress-bar" role="presentation"></span>
		</div>
	<?php endif; ?>
</div>
