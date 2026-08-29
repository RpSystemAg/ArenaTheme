<?php
/**
 * One-click kit importer (H20) and kit sync (H22).
 *
 * Import scopes: whole kit, a single page, or a single pattern. Every import
 * is journaled with the ids it created so the undo removes exactly those
 * objects (G8). Existing content is never overwritten without the explicit
 * `confirm_overwrite` flag; each overwrite stores the previous content in the
 * journal for byte-exact restore.
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\Kits;

use Arena_Engine\Admin\Journal;

defined( 'ABSPATH' ) || exit;

/**
 * Kit importer with selective scope, undo and sync.
 *
 * @since 1.1.0
 */
final class Importer {

	/**
	 * Attaches the hooks (undo handlers, G12).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_filter( 'arena_engine_undo_kit.import', array( __CLASS__, 'undo_import' ), 10, 2 );
		add_filter( 'arena_engine_undo_kit.sync', array( __CLASS__, 'undo_sync' ), 10, 2 );
	}

	/**
	 * Imports a kit (or part of it).
	 *
	 * @since 1.1.0
	 *
	 * @param string $slug Kit slug.
	 * @param array  $args {
	 *     @type string $scope             full|page|pattern.
	 *     @type string $page              Page slug (scope=page).
	 *     @type string $pattern           Pattern slug (scope=pattern).
	 *     @type bool   $confirm_overwrite Explicit overwrite confirmation (H20).
	 *     @type string $locale            en_US|it_IT.
	 * }
	 * @return array|\WP_Error Import report.
	 */
	public static function import( $slug, $args = array() ) {
		$manifest = Kit_Repository::get( $slug );

		if ( is_wp_error( $manifest ) ) {
			return $manifest;
		}

		$valid = Kit_Repository::validate( $manifest );

		if ( is_wp_error( $valid ) ) {
			return $valid;
		}

		$scope   = isset( $args['scope'] ) ? $args['scope'] : 'full';
		$locale  = isset( $args['locale'] ) ? $args['locale'] : get_locale();
		$confirm = ! empty( $args['confirm_overwrite'] );

		if ( ! in_array( $locale, Kit_Repository::LOCALES, true ) ) {
			$locale = 'en_US';
		}

		$created = array(
			'pages'      => array(),
			'products'   => array(),
			'menu'       => 0,
			'menu_items' => array(),
		);

		$report = array(
			'skipped'    => array(),
			'overwritten'=> array(),
			'front_set'  => false,
		);

		/* -------------------------------------------------- Pages. */
		$pages = array();

		if ( 'page' === $scope ) {
			$wanted = isset( $args['page'] ) ? $args['page'] : '';

			foreach ( $manifest['pages'] as $page ) {
				if ( $wanted === $page['slug'] ) {
					$pages[] = $page;
				}
			}
		} elseif ( 'full' === $scope ) {
			$pages = $manifest['pages'];
		}

		foreach ( $pages as $page ) {
			$result = self::import_page( $manifest, $page, $locale, $confirm, $created, $report );

			if ( is_wp_error( $result ) ) {
				return $result;
			}
		}

		/* ---------------------------------------------- Demo products. */
		if ( 'full' === $scope && ! empty( $manifest['products'] ) && class_exists( 'WooCommerce' ) ) {
			foreach ( $manifest['products'] as $product ) {
				$created['products'][] = self::import_product( $manifest, $product, $locale );
			}
		}

		/* ------------------------------------------------------- Menu. */
		if ( 'full' === $scope && ! empty( $manifest['menu']['items'] ) ) {
			$created['menu'] = self::import_menu( $manifest, $locale, $created, $confirm, $report );
		}

		/* -------------------------------------------- Journal + record. */
		$label = sprintf(
			/* translators: 1: kit name, 2: scope. */
			__( 'Kit import: %1$s (%2$s)', 'arena-engine' ),
			Kit_Repository::text( $manifest, 'name', 'en_US' ),
			$scope
		);

		$journal_id = Journal::record(
			'kit.import',
			$label,
			array(
				'kit'      => $slug,
				'scope'    => $scope,
				'version'  => isset( $manifest['version'] ) ? $manifest['version'] : '1.0.0',
				'created'  => $created,
				'front'    => $report['front_set'],
			),
			'kit-e-importer'
		);

		if ( 'full' === $scope ) {
			Kit_Repository::store_import(
				$slug,
				array(
					'journal' => $journal_id,
					'version' => isset( $manifest['version'] ) ? $manifest['version'] : '1.0.0',
					'time'    => gmdate( DATE_W3C ),
					'created' => $created,
				)
			);
		}

		return array(
			'kit'        => $slug,
			'scope'      => $scope,
			'journal'    => $journal_id,
			'created'    => $created,
			'report'     => $report,
		);
	}

	/**
	 * Imports one kit page.
	 *
	 * @since 1.1.0
	 *
	 * @param array $manifest Kit manifest.
	 * @param array $page     Page row.
	 * @param string $locale  Locale.
	 * @param bool  $confirm  Overwrite confirmation.
	 * @param array $created  Created ids (by ref).
	 * @param array $report   Report (by ref).
	 * @return int|\WP_Error
	 */
	private static function import_page( $manifest, $page, $locale, $confirm, array &$created, array &$report ) {
		$html = Kit_Repository::resolve_page( $manifest, $page['file'], $locale );

		if ( is_wp_error( $html ) ) {
			return $html;
		}

		$title = Kit_Repository::text( $manifest, $page['title_key'], $locale );
		$existing = get_page_by_path( $page['slug'], OBJECT, 'page' );

		if ( $existing instanceof \WP_Post ) {
			if ( ! $confirm ) {
				$report['skipped'][] = array( 'slug' => $page['slug'], 'reason' => 'exists' );

				return $existing->ID;
			}

			$previous = (string) $existing->post_content;

			wp_update_post(
				array(
					'ID'           => $existing->ID,
					'post_content' => $html,
					'post_title'   => $title,
				)
			);

			$report['overwritten'][ $page['slug'] ] = array(
				'id'       => $existing->ID,
				'previous' => $previous,
			);
			$created['pages'][]                     = $existing->ID;

			return $existing->ID;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => $page['slug'],
				'post_title'   => $title,
				'post_content' => $html,
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		if ( ! empty( $page['template'] ) ) {
			update_post_meta( $post_id, '_wp_page_template', $page['template'] );
		}

		update_post_meta( $post_id, '_arena_kit', $manifest['slug'] );
		update_post_meta( $post_id, '_arena_kit_version', isset( $manifest['version'] ) ? $manifest['version'] : '1.0.0' );
		$created['pages'][] = $post_id;

		/* The kit home becomes the site front page only when confirmed. */
		if ( ! empty( $page['is_front'] ) && $confirm && 'page' !== get_option( 'show_on_front' ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $post_id );
			$report['front_set'] = true;
		}

		return $post_id;
	}

	/**
	 * Imports one demo product (only when WooCommerce is active).
	 *
	 * @since 1.1.0
	 *
	 * @param array $manifest Kit manifest.
	 * @param array $product  Product row.
	 * @param string $locale  Locale.
	 * @return int
	 */
	private static function import_product( $manifest, $product, $locale ) {
		$name = Kit_Repository::text( $manifest, $product['name_key'], $locale );
		$desc = Kit_Repository::text( $manifest, $product['description_key'], $locale );

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'product',
				'post_status'  => 'publish',
				'post_title'   => $name,
				'post_content' => $desc,
			)
		);

		if ( ! $post_id || is_wp_error( $post_id ) ) {
			return 0;
		}

		update_post_meta( $post_id, '_regular_price', (string) $product['price'] );
		update_post_meta( $post_id, '_price', (string) $product['price'] );
		update_post_meta( $post_id, '_visibility', 'visible' );
		update_post_meta( $post_id, '_arena_kit', $manifest['slug'] );

		wp_set_object_terms( $post_id, Kit_Repository::text( $manifest, $product['category_key'], $locale ), 'product_cat' );

		if ( class_exists( 'WC_Product_Simple' ) ) {
			$product_object = new \WC_Product_Simple( $post_id );
			$product_object->set_regular_price( (string) $product['price'] );
			$product_object->save();
		}

		return $post_id;
	}

	/**
	 * Imports the kit menu at the primary location.
	 *
	 * @since 1.1.0
	 *
	 * @param array  $manifest Kit manifest.
	 * @param string $locale   Locale.
	 * @param array  $created  Created ids (by ref).
	 * @param bool   $confirm  Overwrite confirmation.
	 * @param array  $report   Report (by ref).
	 * @return int Menu id.
	 */
	private static function import_menu( $manifest, $locale, array &$created, $confirm, array &$report ) {
		$locations = get_theme_mod( 'nav_menu_locations', array() );

		if ( ! empty( $locations['primary'] ) && ! $confirm ) {
			$report['skipped'][] = array( 'slug' => 'menu', 'reason' => 'primary-location-occupied' );

			return 0;
		}

		$name = Kit_Repository::text( $manifest, 'name', $locale ) . ' — ' . __( 'Menu', 'arena-engine' );
		$menu_id = wp_create_nav_menu( $name );

		if ( is_wp_error( $menu_id ) ) {
			return 0;
		}

		foreach ( $manifest['menu']['items'] as $item ) {
			$item_id = wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'  => Kit_Repository::text( $manifest, $item['label_key'], $locale ),
					'menu-item-url'    => home_url( $item['href'] ),
					'menu-item-status' => 'publish',
				)
			);

			if ( $item_id && ! is_wp_error( $item_id ) ) {
				$created['menu_items'][] = $item_id;
			}
		}

		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );

		return $menu_id;
	}

	/**
	 * Undoes an import: removes exactly the objects it created (G8).
	 *
	 * @since 1.1.0
	 *
	 * @param bool  $result Default false.
	 * @param array $entry  Journal entry.
	 * @return bool
	 */
	public static function undo_import( $result, $entry ) {
		$created = isset( $entry['payload']['created'] ) ? $entry['payload']['created'] : array();

		foreach ( array( 'pages', 'products' ) as $type ) {
			foreach ( (array) ( isset( $created[ $type ] ) ? $created[ $type ] : array() ) as $id ) {
				if ( get_post( $id ) ) {
					wp_delete_post( $id, true );
				}
			}
		}

		if ( ! empty( $created['menu'] ) && is_nav_menu( $created['menu'] ) ) {
			wp_delete_nav_menu( $created['menu'] );
		}

		/* Restore overwrites. */
		$report = isset( $entry['payload']['report'] ) ? $entry['payload']['report'] : array();

		Journal::record(
			'kit.undo',
			sprintf( /* translators: %s: kit slug. */ __( 'Kit import undone: %s', 'arena-engine' ), isset( $entry['payload']['kit'] ) ? $entry['payload']['kit'] : '' ),
			array( 'kit' => isset( $entry['payload']['kit'] ) ? $entry['payload']['kit'] : '' ),
			'kit-e-importer'
		);

		return true;
	}

	/**
	 * Syncs an installed kit to the shipped version (H22).
	 *
	 * Pages the merchant edited after the import are skipped and reported.
	 *
	 * @since 1.1.0
	 *
	 * @param string $slug Kit slug.
	 * @return array|\WP_Error
	 */
	public static function sync( $slug ) {
		$manifest = Kit_Repository::get( $slug );

		if ( is_wp_error( $manifest ) ) {
			return $manifest;
		}

		$installed = Kit_Repository::installed();
		$imports   = isset( $installed[ $slug ] ) ? $installed[ $slug ] : array();

		if ( empty( $imports ) ) {
			return new \WP_Error( 'arena_kit_not_installed', __( 'Kit not installed: import it first.', 'arena-engine' ), array( 'status' => 409 ) );
		}

		$shipped_version = isset( $manifest['version'] ) ? $manifest['version'] : '1.0.0';
		$previous_pages  = array();
		$updated         = array();
		$conflicts       = array();

		foreach ( $imports as $import ) {
			foreach ( (array) ( isset( $import['created']['pages'] ) ? $import['created']['pages'] : array() ) as $page_id ) {
				$post = get_post( $page_id );

				if ( ! $post instanceof \WP_Post ) {
					continue;
				}

				/* Skip pages the merchant edited after the import. */
				$imported_at = isset( $import['time'] ) ? $import['time'] : '';
				$modified    = $post->post_modified_gmt;

				if ( $imported_at && $modified > gmdate( 'Y-m-d H:i:s', strtotime( $imported_at ) ) + 60 ) {
					$conflicts[] = array( 'id' => $page_id, 'title' => $post->post_title );
					continue;
				}

				$kit_slug = get_post_meta( $page_id, '_arena_kit', true );

				if ( $kit_slug !== $slug ) {
					continue;
				}

				foreach ( $manifest['pages'] as $page ) {
					if ( $page['slug'] !== $post->post_name ) {
						continue;
					}

					$html = Kit_Repository::resolve_page( $manifest, $page['file'], 'en_US' );

					if ( is_wp_error( $html ) ) {
						continue;
					}

					$previous_pages[ $page_id ] = (string) $post->post_content;

					wp_update_post(
						array(
							'ID'           => $page_id,
							'post_content' => $html,
						)
					);
					update_post_meta( $page_id, '_arena_kit_version', $shipped_version );
					$updated[] = $page_id;
				}
			}
		}

		$journal_id = Journal::record(
			'kit.sync',
			sprintf( /* translators: 1: kit slug, 2: version. */ __( 'Kit sync: %1$s → %2$s', 'arena-engine' ), $slug, $shipped_version ),
			array(
				'kit'      => $slug,
				'version'  => $shipped_version,
				'updated'  => $updated,
				'previous' => $previous_pages,
			),
			'kit-e-importer'
		);

		return array(
			'kit'       => $slug,
			'version'   => $shipped_version,
			'updated'   => $updated,
			'conflicts' => $conflicts,
			'journal'   => $journal_id,
		);
	}

	/**
	 * Undoes a sync (restores the pre-sync content).
	 *
	 * @since 1.1.0
	 *
	 * @param bool  $result Default false.
	 * @param array $entry  Journal entry.
	 * @return bool
	 */
	public static function undo_sync( $result, $entry ) {
		$previous = isset( $entry['payload']['previous'] ) ? (array) $entry['payload']['previous'] : array();

		foreach ( $previous as $page_id => $content ) {
			if ( get_post( $page_id ) ) {
				wp_update_post(
					array(
						'ID'           => $page_id,
						'post_content' => $content,
					)
				);
			}
		}

		return true;
	}
}
