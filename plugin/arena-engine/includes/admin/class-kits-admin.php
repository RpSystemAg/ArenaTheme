<?php
/**
 * The Kits admin page (H20): Admin → Arena → Starter kits.
 *
 * Server-rendered kit cards enhanced by assets/js/admin-arena.js:
 * one-click import (whole kit / single page), per-step progress bar,
 * explicit overwrite confirmation, sync (H22) and undo — all through the
 * versioned arena/v1 REST endpoints.
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Renders the kits page.
 *
 * @since 1.1.0
 */
final class Kits_Admin {

	/**
	 * Renders the page.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function render() {
		$kits      = \Arena_Engine\Kits\Kit_Repository::all();
		$installed = \Arena_Engine\Kits\Kit_Repository::installed();
		?>
		<div class="wrap arena-wrap">
			<h1><?php esc_html_e( 'Starter kits', 'arena-engine' ); ?></h1>
			<p class="description">
				<?php esc_html_e( 'Each kit is a complete site: home + internal pages, menu, demo content and its own preset. Imports are selective, journaled and fully reversible; existing content is never overwritten without your explicit confirmation (H20).', 'arena-engine' ); ?>
			</p>

			<div class="arena-kit-grid" data-arena-kits>
				<?php foreach ( $kits as $slug => $manifest ) : ?>
					<?php
					$imports  = isset( $installed[ $slug ] ) ? $installed[ $slug ] : array();
					$latest   = $imports ? end( $imports ) : null;
					$shipped  = isset( $manifest['version'] ) ? (string) $manifest['version'] : '1.0.0';
					$ver      = $latest ? (string) $latest['version'] : null;
					$uptodate = $ver === $shipped;
					?>
					<article class="arena-kit-card" data-arena-kit="<?php echo esc_attr( $slug ); ?>"
						data-locale-default="<?php echo esc_attr( get_locale() ); ?>"
						data-pages='<?php echo esc_attr( wp_json_encode( array_map( static function ( $page ) { return $page['slug']; }, $manifest['pages'] ) ) ); ?>'>
						<header>
							<h2><?php echo esc_html( \Arena_Engine\Kits\Kit_Repository::text( $manifest, 'name', 'en_US' ) ); ?></h2>
							<p class="description"><?php echo esc_html( isset( $manifest['description'] ) ? $manifest['description'] : '' ); ?></p>
							<p class="arena-kit-meta">
								<span class="arena-chip"><?php echo esc_html( sprintf( /* translators: %d: pages. */ _n( '%d page', '%d pages', count( $manifest['pages'] ), 'arena-engine' ), count( $manifest['pages'] ) ) ); ?></span>
								<span class="arena-chip"><?php echo esc_html( sprintf( /* translators: %s: family name. */ __( 'Family: %s', 'arena-engine' ), isset( $manifest['family'] ) ? $manifest['family'] : '—' ) ); ?></span>
								<span class="arena-chip"><?php echo esc_html( sprintf( /* translators: %s: preset name. */ __( 'Preset: %s', 'arena-engine' ), isset( $manifest['preset'] ) ? $manifest['preset'] : 'default' ) ); ?></span>
								<span class="arena-chip">en_US · it_IT</span>
							</p>
						</header>

						<div class="arena-kit-actions">
							<label class="screen-reader-text" for="arena-kit-locale-<?php echo esc_attr( $slug ); ?>"><?php esc_html_e( 'Import language', 'arena-engine' ); ?></label>
							<select id="arena-kit-locale-<?php echo esc_attr( $slug ); ?>" data-arena-kit-locale>
								<option value="en_US">English (en_US)</option>
								<option value="it_IT"<?php echo str_starts_with( get_locale(), 'it_' ) ? ' selected' : ''; ?>>Italiano (it_IT)</option>
							</select>

							<button type="button" class="button button-primary" data-arena-kit-import>
								<?php echo esc_html( $latest ? __( 'Re-import kit', 'arena-engine' ) : __( 'Import kit', 'arena-engine' ) ); ?>
							</button>

							<button type="button" class="button" data-arena-kit-page-import aria-expanded="false">
								<?php esc_html_e( 'Import a single page', 'arena-engine' ); ?>
							</button>

							<?php if ( $latest ) : ?>
								<button type="button" class="button" data-arena-kit-sync <?php disabled( $uptodate ); ?>>
									<?php echo esc_html( $uptodate ? __( 'Up to date', 'arena-engine' ) : __( 'Sync to ' . $shipped, 'arena-engine' ) ); ?>
								</button>
								<button type="button" class="button" data-arena-kit-undo data-journal="<?php echo esc_attr( (string) $latest['journal'] ); ?>">
									<?php esc_html_e( 'Undo import', 'arena-engine' ); ?>
								</button>
							<?php endif; ?>
						</div>

						<div class="arena-kit-pages" hidden>
							<ul>
								<?php foreach ( $manifest['pages'] as $page ) : ?>
									<li>
										<button type="button" class="button-link" data-arena-kit-page="<?php echo esc_attr( $page['slug'] ); ?>">
											<?php echo esc_html( \Arena_Engine\Kits\Kit_Repository::text( $manifest, $page['title_key'], 'en_US' ) ); ?>
										</button>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

						<div class="arena-kit-progress" role="status" aria-live="polite" hidden>
							<div class="arena-kit-progress__bar"><div class="arena-kit-progress__fill" style="inline-size:0%"></div></div>
							<p class="arena-kit-progress__label"></p>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
