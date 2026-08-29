<?php
/**
 * Per-page meta box (H32): disable title, transparent header, footer,
 * sidebar; container width and typographic preset overrides.
 *
 * The box renders the current overrides so they are inspectable, saves them
 * through the journaled REST endpoint, and resets everything with one click.
 * The meta itself is registered by the theme (Page_Options) with
 * show_in_rest, so the box and the REST API share one source of truth.
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * The Arena per-page meta box.
 *
 * @since 1.1.0
 */
final class Meta_Box {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'register' ) );
	}

	/**
	 * Registers the box for pages and posts.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function register() {
		foreach ( array( 'page', 'post' ) as $post_type ) {
			add_meta_box(
				'arena-page-options',
				__( 'Arena: page options', 'arena-engine' ),
				array( __CLASS__, 'render' ),
				$post_type,
				'side',
				'default',
				array( '__back_compat_meta_box' => true )
			);
		}
	}

	/**
	 * Renders the box.
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_Post $post Current post.
	 * @return void
	 */
	public static function render( $post ) {
		$schema = REST_Panel::meta_schema();
		$values = array();

		foreach ( $schema as $key => $meta ) {
			$values[ $key ] = (string) get_post_meta( $post->ID, $key, true );
		}

		wp_nonce_field( 'arena_meta_box', 'arena_meta_box_nonce' );
		?>
		<div class="arena-meta-box" data-arena-meta-box data-post-id="<?php echo esc_attr( (string) $post->ID ); ?>">
			<?php foreach ( $schema as $key => $meta ) : ?>
				<?php $current = $values[ $key ]; ?>
				<?php if ( 'bool' === $meta['type'] ) : ?>
					<p>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $key ); ?>" value="1" <?php checked( $current, '1' ); ?> />
							<?php echo esc_html( $meta['label'] ); ?>
						</label>
					</p>
				<?php elseif ( 'enum' === $meta['type'] ) : ?>
					<p>
						<label>
							<?php echo esc_html( $meta['label'] ); ?><br />
							<select name="<?php echo esc_attr( $key ); ?>">
								<option value=""><?php esc_html_e( 'Site default', 'arena-engine' ); ?></option>
								<?php foreach ( $meta['values'] as $value ) : ?>
									<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current, $value ); ?>><?php echo esc_html( $value ); ?></option>
								<?php endforeach; ?>
							</select>
						</label>
					</p>
				<?php else : ?>
					<p>
						<label>
							<?php echo esc_html( $meta['label'] ); ?><br />
							<input type="text" class="widefat" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $current ); ?>" />
						</label>
					</p>
				<?php endif; ?>
			<?php endforeach; ?>

			<p>
				<button type="button" class="button" data-arena-meta-save><?php esc_html_e( 'Save overrides', 'arena-engine' ); ?></button>
				<button type="button" class="button-link-delete" data-arena-meta-reset><?php esc_html_e( 'Reset all (one click)', 'arena-engine' ); ?></button>
			</p>

			<p class="description" data-arena-meta-status aria-live="polite">
				<?php
				$active = array_filter( $values );

				printf(
					/* translators: %d: number of active overrides. */
					esc_html( _n( '%d active override. Every save is journaled with undo.', '%d active overrides. Every save is journaled with undo.', count( $active ), 'arena-engine' ) ),
					count( $active )
				);
				?>
			</p>
		</div>
		<?php
	}
}
