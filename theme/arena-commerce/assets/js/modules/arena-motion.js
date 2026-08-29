/**
 * Arena Commerce — motion module (H45 on-demand).
 *
 * Loaded only on pages that actually render a carousel or a marquee
 * (detection in inc/class-assets.php). Scroll-snap means everything here is
 * progressive enhancement: without this module the viewport still scrolls
 * with a trackpad, a wheel or a finger (WCAG 2.5.7).
 */
( function () {
	'use strict';

	var doc = document.documentElement;
	var reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	var config = window.Arena && window.Arena.config ? window.Arena.config : {};
	var i18n = config.i18n || {};

	function t( key, fallback ) {
		return i18n[ key ] || fallback;
	}

	function announce( message ) {
		var region = document.getElementById( 'arena-live-region' );

		if ( region ) {
			region.textContent = message;
		}
	}

	/* ------------------------------------------------------------- Marquee */
	function initMarquee() {
		var marquees = document.querySelectorAll( '[data-arena-marquee], .arena-marquee__viewport' );

		if ( reduced || ! marquees.length ) {
			return;
		}

		Array.prototype.forEach.call( marquees, function ( viewport ) {
			var track = viewport.querySelector( '.arena-marquee__track' );

			if ( ! track || viewport.dataset.arenaMarqueeCloned ) {
				return;
			}

			/* Duplicate the track for a seamless loop, then hide the copy from
			   assistive technology so screen readers hear each logo once. */
			var clone = track.cloneNode( true );
			clone.setAttribute( 'aria-hidden', 'true' );
			clone.querySelectorAll( 'a' ).forEach( function ( link ) {
				link.setAttribute( 'tabindex', '-1' );
			} );

			viewport.appendChild( clone );
			viewport.dataset.arenaMarqueeCloned = 'true';
		} );
	}

	/* ------------------------------------------------------------ Carousel */
	function initCarousel( root ) {
		var viewport = root.querySelector( '.arena-carousel__viewport' );

		if ( ! viewport ) {
			return;
		}

		var slides = Array.prototype.slice.call( viewport.children );

		if ( slides.length < 2 ) {
			return;
		}

		root.setAttribute( 'role', 'group' );
		root.setAttribute( 'aria-roledescription', 'carousel' );

		if ( ! root.getAttribute( 'aria-label' ) ) {
			root.setAttribute( 'aria-label', t( 'carousel', 'Carousel' ) );
		}

		viewport.setAttribute( 'tabindex', '0' );

		var controls = root.querySelector( '.arena-carousel__controls' );
		var bar = root.querySelector( '.arena-carousel__progress-bar' );
		var prev = controls ? controls.querySelector( '[data-arena-carousel-prev]' ) : null;
		var next = controls ? controls.querySelector( '[data-arena-carousel-next]' ) : null;

		function currentIndex() {
			var closest = 0;
			var delta = Math.abs( viewport.scrollLeft );

			slides.forEach( function ( slide, index ) {
				var distance = Math.abs( slide.offsetLeft - viewport.scrollLeft );

				if ( distance < delta ) {
					delta = distance;
					closest = index;
				}
			} );

			return closest;
		}

		function sync() {
			var index = currentIndex();
			var ratio = viewport.scrollWidth - viewport.clientWidth;

			if ( bar ) {
				bar.style.inlineSize = ratio > 0
					? Math.round( ( viewport.scrollLeft / ratio ) * 100 ) + '%'
					: '100%';
			}

			if ( prev ) {
				prev.disabled = viewport.scrollLeft <= 1;
			}

			if ( next ) {
				next.disabled = viewport.scrollLeft >= ratio - 1;
			}

			slides.forEach( function ( slide, position ) {
				slide.setAttribute( 'aria-hidden', position === index ? 'false' : 'true' );
			} );
		}

		function goTo( index ) {
			var target = slides[ Math.max( 0, Math.min( slides.length - 1, index ) ) ];

			if ( ! target ) {
				return;
			}

			viewport.scrollTo( { left: target.offsetLeft, behavior: reduced ? 'auto' : 'smooth' } );
			announce(
				t( 'slideStatus', 'Slide %1$s of %2$s' )
					.replace( '%1$s', String( Math.min( slides.length, index + 1 ) ) )
					.replace( '%2$s', String( slides.length ) )
			);
		}

		if ( prev ) {
			prev.addEventListener( 'click', function () {
				goTo( currentIndex() - 1 );
			} );
		}

		if ( next ) {
			next.addEventListener( 'click', function () {
				goTo( currentIndex() + 1 );
			} );
		}

		/* WCAG 2.5.7 — a keyboard alternative to dragging. */
		viewport.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'ArrowRight' ) {
				event.preventDefault();
				goTo( currentIndex() + 1 );
			} else if ( event.key === 'ArrowLeft' ) {
				event.preventDefault();
				goTo( currentIndex() - 1 );
			} else if ( event.key === 'Home' ) {
				event.preventDefault();
				goTo( 0 );
			} else if ( event.key === 'End' ) {
				event.preventDefault();
				goTo( slides.length - 1 );
			}
		} );

		var ticking = false;

		viewport.addEventListener( 'scroll', function () {
			if ( ticking ) {
				return;
			}

			ticking = true;
			window.requestAnimationFrame( function () {
				sync();
				ticking = false;
			} );
		}, { passive: true } );

		sync();
	}

	function boot() {
		initMarquee();

		var carousels = document.querySelectorAll( '[data-arena-carousel], .arena-carousel' );
		Array.prototype.forEach.call( carousels, initCarousel );

		doc.classList.add( 'arena-motion-module-ready' );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', boot, { once: true } );
	} else {
		boot();
	}
}() );
