/**
 * Arena Commerce — cart module (H29/H33/H34/H35).
 *
 * Everything here talks to the WooCommerce Store API (/wc/store/v1) with the
 * nonce printed in the runtime config and the Cart-Token returned by the first
 * cart response. No page ever reloads (AP10): add-to-cart, quantity updates,
 * removals and the mini-cart drawer are plain fetch() round-trips.
 *
 * Progressive enhancement contract: without this module (or before it boots)
 * every add-to-cart link and form still works — Woo's default, full-reload
 * flow takes over.
 */
( function () {
	'use strict';

	var cfg = window.Arena || {};
	var config = cfg.config || {};
	var cart = config.cart || {};
	var i18n = cart.i18n || {};

	var API = String( cart.endpoint || '/wp-json/wc/store/v1' );
	var NONCE = String( cart.nonce || '' );
	var CART_TOKEN_HEADER = 'Cart-Token';
	var token = null;

	var drawer = document.getElementById( 'arena-cart-drawer' );
	var panelCssLoaded = false;

	function t( key, fallback ) {
		return i18n[ key ] || fallback;
	}

	function announce( message ) {
		if ( cfg.announce ) {
			cfg.announce( message );
		}
	}

	/* --------------------------------------------------------- API client */
	function request( path, options ) {
		options = options || {};
		options.credentials = 'same-origin';
		options.headers = Object.assign(
			{
				'Content-Type': 'application/json',
				'X-WC-Store-API-Nonce': NONCE
			},
			options.headers || {}
		);

		if ( token ) {
			options.headers[ CART_TOKEN_HEADER ] = token;
		}

		return fetch( API + path, options ).then( function ( response ) {
			var fresh = response.headers.get( 'cart-token' );

			if ( fresh ) {
				token = fresh;
			}

			if ( ! response.ok ) {
				throw new Error( 'store-api ' + response.status );
			}

			return response.json();
		} );
	}

	/* Loads the drawer/quick-view/filters CSS the first time a panel opens. */
	function ensurePanelCss() {
		if ( panelCssLoaded || ! cart.cssUrl ) {
			return;
		}

		panelCssLoaded = true;
		var link = document.createElement( 'link' );
		link.rel = 'stylesheet';
		link.href = cart.cssUrl;
		document.head.appendChild( link );
	}

	/* ------------------------------------------------------ Drawer render */
	function money( value, currency ) {
		try {
			return new Intl.NumberFormat( document.documentElement.lang || 'en', {
				style: 'currency',
				currency: currency || 'USD'
			} ).format( value );
		} catch {
			return String( value );
		}
	}

	function itemRow( item ) {
		var media = item.images && item.images.length ? item.images[ 0 ].thumbnail || item.images[ 0 ].src : '';
		var img = media ? '<img src="' + media + '" alt="" loading="lazy" width="56" height="56">' : '';

		return '<div class="arena-cart-item" data-key="' + item.key + '" data-id="' + item.id + '" data-qty="' + item.quantity + '">' +
			'<div class="arena-cart-item__media">' + img + '</div>' +
			'<div>' +
			'<p class="arena-cart-item__name">' + ( item.name || '' ) + '</p>' +
			'<div class="arena-quantity" data-arena-stepper>' +
			'<button type="button" data-arena-qty="-1" aria-label="' + t( 'decrease', 'Decrease quantity' ) + '">−</button>' +
			'<input type="number" min="0" value="' + item.quantity + '" aria-label="' + t( 'quantity', 'Quantity' ) + '" readonly>' +
			'<button type="button" data-arena-qty="1" aria-label="' + t( 'increase', 'Increase quantity' ) + '">+</button>' +
			'</div>' +
			'</div>' +
			'<button type="button" class="arena-cart-item__remove" data-arena-remove aria-label="' + t( 'remove', 'Remove item' ) + '">✕</button>' +
			'</div>';
	}

	function render( data ) {
		if ( ! drawer ) {
			return;
		}

		var body = drawer.querySelector( '.arena-cart-drawer__body' );
		var footer = drawer.querySelector( '.arena-cart-drawer__footer' );

		if ( body ) {
			body.innerHTML = data.items && data.items.length
				? data.items.map( itemRow ).join( '' )
				: '<p>' + t( 'empty', 'Your cart is empty.' ) + '</p>';
		}

		if ( footer ) {
			footer.innerHTML = data.totals && data.items && data.items.length
				? '<p><strong>' + t( 'total', 'Total' ) + ':</strong> ' + money(
					parseFloat( data.totals.total_price || 0 ) / 100,
					data.totals.currency_code
				) + '</p>' +
				'<a class="wp-element-button" href="' + ( cart.checkoutUrl || '/checkout/' ) + '">' + t( 'checkout', 'Checkout' ) + '</a>'
				: '';
		}

		var count = data.items_count || ( data.items ? data.items.length : 0 );

		document.body.classList.toggle( 'arena-cart-has-items', count > 0 );

		document.querySelectorAll( '[data-arena-cart-count]' ).forEach( function ( node ) {
			node.textContent = String( count );
		} );

		drawer.dataset.itemsCount = String( count );
	}

	function refresh() {
		return request( '/cart', { method: 'GET' } ).then( render );
	}

	function openDrawer() {
		ensurePanelCss();

		if ( drawer && cfg.dialog ) {
			cfg.dialog.open( drawer );
		}
	}

	/* --------------------------------------------------------- Add to cart */
	function addToCart( id, quantity ) {
		return request( '/cart/add-item', {
			method: 'POST',
			body: JSON.stringify( { id: id, quantity: quantity } )
		} ).then( function ( data ) {
			render( data );
			announce( t( 'added', 'Added to your cart.' ) );
			openDrawer();
			return data;
		} );
	}

	/* Intercept loop add-to-cart links (class ajax_add_to_cart). */
	document.addEventListener( 'click', function ( event ) {
		var link = event.target.closest ? event.target.closest( 'a.ajax_add_to_cart, [data-arena-add-to-cart]' ) : null;

		if ( ! link ) {
			return;
		}

		var id = parseInt( link.getAttribute( 'data-product_id' ) || link.getAttribute( 'data-arena-add-to-cart' ), 10 );

		if ( ! id ) {
			return;
		}

		event.preventDefault();
		link.classList.add( 'is-loading' );

		addToCart( id, 1 ).catch( function () {
			/* Fall back to the default full flow if the API refuses. */
			window.location.href = link.href;
		} ).then( function () {
			link.classList.remove( 'is-loading' );
		} );
	} );

	/* Intercept the single-product add-to-cart form. */
	document.addEventListener( 'submit', function ( event ) {
		var form = event.target;

		if ( ! form.matches || ! form.matches( 'form.cart' ) ) {
			return;
		}

		var idField = form.querySelector( '[name="add-to-cart"]' );
		var id = idField ? parseInt( idField.value, 10 ) : 0;
		var qtyField = form.querySelector( '[name="quantity"]' );
		var quantity = qtyField ? parseInt( qtyField.value, 10 ) || 1 : 1;

		if ( ! id || form.querySelector( '[name="variation_id"]' ) ) {
			return; /* Variable products keep the server flow. */
		}

		event.preventDefault();
		addToCart( id, quantity ).catch( function () {
			form.submit();
		} );
	}, true );

	/* ------------------------------------------- Stepper, removal, undo */
	function updateItem( key, quantity ) {
		return request( '/cart/update-item', {
			method: 'POST',
			body: JSON.stringify( { key: key, quantity: quantity } )
		} ).then( render );
	}

	function removeWithUndo( row ) {
		var key = row.dataset.key;
		var id = parseInt( row.dataset.id, 10 );
		var quantity = parseInt( row.dataset.qty, 10 ) || 1;

		updateItem( key, 0 ).then( function () {
			showUndo( id, quantity );
		} );
	}

	function showUndo( id, quantity ) {
		var previous = document.querySelector( '.arena-undo-toast' );

		if ( previous ) {
			previous.remove();
		}

		var toast = document.createElement( 'div' );
		toast.className = 'arena-undo-toast';
		toast.setAttribute( 'role', 'status' );
		toast.innerHTML = '<span>' + t( 'removed', 'Item removed.' ) + '</span>' +
			'<button type="button">' + t( 'undo', 'Undo' ) + '</button>';

		toast.querySelector( 'button' ).addEventListener( 'click', function () {
			toast.remove();
			addToCart( id, quantity );
		} );

		document.body.appendChild( toast );
		announce( t( 'removed', 'Item removed.' ) );

		setTimeout( function () {
			toast.remove();
		}, 7000 );
	}

	document.addEventListener( 'click', function ( event ) {
		var stepperButton = event.target.closest ? event.target.closest( '[data-arena-qty]' ) : null;
		var removeButton = event.target.closest ? event.target.closest( '[data-arena-remove]' ) : null;

		if ( stepperButton ) {
			var row = stepperButton.closest( '.arena-cart-item' );

			if ( row ) {
				var delta = parseInt( stepperButton.getAttribute( 'data-arena-qty' ), 10 );
				var current = parseInt( row.dataset.qty, 10 ) || 1;

				updateItem( row.dataset.key, Math.max( 0, current + delta ) );
			}

			return;
		}

		if ( removeButton ) {
			var item = removeButton.closest( '.arena-cart-item' );

			if ( item ) {
				removeWithUndo( item );
			}
		}
	} );

	/* Drawer triggers (header slot, sticky bar, bottom nav). */
	document.addEventListener( 'click', function ( event ) {
		var trigger = event.target.closest ? event.target.closest( '[data-arena-cart-open]' ) : null;

		if ( trigger ) {
			event.preventDefault();
			ensurePanelCss();
			refresh().then( openDrawer );
		}
	} );

	/* ----------------------------------------------------- Quick view (H34) */
	document.addEventListener( 'click', function ( event ) {
		var trigger = event.target.closest ? event.target.closest( '[data-arena-quickview]' ) : null;

		if ( ! trigger ) {
			return;
		}

		event.preventDefault();
		ensurePanelCss();

		var id = parseInt( trigger.getAttribute( 'data-arena-quickview' ), 10 );

		fetch( API + '/products/' + id, { credentials: 'same-origin' } )
			.then( function ( response ) { return response.json(); } )
			.then( function ( product ) {
				var modal = document.getElementById( 'arena-quickview' );

				if ( ! modal ) {
					return;
				}

				var image = product.images && product.images.length ? product.images[ 0 ].src : '';
				var price = product.prices ? parseFloat( product.prices.price ) / 100 : 0;
				var currency = product.prices ? product.prices.currency_code : 'USD';

				modal.innerHTML = '<div class="arena-quickview__grid">' +
					'<div class="arena-quickview__media">' + ( image ? '<img src="' + image + '" alt="">' : '' ) + '</div>' +
					'<div>' +
					'<h2 class="arena-quickview__title">' + ( product.name || '' ) + '</h2>' +
					'<p class="arena-quickview__price">' + money( price, currency ) + '</p>' +
					'<div>' + ( product.short_description || '' ) + '</div>' +
					'<button type="button" class="wp-element-button" data-arena-add-to-cart="' + product.id + '">' + t( 'addToCart', 'Add to cart' ) + '</button>' +
					'<a href="' + ( product.permalink || '#' ) + '">' + t( 'viewDetails', 'View full details' ) + '</a>' +
					'</div></div>';

				if ( cfg.dialog ) {
					cfg.dialog.open( modal );
				}
			} );
	} );

	/* ------------------------------------------- Native gallery (H35) */
	function initGallery() {
		var gallery = document.querySelector( '.arena-gallery' );

		if ( ! gallery ) {
			return;
		}

		var viewport = gallery.querySelector( '.arena-gallery__viewport' );
		var thumbs = gallery.querySelectorAll( '.arena-gallery__thumbs button' );

		if ( viewport ) {
			thumbs.forEach( function ( thumb ) {
				thumb.addEventListener( 'click', function () {
					var slide = gallery.querySelector( '.arena-gallery__slide:nth-child(' + thumb.dataset.slide + ')' );

					if ( slide ) {
						viewport.scrollTo( { left: slide.offsetLeft, behavior: 'smooth' } );
					}
				} );
			} );

			viewport.addEventListener( 'scroll', function () {
				var index = Math.round( viewport.scrollLeft / Math.max( 1, viewport.clientWidth ) );

				thumbs.forEach( function ( thumb, position ) {
					thumb.setAttribute( 'aria-current', position === index ? 'true' : 'false' );
				} );
			}, { passive: true } );
		}

		/* Click-to-zoom lightbox (WCAG 2.5.7 alternative to hover zoom). */
		gallery.addEventListener( 'click', function ( event ) {
			var slide = event.target.closest ? event.target.closest( '.arena-gallery__slide' ) : null;

			if ( ! slide || ! event.target.matches( 'img' ) ) {
				return;
			}

			var lightbox = document.getElementById( 'arena-lightbox' );

			if ( lightbox ) {
				lightbox.innerHTML = '<img src="' + event.target.currentSrc + '" alt="">';

				if ( cfg.dialog ) {
					cfg.dialog.open( lightbox );
				}
			}
		} );
	}

	/* -------------------------------------------------------------- Boot */
	function boot() {
		if ( ! document.body.classList.contains( 'woocommerce-active' ) && ! cart.enabled ) {
			return;
		}

		initGallery();

		/* Sync counts with the server on load (cheap GET, same session). */
		if ( drawer ) {
			refresh().catch( function () {} );
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', boot, { once: true } );
	} else {
		boot();
	}
}() );
