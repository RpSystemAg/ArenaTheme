/**
 * Arena Commerce — my-account dashboard module (H36).
 * The dashboard cards are real server-rendered sections; this module only
 * makes their order a per-visitor preference (drag handle + keyboard move),
 * persisted in localStorage. Nothing is written to the database.
 */
( function () {
	'use strict';

	var PREF_KEY = 'arena-account-order';

	function loadOrder() {
		try {
			return JSON.parse( window.localStorage.getItem( PREF_KEY ) || '[]' );
		} catch {
			return [];
		}
	}

	function saveOrder( ids ) {
		try {
			window.localStorage.setItem( PREF_KEY, JSON.stringify( ids ) );
		} catch {
			/* Private mode: order lasts for the page view only. */
		}
	}

	function applyOrder( grid ) {
		var order = loadOrder();

		if ( ! order.length ) {
			return;
		}

		order.forEach( function ( id ) {
			var card = grid.querySelector( '[data-arena-account-card="' + id + '"]' );

			if ( card ) {
				grid.appendChild( card );
			}
		} );
	}

	function init() {
		var grid = document.querySelector( '[data-arena-account-cards]' );

		if ( ! grid ) {
			return;
		}

		applyOrder( grid );

		function commit() {
			saveOrder( Array.prototype.map.call(
				grid.querySelectorAll( '[data-arena-account-card]' ),
				function ( card ) {
					return card.getAttribute( 'data-arena-account-card' );
				}
			) );
		}

		/* Keyboard reorder: the drag handle moves the card up/down with the
		   arrow keys, so the feature never depends on pointer dragging. */
		grid.addEventListener( 'keydown', function ( event ) {
			var handle = event.target.closest ? event.target.closest( '[data-arena-card-move]' ) : null;

			if ( ! handle ) {
				return;
			}

			var card = handle.closest( '[data-arena-account-card]' );
			var before = null;

			if ( event.key === 'ArrowUp' ) {
				before = card.previousElementSibling;
				card.parentNode.insertBefore( card, before );
			} else if ( event.key === 'ArrowDown' ) {
				before = card.nextElementSibling ? card.nextElementSibling.nextElementSibling : null;
				card.parentNode.insertBefore( card, before );
			} else {
				return;
			}

			event.preventDefault();
			commit();
			handle.focus();
		} );

		/* Pointer reorder with the HTML5 drag API (no library). */
		grid.querySelectorAll( '[data-arena-account-card]' ).forEach( function ( card ) {
			card.setAttribute( 'draggable', 'true' );

			card.addEventListener( 'dragstart', function ( event ) {
				event.dataTransfer.setData( 'text/plain', card.getAttribute( 'data-arena-account-card' ) );
				card.classList.add( 'is-dragging' );
			} );

			card.addEventListener( 'dragend', function () {
				card.classList.remove( 'is-dragging' );
				commit();
			} );
		} );

		grid.addEventListener( 'dragover', function ( event ) {
			event.preventDefault();
		} );

		grid.addEventListener( 'drop', function ( event ) {
			event.preventDefault();

			var id = event.dataTransfer.getData( 'text/plain' );
			var dragged = grid.querySelector( '[data-arena-account-card="' + id + '"]' );
			var target = event.target.closest ? event.target.closest( '[data-arena-account-card]' ) : null;

			if ( dragged && target && dragged !== target ) {
				grid.insertBefore( dragged, target );
				commit();
			}
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init, { once: true } );
	} else {
		init();
	}
}() );
