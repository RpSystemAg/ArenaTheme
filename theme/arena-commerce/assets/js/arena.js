/**
 * Arena Commerce — global progressive-enhancement core.
 *
 * Rules:
 *   1. Every feature below is optional. Remove this file and the page still
 *      works: reveals never hide, dialogs stay closed, the nav never hides.
 *   2. No dependencies, no jQuery, no build step, no framework.
 *   3. Nothing animates when the visitor prefers reduced motion.
 *   4. Configuration is read from a JSON data block, so no inline script and
 *      no wp-i18n payload is needed (CSP friendly).
 *   5. Only the core runs everywhere (H45). Carousels/marquees load on demand
 *      from assets/js/modules/arena-motion.js; commerce, blog, search, mega
 *      menu and account behaviour live in their own script modules.
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
		} catch {
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

	/* Shared API for the script modules (H45). */
	window.Arena = window.Arena || {};
	window.Arena.config = config;
	window.Arena.t = t;
	window.Arena.announce = announce;

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

		Array.prototype.forEach.call( items, function ( item, index ) {
			/* H11: sibling stagger of 40-80ms, read from the nearest
			   [data-arena-stagger] group so descendants stagger against their
			   siblings, not against every reveal on the page. */
			var group = item.closest ? item.closest( '[data-arena-stagger]' ) : null;

			if ( group ) {
				var siblings = group.querySelectorAll( '[data-arena-reveal]' );
				var position = Array.prototype.indexOf.call( siblings, item );

				if ( position > -1 ) {
					item.style.setProperty( '--arena-reveal-index', String( position ) );
				}
			} else {
				item.style.setProperty( '--arena-reveal-index', String( index % 6 ) );
			}

			observer.observe( item );
		} );
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

	/* Exported so the cart drawer, flyout and quick-view modules reuse the
	   same focus trap instead of shipping their own (H45 decoupling). */
	window.Arena.dialog = { open: openDialog, close: closeDialog, trap: trapFocus };

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

	/* --------------------------------------------------- Scroll parallax */
	function initParallax() {
		var items = document.querySelectorAll( '[data-arena-parallax]' );

		if ( reduced || ! items.length || ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		var ticking = false;

		function update() {
			var viewport = window.innerHeight;

			Array.prototype.forEach.call( items, function ( item ) {
				var box = item.getBoundingClientRect();

				/* Never animate off-screen elements. */
				if ( box.bottom < -viewport * 0.2 || box.top > viewport * 1.2 ) {
					return;
				}

				var factor = parseFloat( item.getAttribute( 'data-arena-parallax' ) );

				if ( ! Number.isFinite( factor ) ) {
					factor = 0.1;
				}

				/* H11: cap parallax at 15% of the viewport height. */
				var cap = viewport * 0.15;
				var offset = ( box.top + box.height / 2 - viewport / 2 ) * factor;

				if ( offset > cap ) {
					offset = cap;
				} else if ( offset < -cap ) {
					offset = -cap;
				}

				item.style.transform = 'translate3d(0, ' + offset.toFixed( 1 ) + 'px, 0)';
			} );

			ticking = false;
		}

		function onScroll() {
			if ( ticking ) {
				return;
			}

			ticking = true;
			window.requestAnimationFrame( update );
		}

		window.addEventListener( 'scroll', onScroll, { passive: true } );
		window.addEventListener( 'resize', onScroll );
		update();
	}

	/* ------------------------------------------------- Bottom navigation */
	function initBottomNav() {
		var nav = document.getElementById( 'arena-bottom-nav' );

		if ( ! nav ) {
			return;
		}

		var lastY = window.scrollY;
		var ticking = false;

		function update() {
			var y = window.scrollY;
			var delta = y - lastY;

			/* Hide only after a clear downward flick and never while near the top. */
			if ( y > 96 && delta > 6 ) {
				nav.classList.add( 'arena-bottom-nav--hidden' );
			} else if ( delta < -6 ) {
				nav.classList.remove( 'arena-bottom-nav--hidden' );
			} else if ( y <= 96 ) {
				nav.classList.remove( 'arena-bottom-nav--hidden' );
			}

			lastY = y;
			ticking = false;
		}

		window.addEventListener(
			'scroll',
			function () {
				if ( ticking ) {
					return;
				}

				ticking = true;
				window.requestAnimationFrame( update );
			},
			{ passive: true }
		);
	}

	/* ------------------------------------------------ Header state (H27) */
	/* The transparent-over-hero header gains the solid canvas once scrolled;
	   the sticky variant adds the shadow. Both are transform-free (a class
	   toggle only), so no layout work happens per frame. */
	function initHeaderState() {
		var header = document.querySelector( '.arena-header' );

		if ( ! header ) {
			return;
		}

		var ticking = false;

		function update() {
			header.classList.toggle( 'arena-header--scrolled', window.scrollY > 24 );
			ticking = false;
		}

		window.addEventListener(
			'scroll',
			function () {
				if ( ! ticking ) {
					ticking = true;
					window.requestAnimationFrame( update );
				}
			},
			{ passive: true }
		);

		update();
	}

	/* ------------------------------------------------ Dark mode (H47/H48) */
	/* The initial scheme is pinned before first paint by the tiny inline
	   bootstrap printed by Dark_Mode::print_boot_script(); here we only sync
	   every toggle, persist the choice and keep the OS preference honoured
	   while the visitor has not chosen yet. */
	function initThemeToggle() {
		var root = doc;
		var buttons = document.querySelectorAll( '[data-arena-theme-toggle]' );

		if ( ! buttons.length ) {
			return;
		}

		function apply( scheme ) {
			root.setAttribute( 'data-theme', scheme );

			try {
				window.localStorage.setItem( 'arena-theme', scheme );
			} catch {
				/* Private mode: the choice lasts for the page view only. */
			}

			announce( scheme === 'dark' ? t( 'darkModeOn', 'Dark mode on' ) : t( 'darkModeOff', 'Light mode on' ) );
		}

		function toggle() {
			apply( root.getAttribute( 'data-theme' ) === 'dark' ? 'light' : 'dark' );
		}

		Array.prototype.forEach.call( buttons, function ( button ) {
			button.addEventListener( 'click', toggle );
		} );

		/* Follow the OS while the visitor has not expressed a choice. */
		var schemeQuery = window.matchMedia( '(prefers-color-scheme: dark)' );

		function follow( event ) {
			var stored = null;

			try {
				stored = window.localStorage.getItem( 'arena-theme' );
			} catch {
				stored = null;
			}

			if ( ! stored ) {
				root.setAttribute( 'data-theme', event.matches ? 'dark' : 'light' );
			}
		}

		if ( schemeQuery.addEventListener ) {
			schemeQuery.addEventListener( 'change', follow );
		}
	}

	/* ----------------------------------------------------------------- FLIP
	 *
	 * First-Last-Invert-Play helper (H11). Exposed on window.Arena.flip for
	 * block/view-script consumption: measure "first", mutate the DOM, then
	 * play a transform-only 200-500ms animation into the new geometry.
	 * Reduced motion short-circuits to a no-op.
	 */
	function initFLIP() {
		if ( reduced ) {
			window.Arena.flip = function () {};
			return;
		}

		if ( typeof window.MutationObserver === 'undefined' ) {
			return;
		}

		function measure( el ) {
			var box = el.getBoundingClientRect();
			return { left: box.left, top: box.top, width: box.width, height: box.height };
		}

		function flip( container ) {
			if ( reduced ) {
				return;
			}

			var children = Array.prototype.slice.call( container.children );

			if ( ! children.length ) {
				return;
			}

			var first = new Map();
			children.forEach( function ( child ) {
				first.set( child, measure( child ) );
			} );

			// One frame so the caller's DOM mutations commit before "last".
			requestAnimationFrame( function () {
				children.forEach( function ( child ) {
					var start = first.get( child );

					if ( ! start ) {
						return;
					}

					var end = measure( child );
					var dx = start.left - end.left;
					var dy = start.top - end.top;
					var sx = start.width / end.width;
					var sy = start.height / end.height;
					var moved = Math.abs( dx ) > 0.5 || Math.abs( dy ) > 0.5;
					var scaled = Math.abs( sx - 1 ) > 0.005 || Math.abs( sy - 1 ) > 0.005;

					if ( ! moved && ! scaled ) {
						return;
					}

					child.style.transition = 'none';
					child.style.transformOrigin = 'top left';
					child.style.transform =
						'translate3d(' + dx.toFixed( 2 ) + 'px,' + dy.toFixed( 2 ) + 'px,0)' +
						( scaled ? ' scale(' + sx.toFixed( 4 ) + ',' + sy.toFixed( 4 ) + ')' : '' );

					// Force reflow.
					void child.offsetWidth;

					var duration = parseInt( container.getAttribute( 'data-arena-flip-duration' ) || '360', 10 );
					duration = Math.max( 200, Math.min( 500, duration ) );

					child.style.transition = 'transform ' + duration + 'ms cubic-bezier(0.2,0,0,1)';
					child.style.transform = '';

					var cleanup = function () {
						child.style.transition = '';
						child.style.transformOrigin = '';
						child.removeEventListener( 'transitionend', cleanup );
					};
					child.addEventListener( 'transitionend', cleanup );
					setTimeout( cleanup, duration + 50 );
				} );
			} );
		}

		// Run FLIP when a [data-arena-flip] container's child list changes.
		var mo = new MutationObserver( function ( records ) {
			records.forEach( function ( rec ) {
				if ( rec.target && rec.target.matches && rec.target.matches( '[data-arena-flip]' ) ) {
					if ( rec.target.classList.contains( 'arena-flip-pending' ) ) {
						flip( rec.target );
						rec.target.classList.remove( 'arena-flip-pending' );
					}
				}
			} );
		} );

		Array.prototype.forEach.call( document.querySelectorAll( '[data-arena-flip]' ), function ( root ) {
			mo.observe( root, { childList: true, subtree: false } );
		} );

		document.addEventListener( 'click', function ( event ) {
			var trigger = event.target && event.target.closest ? event.target.closest( '[data-arena-flip-trigger]' ) : null;

			if ( ! trigger ) {
				return;
			}

			var id = trigger.getAttribute( 'data-arena-flip-trigger' );
			var root = id ? document.getElementById( id ) : trigger.parentElement;

			if ( root && root.matches( '[data-arena-flip]' ) ) {
				root.classList.add( 'arena-flip-pending' );
				setTimeout( function () {
					if ( root.classList.contains( 'arena-flip-pending' ) ) {
						flip( root );
						root.classList.remove( 'arena-flip-pending' );
					}
				}, 0 );
			}
		} );

		window.Arena.flip = flip;
	}

	/* --------------------------------------------------------------- Boot */
	function boot() {
		initReveal();
		initParallax();
		initBottomNav();
		initHeaderState();
		initThemeToggle();
		initDialogs();
		initFLIP();
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
