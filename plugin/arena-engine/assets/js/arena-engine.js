/**
 * Arena Engine — front-end enhancement.
 *
 * Deliberately tiny: skip-link targets, the marquee clone, focus restoration
 * after cart updates and reduced-motion consent. Everything else is either CSS
 * or the Interactivity API.
 */
( function () {
	'use strict';

	var doc = document.documentElement;
	var reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function ready( fn ) {
		if ( document.readyState === 'loading' ) {
			document.addEventListener( 'DOMContentLoaded', fn, { once: true } );
		} else {
			fn();
		}
	}

	function announce( message ) {
		var region = document.getElementById( 'arena-engine-live-region' ) || document.getElementById( 'arena-live-region' );

		if ( region ) {
			region.textContent = message;
		}
	}

	function cloneMarquees() {
		if ( reduced ) {
			return;
		}

		document.querySelectorAll( '.arena-marquee__viewport' ).forEach( function ( viewport ) {
			var track = viewport.querySelector( '.arena-marquee__track' );

			if ( ! track || viewport.dataset.arenaCloned ) {
				return;
			}

			var clone = track.cloneNode( true );
			clone.setAttribute( 'aria-hidden', 'true' );
			clone.querySelectorAll( 'a' ).forEach( function ( link ) {
				link.setAttribute( 'tabindex', '-1' );
			} );

			viewport.appendChild( clone );
			viewport.dataset.arenaCloned = 'true';
		} );
	}

	function revealOnScroll() {
		var items = document.querySelectorAll( '[data-arena-reveal]' );

		if ( reduced || ! items.length || ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		doc.classList.add( 'arena-motion-ready' );

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.classList.add( 'arena-in-view' );
						observer.unobserve( entry.target );
					}
				} );
			},
			{ rootMargin: '0px 0px -10% 0px', threshold: 0.01 }
		);

		items.forEach( function ( item ) {
			observer.observe( item );
		} );
	}

	function cartAnnouncements() {
		document.body.addEventListener(
			'wc_fragments_refreshed added_to_cart removed_from_cart',
			function () {
				var count = document.querySelector( '.arena-cart-count' );

				if ( count ) {
					announce( count.textContent.trim() );
				}
			},
			true
		);
	}

	ready( function () {
		cloneMarquees();
		revealOnScroll();
		cartAnnouncements();
	} );
}() );
