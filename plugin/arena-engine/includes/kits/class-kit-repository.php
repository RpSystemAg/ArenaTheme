<?php
/**
 * Starter kit repository (H19–H23).
 *
 * Reads and validates the kit manifests shipped in /kits. A kit is a working
 * site: home + 5–8 internal pages + menu + demo content + preset, expressed
 * as core block markup (H23 — zero proprietary shortcodes, zero lock-in).
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\Kits;

defined( 'ABSPATH' ) || exit;

/**
 * Kit manifest repository.
 *
 * @since 1.1.0
 */
final class Kit_Repository {

	/**
	 * Minimum kit size: home + 5 internal pages (H19).
	 *
	 * @var int
	 */
	const MIN_PAGES = 6;

	/**
	 * Maximum kit size: home + 8 internal pages (H19).
	 *
	 * @var int
	 */
	const MAX_PAGES = 9;

	/**
	 * Locales every kit must ship (H42).
	 *
	 * @var string[]
	 */
	const LOCALES = array( 'en_US', 'it_IT' );

	/**
	 * Lists every kit manifest, keyed by slug.
	 *
	 * @since 1.1.0
	 *
	 * @return array[]
	 */
	public static function all() {
		$kits  = array();
		$dir   = ARENA_ENGINE_DIR . 'kits';

		if ( ! is_dir( $dir ) ) {
			return $kits;
		}

		foreach ( glob( $dir . '/*/kit.json' ) ?: array() as $file ) {
			$manifest = json_decode( (string) file_get_contents( $file ), true );

			if ( ! is_array( $manifest ) || empty( $manifest['slug'] ) ) {
				continue;
			}

			$manifest['_path']     = dirname( $file );
			$manifest['_version']  = isset( $manifest['version'] ) ? (string) $manifest['version'] : '1.0.0';
			$kits[ $manifest['slug'] ] = $manifest;
		}

		ksort( $kits );

		return $kits;
	}

	/**
	 * Reads one kit manifest.
	 *
	 * @since 1.1.0
	 *
	 * @param string $slug Kit slug.
	 * @return array|\WP_Error
	 */
	public static function get( $slug ) {
		$kits = self::all();

		if ( ! isset( $kits[ $slug ] ) ) {
			return new \WP_Error( 'arena_kit_unknown', __( 'Unknown starter kit.', 'arena-engine' ), array( 'status' => 404 ) );
		}

		return $kits[ $slug ];
	}

	/**
	 * Validates a manifest against the H19/H42 rules.
	 *
	 * @since 1.1.0
	 *
	 * @param array $manifest Kit manifest.
	 * @return true|\WP_Error
	 */
	public static function validate( $manifest ) {
		$pages = isset( $manifest['pages'] ) && is_array( $manifest['pages'] ) ? $manifest['pages'] : array();
		$count = count( $pages );

		if ( $count < self::MIN_PAGES ) {
			return new \WP_Error(
				'arena_kit_too_small',
				sprintf( /* translators: %d: number of pages. */ __( 'A kit needs at least %d pages (home + 5 internal).', 'arena-engine' ), self::MIN_PAGES ),
				array( 'status' => 422 )
			);
		}

		if ( $count > self::MAX_PAGES ) {
			return new \WP_Error(
				'arena_kit_too_large',
				sprintf( /* translators: %d: number of pages. */ __( 'A kit may ship at most %d pages (home + 8 internal).', 'arena-engine' ), self::MAX_PAGES ),
				array( 'status' => 422 )
			);
		}

		if ( empty( $manifest['i18n'] ) || array_diff( self::LOCALES, array_keys( (array) $manifest['i18n'] ) ) ) {
			return new \WP_Error( 'arena_kit_locales', __( 'A kit must ship en_US and it_IT content (H42).', 'arena-engine' ), array( 'status' => 422 ) );
		}

		if ( empty( $manifest['menu'] ) || empty( $manifest['menu']['items'] ) ) {
			return new \WP_Error( 'arena_kit_menu', __( 'A kit must ship a menu (H19).', 'arena-engine' ), array( 'status' => 422 ) );
		}

		return true;
	}

	/**
	 * Resolves a kit page file: translation tokens and pattern includes.
	 *
	 * Tokens (H23):
	 *   {{t:key}}        replaced from the kit i18n map for the chosen locale;
	 *   {{pattern:slug}} replaced with the theme pattern markup (core blocks).
	 *
	 * @since 1.1.0
	 *
	 * @param array  $manifest Kit manifest.
	 * @param string $file     Page file name relative to the kit directory.
	 * @param string $locale   Locale key.
	 * @return string|\WP_Error
	 */
	public static function resolve_page( $manifest, $file, $locale = 'en_US' ) {
		$path = trailingslashit( $manifest['_path'] ) . $file;

		if ( ! is_readable( $path ) ) {
			return new \WP_Error( 'arena_kit_page_missing', __( 'Kit page file missing.', 'arena-engine' ), array( 'status' => 500 ) );
		}

		$html   = (string) file_get_contents( $path );
		$locale = isset( $manifest['i18n'][ $locale ] ) ? $locale : 'en_US';
		$map    = isset( $manifest['i18n'][ $locale ] ) ? (array) $manifest['i18n'][ $locale ] : array();

		/* Translations. */
		$html = preg_replace_callback(
			'/\{\{t:([a-zA-Z0-9_.-]+)\}\}/',
			static function ( $matches ) use ( $map ) {
				return isset( $map[ $matches[1] ] ) ? $map[ $matches[1] ] : $matches[1];
			},
			$html
		);

		/* Pattern includes resolved to real markup → zero lock-in (H23). */
		$html = preg_replace_callback(
			'/\{\{pattern:([a-zA-Z0-9\/_-]+)\}\}/',
			static function ( $matches ) {
				$slug  = $matches[1];
				$theme = get_stylesheet_directory();
				$file  = $theme . '/patterns/' . str_replace( 'arena-commerce/', '', $slug ) . '.php';

				if ( ! is_readable( $file ) ) {
					return '';
				}

				$raw    = (string) file_get_contents( $file );
				$header = strpos( $raw, '?>' );

				return false === $header ? $raw : substr( $raw, $header + 2 );
			},
			$html
		);

		return $html;
	}

	/**
	 * Resolves a translated string from the manifest.
	 *
	 * @since 1.1.0
	 *
	 * @param array  $manifest Kit manifest.
	 * @param string $key      i18n key.
	 * @param string $locale   Locale.
	 * @return string
	 */
	public static function text( $manifest, $key, $locale = 'en_US' ) {
		$locale = isset( $manifest['i18n'][ $locale ] ) ? $locale : 'en_US';

		return isset( $manifest['i18n'][ $locale ][ $key ] ) ? (string) $manifest['i18n'][ $locale ][ $key ] : $key;
	}

	/**
	 * The installed-import records for every kit.
	 *
	 * @since 1.1.0
	 *
	 * @return array[] slug → array of import records.
	 */
	public static function installed() {
		$records = get_option( 'arena_kit_imports', array() );

		return is_array( $records ) ? $records : array();
	}

	/**
	 * Stores an import record.
	 *
	 * @since 1.1.0
	 *
	 * @param string $slug   Kit slug.
	 * @param array  $record Import record (journal id, version, created ids).
	 * @return void
	 */
	public static function store_import( $slug, $record ) {
		$records         = self::installed();
		$records[ $slug ]   = isset( $records[ $slug ] ) && is_array( $records[ $slug ] ) ? $records[ $slug ] : array();
		$records[ $slug ][] = $record;

		update_option( 'arena_kit_imports', $records, false );
	}
}
