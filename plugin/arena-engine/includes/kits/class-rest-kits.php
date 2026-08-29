<?php
/**
 * Kit REST API — namespace arena/v1 (H20/H22).
 *
 * Versioned and documented (docs/dev/kits-api.md):
 *   GET  /arena/v1/kits                      list + installed state
 *   GET  /arena/v1/kits/<slug>               manifest
 *   POST /arena/v1/kits/<slug>/import        {scope, page, confirm_overwrite, locale}
 *   POST /arena/v1/kits/<slug>/undo          {journal}
 *   POST /arena/v1/kits/<slug>/sync          update installed kit (H22)
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\Kits;

defined( 'ABSPATH' ) || exit;

/**
 * REST controller for the kit endpoints.
 *
 * @since 1.1.0
 */
final class REST_Kits {

	/**
	 * REST namespace (versioned, H22).
	 *
	 * @var string
	 */
	const NAMESPACE_V1 = 'arena/v1';

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'routes' ) );
	}

	/**
	 * Registers the routes.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function routes() {
		register_rest_route(
			self::NAMESPACE_V1,
			'/kits',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( __CLASS__, 'list_kits' ),
					'permission_callback' => array( __CLASS__, 'can_manage' ),
				),
			)
		);

		register_rest_route(
			self::NAMESPACE_V1,
			'/kits/(?P<slug>[a-z0-9-]+)',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( __CLASS__, 'get_kit' ),
					'permission_callback' => array( __CLASS__, 'can_manage' ),
					'args'                => array(
						'slug' => array( 'sanitize_callback' => 'sanitize_key' ),
					),
				),
			)
		);

		register_rest_route(
			self::NAMESPACE_V1,
			'/kits/(?P<slug>[a-z0-9-]+)/import',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( __CLASS__, 'import_kit' ),
					'permission_callback' => array( __CLASS__, 'can_manage' ),
					'args'                => array(
						'slug'               => array( 'sanitize_callback' => 'sanitize_key' ),
						'scope'              => array(
							'default'           => 'full',
							'sanitize_callback' => static function ( $value ) {
								return in_array( $value, array( 'full', 'page' ), true ) ? $value : 'full';
							},
						),
						'page'               => array( 'sanitize_callback' => 'sanitize_title' ),
						'confirm_overwrite'  => array( 'default' => false ),
						'locale'             => array(
							'default'           => 'en_US',
							'sanitize_callback' => static function ( $value ) {
								return in_array( $value, Kit_Repository::LOCALES, true ) ? $value : 'en_US';
							},
						),
					),
				),
			)
		);

		register_rest_route(
			self::NAMESPACE_V1,
			'/kits/(?P<slug>[a-z0-9-]+)/undo',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( __CLASS__, 'undo_kit' ),
					'permission_callback' => array( __CLASS__, 'can_manage' ),
				),
			)
		);

		register_rest_route(
			self::NAMESPACE_V1,
			'/kits/(?P<slug>[a-z0-9-]+)/sync',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( __CLASS__, 'sync_kit' ),
					'permission_callback' => array( __CLASS__, 'can_manage' ),
				),
			)
		);
	}

	/**
	 * Capability guard.
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public static function can_manage() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * GET /kits — every kit with its installed state.
	 *
	 * @since 1.1.0
	 *
	 * @return \WP_REST_Response
	 */
	public static function list_kits() {
		$installed = Kit_Repository::installed();
		$kits      = array();

		foreach ( Kit_Repository::all() as $slug => $manifest ) {
			$kits[] = self::kit_row( $slug, $manifest, $installed );
		}

		return rest_ensure_response( $kits );
	}

	/**
	 * GET /kits/<slug>.
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function get_kit( $request ) {
		$manifest = Kit_Repository::get( (string) $request['slug'] );

		if ( is_wp_error( $manifest ) ) {
			return $manifest;
		}

		$row = self::kit_row( (string) $request['slug'], $manifest, Kit_Repository::installed() );
		$row['manifest'] = $manifest;

		return rest_ensure_response( $row );
	}

	/**
	 * POST /kits/<slug>/import.
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function import_kit( $request ) {
		$result = Importer::import(
			(string) $request['slug'],
			array(
				'scope'             => (string) $request['scope'],
				'page'              => (string) $request['page'],
				'confirm_overwrite' => ! empty( $request['confirm_overwrite'] ),
				'locale'            => (string) $request['locale'],
			)
		);

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return rest_ensure_response( $result );
	}

	/**
	 * POST /kits/<slug>/undo.
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function undo_kit( $request ) {
		$journal_id = (string) $request->get_param( 'journal' );
		$result     = \Arena_Engine\Admin\Journal::undo( $journal_id );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return rest_ensure_response( array( 'undone' => $journal_id ) );
	}

	/**
	 * POST /kits/<slug>/sync (H22).
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function sync_kit( $request ) {
		$result = Importer::sync( (string) $request['slug'] );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return rest_ensure_response( $result );
	}

	/**
	 * Builds the kit row for list responses.
	 *
	 * @since 1.1.0
	 *
	 * @param string $slug      Kit slug.
	 * @param array  $manifest  Manifest.
	 * @param array  $installed Installed records.
	 * @return array
	 */
	private static function kit_row( $slug, $manifest, $installed ) {
		$imports       = isset( $installed[ $slug ] ) ? $installed[ $slug ] : array();
		$latest        = $imports ? end( $imports ) : null;
		$shipped       = isset( $manifest['version'] ) ? (string) $manifest['version'] : '1.0.0';
		$installed_ver = $latest ? (string) $latest['version'] : null;

		return array(
			'slug'        => $slug,
			'name'        => Kit_Repository::text( $manifest, 'name', 'en_US' ),
			'description' => isset( $manifest['description'] ) ? $manifest['description'] : '',
			'family'      => isset( $manifest['family'] ) ? $manifest['family'] : '',
			'preset'      => isset( $manifest['preset'] ) ? $manifest['preset'] : 'default',
			'pages'       => count( (array) ( isset( $manifest['pages'] ) ? $manifest['pages'] : array() ) ),
			'products'    => count( (array) ( isset( $manifest['products'] ) ? $manifest['products'] : array() ) ),
			'locales'     => array_keys( (array) ( isset( $manifest['i18n'] ) ? $manifest['i18n'] : array() ) ),
			'version'     => $shipped,
			'installed'   => array(
				'is'        => null !== $latest,
				'version'   => $installed_ver,
				'upToDate'  => $installed_ver === $shipped,
				'journal'   => $latest ? $latest['journal'] : null,
				'sync'      => rest_url( self::NAMESPACE_V1 . '/kits/' . $slug . '/sync' ),
			),
		);
	}
}
