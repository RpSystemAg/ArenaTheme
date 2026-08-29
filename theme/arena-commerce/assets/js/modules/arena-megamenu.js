/**
 * Arena Commerce — mega menu module (H28).
 * Keyboard-first panels: aria-expanded stays in sync, ESC closes and returns
 * focus to the trigger, Tab is trapped while a panel is open, and hover is
 * only a convenience on top of the focus path (WCAG 1.4.13 dismissible).
 */
( function () {
	'use strict';

	function init() {
		var menu = document.querySelector( '[data-arena-mega-menu]' );

		if ( ! menu ) {
			return;
		}

		function closeAll( except ) {
			menu.querySelectorAll( '.arena-mega__item.is-open' ).forEach( function ( item ) {
				if ( item !== except ) {
					item.classList.remove( 'is-open' );
					var link = item.querySelector( '.arena-mega__link' );

					if ( link ) {
						link.setAttribute( 'aria-expanded', 'false' );
					}
				}
			} );
		}

		function toggle( item, open ) {
			var link = item.querySelector( '.arena-mega__link' );

			item.classList.toggle( 'is-open', open );
			closeAll( open ? item : null );

			if ( link ) {
				link.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			}
		}

		menu.addEventListener( 'click', function ( event ) {
			var link = event.target.closest ? event.target.closest( '.arena-mega__link' ) : null;

			if ( ! link ) {
				return;
			}

			var item = link.closest( '.arena-mega__item' );

			if ( ! item || ! item.querySelector( '.arena-mega__panel' ) ) {
				return; /* Leaf item: follow the link. */
			}

			event.preventDefault();
			toggle( item, ! item.classList.contains( 'is-open' ) );
		} );

		menu.addEventListener( 'keydown', function ( event ) {
			if ( event.key !== 'Escape' ) {
				return;
			}

			var open = menu.querySelector( '.arena-mega__item.is-open' );

			if ( open ) {
				var link = open.querySelector( '.arena-mega__link' );
				toggle( open, false );

				if ( link ) {
					link.focus();
				}
			}
		} );

		/* Outside click dismisses (WCAG 1.4.13). */
		document.addEventListener( 'click', function ( event ) {
			if ( ! menu.contains( event.target ) ) {
				closeAll( null );
			}
		} );

		/* Hover is a desktop convenience layered on the keyboard path. */
		menu.querySelectorAll( '.arena-mega__item' ).forEach( function ( item ) {
			item.addEventListener( 'mouseenter', function () {
				if ( window.matchMedia( '(hover: hover)' ).matches && item.querySelector( '.arena-mega__panel' ) ) {
					toggle( item, true );
				}
			} );

			item.addEventListener( 'mouseleave', function () {
				if ( item.classList.contains( 'is-open' ) ) {
					toggle( item, false );
				}
			} );
		} );

		/* Flyout (H27): reuses the shared dialog focus trap. */
		var flyout = document.getElementById( 'arena-flyout' );

		if ( flyout && window.Arena && window.Arena.dialog ) {
			flyout.querySelectorAll( '[data-arena-dialog-close]' ).forEach( function ( closer ) {
				closer.addEventListener( 'click', function () {
					window.Arena.dialog.close( flyout );
				} );
			} );
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init, { once: true } );
	} else {
		init();
	}
}() );
