/**
 * Arena Commerce — progressive enhancement.
 *
 * Rules:
 *   1. Every feature below is optional. Remove this file and the page still
 *      works: carousels scroll natively, marquees stop, reveals never hide.
 *   2. No dependencies, no jQuery, no build step, no framework.
 *   3. Nothing animates when the visitor prefers reduced motion.
 *   4. Configuration is read from a JSON data block, so no inline script and no
 *      wp-i18n payload is needed (CSP friendly).
 */
( function () {
	'use strict';

	var doc = document.documentElement;
	var motionQuery = window.matchMedia( '(prefers-reduced-motion: reduce)' );
	var reduced = motionQuery.matches;

	var config = ( function () {
		var node = document.getElementById( 'arena-commerce-config' );

		if ( ! node || ! node.textContent ) {
			return {};
		}

		try {
			return JSON.parse( node.textContent );
		} catch ( error ) {
			return {};
		}
	}() );

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

	/* ------------------------------------------------------------- Reveal */
	function initReveal() {
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

		Array.prototype.forEach.call( items, function ( item ) {
			observer.observe( item );
		} );
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

	/* -------------------------------------------------------------- Dialog */
	var FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';
	var lastFocused = null;

	function trapFocus( dialog, event ) {
		if ( event.key !== 'Tab' ) {
			return;
		}

		var nodes = Array.prototype.filter.call( dialog.querySelectorAll( FOCUSABLE ), function ( node ) {
			return node.offsetParent !== null;
		} );

		if ( ! nodes.length ) {
			return;
		}

		var first = nodes[ 0 ];
		var last = nodes[ nodes.length - 1 ];

		if ( event.shiftKey && document.activeElement === first ) {
			event.preventDefault();
			last.focus();
		} else if ( ! event.shiftKey && document.activeElement === last ) {
			event.preventDefault();
			first.focus();
		}
	}

	function openDialog( dialog ) {
		lastFocused = document.activeElement;
		dialog.hidden = false;
		dialog.setAttribute( 'aria-hidden', 'false' );
		doc.style.overflow = 'hidden';
		dialog.addEventListener( 'keydown', trapFocus );

		var focusTarget = dialog.querySelector( FOCUSABLE );

		if ( focusTarget ) {
			focusTarget.focus();
		}
	}

	function closeDialog( dialog ) {
		dialog.hidden = true;
		dialog.setAttribute( 'aria-hidden', 'true' );
		doc.style.overflow = '';
		dialog.removeEventListener( 'keydown', trapFocus );

		if ( lastFocused ) {
			lastFocused.focus();
		}
	}

	function initDialogs() {
		var dialogs = document.querySelectorAll( '[data-arena-dialog]' );

		Array.prototype.forEach.call( dialogs, function ( dialog ) {
			var triggers = document.querySelectorAll( '[data-arena-dialog-open="' + dialog.id + '"]' );

			Array.prototype.forEach.call( triggers, function ( trigger ) {
				trigger.addEventListener( 'click', function ( event ) {
					event.preventDefault();
					openDialog( dialog );
				} );
			} );

			Array.prototype.forEach.call( dialog.querySelectorAll( '[data-arena-dialog-close]' ), function ( closer ) {
				closer.addEventListener( 'click', function () {
					closeDialog( dialog );
				} );
			} );

			dialog.addEventListener( 'keydown', function ( event ) {
				if ( event.key === 'Escape' ) {
					closeDialog( dialog );
				}
			} );
		} );
	}

	/* --------------------------------------------------------------- Boot */
	function boot() {
		initReveal();
		initMarquee();
		initDialogs();

		var carousels = document.querySelectorAll( '[data-arena-carousel], .arena-carousel' );
		Array.prototype.forEach.call( carousels, initCarousel );
	}

	/* Keep honouring the OS setting if the visitor changes it mid-session. */
	if ( motionQuery.addEventListener ) {
		motionQuery.addEventListener( 'change', function ( event ) {
			reduced = event.matches;

			if ( reduced ) {
				doc.classList.remove( 'arena-motion-ready' );
			}
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', boot, { once: true } );
	} else {
		boot();
	}
}() );
