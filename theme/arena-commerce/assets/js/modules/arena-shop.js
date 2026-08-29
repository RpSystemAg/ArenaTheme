/**
 * Arena Commerce — shop browsing module (H34).
 * Load-more / infinite scroll (visitor's preference, stored in localStorage —
 * never in the database), the off-canvas filter drawer and the active-filter
 * chips. Progressive enhancement: the URL-based filter blocks keep working.
 */
( function () {
	'use strict';

	var PREF_KEY = 'arena-shop-pagination';

	var cfg = window.Arena || {};
	var config = cfg.config || {};
	var i18n = ( config.shop && config.shop.i18n ) || {};

	function t( key, fallback ) {
		return i18n[ key ] || fallback;
	}

	/* -------------------------------------------- Pagination preference */
	function preference() {
		try {
			return window.localStorage.getItem( PREF_KEY ) || 'load-more';
		} catch {
			return 'load-more';
		}
	}

	function setPreference( value ) {
		try {
			window.localStorage.setItem( PREF_KEY, value );
		} catch {
			/* Private mode: preference lasts for the page view only. */
		}
	}

	function initPagination() {
		var loadMore = document.querySelector( '[data-arena-load-more]' );

		if ( ! loadMore ) {
			return;
		}

		var url = loadMore.getAttribute( 'data-arena-load-more' );

		function load( replace ) {
			loadMore.disabled = true;
			loadMore.textContent = t( 'loading', 'Loading…' );

			fetch( url, { credentials: 'same-origin' } )
				.then( function ( response ) { return response.text(); } )
				.then( function ( html ) {
					var doc = new DOMParser().parseFromString( html, 'text/html' );
					var incoming = doc.querySelector( '.arena-loop-products' );
					var here = document.querySelector( '.arena-loop-products' );

					if ( incoming && here ) {
						if ( replace ) {
							here.innerHTML = incoming.innerHTML;
						} else {
							here.insertAdjacentHTML( 'beforeend', incoming.innerHTML );
						}
					}

					var next = doc.querySelector( '[data-arena-load-more]' );

					if ( next && next.getAttribute( 'data-arena-load-more' ) ) {
						url = next.getAttribute( 'data-arena-load-more' );
						loadMore.disabled = false;
						loadMore.textContent = t( 'loadMore', 'Load more' );
					} else {
						loadMore.remove();
					}
				} )
				.catch( function () {
					window.location.href = url; /* Full reload fallback. */
				} );
		}

		if ( preference() === 'infinite' ) {
			var observer = new IntersectionObserver( function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting && ! loadMore.disabled ) {
						load( false );
					}
				} );
			} );

			observer.observe( loadMore );
		}

		loadMore.addEventListener( 'click', function () {
			load( false );
		} );

		/* Preference switcher rendered next to the grid. */
		var switcher = document.querySelector( '[data-arena-pagination-pref]' );

		if ( switcher ) {
			switcher.value = preference();

			switcher.addEventListener( 'change', function () {
				setPreference( switcher.value );

				if ( switcher.value === 'infinite' ) {
					window.location.reload(); /* Activate the observer once. */
				}
			} );
		}
	}

	/* ------------------------------------------------- Off-canvas filters */
	function initFilters() {
		var drawer = document.getElementById( 'arena-filters' );

		if ( ! drawer ) {
			return;
		}

		document.addEventListener( 'click', function ( event ) {
			var open = event.target.closest ? event.target.closest( '[data-arena-filters-open]' ) : null;
			var close = event.target.closest ? event.target.closest( '[data-arena-filters-close]' ) : null;

			if ( open ) {
				event.preventDefault();

				if ( cfg.dialog ) {
					cfg.dialog.open( drawer );
				} else {
					drawer.hidden = false;
				}
			}

			if ( close ) {
				event.preventDefault();

				if ( cfg.dialog ) {
					cfg.dialog.close( drawer );
				} else {
					drawer.hidden = true;
				}
			}
		} );
	}

	/* ------------------------------------------------------ Active chips */
	function initChips() {
		var chips = document.querySelector( '.arena-filter-chips' );

		if ( ! chips ) {
			return;
		}

		chips.addEventListener( 'click', function ( event ) {
			var chip = event.target.closest ? event.target.closest( '[data-arena-chip-remove]' ) : null;

			if ( ! chip ) {
				return;
			}

			var params = new URLSearchParams( window.location.search );
			var key = chip.getAttribute( 'data-arena-chip-remove' );

			params.delete( key );
			params.delete( 'query-type-' + key.replace( /filter\[|\]/g, '' ) );
			window.location.search = params.toString();
		} );
	}

	function boot() {
		initPagination();
		initFilters();
		initChips();
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', boot, { once: true } );
	} else {
		boot();
	}
}() );
