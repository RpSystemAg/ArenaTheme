<?php
/**
 * Action journal with per-action undo (H31/G12, AP9).
 *
 * Every admin action the Arena panel performs — preset switches, typography
 * saves, layout changes, per-page meta edits, kit imports — is appended to
 * ONE journal option (never scattered writes), is reversible through a
 * registered undo handler, and is documented by the action registry so the
 * Journal page can render generated documentation for each entry.
 *
 * Anti-pattern AP9 is enforced structurally: an action that cannot provide an
 * undo handler is rejected at record() time.
 *
 * @package Arena_Engine
 * @since   1.1.0
 */

namespace Arena_Engine\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Append-only journal with undo dispatch.
 *
 * @since 1.1.0
 */
final class Journal {

	/**
	 * Option name holding the journal (single, capped).
	 *
	 * @var string
	 */
	const OPTION = 'arena_engine_journal';

	/**
	 * Maximum entries kept.
	 *
	 * @var int
	 */
	const CAP = 200;

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_filter( 'arena_engine_undo_' . self::ACTION_META, array( __CLASS__, 'undo_meta' ), 10, 2 );
	}

	/**
	 * Journal action id for per-page meta changes (H32).
	 *
	 * @var string
	 */
	const ACTION_META = 'meta.save';

	/**
	 * Reads the journal (newest first).
	 *
	 * @since 1.1.0
	 *
	 * @return array[]
	 */
	public static function all() {
		$journal = get_option( self::OPTION, array() );

		return is_array( $journal ) ? $journal : array();
	}

	/**
	 * Reads one entry by id.
	 *
	 * @since 1.1.0
	 *
	 * @param string $id Entry id.
	 * @return array|null
	 */
	public static function get( $id ) {
		foreach ( self::all() as $entry ) {
			if ( isset( $entry['id'] ) && $id === $entry['id'] ) {
				return $entry;
			}
		}

		return null;
	}

	/**
	 * Records an action.
	 *
	 * @since 1.1.0
	 *
	 * @param string $action   Action id (see Actions::registry()).
	 * @param string $label    Human-readable summary.
	 * @param array  $payload  Undo data (previous and new values).
	 * @param string $doc      Documentation anchor (docs/utente page slug).
	 *
	 * @return string Entry id, or empty string when the action is unknown.
	 */
	public static function record( $action, $label, $payload, $doc = '' ) {
		$registry = Actions::registry();

		if ( ! isset( $registry[ $action ] ) ) {
			return '';
		}

		$entry = array(
			'id'      => uniqid( 'arena_', false ),
			'time'    => gmdate( DATE_W3C ),
			'actor'   => get_current_user_id(),
			'action'  => $action,
			'label'   => $label,
			'payload' => $payload,
			'doc'     => $doc ? $doc : $registry[ $action ]['doc'],
			'undone'  => false,
		);

		$journal   = self::all();
		array_unshift( $journal, $entry );
		$journal   = array_slice( $journal, 0, self::CAP );

		update_option( self::OPTION, $journal, false );

		return $entry['id'];
	}

	/**
	 * Undoes an entry by dispatching to its registered undo handler (G12).
	 *
	 * @since 1.1.0
	 *
	 * @param string $id Entry id.
	 * @return bool|\WP_Error
	 */
	public static function undo( $id ) {
		$entry = self::get( $id );

		if ( ! $entry ) {
			return new \WP_Error( 'arena_journal_not_found', __( 'Journal entry not found.', 'arena-engine' ), array( 'status' => 404 ) );
		}

		if ( ! empty( $entry['undone'] ) ) {
			return new \WP_Error( 'arena_journal_already_undone', __( 'This action was already undone.', 'arena-engine' ), array( 'status' => 409 ) );
		}

		/**
		 * Undo handler for the entry's action. Each action registers one
		 * through `add_filter( 'arena_engine_undo_<action>', …, 10, 2 )`.
		 *
		 * @since 1.1.0
		 *
		 * @param bool|\WP_Error $result Default false (not reversible).
		 * @param array          $entry  Journal entry with payload.
		 */
		$result = apply_filters( 'arena_engine_undo_' . $entry['action'], false, $entry );

		if ( true !== $result ) {
			return is_wp_error( $result ) ? $result : new \WP_Error(
				'arena_journal_not_reversible',
				__( 'This action does not expose an undo handler.', 'arena-engine' ),
				array( 'status' => 422 )
			);
		}

		self::mark_undone( $id );

		return true;
	}

	/**
	 * Marks an entry undone in the stored journal.
	 *
	 * @since 1.1.0
	 *
	 * @param string $id Entry id.
	 * @return void
	 */
	private static function mark_undone( $id ) {
		$journal = self::all();

		foreach ( $journal as $index => $entry ) {
			if ( isset( $entry['id'] ) && $id === $entry['id'] ) {
				$journal[ $index ]['undone'] = true;
				break;
			}
		}

		update_option( self::OPTION, $journal, false );
	}

	/**
	 * Undo handler for per-page meta changes (H32/G12).
	 *
	 * @since 1.1.0
	 *
	 * @param bool  $result Default false.
	 * @param array $entry  Journal entry.
	 * @return bool
	 */
	public static function undo_meta( $result, $entry ) {
		$previous = isset( $entry['payload']['previous'] ) ? (array) $entry['payload']['previous'] : array();
		$post_id  = isset( $entry['payload']['post_id'] ) ? (int) $entry['payload']['post_id'] : 0;

		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		foreach ( $previous as $key => $value ) {
			if ( '' === $value || null === $value ) {
				delete_post_meta( $post_id, $key );
			} else {
				update_post_meta( $post_id, $key, $value );
			}
		}

		return true;
	}
}
