<?php
/**
 * The Arena admin menu (H20/H31): Admin → Arena.
 *
 * Subpages: Overview, Starter kits, Presets, Typography, Layout & options,
 * Journal (generated documentation + undo for every action, G12).
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Arena menu tree.
 *
 * @since 1.1.0
 */
final class Menu {

	/**
	 * Capability required.
	 *
	 * @var string
	 */
	const CAPABILITY = 'manage_options';

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'assets' ) );
	}

	/**
	 * Registers the menu and submenus.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function register() {
		add_menu_page(
			__( 'Arena', 'arena-engine' ),
			__( 'Arena', 'arena-engine' ),
			self::CAPABILITY,
			'arena',
			array( __CLASS__, 'render_overview' ),
			'dashicons-superhero-alt',
			59
		);

		add_submenu_page(
			'arena',
			__( 'Starter kits', 'arena-engine' ),
			__( 'Starter kits', 'arena-engine' ),
			self::CAPABILITY,
			'arena-kits',
			array( Kits_Admin::class, 'render' )
		);

		add_submenu_page(
			'arena',
			__( 'Presets', 'arena-engine' ),
			__( 'Presets', 'arena-engine' ),
			self::CAPABILITY,
			'arena-presets',
			array( Panel::class, 'render_presets' )
		);

		add_submenu_page(
			'arena',
			__( 'Typography', 'arena-engine' ),
			__( 'Typography', 'arena-engine' ),
			self::CAPABILITY,
			'arena-typography',
			array( Panel::class, 'render_typography' )
		);

		add_submenu_page(
			'arena',
			__( 'Layout & options', 'arena-engine' ),
			__( 'Layout & options', 'arena-engine' ),
			self::CAPABILITY,
			'arena-layout',
			array( Panel::class, 'render_layout' )
		);

		add_submenu_page(
			'arena',
			__( 'Journal', 'arena-engine' ),
			__( 'Journal', 'arena-engine' ),
			self::CAPABILITY,
			'arena-journal',
			array( Panel::class, 'render_journal' )
		);
	}

	/**
	 * Enqueues the admin assets on Arena pages only.
	 *
	 * @since 1.1.0
	 *
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public static function assets( $hook ) {
		if ( false === strpos( (string) $hook, 'arena' ) ) {
			return;
		}

		wp_enqueue_style(
			'arena-admin',
			ARENA_ENGINE_URL . 'assets/css/admin-arena.css',
			array(),
			ARENA_ENGINE_VERSION
		);

		wp_enqueue_script(
			'arena-admin',
			ARENA_ENGINE_URL . 'assets/js/admin-arena.js',
			array(),
			ARENA_ENGINE_VERSION,
			array( 'strategy' => 'defer' )
		);

		wp_localize_script(
			'arena-admin',
			'arenaAdmin',
			array(
				'rest'  => esc_url_raw( rest_url( 'arena/v1/' ) ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'i18n'  => array(
					'importing' => __( 'Importing…', 'arena-engine' ),
					'imported'  => __( 'Imported', 'arena-engine' ),
					'undoing'   => __( 'Undoing…', 'arena-engine' ),
					'undone'    => __( 'Undone', 'arena-engine' ),
					'saving'    => __( 'Saving…', 'arena-engine' ),
					'saved'     => __( 'Saved', 'arena-engine' ),
					'error'     => __( 'Something went wrong. Reload the page and try again.', 'arena-engine' ),
					'confirm'   => __( 'The kit will overwrite existing pages and set the front page. Continue?', 'arena-engine' ),
				),
			)
		);
	}

	/**
	 * Renders the overview page.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function render_overview() {
		$kits     = \Arena_Engine\Kits\Kit_Repository::all();
		$journal  = Journal::all();
		$presets  = glob( get_stylesheet_directory() . '/styles/*.json' ) ?: array();
		?>
		<div class="wrap arena-wrap">
			<h1><?php esc_html_e( 'Arena', 'arena-engine' ); ?></h1>
			<p class="description">
				<?php esc_html_e( 'Every action here is tracked in the Journal and reversible. Nothing writes to the database without an undo and a documentation page.', 'arena-engine' ); ?>
			</p>

			<div class="arena-cards">
				<a class="arena-card arena-card--link" href="<?php echo esc_url( admin_url( 'admin.php?page=arena-kits' ) ); ?>">
					<h2><?php esc_html_e( 'Starter kits', 'arena-engine' ); ?></h2>
					<p><?php echo esc_html( sprintf( /* translators: %d: number of kits. */ _n( '%d kit ready to import in one click', '%d kits ready to import in one click', count( $kits ), 'arena-engine' ), count( $kits ) ) ); ?></p>
				</a>
				<a class="arena-card arena-card--link" href="<?php echo esc_url( admin_url( 'admin.php?page=arena-presets' ) ); ?>">
					<h2><?php esc_html_e( 'Presets', 'arena-engine' ); ?></h2>
					<p><?php echo esc_html( sprintf( /* translators: %d: number of presets. */ _n( '%d global preset', '%d global presets', count( $presets ), 'arena-engine' ), count( $presets ) ) ); ?></p>
				</a>
				<a class="arena-card arena-card--link" href="<?php echo esc_url( admin_url( 'admin.php?page=arena-typography' ) ); ?>">
					<h2><?php esc_html_e( 'Typography', 'arena-engine' ); ?></h2>
					<p><?php esc_html_e( 'Per-level family, weight and scale — saved as a tracked styles variation.', 'arena-engine' ); ?></p>
				</a>
				<a class="arena-card arena-card--link" href="<?php echo esc_url( admin_url( 'admin.php?page=arena-journal' ) ); ?>">
					<h2><?php esc_html_e( 'Journal', 'arena-engine' ); ?></h2>
					<p><?php echo esc_html( sprintf( /* translators: %d: number of actions. */ _n( '%d tracked action', '%d tracked actions', count( $journal ), 'arena-engine' ), count( $journal ) ) ); ?></p>
				</a>
			</div>
		</div>
		<?php
	}
}
