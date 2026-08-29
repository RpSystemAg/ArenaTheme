/**
 * Arena Commerce — live search module (H29).
 * Debounced (250ms) live results for the header search slot. Products are
 * searched through the WooCommerce Store API when present, posts through the
 * core search REST route. Without JS the native search form still submits.
 */
( function () {
	'use strict';

	var DEBOUNCE = 250;
	var MIN_QUERY = 2;

	var cfg = window.Arena || {};
	var config = cfg.config || {};
	var search = config.search || {};
	var i18n = search.i18n || {};

	function t( key, fallback ) {
		return i18n[ key ] || fallback;
	}

	function init() {
		var forms = document.querySelectorAll( '.arena-search .wp-block-search__inside-wrapper, form.arena-search' );

		forms.forEach( function ( wrap ) {
			var input = wrap.querySelector( 'input[type="search"], input.wp-block-search__input' );

			if ( ! input ) {
				return;
			}

			var list = document.createElement( 'ul' );
			list.className = 'arena-search-results';
			list.setAttribute( 'role', 'listbox' );
			list.hidden = true;
			wrap.appendChild( list );

			var timer = null;
			var controller = null;

			function close() {
				list.hidden = true;
			}

			function render( items ) {
				if ( ! items.length ) {
					list.innerHTML = '<li class="arena-search-results__status">' + t( 'noResults', 'No results.' ) + '</li>';
				} else {
					list.innerHTML = items.map( function ( item ) {
						return '<li class="arena-search-results__item" role="option">' +
							'<a class="arena-search-results__link" href="' + item.url + '">' +
							'<span class="arena-search-results__title">' + item.title + '</span>' +
							'<span class="arena-search-results__meta">' + ( item.meta || '' ) + '</span>' +
							'<span class="arena-search-results__price">' + ( item.price || '' ) + '</span>' +
							'</a></li>';
					} ).join( '' );
				}

				list.hidden = false;
			}

			function run() {
				var query = input.value.trim();

				if ( query.length < MIN_QUERY ) {
					close();
					return;
				}

				if ( controller ) {
					controller.abort();
				}

				controller = new AbortController();
				var url = search.products && search.endpoint
					? search.endpoint + '/products?per_page=6&search=' + encodeURIComponent( query )
					: '/wp-json/wp/v2/search?per_page=6&type=post&_embed=0&search=' + encodeURIComponent( query );

				fetch( url, { credentials: 'same-origin', signal: controller.signal } )
					.then( function ( response ) { return response.json(); } )
					.then( function ( data ) {
						if ( search.products ) {
							render( data.map( function ( product ) {
								return {
									title: product.name || '',
									url: product.permalink || '#',
									price: product.prices ? product.prices.price / 100 : '',
									meta: ''
								};
							} ) );
						} else {
							render( data.map( function ( item ) {
								return { title: item.title || '', url: item.url || '#', meta: '', price: '' };
							} ) );
						}
					} )
					.catch( function () {} );
			}

			input.addEventListener( 'input', function () {
				clearTimeout( timer );
				timer = setTimeout( run, DEBOUNCE );
			} );

			input.setAttribute( 'aria-expanded', 'false' );

			document.addEventListener( 'click', function ( event ) {
				if ( ! wrap.contains( event.target ) ) {
					close();
				}
			} );

			document.addEventListener( 'keydown', function ( event ) {
				if ( event.key === 'Escape' ) {
					close();
				}
			} );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init, { once: true } );
	} else {
		init();
	}
}() );
