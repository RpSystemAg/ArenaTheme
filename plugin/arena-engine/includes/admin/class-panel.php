<?php
/**
 * Panel pages: Presets (H40), Typography (H25), Layout & options (H31) and
 * the Journal with generated documentation + undo (G12).
 *
 * The pages are server-rendered forms progressively enhanced by
 * assets/js/admin-arena.js, which talks to the arena/v1 REST endpoints. No
 * page reload is needed and every save returns the journal id.
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Renders the Arena panel pages.
 *
 * @since 1.1.0
 */
final class Panel {

	/**
	 * Renders the Presets page (H40).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function render_presets() {
		$rest     = REST_Panel::class;
		$active   = (string) get_option( 'arena_active_preset', 'default' );
		$presets  = array();
		$files    = glob( get_stylesheet_directory() . '/styles/*.json' ) ?: array();

		foreach ( $files as $file ) {
			$slug = basename( $file, '.json' );
			$json = json_decode( (string) file_get_contents( $file ), true );
			$presets[] = array(
				'slug'        => $slug,
				'title'       => is_array( $json ) && isset( $json['title'] ) ? $json['title'] : $slug,
				'description' => is_array( $json ) && isset( $json['description'] ) ? $json['description'] : '',
			);
		}
		?>
		<div class="wrap arena-wrap">
			<h1><?php esc_html_e( 'Global presets', 'arena-engine' ); ?></h1>
			<p class="description"><?php esc_html_e( 'One click switches palette, type pairing, radius and density — as a tracked styles variation with undo. The dark twin of every palette ships with the theme (H47).', 'arena-engine' ); ?></p>

			<div class="arena-preset-grid" data-arena-panel="presets">
				<?php foreach ( $presets as $preset ) : ?>
					<div class="arena-preset-card<?php echo $preset['slug'] === $active ? ' is-active' : ''; ?>">
						<h2><?php echo esc_html( $preset['title'] ); ?></h2>
						<p class="description"><?php echo esc_html( $preset['description'] ); ?></p>
						<button
							type="button"
							class="button button-primary<?php echo $preset['slug'] === $active ? ' is-active' : ''; ?>"
							data-arena-apply-preset="<?php echo esc_attr( $preset['slug'] ); ?>"
							<?php disabled( $preset['slug'] === $active ); ?>
						>
							<?php echo esc_html( $preset['slug'] === $active ? __( 'Active', 'arena-engine' ) : __( 'Apply in 1 click', 'arena-engine' ) ); ?>
						</button>
					</div>
				<?php endforeach; ?>
			</div>

			<p class="description"><?php esc_html_e( 'Presets are also available as Style variations in the Site Editor (Styles → Browse styles).', 'arena-engine' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Renders the Typography page (H25).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function render_typography() {
		$saved  = Variations::read( 'typography.json' );
		$levels = isset( $saved['arenaTypography'] ) && is_array( $saved['arenaTypography'] ) ? $saved['arenaTypography'] : array();
		$scale  = isset( $saved['arenaTypographyScale'] ) && is_array( $saved['arenaTypographyScale'] ) ? $saved['arenaTypographyScale'] : array( 'mobile' => 1, 'desktop' => 1 );

		$families = array( 'system-sans', 'system-serif', 'system-mono', 'display', 'body' );
		$controls = array(
			'family'        => array( 'label' => __( 'Family', 'arena-engine' ), 'type' => 'select', 'options' => $families ),
			'weight'        => array( 'label' => __( 'Weight', 'arena-engine' ), 'type' => 'text', 'placeholder' => '700' ),
			'lineHeight'    => array( 'label' => __( 'Line height', 'arena-engine' ), 'type' => 'text', 'placeholder' => '1.2' ),
			'letterSpacing' => array( 'label' => __( 'Letter spacing', 'arena-engine' ), 'type' => 'text', 'placeholder' => '-0.02em' ),
			'textTransform' => array( 'label' => __( 'Transform', 'arena-engine' ), 'type' => 'select', 'options' => array( '', 'none', 'uppercase', 'lowercase', 'capitalize' ) ),
		);
		?>
		<div class="wrap arena-wrap">
			<h1><?php esc_html_e( 'Typography', 'arena-engine' ); ?></h1>
			<p class="description"><?php esc_html_e( 'Per-level typography with separate mobile and desktop scale. Saving generates a tracked styles/variation — never scattered database options (H25).', 'arena-engine' ); ?></p>

			<form data-arena-panel="typography">
				<table class="widefat striped arena-typo-table" role="presentation">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Level', 'arena-engine' ); ?></th>
							<?php foreach ( $controls as $control ) : ?>
								<th><?php echo esc_html( $control['label'] ); ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( REST_Panel::TYPO_LEVELS as $level ) : ?>
							<tr>
								<th scope="row"><?php echo esc_html( $level ); ?></th>
								<?php foreach ( $controls as $key => $control ) : ?>
									<td>
										<?php $current = isset( $levels[ $level ][ $key ] ) ? (string) $levels[ $level ][ $key ] : ''; ?>
										<?php if ( 'select' === $control['type'] ) : ?>
											<select name="arena-typo[<?php echo esc_attr( $level ); ?>][<?php echo esc_attr( $key ); ?>]">
												<?php foreach ( $control['options'] as $option ) : ?>
													<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $current, (string) $option ); ?>><?php echo esc_html( $option ? $option : '—' ); ?></option>
												<?php endforeach; ?>
											</select>
										<?php else : ?>
											<input
												type="text"
												name="arena-typo[<?php echo esc_attr( $level ); ?>][<?php echo esc_attr( $key ); ?>]"
												value="<?php echo esc_attr( $current ); ?>"
												placeholder="<?php echo esc_attr( $control['placeholder'] ); ?>"
												size="8"
											/>
										<?php endif; ?>
									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<h2><?php esc_html_e( 'Scale', 'arena-engine' ); ?></h2>
				<p>
					<label>
						<?php esc_html_e( 'Mobile scale factor', 'arena-engine' ); ?>
						<input type="number" step="0.05" min="0.75" max="1.5" name="arena-typo-scale-mobile" value="<?php echo esc_attr( (string) ( isset( $scale['mobile'] ) ? $scale['mobile'] : 1 ) ); ?>" />
					</label>
					&nbsp;
					<label>
						<?php esc_html_e( 'Desktop scale factor', 'arena-engine' ); ?>
						<input type="number" step="0.05" min="0.9" max="2" name="arena-typo-scale-desktop" value="<?php echo esc_attr( (string) ( isset( $scale['desktop'] ) ? $scale['desktop'] : 1 ) ); ?>" />
					</label>
				</p>

				<p>
					<button type="submit" class="button button-primary"><?php esc_html_e( 'Save typography variation', 'arena-engine' ); ?></button>
					<span class="arena-status" aria-live="polite"></span>
				</p>
			</form>

			<p class="description">
				<?php esc_html_e( 'Every level inherits a semantic slot (price, quote, caption…) mapped by the theme — no block hard-codes a font (H24). Undo lives in the Journal.', 'arena-engine' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Renders the Layout & options page (H31).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function render_layout() {
		$schema = REST_Panel::layout_schema();
		$current = array();

		foreach ( $schema as $key => $meta ) {
			$current[ $key ] = get_option( $key, $meta['default'] );
		}

		$select = static function ( $key, $label, $options ) use ( $current ) {
			printf(
				'<label><strong>%s</strong><select name="arena-layout[%s]">',
				esc_html( $label ),
				esc_attr( $key )
			);

			foreach ( $options as $value => $text ) {
				printf(
					'<option value="%s" %s>%s</option>',
					esc_attr( (string) $value ),
					selected( (string) $current[ $key ], (string) $value, false ),
					esc_html( $text )
				);
			}

			echo '</select></label>';
		};
		?>
		<div class="wrap arena-wrap">
			<h1><?php esc_html_e( 'Layout & options', 'arena-engine' ); ?></h1>
			<p class="description"><?php esc_html_e( 'Container, sidebar, breadcrumb, breakpoints and the opinionated commerce options. Every change is journaled and reversible (H31).', 'arena-engine' ); ?></p>

			<form data-arena-panel="layout" class="arena-layout-form">
				<fieldset>
					<legend><?php esc_html_e( 'Container', 'arena-engine' ); ?></legend>
					<?php
					$select( 'arena_container', __( 'Default container', 'arena-engine' ), array( 'boxed' => __( 'Boxed', 'arena-engine' ), 'fullwidth' => __( 'Full width', 'arena-engine' ), 'narrow' => __( 'Narrow', 'arena-engine' ) ) );
					?>
					<label>
						<strong><?php esc_html_e( 'Container width (rem, 30–90)', 'arena-engine' ); ?></strong>
						<input type="number" min="30" max="90" step="0.5" name="arena-layout[arena_container_width]" value="<?php echo esc_attr( (string) $current['arena_container_width'] ); ?>" />
					</label>
				</fieldset>

				<fieldset>
					<legend><?php esc_html_e( 'Navigation & breadcrumb', 'arena-engine' ); ?></legend>
					<?php
					$select( 'arena_breadcrumb_position', __( 'Breadcrumb position (H30)', 'arena-engine' ), array(
						'above-header' => __( 'Above the header', 'arena-engine' ),
						'below-header' => __( 'Below the header', 'arena-engine' ),
						'in-content'   => __( 'Inside the content', 'arena-engine' ),
						'hidden'       => __( 'Hidden', 'arena-engine' ),
					) );
					$select( 'arena_mobile_breakpoint', __( 'Mobile breakpoint (H27)', 'arena-engine' ), array( 600 => '600px', 782 => '782px', 960 => '960px' ) );
					?>
				</fieldset>

				<fieldset>
					<legend><?php esc_html_e( 'Blog (H38/H39)', 'arena-engine' ); ?></legend>
					<?php
					$select( 'arena_blog_layout', __( 'Loop layout', 'arena-engine' ), array( 'grid' => __( 'Grid', 'arena-engine' ), 'list' => __( 'List', 'arena-engine' ), 'fullwidth' => __( 'Full width', 'arena-engine' ), 'masonry' => __( 'Masonry', 'arena-engine' ) ) );
					$select( 'arena_blog_sidebar', __( 'Sidebar', 'arena-engine' ), array( 'right' => __( 'Right', 'arena-engine' ), 'left' => __( 'Left', 'arena-engine' ), 'none' => __( 'None', 'arena-engine' ) ) );
					$select( 'arena_blog_content', __( 'Loop content', 'arena-engine' ), array( 'excerpt' => __( 'Excerpt', 'arena-engine' ), 'full' => __( 'Full content', 'arena-engine' ) ) );
					$select( 'arena_blog_ratio', __( 'Featured image ratio', 'arena-engine' ), array( '4-3' => '4:3', '16-9' => '16:9', '1-1' => '1:1', '3-2' => '3:2' ) );
					?>
					<label>
						<strong><?php esc_html_e( 'Post meta order (comma separated)', 'arena-engine' ); ?></strong>
						<input type="text" name="arena-layout[arena_post_meta]" value="<?php echo esc_attr( implode( ', ', (array) $current['arena_post_meta'] ) ); ?>" size="50" />
						<span class="description"><?php esc_html_e( 'author, date, categories, tags, comments, reading-time', 'arena-engine' ); ?></span>
					</label>
				</fieldset>

				<fieldset>
					<legend><?php esc_html_e( 'Commerce (H33–H36)', 'arena-engine' ); ?></legend>
					<?php
					$select( 'arena_checkout_mode', __( 'Checkout mode', 'arena-engine' ), array( 'standard' => __( 'Standard', 'arena-engine' ), 'distraction-free' => __( 'Distraction-free', 'arena-engine' ) ) );
					$select( 'arena_sale_badge', __( 'Sale badge variant', 'arena-engine' ), array( 'bubble' => __( 'Bubble', 'arena-engine' ), 'ribbon' => __( 'Ribbon', 'arena-engine' ), 'tag' => __( 'Tag', 'arena-engine' ) ) );
					$select( 'arena_catalog_mode', __( 'Catalog mode', 'arena-engine' ), array( 0 => __( 'Off', 'arena-engine' ), 1 => __( 'On — hide prices and purchase buttons', 'arena-engine' ) ) );
					?>
					<label>
						<strong><?php esc_html_e( 'Product tabs order (comma separated)', 'arena-engine' ); ?></strong>
						<input type="text" name="arena-layout[arena_product_tabs_order]" value="<?php echo esc_attr( implode( ', ', (array) $current['arena_product_tabs_order'] ) ); ?>" size="40" />
						<span class="description"><?php esc_html_e( 'e.g. description, additional_information, reviews', 'arena-engine' ); ?></span>
					</label>
				</fieldset>

				<p>
					<button type="submit" class="button button-primary"><?php esc_html_e( 'Save layout options', 'arena-engine' ); ?></button>
					<span class="arena-status" aria-live="polite"></span>
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Renders the Journal page: every action with undo + generated docs (G12).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function render_journal() {
		$journal = Journal::all();
		$registry = Actions::registry();
		?>
		<div class="wrap arena-wrap">
			<h1><?php esc_html_e( 'Journal', 'arena-engine' ); ?></h1>
			<p class="description"><?php esc_html_e( 'Every Arena action, newest first. Undo restores the previous state; the documentation below each entry is generated from the action registry.', 'arena-engine' ); ?></p>

			<?php if ( empty( $journal ) ) : ?>
				<p><?php esc_html_e( 'No actions yet. Import a kit, apply a preset or change the typography and the journal will start filling up.', 'arena-engine' ); ?></p>
			<?php else : ?>
				<table class="widefat striped arena-journal-table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'When', 'arena-engine' ); ?></th>
							<th><?php esc_html_e( 'Action', 'arena-engine' ); ?></th>
							<th><?php esc_html_e( 'Documentation (generated)', 'arena-engine' ); ?></th>
							<th><?php esc_html_e( 'Undo', 'arena-engine' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $journal as $entry ) : ?>
							<tr>
								<td><?php echo esc_html( $entry['time'] ); ?></td>
								<td>
									<strong><?php echo esc_html( $entry['label'] ); ?></strong>
									<code><?php echo esc_html( $entry['action'] ); ?></code>
									<?php if ( ! empty( $entry['undone'] ) ) : ?>
										<em>— <?php esc_html_e( 'undone', 'arena-engine' ); ?></em>
									<?php endif; ?>
								</td>
								<td>
									<?php echo Actions::documentation( $entry['action'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
									<p class="description"><a href="<?php echo esc_url( home_url( '/docs-utente/' . $entry['doc'] . '/' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Open the documentation page →', 'arena-engine' ); ?></a></p>
								</td>
								<td>
									<?php $reversible = isset( $registry[ $entry['action'] ] ) && $registry[ $entry['action'] ]['reversible']; ?>
									<?php if ( $reversible && empty( $entry['undone'] ) ) : ?>
										<button type="button" class="button" data-arena-undo="<?php echo esc_attr( $entry['id'] ); ?>"><?php esc_html_e( 'Undo', 'arena-engine' ); ?></button>
									<?php else : ?>
										<span aria-hidden="true">—</span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
		<?php
	}
}
