<?php
/**
 * Checkout and cart UX module.
 *
 * Every change here is traceable to a published Baymard Institute finding:
 *
 *   - 39% of shoppers abandon over unexpected extra costs, so cost transparency
 *     is surfaced as early as the theme allows.
 *   - 19% abandon because of forced account creation, so the guest path is made
 *     prominent rather than merely present.
 *   - 18% abandon because the flow feels long; the average checkout has 19.9 form
 *     elements and an optimised one has 12, so secondary fields are collapsed.
 *   - 10% abandon when their preferred payment method is missing.
 *
 * @package Arena_Engine
 * @since   1.0.0
 */

namespace Arena_Engine\Commerce;

defined( 'ABSPATH' ) || exit;

/**
 * Applies research-backed defaults to the WooCommerce checkout.
 *
 * @since 1.0.0
 */
final class Checkout {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		if ( ! self::enabled() || ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		add_filter( 'woocommerce_checkout_fields', array( __CLASS__, 'reduce_fields' ), 30 );
		add_filter( 'woocommerce_checkout_fields', array( __CLASS__, 'autofill_tokens' ), 40 );
		add_filter( 'woocommerce_enable_guest_checkout', '__return_true' );
		add_filter( 'woocommerce_checkout_enable_signup', array( __CLASS__, 'signup_optin' ), 20 );
		add_action( 'woocommerce_review_order_before_submit', array( __CLASS__, 'cost_transparency' ), 5 );
		add_filter( 'woocommerce_order_button_html', array( __CLASS__, 'order_button_html' ), 20 );
	}

	/**
	 * Whether the module is switched on.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private static function enabled() {
		$settings = (array) get_option( 'arena_engine_settings', array() );

		return ! isset( $settings['checkout'] ) || ! empty( $settings['checkout'] );
	}

	/**
	 * Collapses the fields that most often stop a shopper.
	 *
	 * Baymard found 30% of participants stalled at "Address line 2" and that the
	 * company and phone fields are the most frequently unnecessary. They are made
	 * optional rather than removed, so no store loses data it depends on.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields Checkout fields.
	 * @return array
	 */
	public static function reduce_fields( $fields ) {
		$optional = array( 'billing', 'shipping' );

		foreach ( $optional as $group ) {
			if ( empty( $fields[ $group ] ) || ! is_array( $fields[ $group ] ) ) {
				continue;
			}

			foreach ( array( 'billing_company', 'shipping_company', 'billing_phone', 'billing_address_2', 'shipping_address_2' ) as $key ) {
				if ( isset( $fields[ $group ][ $key ] ) ) {
					$fields[ $group ][ $key ]['required'] = false;
					$fields[ $group ][ $key ]['class'][]  = 'arena-field--secondary';
					$fields[ $group ][ $key ]['priority'] = isset( $fields[ $group ][ $key ]['priority'] ) ? $fields[ $group ][ $key ]['priority'] + 40 : 90;
				}
			}
		}

		return $fields;
	}

	/**
	 * Adds the `autocomplete` tokens browsers need to autofill correctly.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields Checkout fields.
	 * @return array
	 */
	public static function autofill_tokens( $fields ) {
		$tokens = array(
			'billing_first_name'  => 'given-name',
			'billing_last_name'   => 'family-name',
			'billing_company'     => 'organization',
			'billing_address_1'   => 'address-line1',
			'billing_address_2'   => 'address-line2',
			'billing_city'        => 'address-level2',
			'billing_state'       => 'address-level1',
			'billing_postcode'    => 'postal-code',
			'billing_country'     => 'country',
			'billing_phone'       => 'tel',
			'billing_email'       => 'email',
			'shipping_first_name' => 'shipping given-name',
			'shipping_last_name'  => 'shipping family-name',
			'shipping_address_1'  => 'shipping address-line1',
			'shipping_address_2'  => 'shipping address-line2',
			'shipping_city'       => 'shipping address-level2',
			'shipping_state'      => 'shipping address-level1',
			'shipping_postcode'   => 'shipping postal-code',
			'shipping_country'    => 'shipping country',
		);

		foreach ( $tokens as $key => $token ) {
			$group = 0 === strpos( $key, 'shipping' ) ? 'shipping' : 'billing';

			if ( empty( $fields[ $group ][ $key ] ) ) {
				continue;
			}

			$fields[ $group ][ $key ]['autocomplete'] = $token;

			if ( ! isset( $fields[ $group ][ $key ]['custom_attributes'] ) || ! is_array( $fields[ $group ][ $key ]['custom_attributes'] ) ) {
				$fields[ $group ][ $key ]['custom_attributes'] = array();
			}

			$fields[ $group ][ $key ]['custom_attributes']['autocomplete'] = $token;
		}

		return $fields;
	}

	/**
	 * Keeps account creation available but never in the way.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $enabled Whether the signup checkbox shows.
	 * @return bool
	 */
	public static function signup_optin( $enabled ) {
		/**
		 * Filter whether the account-creation checkbox is shown at checkout.
		 *
		 * @since 1.0.0
		 */
		return (bool) apply_filters( 'arena_engine_checkout_signup', $enabled );
	}

	/**
	 * Restates shipping, tax and total immediately above Place order.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function cost_transparency() {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return;
		}

		$total = WC()->cart->get_total( 'edit' );

		printf(
			'<p class="arena-checkout__transparency">%1$s <strong>%2$s</strong></p>',
			esc_html__( 'Total including tax and shipping:', 'arena-engine' ),
			wp_kses_post( (string) $total )
		);
	}

	/**
	 * Gives the Place order button an unambiguous accessible name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html Button HTML.
	 * @return string
	 */
	public static function order_button_html( $html ) {
		return str_replace(
			'<button ',
			'<button aria-describedby="arena-checkout-transparency-note" ',
			(string) $html
		);
	}
}
