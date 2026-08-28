<?php
/**
 * Static contract test for the Arena Engine Abilities API registration.
 *
 * This is a deliberately dependency-free CLI script so it can run in CI
 * without a database, WordPress core or a test bootstrap. It tokenises the
 * real registry source and asserts the invariants the Abilities API users
 * rely on:
 *
 *  1. The registry is hooked to `wp_abilities_api_init`.
 *  2. At least one ability is registered through `wp_register_ability()`.
 *  3. Every ability declares a JSON Schema output and the side-effect
 *     annotations (readonly / non-destructive / idempotent).
 *  4. The permission callback is orthogonal to the execution callback.
 *
 * Exit code 0 means PASS; any other exit code means the contract was
 * violated and the release must be blocked.
 *
 * @package Arena_QA
 * @since   1.0.0
 */

namespace Arena_QA;

/** Absolute path of the repository root. */
const REPO_ROOT = __DIR__ . '/../..';

/** Path of the Abilities registry under test. */
const REGISTRY_PATH = REPO_ROOT . '/plugin/arena-engine/includes/abilities/class-registry.php';

/**
 * Reads the registry source.
 *
 * @return string
 */
function registry_source() {
	$source = file_get_contents( REGISTRY_PATH );

	if ( false === $source ) {
		return '';
	}

	return $source;
}

/**
 * Extracts the bodies of every `wp_register_ability( ... )` call.
 *
 * @param string $source PHP source.
 * @return string[]
 */
function ability_bodies( $source ) {
	$tokens = token_get_all( $source );
	$calls  = array();
	$count  = count( $tokens );

	for ( $i = 0; $i < $count; $i++ ) {
		if ( ! is_array( $tokens[ $i ] ) || T_STRING !== $tokens[ $i ][0] || 'wp_register_ability' !== strtolower( $tokens[ $i ][1] ) ) {
			continue;
		}

		$cursor = $i + 1;
		while ( $cursor < $count ) {
			$token = $tokens[ $cursor ];
			if ( is_array( $token ) || '' === trim( $token ) ) {
				++$cursor;
				continue;
			}
			break;
		}

		if ( ! isset( $tokens[ $cursor ] ) || is_array( $tokens[ $cursor ] ) || '(' !== $tokens[ $cursor ] ) {
			continue;
		}

		$depth = 0;
		$end   = null;

		for ( $k = $cursor; $k < $count; ++$k ) {
			$token = $tokens[ $k ];
			if ( is_array( $token ) ) {
				continue;
			}

			if ( '(' === $token ) {
				++$depth;
			} elseif ( ')' === $token ) {
				--$depth;
				if ( 0 === $depth ) {
					$end = $k;
					break;
				}
			}
		}

		if ( null === $end ) {
			continue;
		}

		$body = '';
		for ( $j = $cursor + 1; $j < $end; ++$j ) {
			$body .= is_array( $tokens[ $j ] ) ? $tokens[ $j ][1] : $tokens[ $j ];
		}

		$calls[]  = $body;
		$i        = $end;
	}

	return $calls;
}

/**
 * Returns the single-quoted callback method registered for a key.
 *
 * @param string $body Ability registration body.
 * @param string $key  `execute_callback` or `permission_callback`.
 * @return string|null
 */
function callback_for( $body, $key ) {
	if ( preg_match(
		'#' . preg_quote( "'" . $key . "'", '#' ) . '\s*=>\s*array\s*\(\s*__CLASS__\s*,\s*\'([a-zA-Z0-9_]+)\'\s*\)#s',
		$body,
		$match
	) ) {
		return $match[1];
	}

	return null;
}

/**
 * Runs the contract and prints PASS/FAIL lines.
 *
 * @return int Exit code.
 */
function run() {
	$errors = array();

	$source = registry_source();
	if ( '' === $source || false === strpos( $source, 'wp_abilities_api_init' ) ) {
		$errors[] = 'FAIL: the registry must hook `wp_abilities_api_init`.';
	}

	$bodies = ability_bodies( $source );

	if ( 0 === count( $bodies ) ) {
		$errors[] = 'FAIL: no `wp_register_ability()` calls were found.';
	}

	foreach ( $bodies as $index => $body ) {
		$label = sprintf( 'ability #%d', $index + 1 );

		if ( ! preg_match( '/\'[a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-]+\'/s', $body, $name_match ) ) {
			$errors[] = "{$label}: missing or invalid ability ID.";
		} else {
			$label = $name_match[0];
		}

		$execute = callback_for( $body, 'execute_callback' );
		$permission = callback_for( $body, 'permission_callback' );

		if ( null === $execute ) {
			$errors[] = "{$label}: missing `execute_callback`.";
		}
		if ( null === $permission ) {
			$errors[] = "{$label}: missing `permission_callback`.";
		}

		if ( null !== $execute && null !== $permission && $execute === $permission ) {
			$errors[] = "{$label}: `permission_callback` must be orthogonal to `execute_callback`.";
		}

		if ( ! preg_match( '/[\'"]output_schema[\'"]\s*=>\s*array/s', $body ) ) {
			$errors[] = "{$label}: missing `output_schema` field.";
		}

		if ( ! preg_match( '/[\'"](type|properties)[\'"]\s*=>/s', $body ) ) {
			$errors[] = "{$label}: `output_schema` must declare schema fields (`type` and `properties`).";
		}

		if ( ! preg_match( '/[\'"]annotations[\'"]\s*=>\s*array/s', $body ) ) {
			$errors[] = "{$label}: missing `meta.annotations` block.";
		}

		if ( ! preg_match( '/[\'"]readonly[\'"]\s*=>\s*true/s', $body ) ) {
			$errors[] = "{$label}: `meta.annotations.readonly` must be `true`.";
		}

		if ( ! preg_match( '/[\'"]destructive[\'"]\s*=>\s*false/s', $body ) ) {
			$errors[] = "{$label}: `meta.annotations.destructive` must be `false`.";
		}

		if ( ! preg_match( '/[\'"]idempotent[\'"]\s*=>\s*true/s', $body ) ) {
			$errors[] = "{$label}: `meta.annotations.idempotent` must be `true`.";
		}
	}

	if ( count( $errors ) > 0 ) {
		foreach ( $errors as $error ) {
			fwrite( STDERR, "{$error}\n" );
		}
		fwrite( STDERR, sprintf( "FAIL: %d contract violation(s) across %d ability registration(s).\n", count( $errors ), count( $bodies ) ) );
		return 1;
	}

	echo sprintf( "PASS: Abilities API contract validated (%d ability registration(s)).\n", count( $bodies ) );
	return 0;
}

exit( run() );
