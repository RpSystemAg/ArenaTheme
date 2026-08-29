<?php
/**
 * Action registry: every panel action, its undo handler and its generated
 * documentation anchor (H31/G12/AP9).
 *
 * The registry is the single source of truth tests and the Journal page read:
 * no action ships without a documentation page (docs/utente), and every undo
 * handler is registered by the owning class.
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Panel action registry.
 *
 * @since 1.1.0
 */
final class Actions {

	/**
	 * The registry rows.
	 *
	 * Each row: label, doc (docs/utente page anchor), reversible (bool),
	 * description (the generated documentation for the Journal page).
	 *
	 * @since 1.1.0
	 *
	 * @return array[]
	 */
	public static function registry() {
		$rows = array(
			'preset.apply'   => array(
				'label'       => __( 'Apply global preset', 'arena-engine' ),
				'doc'         => 'preset-globali',
				'reversible'  => true,
				'description' => __( 'Switches the whole design system (palette, type pairing, radius, density) by writing the tracked styles/variation file in uploads/arena/preset.json and the arena_active_preset pointer. Undo restores the previously active variation file and pointer. No database rows besides the journal entry and the pointer option.', 'arena-engine' ),
			),
			'typography.save' => array(
				'label'       => __( 'Save typography levels', 'arena-engine' ),
				'doc'         => 'tipografia',
				'reversible'  => true,
				'description' => __( 'Regenerates the typography styles/variation (uploads/arena/typography.json): per-level family, weight, line-height, letter-spacing, text-transform and the separate mobile/desktop scale. The previous file content is stored in the journal entry, so undo restores it byte-for-byte. Typography never writes scattered options (H25).', 'arena-engine' ),
			),
			'layout.save'    => array(
				'label'       => __( 'Save layout options', 'arena-engine' ),
				'doc'         => 'layout-e-contenitori',
				'reversible'  => true,
				'description' => __( 'Updates the tracked layout settings (container mode and width, sidebar, breadcrumb position, mobile breakpoint, blog loop, checkout mode, catalog mode, sale badge, product tabs order, post-meta order). The previous values are stored in the journal entry; undo restores every one of them.', 'arena-engine' ),
			),
			'meta.save'      => array(
				'label'       => __( 'Per-page overrides', 'arena-engine' ),
				'doc'         => 'meta-per-pagina',
				'reversible'  => true,
				'description' => __( 'Writes the arena_* post meta for one page or post (title/header/footer/sidebar switches, container width, typographic preset override). Undo restores the previous meta values; Reset all deletes them in one click.', 'arena-engine' ),
			),
			'kit.import'     => array(
				'label'       => __( 'Import starter kit', 'arena-engine' ),
				'doc'         => 'kit-e-importer',
				'reversible'  => true,
				'description' => __( 'Imports kit pages, demo products and the kit menu as plain core blocks. Every created object id is stored in the journal entry; undo deletes exactly those objects. Existing content is never overwritten unless the explicit confirmation flag is set, and each overwrite is journaled separately with the previous content.', 'arena-engine' ),
			),
			'kit.undo'       => array(
				'label'       => __( 'Undo kit import', 'arena-engine' ),
				'doc'         => 'kit-e-importer',
				'reversible'  => false,
				'description' => __( 'Removes the objects created by a kit import (pages, products, menu). The removal itself is journaled for auditability but is not re-doable: re-import the kit instead.', 'arena-engine' ),
			),
			'kit.sync'       => array(
				'label'       => __( 'Sync starter kit', 'arena-engine' ),
				'doc'         => 'kit-e-importer',
				'reversible'  => true,
				'description' => __( 'Updates installed kit pages to the kit version shipped with the engine, skipping (and reporting) any page the merchant has edited since the import. Undo restores the pre-sync content stored in the journal entry.', 'arena-engine' ),
			),
		);

		/**
		 * Filter the panel action registry.
		 *
		 * @since 1.1.0
		 *
		 * @param array[] $rows Registry rows.
		 */
		return apply_filters( 'arena_engine_actions_registry', $rows );
	}

	/**
	 * Renders the generated documentation for one action (Journal page, G12).
	 *
	 * @since 1.1.0
	 *
	 * @param string $action Action id.
	 * @return string
	 */
	public static function documentation( $action ) {
		$registry = self::registry();

		if ( ! isset( $registry[ $action ] ) ) {
			return '';
		}

		$row = $registry[ $action ];

		return sprintf(
			'<p class="description">%1$s</p><p class="description"><strong>%2$s</strong> %3$s</p>',
			esc_html( $row['description'] ),
			esc_html__( 'Undo:', 'arena-engine' ),
			$row['reversible']
				? esc_html__( 'available from this page and from the REST endpoint arena/v1/actions/<id>/undo.', 'arena-engine' )
				: esc_html__( 'not applicable (the action only removes kit objects; re-import to restore).', 'arena-engine' )
		);
	}
}
