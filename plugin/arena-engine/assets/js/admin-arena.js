/**
 * Arena Engine — admin panel JS (H20/H25/H31/H32/G12).
 *
 * Progressive enhancement over the server-rendered pages: talks to the
 * versioned arena/v1 REST endpoints with fetch, renders the kit import
 * progress bar step by step, and wires the undo buttons. No jQuery.
 */
( function () {
	'use strict';

	var cfg = window.arenaAdmin || {};
	var REST = cfg.rest || '/wp-json/arena/v1/';
	var NONCE = cfg.nonce || '';
	var i18n = cfg.i18n || {};

	function t( key, fallback ) {
		return i18n[ key ] || fallback;
	}

	function api( path, options ) {
		options = options || {};
		options.headers = Object.assign(
			{ 'X-WP-Nonce': NONCE, 'Content-Type': 'application/json' },
			options.headers || {}
		);
		options.credentials = 'same-origin';

		return fetch( REST + path, options ).then( function ( response ) {
			if ( ! response.ok ) {
				return response.json().then( function ( data ) {
					throw new Error( data.message || 'HTTP ' + response.status );
				} );
			}

			return response.json();
		} );
	}

	function setStatus( node, text ) {
		if ( ! node ) {
			return;
		}

		node.textContent = text;
	}

	/* ------------------------------------------------------ Kits (H20) */
	function initKits() {
		document.querySelectorAll( '[data-arena-kits]' ).forEach( function ( grid ) {
			grid.querySelectorAll( '[data-arena-kit]' ).forEach( function ( card ) {
				var slug = card.getAttribute( 'data-arena-kit' );
				var pages;
				try {
					pages = JSON.parse( card.getAttribute( 'data-pages' ) || '[]' );
				} catch {
					pages = [];
				}

				var progress = card.querySelector( '.arena-kit-progress' );
				var fill = card.querySelector( '.arena-kit-progress__fill' );
				var label = card.querySelector( '.arena-kit-progress__label' );
				var localeSelect = card.querySelector( '[data-arena-kit-locale]' );

				function locale() {
					return localeSelect ? localeSelect.value : 'en_US';
				}

				function runImport( payload, steps ) {
					progress.hidden = false;

					var done = 0;

					function next() {
						setStatus( label, t( 'importing', 'Importing…' ) + ' (' + ( done + 1 ) + '/' + steps + ')' );

						return api( 'kits/' + slug + '/import', {
							method: 'POST',
							body: JSON.stringify( payload )
						} ).then( function () {
							done++;
							fill.style.inlineSize = Math.round( ( done / steps ) * 100 ) + '%';

							if ( done < steps ) {
								return next();
							}

							setStatus( label, t( 'imported', 'Imported' ) + ' ✓' );
							window.setTimeout( function () {
								window.location.reload();
							}, 900 );
						} );
					}

					return next().catch( function ( error ) {
						setStatus( label, error.message );
					} );
				}

				card.querySelector( '[data-arena-kit-import]' ).addEventListener( 'click', function () {
					var payload = {
						scope: 'full',
						locale: locale(),
						confirm_overwrite: window.confirm( t( 'confirm', 'Overwrite existing content?' ) )
					};

					/* Step-by-step import: one request per page keeps every hop short and
					   gives the progress bar real steps (H20). */
					var steps = 1 + pages.length;
					runImport( payload, 1 );

					/* Full import first (menu + products), then each page. */
					var done = Promise.resolve();
					pages.forEach( function ( page ) {
						done = done.then( function () {
							return api( 'kits/' + slug + '/import', {
								method: 'POST',
								body: JSON.stringify( { scope: 'page', page: page, locale: locale(), confirm_overwrite: payload.confirm_overwrite } )
							} );
						} );
					} );
				} );

				var pageToggle = card.querySelector( '[data-arena-kit-page-import]' );

				if ( pageToggle ) {
					pageToggle.addEventListener( 'click', function () {
						var list = card.querySelector( '.arena-kit-pages' );
						list.hidden = ! list.hidden;
						pageToggle.setAttribute( 'aria-expanded', list.hidden ? 'false' : 'true' );
					} );
				}

				card.querySelectorAll( '[data-arena-kit-page]' ).forEach( function ( button ) {
					button.addEventListener( 'click', function () {
						runImport( { scope: 'page', page: button.getAttribute( 'data-arena-kit-page' ), locale: locale(), confirm_overwrite: window.confirm( t( 'confirm', 'Overwrite existing content?' ) ) }, 1 );
					} );
				} );

				var sync = card.querySelector( '[data-arena-kit-sync]' );

				if ( sync ) {
					sync.addEventListener( 'click', function () {
						setStatus( label, 'Syncing…' );
						api( 'kits/' + slug + '/sync', { method: 'POST' } )
							.then( function () {
								window.location.reload();
							} )
							.catch( function ( error ) {
								setStatus( label, error.message );
							} );
					} );
				}

				var undo = card.querySelector( '[data-arena-kit-undo]' );

				if ( undo ) {
					undo.addEventListener( 'click', function () {
						setStatus( label, t( 'undoing', 'Undoing…' ) );
						api( 'actions/' + undo.getAttribute( 'data-journal' ) + '/undo', { method: 'POST' } )
							.then( function () {
								setStatus( label, t( 'undone', 'Undone' ) + ' ✓' );
								window.setTimeout( function () {
									window.location.reload();
								}, 700 );
							} )
							.catch( function ( error ) {
								setStatus( label, error.message );
							} );
					} );
				}
			} );
		} );
	}

	/* ---------------------------------------------------- Presets (H40) */
	function initPresets() {
		document.querySelectorAll( '[data-arena-apply-preset]' ).forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				button.disabled = true;
				button.textContent = '…';

				api( 'presets', {
					method: 'POST',
					body: JSON.stringify( { slug: button.getAttribute( 'data-arena-apply-preset' ) } )
				} ).then( function () {
					window.location.reload();
				} ).catch( function () {
					button.disabled = false;
					button.textContent = 'Retry';
				} );
			} );
		} );
	}

	/* ------------------------------------------------ Typography (H25) */
	function initTypography() {
		var form = document.querySelector( '[data-arena-panel="typography"]' );

		if ( ! form ) {
			return;
		}

		form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();

			var status = form.querySelector( '.arena-status' );
			setStatus( status, t( 'saving', 'Saving…' ) );

			var levels = {};
			var data = new FormData( form );

			data.forEach( function ( value, name ) {
				var match = name.match( /^arena-typo\[(.+?)\]\[(.+?)\]$/ );

				if ( match ) {
					levels[ match[ 1 ] ] = levels[ match[ 1 ] ] || {};
					levels[ match[ 1 ] ][ match[ 2 ] ] = String( value );
				}
			} );

			api( 'typography', {
				method: 'POST',
				body: JSON.stringify( {
					levels: levels,
					scale: {
						mobile: parseFloat( form.querySelector( '[name="arena-typo-scale-mobile"]' ).value ) || 1,
						desktop: parseFloat( form.querySelector( '[name="arena-typo-scale-desktop"]' ).value ) || 1
					}
				} )
			} ).then( function () {
				setStatus( status, t( 'saved', 'Saved' ) + ' ✓ — ' + 'undo available in the Journal' );
			} ).catch( function ( error ) {
				setStatus( status, error.message );
			} );
		} );
	}

	/* ---------------------------------------------------- Layout (H31) */
	function initLayout() {
		var form = document.querySelector( '[data-arena-panel="layout"]' );

		if ( ! form ) {
			return;
		}

		form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();

			var status = form.querySelector( '.arena-status' );
			setStatus( status, t( 'saving', 'Saving…' ) );

			var data = new FormData( form );
			var payload = {};

			data.forEach( function ( value, name ) {
				var match = name.match( /^arena-layout\[(.+?)\]$/ );

				if ( ! match ) {
					return;
				}

				var key = match[ 1 ];

				if ( key === 'arena_post_meta' || key === 'arena_product_tabs_order' ) {
					payload[ key ] = String( value ).split( ',' ).map( function ( part ) {
						return part.trim();
					} ).filter( Boolean );
				} else if ( key === 'arena_catalog_mode' ) {
					payload[ key ] = '1' === String( value );
				} else if ( key === 'arena_mobile_breakpoint' ) {
					payload[ key ] = parseInt( value, 10 );
				} else {
					payload[ key ] = String( value );
				}
			} );

			api( 'layout', { method: 'POST', body: JSON.stringify( payload ) } )
				.then( function () {
					setStatus( status, t( 'saved', 'Saved' ) + ' ✓' );
				} )
				.catch( function ( error ) {
					setStatus( status, error.message );
				} );
		} );
	}

	/* --------------------------------------------------- Meta box (H32) */
	function initMetaBox() {
		document.querySelectorAll( '[data-arena-meta-box]' ).forEach( function ( box ) {
			var postId = box.getAttribute( 'data-post-id' );
			var status = box.querySelector( '[data-arena-meta-status]' );

			function collect() {
				var values = {};

				box.querySelectorAll( 'input, select' ).forEach( function ( field ) {
					if ( field.type === 'checkbox' ) {
						values[ field.name ] = field.checked ? '1' : '';
					} else {
						values[ field.name ] = field.value;
					}
				} );

				return values;
			}

			function send( body ) {
				return api( 'meta/' + postId, { method: 'POST', body: JSON.stringify( body ) } )
					.then( function () {
						setStatus( status, t( 'saved', 'Saved' ) + ' ✓ — undo in Arena → Journal' );
					} )
					.catch( function ( error ) {
						setStatus( status, error.message );
					} );
			}

			box.querySelector( '[data-arena-meta-save]' ).addEventListener( 'click', function () {
				send( { values: collect() } );
			} );

			box.querySelector( '[data-arena-meta-reset]' ).addEventListener( 'click', function () {
				send( { reset: true } ).then( function () {
					box.querySelectorAll( 'input[type="checkbox"]' ).forEach( function ( field ) {
						field.checked = false;
					} );
					box.querySelectorAll( 'select' ).forEach( function ( field ) {
						field.selectedIndex = 0;
					} );
					box.querySelectorAll( 'input[type="text"]' ).forEach( function ( field ) {
						field.value = '';
					} );
				} );
			} );
		} );
	}

	/* ------------------------------------------------ Journal undo (G12) */
	function initUndo() {
		document.querySelectorAll( '[data-arena-undo]' ).forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				button.disabled = true;
				button.textContent = t( 'undoing', 'Undoing…' );

				api( 'actions/' + button.getAttribute( 'data-arena-undo' ) + '/undo', { method: 'POST' } )
					.then( function () {
						window.location.reload();
					} )
					.catch( function ( error ) {
						button.disabled = false;
						button.textContent = error.message;
					} );
			} );
		} );
	}

	function boot() {
		initKits();
		initPresets();
		initTypography();
		initLayout();
		initMetaBox();
		initUndo();
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', boot, { once: true } );
	} else {
		boot();
	}
}() );
