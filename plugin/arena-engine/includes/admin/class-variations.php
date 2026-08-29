<?php
/**
 * Tracked theme.json variation store (H25/H31/H40).
 *
 * Panel actions never scatter options for design decisions: they write real
 * theme.json variation files into uploads/arena/ (typography.json,
 * preset.json, layout.json) which are merged into the active theme's Global
 * Styles through the `wp_theme_json_data_user` filter. Every write is
 * journaled with the previous file content, so undo is byte-exact.
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Variation file store + merge.
 *
 * @since 1.1.0
 */
final class Variations {

	/**
	 * Variation files managed by the panel, in merge order.
	 *
	 * @since 1.1.0
	 *
	 * @var string[]
	 */
	const FILES = array( 'preset.json', 'typography.json', 'layout.json' );

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_filter( 'wp_theme_json_data_user', array( __CLASS__, 'merge' ) );
	}

	/**
	 * Absolute path of one variation file.
	 *
	 * @since 1.1.0
	 *
	 * @param string $file File name (preset.json|typography.json|layout.json).
	 * @return string
	 */
	public static function path( $file ) {
		$uploads = wp_upload_dir();

		return trailingslashit( $uploads['basedir'] ) . 'arena/' . $file;
	}

	/**
	 * Reads one variation file as an array.
	 *
	 * @since 1.1.0
	 *
	 * @param string $file File name.
	 * @return array
	 */
	public static function read( $file ) {
		$path = self::path( $file );

		if ( ! is_readable( $path ) ) {
			return array();
		}

		$decoded = json_decode( (string) file_get_contents( $path ), true );

		return is_array( $decoded ) ? $decoded : array();
	}

	/**
	 * Writes one variation file and returns the previous content for the
	 * journal payload.
	 *
	 * @since 1.1.0
	 *
	 * @param string $file    File name.
	 * @param array  $config  theme.json-shaped config.
	 * @return string[] {status, previous}
	 */
	public static function write( $file, $config ) {
		$path     = self::path( $file );
		$previous = is_readable( $path ) ? (string) file_get_contents( $path ) : '';
		$dir      = dirname( $path );

		if ( ! is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
		}

		$json = wp_json_encode( $config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- uploads path, validated above.
		file_put_contents( $path, $json . "\n" );

		return array(
			'status'   => 'written',
			'previous' => $previous,
			'path'     => $path,
		);
	}

	/**
	 * Restores a variation file's previous content (undo).
	 *
	 * @since 1.1.0
	 *
	 * @param string $file     File name.
	 * @param string $content  Previous content ('' removes the file).
	 * @return bool
	 */
	public static function restore( $file, $content ) {
		$path = self::path( $file );

		if ( '' === $content ) {
			return ! is_readable( $path ) || wp_delete_file( $path ) || ! is_readable( $path );
		}

		$dir = dirname( $path );

		if ( ! is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- uploads path.
		file_put_contents( $path, $content );

		return true;
	}

	/**
	 * Merges the panel variation files into the user Global Styles layer.
	 *
	 * @since 1.1.0
	 *
	 * @param \WP_Theme_JSON_Data $data User-origin theme JSON data.
	 * @return \WP_Theme_JSON_Data
	 */
	public static function merge( $data ) {
		foreach ( self::FILES as $file ) {
			$config = self::read( $file );

			if ( ! empty( $config ) && method_exists( $data, 'update_with' ) ) {
				$data->update_with( $config );
			}
		}

		return $data;
	}
}
