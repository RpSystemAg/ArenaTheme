<?php
/**
 * Native mega menu (H28).
 *
 * A server-rendered block, `arena/mega-menu`, that turns an assigned menu
 * (location `arena-mega`) into multi-column panels: links, descriptions,
 * badges, icons (WP 7.1 Icons API) and images per item, with an action to
 * embed a pattern at the bottom of a panel. Interaction is progressive
 * enhancement by the arena-megamenu script module: aria-expanded sync, ESC to
 * close, focus follows the panel, hover layered on the keyboard path.
 *
 * Menu items gain two custom fields in the admin (Appearance → Menus):
 * "Mega columns" (how many columns the panel uses) and "Badge".
 *
 * @package Arena_Theme
 * @since   1.1.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Mega menu block, walker and admin fields.
 *
 * @since 1.1.0
 */
final class Mega_Menu {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register' ), 20 );
		add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, 'item_fields' ), 10, 4 );
		add_action( 'wp_update_nav_menu_item', array( __CLASS__, 'save_item_fields' ), 10, 2 );
	}

	/**
	 * Registers the block.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function register() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'arena/mega-menu',
			array(
				'api_version'     => 3,
				'name'            => 'arena/mega-menu',
				'title'           => __( 'Arena mega menu', 'arena-commerce' ),
				'category'        => 'theme',
				'description'     => __( 'Multi-column mega menu with descriptions, badges, icons and images.', 'arena-commerce' ),
				'textdomain'      => 'arena-commerce',
				'render_callback' => array( __CLASS__, 'render' ),
				'attributes'      => array(
					'location'       => array( 'type' => 'string', 'default' => 'arena-mega' ),
					'fallback'       => array( 'type' => 'string', 'default' => '' ),
					'defaultColumns' => array( 'type' => 'integer', 'default' => 3 ),
				),
			)
		);
	}

	/**
	 * Renders the mega menu.
	 *
	 * @since 1.1.0
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render( $attributes ) {
		$location = isset( $attributes['location'] ) ? $attributes['location'] : 'arena-mega';
		$default  = isset( $attributes['defaultColumns'] ) ? max( 2, min( 4, (int) $attributes['defaultColumns'] ) ) : 3;

		$locations = get_nav_menu_locations();
		$menu_id   = isset( $locations[ $location ] ) ? $locations[ $location ] : 0;

		if ( ! $menu_id && ! empty( $attributes['fallback'] ) ) {
			$menu = wp_get_nav_menu_object( $attributes['fallback'] );
			$menu_id = $menu ? $menu->term_id : 0;
		}

		if ( ! $menu_id ) {
			return '';
		}

		$items = wp_get_nav_menu_items( $menu_id );

		if ( ! $items ) {
			return '';
		}

		ob_start();
		?>
		<nav class="arena-mega" data-arena-mega-menu aria-label="<?php esc_attr_e( 'Primary', 'arena-commerce' ); ?>">
			<ul class="arena-mega__bar">
				<?php self::render_items( $items, $default ); ?>
			</ul>
		</nav>
		<?php

		return ob_get_clean();
	}

	/**
	 * Renders top-level items and their panels.
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_Post[] $items Menu items (flat).
	 * @param int        $default_columns Default panel columns.
	 * @return void
	 */
	private static function render_items( $items, $default_columns ) {
		$children = array();

		foreach ( $items as $item ) {
			$children[ (int) $item->menu_item_parent ][] = $item;
		}

		foreach ( $children[0] as $top ) {
			$subs   = isset( $children[ $top->ID ] ) ? $children[ $top->ID ] : array();
			$has_panel = ! empty( $subs ) && 1 < count( $subs ) + (int) self::item_meta( $top->ID, 'columns' ) - 1;
			$columns = (int) self::item_meta( $top->ID, 'columns' );

			if ( $columns < 2 || $columns > 4 ) {
				$columns = $default_columns;
			}

			$badge = self::item_meta( $top->ID, 'badge' );
			?>
			<li class="arena-mega__item">
				<?php if ( $has_panel ) : ?>
					<button type="button" class="arena-mega__link" aria-expanded="false" aria-controls="arena-mega-panel-<?php echo esc_attr( (string) $top->ID ); ?>">
						<span><?php echo esc_html( $top->title ); ?></span>
						<?php if ( $badge ) : ?>
							<span class="arena-mega__badge"><?php echo esc_html( $badge ); ?></span>
						<?php endif; ?>
						<svg class="arena-mega__chevron" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
					</button>
					<div class="arena-mega__panel" id="arena-mega-panel-<?php echo esc_attr( (string) $top->ID ); ?>">
						<div class="arena-mega__grid arena-mega__grid--<?php echo esc_attr( (string) $columns ); ?>">
							<?php
							$column = 0;

							foreach ( $subs as $sub ) {
								if ( 0 === $column % $columns ) {
									echo '<ul class="arena-mega__links">';
								}

								self::render_sub_item( $sub );
								$column++;

								if ( 0 === $column % $columns ) {
									echo '</ul>';
								}
							}

							if ( 0 !== $column % $columns ) {
								echo '</ul>';
							}
							?>
						</div>
						<?php
						/**
						 * Fires at the bottom of a mega menu panel (H28):
						 * pattern embeds go here.
						 *
						 * @since 1.1.0
						 *
						 * @param \WP_Post $top Top-level menu item.
						 */
						do_action( 'arena_theme_mega_menu_panel_after', $top );
						?>
					</div>
				<?php else : ?>
					<a class="arena-mega__link" href="<?php echo esc_url( $top->url ); ?>">
						<span><?php echo esc_html( $top->title ); ?></span>
						<?php if ( $badge ) : ?>
							<span class="arena-mega__badge"><?php echo esc_html( $badge ); ?></span>
						<?php endif; ?>
					</a>
				<?php endif; ?>
			</li>
			<?php
		}
	}

	/**
	 * Renders one link inside a panel.
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_Post $item Sub-menu item.
	 * @return void
	 */
	private static function render_sub_item( $item ) {
		$badge = self::item_meta( $item->ID, 'badge' );
		$icon  = self::item_meta( $item->ID, 'icon' );
		?>
		<li>
			<a href="<?php echo esc_url( $item->url ); ?>">
				<span>
					<?php
					if ( $icon && method_exists( Icons::class, 'render' ) ) {
						echo wp_kses( Icons::render( $icon ), array( 'svg' => self::svg_attrs() ) );
					}
					?>
					<?php echo esc_html( $item->title ); ?>
					<?php if ( $item->description ) : ?>
						<span class="arena-mega__desc"><?php echo esc_html( $item->description ); ?></span>
					<?php endif; ?>
				</span>
				<?php if ( $badge ) : ?>
					<span class="arena-mega__badge"><?php echo esc_html( $badge ); ?></span>
				<?php endif; ?>
			</a>
		</li>
		<?php
	}

	/**
	 * Reads one mega-menu item meta value.
	 *
	 * @since 1.1.0
	 *
	 * @param int    $item_id Menu item ID.
	 * @param string $key     columns|badge|icon.
	 * @return string
	 */
	private static function item_meta( $item_id, $key ) {
		return (string) get_post_meta( $item_id, '_arena_mega_' . $key, true );
	}

	/**
	 * SVG attributes allowed for icon output.
	 *
	 * @return array[]
	 */
	private static function svg_attrs() {
		return array( 'svg' => array( 'viewbox' => true, 'width' => true, 'height' => true, 'fill' => true, 'stroke' => true, 'aria-hidden' => true ) );
	}

	/**
	 * Adds the mega-menu fields to the menu item editor (H28).
	 *
	 * @since 1.1.0
	 *
	 * @param int   $item_id Menu item ID.
	 * @param mixed $item    Menu item.
	 * @param mixed $depth   Depth.
	 * @param array $args    Args.
	 * @return void
	 */
	public static function item_fields( $item_id, $item, $depth, $args ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		$columns = (int) get_post_meta( $item_id, '_arena_mega_columns', true );
		$badge   = (string) get_post_meta( $item_id, '_arena_mega_badge', true );
		$icon    = (string) get_post_meta( $item_id, '_arena_mega_icon', true );
		?>
		<p class="description arena-mega-field" style="display:flex;gap:8px;flex-wrap:wrap;">
			<label>
				<?php esc_html_e( 'Mega columns', 'arena-commerce' ); ?><br />
				<select name="arena_mega_columns[<?php echo esc_attr( (string) $item_id ); ?>]">
					<option value="0"><?php esc_html_e( 'Auto', 'arena-commerce' ); ?></option>
					<?php for ( $i = 2; $i <= 4; $i++ ) : ?>
						<option value="<?php echo esc_attr( (string) $i ); ?>" <?php selected( $columns, $i ); ?>><?php echo esc_html( (string) $i ); ?></option>
					<?php endfor; ?>
				</select>
			</label>
			<label>
				<?php esc_html_e( 'Badge', 'arena-commerce' ); ?><br />
				<input type="text" name="arena_mega_badge[<?php echo esc_attr( (string) $item_id ); ?>]" value="<?php echo esc_attr( $badge ); ?>" size="12" />
			</label>
			<label>
				<?php esc_html_e( 'Icon (Arena slug)', 'arena-commerce' ); ?><br />
				<input type="text" name="arena_mega_icon[<?php echo esc_attr( (string) $item_id ); ?>]" value="<?php echo esc_attr( $icon ); ?>" size="12" />
			</label>
		</p>
		<?php
	}

	/**
	 * Saves the mega-menu item fields.
	 *
	 * @since 1.1.0
	 *
	 * @param int   $menu_id         Menu ID.
	 * @param int   $menu_item_db_id Item ID.
	 * @return void
	 */
	public static function save_item_fields( $menu_id, $menu_item_db_id ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- covered by core's save_nav_menu_item nonce.
		$fields = array(
			'columns' => isset( $_POST['arena_mega_columns'][ $menu_item_db_id ] ) ? (int) wp_unslash( $_POST['arena_mega_columns'][ $menu_item_db_id ] ) : null,
			'badge'   => isset( $_POST['arena_mega_badge'][ $menu_item_db_id ] ) ? sanitize_text_field( wp_unslash( $_POST['arena_mega_badge'][ $menu_item_db_id ] ) ) : null,
			'icon'    => isset( $_POST['arena_mega_icon'][ $menu_item_db_id ] ) ? sanitize_key( wp_unslash( $_POST['arena_mega_icon'][ $menu_item_db_id ] ) ) : null,
		);
		// phpcs:enable

		foreach ( $fields as $key => $value ) {
			if ( null === $value ) {
				continue;
			}

			if ( '' === $value || 0 === $value ) {
				delete_post_meta( $menu_item_db_id, '_arena_mega_' . $key );
			} else {
				update_post_meta( $menu_item_db_id, '_arena_mega_' . $key, $value );
			}
		}
	}
}
