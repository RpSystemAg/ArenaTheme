/**
 * Arena Engine — Interactivity API stores.
 *
 * Loaded as a WordPress script module, so the browser only fetches it when a
 * block that declares `data-wp-interactive="arena/carousel"` is present.
 */
import { store, getContext, getElement } from '@wordpress/interactivity';

store( 'arena/carousel', {
	state: {
		get label() {
			return getContext().label || 'Carousel';
		},
	},
	actions: {
		next() {
			const { ref } = getElement();
			const viewport = ref.querySelector( '.arena-carousel__viewport' );

			if ( viewport ) {
				viewport.scrollBy( { left: viewport.clientWidth, behavior: 'smooth' } );
			}
		},
		prev() {
			const { ref } = getElement();
			const viewport = ref.querySelector( '.arena-carousel__viewport' );

			if ( viewport ) {
				viewport.scrollBy( { left: -viewport.clientWidth, behavior: 'smooth' } );
			}
		},
	},
	callbacks: {
		syncProgress() {
			const { ref } = getElement();
			const viewport = ref.querySelector( '.arena-carousel__viewport' );
			const bar = ref.querySelector( '.arena-carousel__progress-bar' );

			if ( ! viewport || ! bar ) {
				return;
			}

			const max = viewport.scrollWidth - viewport.clientWidth;
			bar.style.width = max > 0 ? Math.round( ( viewport.scrollLeft / max ) * 100 ) + '%' : '100%';
		},
	},
} );

store( 'arena/reveal', {
	callbacks: {
		observe() {
			const { ref } = getElement();
			const reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

			if ( reduced || ! ( 'IntersectionObserver' in window ) ) {
				ref.classList.add( 'arena-in-view' );
				return;
			}

			document.documentElement.classList.add( 'arena-motion-ready' );

			const observer = new IntersectionObserver(
				( entries ) => {
					entries.forEach( ( entry ) => {
						if ( entry.isIntersecting ) {
							entry.target.classList.add( 'arena-in-view' );
							observer.unobserve( entry.target );
						}
					} );
				},
				{ threshold: 0.01, rootMargin: '0px 0px -10% 0px' }
			);

			observer.observe( ref );
		},
	},
} );
