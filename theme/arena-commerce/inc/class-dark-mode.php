<?php
/**
 * Dark mode (H47/H48).
 *
 * Real dark mode: the theme's palette custom properties are swapped under
 * [data-theme="dark"] (see assets/css/modules/arena-dark.css), every preset
 * ships an inverted twin, and both schemes pass WCAG 2.2 AA — verified
 * pair-by-pair by tests/g16-dark-a11y.test.mjs (AP13 bans filter: invert()).
 *
 * The scheme is pinned before first paint by a ~250 byte inline bootstrap
 * (localStorage → prefers-color-scheme default). The toggle lives in the
 * header actions and in the mobile flyout (reachable from the bottom nav),
 * flips data-theme on the root element and never reloads the page.
 *
 * @package Arena_Theme
 * @since   1.1.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Dark mode bootstrap and toggle.
 *
 * @since 1.1.0
 */
final class Dark_Mode {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'wp_head', array( __CLASS__, 'print_boot_script' ), 1 );
		add_filter( 'language_attributes', array( __CLASS__, 'root_attributes' ) );
	}

	/**
	 * Prints the pre-paint bootstrap that resolves the initial scheme.
	 *
	 * This is the only inline script the theme emits, it is ~250 bytes, it is
	 * removable through the `arena_theme_darkmode_inline` filter (strict-CSP
	 * hosts), and without it the CSS `prefers-color-scheme` fallback still
	 * renders the OS-preferred scheme — only the visitor's saved choice needs
	 * the script.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function print_boot_script() {
		/**
		 * Filter whether to print the pre-paint scheme bootstrap (H47).
		 *
		 * @since 1.1.0
		 *
		 * @param bool $print Default true; set false under a strict CSP.
		 */
		if ( ! apply_filters( 'arena_theme_darkmode_inline', true ) ) {
			return;
		}

		$script = '(function(){try{var s=localStorage.getItem("arena-theme");if(!s){s=matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light";}document.documentElement.setAttribute("data-theme",s);}catch(e){}})();';

		echo wp_get_inline_script_tag( $script ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static one-liner above.
	}

	/**
	 * Carries the active preset on the root element so the dark twins in
	 * arena-dark.css can target `[data-arena-preset="…"][data-theme="dark"]`.
	 *
	 * @since 1.1.0
	 *
	 * @param string $output Language attributes.
	 * @return string
	 */
	public static function root_attributes( $output ) {
		$preset = self::active_preset();

		if ( $preset && 'default' !== $preset ) {
			$output .= ' data-arena-preset="' . esc_attr( $preset ) . '"';
		}

		return $output;
	}

	/**
	 * The active global preset slug (H40), applied by the Arena panel as a
	 * tracked theme.json variation and mirrored on the root element.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public static function active_preset() {
		$preset = (string) get_option( 'arena_active_preset', 'default' );

		if ( ! preg_match( '/^[a-z]+$/', $preset ) ) {
			return 'default';
		}

		/**
		 * Filter the active preset slug (H40/H47).
		 *
		 * @since 1.1.0
		 *
		 * @param string $preset Preset slug.
		 */
		return apply_filters( 'arena_theme_active_preset', $preset );
	}

	/**
	 * Renders the header dark-mode toggle button (H47).
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public static function render_toggle() {
		return sprintf(
			'<button type="button" class="arena-theme-toggle" data-arena-theme-toggle aria-label="%s">%s%s</button>',
			esc_attr__( 'Toggle dark mode', 'arena-commerce' ),
			self::icon_moon(),
			self::icon_sun()
		);
	}

	/**
	 * Moon icon (light-scheme state).
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public static function icon_moon() {
		return '<svg class="arena-theme-toggle__icon-moon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 14.5A8.5 8.5 0 1 1 9.5 4a7 7 0 0 0 10.5 10.5z"/></svg>';
	}

	/**
	 * Sun icon (dark-scheme state).
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public static function icon_sun() {
		return '<svg class="arena-theme-toggle__icon-sun" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>';
	}
}
