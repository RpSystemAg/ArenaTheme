<?php
/**
 * Admin dashboard page.
 *
 * @package Arena_Engine
 * @since   1.0.0
 */

namespace Arena_Engine\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * A single settings page with no build step and no framework.
 *
 * @since 1.0.0
 */
final class Dashboard {

	/**
	 * Capability required to see and save the page.
	 *
	 * @var string
	 */
	const CAPABILITY = 'manage_options';

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_post_arena_engine_save', array( __CLASS__, 'save' ) );
	}

	/**
	 * Registers the page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function menu() {
		add_options_page(
			__( 'Arena Engine', 'arena-engine' ),
			__( 'Arena Engine', 'arena-engine' ),
			self::CAPABILITY,
			'arena-engine',
			array( __CLASS__, 'render' )
		);
	}

	/**
	 * Renders the page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function render() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( esc_html__( 'You are not allowed to view this page.', 'arena-engine' ) );
		}

		$settings = wp_parse_args( (array) get_option( 'arena_engine_settings', array() ), \Arena_Engine\Activator::defaults() );
		$modules  = array(
			'performance'   => __( 'Performance budget', 'arena-engine' ),
			'accessibility' => __( 'Accessibility layer', 'arena-engine' ),
			'security'      => __( 'Security headers and hardening', 'arena-engine' ),
			'checkout'      => __( 'Checkout defaults (Baymard-informed)', 'arena-engine' ),
			'abilities'     => __( 'Abilities API registration', 'arena-engine' ),
		);
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Arena Engine', 'arena-engine' ); ?></h1>
			<p class="description">
				<?php esc_html_e( 'Every switch is reversible. Turning a module off restores WordPress defaults for that concern.', 'arena-engine' ); ?>
			</p>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="arena_engine_save" />
				<?php wp_nonce_field( 'arena_engine_save', 'arena_engine_nonce' ); ?>

				<table class="form-table" role="presentation">
					<tbody>
						<?php foreach ( $modules as $key => $label ) : ?>
							<tr>
								<th scope="row"><?php echo esc_html( $label ); ?></th>
								<td>
									<label>
										<input type="checkbox" name="arena_settings[<?php echo esc_attr( $key ); ?>]" value="1" <?php checked( ! empty( $settings[ $key ] ) ); ?> />
										<?php esc_html_e( 'Enabled', 'arena-engine' ); ?>
									</label>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Saves the settings with a nonce and capability check.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function save() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( esc_html__( 'You are not allowed to save these settings.', 'arena-engine' ) );
		}

		check_admin_referer( 'arena_engine_save', 'arena_engine_nonce' );

		$input = isset( $_POST['arena_settings'] ) && is_array( $_POST['arena_settings'] ) ? wp_unslash( $_POST['arena_settings'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- keys are checked against an allow-list below.

		$allowed  = array( 'performance', 'accessibility', 'security', 'checkout', 'abilities' );
		$defaults = \Arena_Engine\Activator::defaults();
		$clean    = array();

		foreach ( $allowed as $key ) {
			$clean[ $key ] = ! empty( $input[ $key ] );
		}

		$clean['motion_default'] = isset( $defaults['motion_default'] ) ? $defaults['motion_default'] : 'respect-os';
		$clean['audit_bar']      = ! empty( $defaults['audit_bar'] );

		update_option( 'arena_engine_settings', $clean, false );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'    => 'arena-engine',
					'updated' => 'true',
				),
				admin_url( 'options-general.php' )
			)
		);
		exit;
	}
}
