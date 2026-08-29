<?php
/**
 * Panel REST API — namespace arena/v1 (H31/H32/G12).
 *
 * Versioned and documented (docs/dev/kits-api.md):
 *   GET/POST /arena/v1/typography    per-level typography (H25)
 *   GET/POST /arena/v1/presets       preset activation (H40)
 *   GET/POST /arena/v1/layout        layout options (H31)
 *   GET/POST /arena/v1/meta/<id>     per-page overrides (H32)
 *   GET      /arena/v1/actions       journal
 *   POST     /arena/v1/actions/<id>/undo
 *
 * Every POST journals the change with its previous state; every action has a
 * registered undo handler (AP9/G12).
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * REST controller for the panel endpoints.
 *
 * @since 1.1.0
 */
final class REST_Panel {

	/**
	 * REST namespace.
	 *
	 * @var string
	 */
	const NAMESPACE_V1 = 'arena/v1';

	/**
	 * Typography levels exposed by the panel (H25).
	 *
	 * @var string[]
	 */
	const TYPO_LEVELS = array( 'display', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'body', 'caption', 'price', 'quote' );

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'routes' ) );

		add_filter( 'arena_engine_undo_preset.apply', array( __CLASS__, 'undo_preset' ), 10, 2 );
		add_filter( 'arena_engine_undo_typography.save', array( __CLASS__, 'undo_typography' ), 10, 2 );
		add_filter( 'arena_engine_undo_layout.save', array( __CLASS__, 'undo_layout' ), 10, 2 );
	}

	/**
	 * Registers the routes.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function routes() {
		$manage = array( __CLASS__, 'can_manage' );

		register_rest_route( self::NAMESPACE_V1, '/typography', array(
			array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( __CLASS__, 'get_typography' ), 'permission_callback' => $manage ),
			array( 'methods' => \WP_REST_Server::CREATABLE, 'callback' => array( __CLASS__, 'save_typography' ), 'permission_callback' => $manage ),
		) );

		register_rest_route( self::NAMESPACE_V1, '/presets', array(
			array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( __CLASS__, 'get_presets' ), 'permission_callback' => $manage ),
			array( 'methods' => \WP_REST_Server::CREATABLE, 'callback' => array( __CLASS__, 'apply_preset' ), 'permission_callback' => $manage ),
		) );

		register_rest_route( self::NAMESPACE_V1, '/layout', array(
			array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( __CLASS__, 'get_layout' ), 'permission_callback' => $manage ),
			array( 'methods' => \WP_REST_Server::CREATABLE, 'callback' => array( __CLASS__, 'save_layout' ), 'permission_callback' => $manage ),
		) );

		register_rest_route( self::NAMESPACE_V1, '/meta/(?P<id>\d+)', array(
			array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( __CLASS__, 'get_meta' ), 'permission_callback' => $manage ),
			array( 'methods' => \WP_REST_Server::CREATABLE, 'callback' => array( __CLASS__, 'save_meta' ), 'permission_callback' => $manage ),
		) );

		register_rest_route( self::NAMESPACE_V1, '/actions', array(
			array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( __CLASS__, 'get_actions' ), 'permission_callback' => $manage ),
		) );

		register_rest_route( self::NAMESPACE_V1, '/actions/(?P<id>[a-zA-Z0-9_]+)/undo', array(
			array( 'methods' => \WP_REST_Server::CREATABLE, 'callback' => array( __CLASS__, 'undo_action' ), 'permission_callback' => $manage ),
		) );
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

	/* ------------------------------------------------------- Typography */

	/**
	 * GET /typography — the current levels and scales.
	 *
	 * @since 1.1.0
	 *
	 * @return \WP_REST_Response
	 */
	public static function get_typography() {
		$saved = Variations::read( 'typography.json' );

		return rest_ensure_response(
			array(
				'levels'   => isset( $saved['arenaTypography'] ) ? $saved['arenaTypography'] : new \stdClass(),
				'scale'    => isset( $saved['arenaTypographyScale'] ) ? $saved['arenaTypographyScale'] : array( 'mobile' => 1, 'desktop' => 1 ),
				'levelsAvailable' => self::TYPO_LEVELS,
			)
		);
	}

	/**
	 * POST /typography — regenerate the tracked typography variation (H25).
	 *
	 * Body: { levels: { h2: {family,weight,lineHeight,letterSpacing,textTransform} … },
	 *         scale: { mobile: 0.9, desktop: 1.1 } }
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function save_typography( $request ) {
		$levels = (array) $request->get_param( 'levels' );
		$scale  = (array) $request->get_param( 'scale' );

		$mobile  = max( 0.75, min( 1.5, (float) ( isset( $scale['mobile'] ) ? $scale['mobile'] : 1 ) ) );
		$desktop = max( 0.9, min( 2, (float) ( isset( $scale['desktop'] ) ? $scale['desktop'] : 1 ) ) );

		$variation = self::typography_variation( $levels, $mobile, $desktop );

		$previous = Variations::read( 'typography.json' );
		$write    = Variations::write( 'typography.json', $variation );

		$journal_id = Journal::record(
			'typography.save',
			__( 'Typography levels saved', 'arena-engine' ),
			array(
				'previous' => isset( $write['previous'] ) ? $write['previous'] : '',
				'new'      => $variation,
			),
			'tipografia'
		);

		return rest_ensure_response(
			array(
				'saved'    => true,
				'journal'  => $journal_id,
				'status'   => $write['status'],
			)
		);
	}

	/**
	 * Builds the typography variation from panel input (H25).
	 *
	 * @since 1.1.0
	 *
	 * @param array $levels  Level overrides.
	 * @param float $mobile  Mobile scale factor.
	 * @param float $desktop Desktop scale factor.
	 * @return array
	 */
	public static function typography_variation( $levels, $mobile, $desktop ) {
		$font_slugs = array(
			'display' => '5xl', 'h1' => '4xl', 'h2' => '3xl', 'h3' => '2xl',
			'h4' => 'xl', 'h5' => 'lg', 'h6' => 'md', 'body' => 'md',
			'caption' => 'caption', 'price' => 'price', 'quote' => 'quote',
		);

		$base = array(
			'display' => array( '2.25rem', '4rem' ), 'h1' => array( '2rem', '3rem' ),
			'h2' => array( '1.375rem', '1.75rem' ), 'h3' => array( '1.1875rem', '1.375rem' ),
			'h4' => array( '1.0625rem', '1.1875rem' ), 'h5' => array( '0.9375rem', '1rem' ),
			'h6' => array( '0.9375rem', '1rem' ), 'body' => array( '0.9375rem', '1rem' ),
			'caption' => array( '0.75rem', '0.8125rem' ), 'price' => array( '1.125rem', '1.375rem' ),
			'quote' => array( '1.25rem', '1.75rem' ),
		);

		$sizes = array();

		foreach ( $font_slugs as $level => $slug ) {
			if ( 'caption' === $level || 'price' === $level || 'quote' === $level ) {
				$slug = $level;
			}

			$sizes[] = array(
				'name'  => ucfirst( $level ),
				'slug'  => $slug,
				'size'  => sprintf( '%.4frem', (float) $base[ $level ][1] * $desktop ),
				'fluid' => array(
					'min' => sprintf( '%.4frem', (float) $base[ $level ][0] * $mobile ),
					'max' => sprintf( '%.4frem', (float) $base[ $level ][1] * $desktop ),
				),
			);
		}

		$elements = array();

		foreach ( $levels as $level => $settings ) {
			$level = sanitize_key( $level );

			if ( ! in_array( $level, self::TYPO_LEVELS, true ) || ! is_array( $settings ) ) {
				continue;
			}

			$typography = array();

			if ( ! empty( $settings['family'] ) ) {
				$typography['fontFamily'] = 'var(--wp--preset--font-family--' . sanitize_key( $settings['family'] ) . ')';
			}

			foreach ( array( 'weight' => 'fontWeight', 'lineHeight' => 'lineHeight', 'letterSpacing' => 'letterSpacing' ) as $in => $out ) {
				if ( isset( $settings[ $in ] ) && '' !== $settings[ $in ] ) {
					$typography[ $out ] = sanitize_text_field( (string) $settings[ $in ] );
				}
			}

			if ( ! empty( $settings['textTransform'] ) ) {
				$typography['textTransform'] = sanitize_key( $settings['textTransform'] );
			}

			if ( $typography ) {
				if ( 'display' === $level ) {
					$elements['heading'] = array( 'typography' => $typography );
				} elseif ( 'body' === $level ) {
					$elements = array( 'text' => array( 'typography' => $typography ) );
				} else {
					$elements[ $level ] = array( 'typography' => $typography );
				}
			}
		}

		return array(
			'$schema' => 'https://schemas.wp.org/trunk/theme.json',
			'version' => 3,
			'title'   => 'Arena typography',
			'settings' => array(
				'typography' => array( 'fluid' => true, 'fontSizes' => $sizes ),
			),
			'styles'   => array( 'elements' => $elements ),
			/* Panel state echoed back so the UI can round-trip exactly. */
			'arenaTypography'      => $levels,
			'arenaTypographyScale' => array( 'mobile' => $mobile, 'desktop' => $desktop ),
		);
	}

	/**
	 * Undo: restore the previous typography file byte-for-byte.
	 *
	 * @since 1.1.0
	 *
	 * @param bool  $result Default false.
	 * @param array $entry  Journal entry.
	 * @return bool
	 */
	public static function undo_typography( $result, $entry ) {
		return Variations::restore( 'typography.json', isset( $entry['payload']['previous'] ) ? $entry['payload']['previous'] : '' );
	}

	/* ---------------------------------------------------------- Presets */

	/**
	 * GET /presets — shipped presets + the active one.
	 *
	 * @since 1.1.0
	 *
	 * @return \WP_REST_Response
	 */
	public static function get_presets() {
		$presets = array();
		$dir     = get_stylesheet_directory() . '/styles';

		foreach ( glob( $dir . '/*.json' ) ?: array() as $file ) {
			$slug = basename( $file, '.json' );
			$json = json_decode( (string) file_get_contents( $file ), true );
			$presets[] = array(
				'slug'  => $slug,
				'title' => is_array( $json ) && isset( $json['title'] ) ? $json['title'] : $slug,
			);
		}

		return rest_ensure_response(
			array(
				'active'  => (string) get_option( 'arena_active_preset', 'default' ),
				'presets' => $presets,
			)
		);
	}

	/**
	 * POST /presets — apply a preset in one click (H40).
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function apply_preset( $request ) {
		$slug = sanitize_key( (string) $request->get_param( 'slug' ) );
		$file = get_stylesheet_directory() . '/styles/' . $slug . '.json';

		if ( ! preg_match( '/^[a-z]+$/', $slug ) || ! is_readable( $file ) ) {
			return new \WP_Error( 'arena_preset_unknown', __( 'Unknown preset.', 'arena-engine' ), array( 'status' => 404 ) );
		}

		$config   = json_decode( (string) file_get_contents( $file ), true );
		$previous = (string) get_option( 'arena_active_preset', 'default' );

		$write = Variations::write( 'preset.json', is_array( $config ) ? $config : array() );
		update_option( 'arena_active_preset', $slug, false );

		$journal_id = Journal::record(
			'preset.apply',
			sprintf( /* translators: %s: preset slug. */ __( 'Preset applied: %s', 'arena-engine' ), $slug ),
			array(
				'preset'   => $slug,
				'previous' => array(
					'slug'   => $previous,
					'file'   => isset( $write['previous'] ) ? $write['previous'] : '',
				),
			),
			'preset-globali'
		);

		return rest_ensure_response( array( 'applied' => $slug, 'journal' => $journal_id ) );
	}

	/**
	 * Undo: restore the previous preset file and pointer.
	 *
	 * @since 1.1.0
	 *
	 * @param bool  $result Default false.
	 * @param array $entry  Journal entry.
	 * @return bool
	 */
	public static function undo_preset( $result, $entry ) {
		$previous = isset( $entry['payload']['previous'] ) ? (array) $entry['payload']['previous'] : array();

		Variations::restore( 'preset.json', isset( $previous['file'] ) ? $previous['file'] : '' );
		update_option( 'arena_active_preset', isset( $previous['slug'] ) ? $previous['slug'] : 'default', false );

		return true;
	}

	/* ----------------------------------------------------------- Layout */

	/**
	 * The layout settings the panel owns (H31) with their sanitizers.
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	public static function layout_schema() {
		return array(
			'arena_container'          => array( 'default' => 'boxed', 'enum' => array( 'boxed', 'fullwidth', 'narrow' ) ),
			'arena_container_width'    => array( 'default' => '', 'pattern' => '/^\d{2,3}(\.\d+)?$/' ),
			'arena_sidebar'            => array( 'default' => 'right', 'enum' => array( 'none', 'left', 'right' ) ),
			'arena_breadcrumb_position' => array( 'default' => 'in-content', 'enum' => array( 'above-header', 'below-header', 'in-content', 'hidden' ) ),
			'arena_mobile_breakpoint'  => array( 'default' => 600, 'enum' => array( 600, 782, 960 ) ),
			'arena_blog_layout'        => array( 'default' => 'grid', 'enum' => array( 'grid', 'list', 'fullwidth', 'masonry' ) ),
			'arena_blog_content'       => array( 'default' => 'excerpt', 'enum' => array( 'excerpt', 'full' ) ),
			'arena_blog_ratio'         => array( 'default' => '4-3', 'enum' => array( '4-3', '16-9', '1-1', '3-2' ) ),
			'arena_checkout_mode'      => array( 'default' => 'standard', 'enum' => array( 'standard', 'distraction-free' ) ),
			'arena_catalog_mode'       => array( 'default' => false, 'bool' => true ),
			'arena_sale_badge'         => array( 'default' => 'bubble', 'enum' => array( 'bubble', 'ribbon', 'tag' ) ),
			'arena_product_tabs_order' => array( 'default' => array(), 'list' => true ),
			'arena_post_meta'          => array( 'default' => array( 'author', 'date', 'categories', 'tags', 'comments', 'reading-time' ), 'list' => true ),
		);
	}

	/**
	 * GET /layout.
	 *
	 * @since 1.1.0
	 *
	 * @return \WP_REST_Response
	 */
	public static function get_layout() {
		$settings = array();

		foreach ( self::layout_schema() as $key => $schema ) {
			$settings[ $key ] = get_option( $key, $schema['default'] );
		}

		return rest_ensure_response( $settings );
	}

	/**
	 * POST /layout — save the tracked layout settings (H31).
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response
	 */
	public static function save_layout( $request ) {
		$input    = (array) $request->get_json_params();
		$previous = array();
		$changed  = array();

		foreach ( self::layout_schema() as $key => $schema ) {
			if ( ! array_key_exists( $key, $input ) ) {
				continue;
			}

			$value = $input[ $key ];

			if ( isset( $schema['list'] ) && $schema['list'] ) {
				$value = array_values( array_filter( (array) $value, 'is_scalar' ) );
				$value = array_map( 'sanitize_text_field', array_map( 'strval', $value ) );
			} elseif ( isset( $schema['bool'] ) && $schema['bool'] ) {
				$value = (bool) $value;
			} elseif ( isset( $schema['pattern'] ) ) {
				$value = preg_match( $schema['pattern'], (string) $value ) ? (string) $value : $schema['default'];
			} elseif ( isset( $schema['enum'] ) ) {
				$value = in_array( $value, $schema['enum'], true ) ? $value : $schema['default'];
			} else {
				$value = sanitize_text_field( (string) $value );
			}

			$previous[ $key ] = get_option( $key, $schema['default'] );
			$changed[ $key ]  = $value;
			update_option( $key, $value, false );
		}

		/* The container width is also a real styles/variation (H31). */
		if ( isset( $changed['arena_container'] ) || isset( $changed['arena_container_width'] ) ) {
			$width = isset( $changed['arena_container_width'] ) && '' !== $changed['arena_container_width']
				? (float) $changed['arena_container_width']
				: null;
			$mode  = isset( $changed['arena_container'] ) ? $changed['arena_container'] : (string) get_option( 'arena_container', 'boxed' );

			Variations::write( 'layout.json', self::layout_variation( $mode, $width ) );
		}

		$journal_id = Journal::record(
			'layout.save',
			__( 'Layout options saved', 'arena-engine' ),
			array( 'previous' => $previous, 'new' => $changed ),
			'layout-e-contenitori'
		);

		return rest_ensure_response( array( 'saved' => true, 'journal' => $journal_id ) );
	}

	/**
	 * Builds the layout variation (container width / content size).
	 *
	 * @since 1.1.0
	 *
	 * @param string   $mode  boxed|fullwidth|narrow.
	 * @param float|null $width Custom width in rem.
	 * @return array
	 */
	public static function layout_variation( $mode, $width = null ) {
		$content = '46rem';
		$wide    = '77.5rem';

		if ( 'fullwidth' === $mode ) {
			$content = '77.5rem';
			$wide    = '100rem';
		} elseif ( 'narrow' === $mode ) {
			$content = '38rem';
			$wide    = '46rem';
		}

		if ( null !== $width && $width >= 30 && $width <= 90 ) {
			$content = $width . 'rem';
		}

		return array(
			'$schema'  => 'https://schemas.wp.org/trunk/theme.json',
			'version'  => 3,
			'title'    => 'Arena layout',
			'settings' => array(
				'layout' => array( 'contentSize' => $content, 'wideSize' => $wide ),
			),
		);
	}

	/**
	 * Undo: restore every previous option and re-write the layout variation.
	 *
	 * @since 1.1.0
	 *
	 * @param bool  $result Default false.
	 * @param array $entry  Journal entry.
	 * @return bool
	 */
	public static function undo_layout( $result, $entry ) {
		$previous = isset( $entry['payload']['previous'] ) ? (array) $entry['payload']['previous'] : array();

		foreach ( $previous as $key => $value ) {
			update_option( $key, $value, false );
		}

		if ( isset( $previous['arena_container'] ) || isset( $previous['arena_container_width'] ) ) {
			$width = isset( $previous['arena_container_width'] ) && '' !== $previous['arena_container_width']
				? (float) $previous['arena_container_width']
				: null;
			$mode  = isset( $previous['arena_container'] ) ? $previous['arena_container'] : 'boxed';

			Variations::write( 'layout.json', self::layout_variation( $mode, $width ) );
		}

		return true;
	}

	/* ------------------------------------------------ Per-page meta box */

	/**
	 * The meta keys the box manages (H32).
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	public static function meta_schema() {
		return array(
			'_arena_hide_title'        => array( 'label' => __( 'Hide page title', 'arena-engine' ), 'type' => 'bool' ),
			'_arena_transparent_header' => array( 'label' => __( 'Transparent header over hero', 'arena-engine' ), 'type' => 'bool' ),
			'_arena_hide_footer'       => array( 'label' => __( 'Hide footer', 'arena-engine' ), 'type' => 'bool' ),
			'_arena_hide_sidebar'      => array( 'label' => __( 'Hide sidebar', 'arena-engine' ), 'type' => 'bool' ),
			'_arena_container'         => array( 'label' => __( 'Container', 'arena-engine' ), 'type' => 'enum', 'values' => array( 'boxed', 'fullwidth', 'narrow' ) ),
			'_arena_container_width'   => array( 'label' => __( 'Container width (rem)', 'arena-engine' ), 'type' => 'text' ),
			'_arena_typo_preset'       => array( 'label' => __( 'Typographic preset override', 'arena-engine' ), 'type' => 'text' ),
			'_arena_header_variant'    => array( 'label' => __( 'Header variant', 'arena-engine' ), 'type' => 'enum', 'values' => array( 'standard', 'transparent', 'sticky' ) ),
		);
	}

	/**
	 * GET /meta/<id> — the current overrides for one post.
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function get_meta( $request ) {
		$post_id = (int) $request['id'];
		$post    = get_post( $post_id );

		if ( ! $post ) {
			return new \WP_Error( 'arena_meta_post', __( 'Post not found.', 'arena-engine' ), array( 'status' => 404 ) );
		}

		$values = array();

		foreach ( self::meta_schema() as $key => $schema ) {
			$values[ $key ] = (string) get_post_meta( $post_id, $key, true );
		}

		return rest_ensure_response( array( 'id' => $post_id, 'meta' => $values ) );
	}

	/**
	 * POST /meta/<id> — save or reset the overrides (H32).
	 *
	 * Body: { values: {…}, reset: false }.
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function save_meta( $request ) {
		$post_id = (int) $request['id'];
		$post    = get_post( $post_id );

		if ( ! $post || ! current_user_can( 'edit_post', $post_id ) ) {
			return new \WP_Error( 'arena_meta_post', __( 'Post not editable.', 'arena-engine' ), array( 'status' => 403 ) );
		}

		$reset  = ! empty( $request->get_param( 'reset' ) );
		$values = (array) $request->get_param( 'values' );
		$previous = array();

		foreach ( self::meta_schema() as $key => $schema ) {
			$previous[ $key ] = (string) get_post_meta( $post_id, $key, true );

			if ( $reset ) {
				delete_post_meta( $post_id, $key );
				continue;
			}

			if ( ! array_key_exists( $key, $values ) ) {
				continue;
			}

			$value = $values[ $key ];

			if ( 'bool' === $schema['type'] ) {
				$value = ! empty( $value ) ? '1' : '';
			} elseif ( 'enum' === $schema['type'] ) {
				$value = in_array( $value, $schema['values'], true ) ? (string) $value : '';
			} else {
				$value = sanitize_text_field( (string) $value );
			}

			if ( '' === $value ) {
				delete_post_meta( $post_id, $key );
			} else {
				update_post_meta( $post_id, $key, $value );
			}
		}

		$journal_id = Journal::record(
			'meta.save',
			sprintf( /* translators: %s: post title. */ __( 'Per-page overrides: %s', 'arena-engine' ), $post->post_title ),
			array(
				'post_id'  => $post_id,
				'previous' => $previous,
				'reset'    => $reset,
			),
			'meta-per-pagina'
		);

		return rest_ensure_response( array( 'saved' => true, 'journal' => $journal_id, 'reset' => $reset ) );
	}

	/* ------------------------------------------------------------ Journal */

	/**
	 * GET /actions — the journal.
	 *
	 * @since 1.1.0
	 *
	 * @return \WP_REST_Response
	 */
	public static function get_actions() {
		return rest_ensure_response( Journal::all() );
	}

	/**
	 * POST /actions/<id>/undo.
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function undo_action( $request ) {
		$result = Journal::undo( (string) $request['id'] );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return rest_ensure_response( array( 'undone' => (string) $request['id'] ) );
	}
}
