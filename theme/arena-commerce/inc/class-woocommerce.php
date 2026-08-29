<?php
/**
 * WooCommerce integration.
 *
 * Every hook is guarded by `class_exists( 'WooCommerce' )`, so the theme is a
 * first-class citizen on plain WordPress and a tuned storefront the moment
 * WooCommerce is activated. The checkout defaults encode Baymard's published
 * findings: fewer fields, autofill that works, costs and reassurance visible
 * before the commitment, and a guest path that is impossible to miss.
 *
 * @package Arena_Theme
 * @since   1.0.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Declares WooCommerce support and applies research-backed defaults.
 *
 * @since 1.0.0
 */
final class WooCommerce {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		add_theme_support(
			'woocommerce',
			array(
				'thumbnail_image_width' => 640,
				'single_image_width'    => 960,
				'product_grid'          => array(
					'default_rows'    => 4,
					'min_rows'        => 1,
					'max_rows'        => 12,
					'default_columns' => 4,
					'min_columns'     => 1,
					'max_columns'     => 6,
				),
			)
		);

		/*
		 * H35: the theme ships its own native gallery (scroll-snap slider,
		 * hover + click zoom, <dialog> lightbox — see the arena/product-gallery
		 * block and assets/js/modules/arena-cart.js), so WooCommerce's gallery
		 * bundles stay unloaded on product pages.
		 */

		add_filter( 'woocommerce_enqueue_styles', array( __CLASS__, 'enqueue_styles' ) );
		add_filter( 'loop_shop_columns', array( __CLASS__, 'shop_columns' ) );
		add_filter( 'loop_shop_per_page', array( __CLASS__, 'shop_per_page' ) );
		add_filter( 'woocommerce_output_related_products_args', array( __CLASS__, 'related_products_args' ) );
		add_filter( 'woocommerce_checkout_fields', array( __CLASS__, 'checkout_autocomplete' ), 20 );
		add_filter( 'woocommerce_quantity_input_args', array( __CLASS__, 'quantity_input_args' ), 20, 2 );
		add_filter( 'woocommerce_add_to_cart_fragments', array( __CLASS__, 'cart_fragments' ) );
		add_filter( 'woocommerce_breadcrumb_defaults', array( __CLASS__, 'breadcrumb_defaults' ) );
		add_filter( 'body_class', array( __CLASS__, 'body_classes' ) );

		add_action( 'woocommerce_after_add_to_cart_button', array( __CLASS__, 'reassurance' ), 10 );

		/* v3.1 — H34/H35/H36 opinionated commerce. */
		add_filter( 'woocommerce_sale_flash', array( __CLASS__, 'sale_badge' ), 10, 1 );
		add_action( 'woocommerce_after_shop_loop_item', array( __CLASS__, 'quick_view_button' ), 12 );
		add_filter( 'woocommerce_product_tabs', array( __CLASS__, 'order_product_tabs' ), 98 );
		add_filter( 'woocommerce_is_purchasable', array( __CLASS__, 'maybe_catalog_mode' ), 10, 2 );
		add_filter( 'body_class', array( __CLASS__, 'commerce_body_classes' ) );
		add_filter( 'pre_render_block', array( __CLASS__, 'distraction_free_header' ), 10, 2 );
		add_action( 'init', array( __CLASS__, 'register_gallery_block' ), 25 );
	}

	/**
	 * The configured checkout mode (H36).
	 *
	 * @since 1.1.0
	 *
	 * @return string standard|distraction-free
	 */
	public static function checkout_mode() {
		$mode = (string) get_option( 'arena_checkout_mode', 'standard' );

		/**
		 * Filter the checkout mode (H36).
		 *
		 * @since 1.1.0
		 *
		 * @param string $mode standard|distraction-free.
		 */
		return apply_filters( 'arena_theme_checkout_mode', 'distraction-free' === $mode ? 'distraction-free' : 'standard' );
	}

	/**
	 * Swaps the header part for the minimal one in distraction-free checkout
	 * (H36) and drops the footer columns via a body class.
	 *
	 * @since 1.1.0
	 *
	 * @param string|null $pre_render Pre-render override.
	 * @param array       $parsed_block Parsed block.
	 * @return string|null
	 */
	public static function distraction_free_header( $pre_render, $parsed_block ) {
		if ( ! isset( $parsed_block['blockName'] ) || 'core/template-part' !== $parsed_block['blockName'] ) {
			return $pre_render;
		}

		$slug = isset( $parsed_block['attrs']['slug'] ) ? $parsed_block['attrs']['slug'] : '';

		if ( 'header' === $slug && function_exists( 'is_checkout' ) && is_checkout() && ! is_order_received_page() && 'distraction-free' === self::checkout_mode() ) {
			return do_blocks( '<!-- wp:template-part {"slug":"header-minimal","tagName":"header"} /-->' );
		}

		return $pre_render;
	}

	/**
	 * Commerce state body classes (H34/H36).
	 *
	 * @since 1.1.0
	 *
	 * @param string[] $classes Body classes.
	 * @return string[]
	 */
	public static function commerce_body_classes( $classes ) {
		if ( self::catalog_mode() ) {
			$classes[] = 'arena-catalog-mode';
		}

		if ( function_exists( 'is_checkout' ) && is_checkout() && 'distraction-free' === self::checkout_mode() ) {
			$classes[] = 'arena-distraction-free';
		}

		return $classes;
	}

	/**
	 * Whether catalog mode is on (H34): prices and purchase buttons hidden.
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public static function catalog_mode() {
		return (bool) apply_filters( 'arena_theme_catalog_mode', (bool) get_option( 'arena_catalog_mode', false ) );
	}

	/**
	 * Makes products non-purchasable in catalog mode (H34).
	 *
	 * @since 1.1.0
	 *
	 * @param bool        $purchasable Purchasable state.
	 * @param \WC_Product $product     Product.
	 * @return bool
	 */
	public static function maybe_catalog_mode( $purchasable, $product ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		return self::catalog_mode() ? false : $purchasable;
	}

	/**
	 * Applies the selected sale badge variant (H34): bubble, ribbon or tag.
	 *
	 * @since 1.1.0
	 *
	 * @param string $html Sale flash markup.
	 * @return string
	 */
	public static function sale_badge( $html ) {
		$variant = (string) get_option( 'arena_sale_badge', 'bubble' );

		if ( ! in_array( $variant, array( 'bubble', 'ribbon', 'tag' ), true ) ) {
			$variant = 'bubble';
		}

		$label = __( 'Sale!', 'woocommerce' );

		if ( preg_match( '/>([^<]+)</', $html, $m ) && trim( $m[1] ) ) {
			$label = trim( $m[1] );
		}

		return sprintf(
			'<span class="onsale arena-onsale arena-onsale--%1$s">%2$s</span>',
			esc_attr( $variant ),
			esc_html( $label )
		);
	}

	/**
	 * Renders the quick-view trigger on catalog cards (H34).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function quick_view_button() {
		global $product;

		if ( ! $product instanceof \WC_Product || self::catalog_mode() ) {
			return;
		}

		printf(
			'<button type="button" class="arena-quickview-trigger button" data-arena-quickview="%d">%s</button>',
			esc_attr( (string) $product->get_id() ),
			esc_html__( 'Quick view', 'arena-commerce' )
		);
	}

	/**
	 * Reorders the product tabs per the tracked option (H35).
	 *
	 * @since 1.1.0
	 *
	 * @param array $tabs Product tabs.
	 * @return array
	 */
	public static function order_product_tabs( $tabs ) {
		$order = get_option( 'arena_product_tabs_order', array() );

		if ( ! is_array( $order ) || empty( $order ) ) {
			return $tabs;
		}

		$ordered = array();

		foreach ( $order as $key ) {
			if ( isset( $tabs[ $key ] ) ) {
				$ordered[ $key ] = $tabs[ $key ];
				unset( $tabs[ $key ] );
			}
		}

		return $ordered + $tabs;
	}

	/**
	 * Registers the native product gallery block (H35).
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function register_gallery_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'arena/product-gallery',
			array(
				'api_version'     => 3,
				'name'            => 'arena/product-gallery',
				'title'           => __( 'Arena product gallery', 'arena-commerce' ),
				'category'        => 'woocommerce',
				'description'     => __( 'Native slider + zoom + lightbox product gallery, zero external libraries.', 'arena-commerce' ),
				'textdomain'      => 'arena-commerce',
				'render_callback' => array( __CLASS__, 'render_gallery' ),
			)
		);
	}

	/**
	 * Renders the native gallery: scroll-snap slider, thumbs, hover zoom and a
	 * dialog lightbox — all enhanced by the arena-cart script module (H35).
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public static function render_gallery() {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;

		if ( ! $product instanceof \WC_Product ) {
			return '';
		}

		$ids = array_merge( array( $product->get_image_id() ), $product->get_gallery_image_ids() );
		$ids = array_values( array_filter( array_map( 'intval', $ids ) ) );

		if ( empty( $ids ) ) {
			return '';
		}

		ob_start();
		echo '<figure class="arena-gallery wp-block-group" data-arena-module="product-gallery-native">';

		if ( $product->is_on_sale() ) {
			printf(
				'<span class="onsale arena-onsale arena-onsale--%s">%s</span>',
				esc_attr( (string) get_option( 'arena_sale_badge', 'bubble' ) ),
				esc_html__( 'Sale!', 'woocommerce' )
			);
		}

		echo '<div class="arena-gallery__viewport">';

		foreach ( $ids as $index => $id ) {
			printf(
				'<div class="arena-gallery__slide">%s</div>',
				wp_get_attachment_image( $id, 'woocommerce_single', false, array( 'alt' => esc_attr( $product->get_name() ) . ' — ' . ( $index + 1 ) ) )
			);
		}

		echo '</div>';

		if ( count( $ids ) > 1 ) {
			echo '<div class="arena-gallery__thumbs" role="tablist" aria-label="' . esc_attr__( 'Gallery thumbnails', 'arena-commerce' ) . '">';

			foreach ( $ids as $index => $id ) {
				printf(
					'<button type="button" data-slide="%d" aria-current="%s" aria-label="%s">%s</button>',
					(int) $index + 1,
					0 === $index ? 'true' : 'false',
					esc_attr( sprintf( /* translators: %d: image number. */ __( 'Show image %d', 'arena-commerce' ), $index + 1 ) ),
					wp_get_attachment_image( $id, 'thumbnail' )
				);
			}

			echo '</div>';
		}

		echo '</figure>';

		return ob_get_clean();
	}

	/**
	 * Points WooCommerce's general stylesheet at the theme's own build.
	 *
	 * @since 1.0.0
	 *
	 * @param array $styles Registered WooCommerce styles.
	 * @return array
	 */
	public static function enqueue_styles( $styles ) {
		$styles['woocommerce-general']['src']  = get_theme_file_uri( 'assets/css/arena-woocommerce.css' );
		$styles['woocommerce-general']['deps'] = array();

		return $styles;
	}

	/**
	 * Shop grid columns.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public static function shop_columns() {
		return (int) apply_filters( 'arena_theme_shop_columns', 4 );
	}

	/**
	 * Products per page. A multiple of the column count avoids orphan cards.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public static function shop_per_page() {
		return (int) apply_filters( 'arena_theme_products_per_page', 12 );
	}

	/**
	 * Related products arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Related product arguments.
	 * @return array
	 */
	public static function related_products_args( $args ) {
		$args['posts_per_page'] = 4;
		$args['columns']        = 4;

		return $args;
	}

	/**
	 * Adds correct `autocomplete` tokens to checkout fields.
	 *
	 * Baymard reports that the average checkout has 19.9 form elements and that
	 * an optimised flow reaches 12. Autofill only fires when the tokens are
	 * right, so this is the highest-leverage change available to a theme.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields Checkout fields.
	 * @return array
	 */
	public static function checkout_autocomplete( $fields ) {
		$map = array(
			'billing'  => array(
				'billing_first_name' => 'given-name',
				'billing_last_name'  => 'family-name',
				'billing_company'    => 'organization',
				'billing_address_1'  => 'address-line1',
				'billing_address_2'  => 'address-line2',
				'billing_city'       => 'address-level2',
				'billing_state'      => 'address-level1',
				'billing_postcode'   => 'postal-code',
				'billing_country'    => 'country',
				'billing_phone'      => 'tel',
				'billing_email'      => 'email',
			),
			'shipping' => array(
				'shipping_first_name' => 'shipping given-name',
				'shipping_last_name'  => 'shipping family-name',
				'shipping_address_1'  => 'shipping address-line1',
				'shipping_address_2'  => 'shipping address-line2',
				'shipping_city'       => 'shipping address-level2',
				'shipping_state'      => 'shipping address-level1',
				'shipping_postcode'   => 'shipping postal-code',
				'shipping_country'    => 'shipping country',
			),
			'account'  => array(
				'account_username' => 'username',
				'account_password' => 'new-password',
			),
		);

		foreach ( $map as $group => $group_fields ) {
			if ( empty( $fields[ $group ] ) || ! is_array( $fields[ $group ] ) ) {
				continue;
			}

			foreach ( $group_fields as $key => $token ) {
				if ( ! isset( $fields[ $group ][ $key ] ) ) {
					continue;
				}

				$fields[ $group ][ $key ]['autocomplete'] = $token;

				if ( ! isset( $fields[ $group ][ $key ]['custom_attributes'] ) || ! is_array( $fields[ $group ][ $key ]['custom_attributes'] ) ) {
					$fields[ $group ][ $key ]['custom_attributes'] = array();
				}

				$fields[ $group ][ $key ]['custom_attributes']['autocomplete'] = $token;
			}
		}

		return $fields;
	}

	/**
	 * Makes the quantity stepper keyboard and screen-reader friendly.
	 *
	 * @since 1.0.0
	 *
	 * @param array            $args    Quantity arguments.
	 * @param \WC_Product|null $product Product object.
	 * @return array
	 */
	public static function quantity_input_args( $args, $product ) {
		$name = ( $product instanceof \WC_Product ) ? $product->get_name() : __( 'product', 'arena-commerce' );

		$args['inputmode'] = 'numeric';
		$args['classes']   = isset( $args['classes'] ) && is_array( $args['classes'] ) ? $args['classes'] : array();
		$args['classes'][] = 'arena-qty';

		if ( ! isset( $args['custom_attributes'] ) || ! is_array( $args['custom_attributes'] ) ) {
			$args['custom_attributes'] = array();
		}

		$args['custom_attributes']['aria-label'] = sprintf(
			/* translators: %s: product name. */
			__( 'Quantity of %s', 'arena-commerce' ),
			(string) $name
		);

		return $args;
	}

	/**
	 * Wraps the mini-cart count in a live region so updates are announced.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fragments Cart fragments.
	 * @return array
	 */
	public static function cart_fragments( $fragments ) {
		$count = ( function_exists( 'WC' ) && WC()->cart ) ? (int) WC()->cart->get_cart_contents_count() : 0;

		$fragments['.arena-cart-count'] = sprintf(
			'<span class="arena-cart-count" role="status" aria-live="polite" aria-atomic="true"><span class="screen-reader-text">%1$s </span>%2$d</span>',
			esc_html__( 'Items in cart:', 'arena-commerce' ),
			$count
		);

		return $fragments;
	}

	/**
	 * Uses the theme's markup for breadcrumbs.
	 *
	 * @since 1.0.0
	 *
	 * @param array $defaults Breadcrumb defaults.
	 * @return array
	 */
	public static function breadcrumb_defaults( $defaults ) {
		$defaults['wrap_before'] = '<nav class="arena-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'arena-commerce' ) . '"><ol class="arena-breadcrumbs__list">';
		$defaults['wrap_after']  = '</ol></nav>';
		$defaults['before']      = '<li class="arena-breadcrumbs__item">';
		$defaults['after']       = '</li>';
		$defaults['delimiter']   = '';

		return $defaults;
	}

	/**
	 * Adds commerce state classes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public static function body_classes( $classes ) {
		if ( function_exists( 'is_cart' ) && is_cart() ) {
			$classes[] = 'arena-page-cart';
		}

		if ( function_exists( 'is_checkout' ) && is_checkout() ) {
			$classes[] = 'arena-page-checkout';
		}

		if ( function_exists( 'is_product' ) && is_product() ) {
			$classes[] = 'arena-page-product';
		}

		return $classes;
	}

	/**
	 * Prints reassurance copy directly under the add-to-cart button.
	 *
	 * Baymard: 39% of shoppers abandon over unexpected extra costs and 21% over
	 * slow or unclear delivery. Both are answered at the point of commitment,
	 * not three clicks later.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function reassurance() {
		$items = apply_filters(
			'arena_theme_reassurance_items',
			array(
				__( 'Free delivery over 75 EUR', 'arena-commerce' ),
				__( '30-day free returns', 'arena-commerce' ),
				__( 'Secure checkout', 'arena-commerce' ),
			)
		);

		if ( empty( $items ) || ! is_array( $items ) ) {
			return;
		}

		echo '<ul class="arena-reassurance">';

		foreach ( $items as $item ) {
			printf( '<li class="arena-reassurance__item">%s</li>', esc_html( (string) $item ) );
		}

		echo '</ul>';
	}
}
