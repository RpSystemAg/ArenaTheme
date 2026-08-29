<?php
/**
 * Structured data / JSON-LD (H43/H44).
 *
 * Server-side JSON-LD per template:
 *   home        WebSite + Organization
 *   single post Article (BlogPosting)
 *   product     Product + Offer
 *   archives    CollectionPage
 *   everywhere  BreadcrumbList (coerent with the H30 breadcrumb)
 *
 * H44 — the theme CEDS THE STEP to an active SEO plugin: when Yoast SEO or
 * Rank Math is detected (their version constants are defined) the theme
 * suppresses its own graphs so zero blocks are duplicated, and every graph
 * can be filtered or disabled individually.
 *
 * @package Arena_Theme
 * @since   1.1.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * JSON-LD generator with SEO-plugin deconfliction.
 *
 * @since 1.1.0
 */
final class Schema {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'wp_head', array( __CLASS__, 'print_schema' ), 20 );
	}

	/**
	 * Whether an SEO plugin already owns structured data (H44).
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public static function seo_plugin_active() {
		$active = defined( 'WPSEO_VERSION' )      // Yoast SEO.
			|| defined( 'RANK_MATH_VERSION' )     // Rank Math.
			|| defined( 'AIOSEO_VERSION' )        // All in One SEO.
			|| defined( 'SEOPRESS_VERSION' );     // SEOPress.

		/**
		 * Filter the list of SEO plugins the theme defers to (H44).
		 *
		 * @since 1.1.0
		 *
		 * @param bool $active True when an SEO plugin owns the markup.
		 */
		return (bool) apply_filters( 'arena_theme_seo_plugin_active', $active );
	}

	/**
	 * Prints the JSON-LD graph for the current template.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function print_schema() {
		if ( self::seo_plugin_active() ) {
			return; /* H44: zero duplicate blocks. */
		}

		$graph = self::graph();

		if ( empty( $graph ) ) {
			return;
		}

		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode(
				array(
					'@context' => 'https://schema.org',
					'@graph'   => $graph,
				),
				JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
			)
		);
	}

	/**
	 * Builds the graph for the current request.
	 *
	 * @since 1.1.0
	 *
	 * @return array[]
	 */
	public static function graph() {
		$graph = array();

		if ( is_front_page() ) {
			$graph[] = self::website();
			$graph[] = self::organization();
		}

		if ( is_singular( 'post' ) ) {
			$graph[] = self::article();
		}

		if ( function_exists( 'is_product' ) && is_product() ) {
			$graph[] = self::product();
		}

		if ( is_home() || is_archive() || is_search() ) {
			$graph[] = self::collection_page();
		}

		if ( ! is_front_page() ) {
			$breadcrumb = self::breadcrumb();

			if ( $breadcrumb ) {
				$graph[] = $breadcrumb;
			}
		}

		/**
		 * Filter the JSON-LD graph (H43).
		 *
		 * @since 1.1.0
		 *
		 * @param array[] $graph Schema.org nodes.
		 */
		return (array) apply_filters( 'arena_theme_schema_graph', $graph );
	}

	/**
	 * WebSite node (home).
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	private static function website() {
		$site = array(
			'@type'     => 'WebSite',
			'@id'       => home_url( '/#website' ),
			'url'       => home_url( '/' ),
			'name'      => get_bloginfo( 'name' ),
			'publisher' => array( '@id' => home_url( '/#organization' ) ),
		);

		if ( get_bloginfo( 'description' ) ) {
			$site['description'] = get_bloginfo( 'description' );
		}

		return $site;
	}

	/**
	 * Organization node (home).
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	private static function organization() {
		$org = array(
			'@type' => 'Organization',
			'@id'   => home_url( '/#organization' ),
			'name'  => get_bloginfo( 'name' ),
			'url'   => home_url( '/' ),
		);

		if ( has_custom_logo() ) {
			$logo    = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
			$org['logo'] = $logo ? $logo[0] : '';
		}

		return $org;
	}

	/**
	 * Article node (single post).
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	private static function article() {
		$author_id = (int) get_post_field( 'post_author' );
		$author    = get_userdata( $author_id );

		return array(
			'@type'            => 'Article',
			'@id'              => get_permalink() . '#article',
			'headline'         => get_the_title(),
			'datePublished'    => get_the_date( DATE_W3C ),
			'dateModified'     => get_the_modified_date( DATE_W3C ),
			'mainEntityOfPage' => array( '@id' => get_permalink() . '#webpage' ) + array( '@type' => 'WebPage' ),
			'author'           => array(
				'@type' => 'Person',
				'name'  => $author ? $author->display_name : get_bloginfo( 'name' ),
			),
			'publisher'        => array( '@id' => home_url( '/#organization' ) ),
			'image'            => get_the_post_thumbnail_url( null, 'large' ) ?: '',
			'wordCount'        => str_word_count( wp_strip_all_tags( (string) get_post_field( 'post_content' ) ) ),
		);
	}

	/**
	 * Product + Offer node (single product).
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	private static function product() {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;

		if ( ! $product instanceof \WC_Product ) {
			return array();
		}

		$offer = array(
			'@type'         => 'Offer',
			'url'           => get_permalink(),
			'price'         => wc_get_price_to_display( $product ),
			'priceCurrency' => get_woocommerce_currency(),
			'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
		);

		if ( $product->is_type( 'simple' ) ) {
			$offer['itemCondition'] = 'https://schema.org/NewCondition';
		}

		return array(
			'@type'       => 'Product',
			'@id'         => get_permalink() . '#product',
			'name'        => $product->get_name(),
			'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
			'sku'         => $product->get_sku() ? $product->get_sku() : (string) $product->get_id(),
			'image'       => wp_get_attachment_url( $product->get_image_id() ) ?: '',
			'offers'      => $offer,
		);
	}

	/**
	 * CollectionPage node (archives).
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	private static function collection_page() {
		$page = array(
			'@type'    => 'CollectionPage',
			'@id'      => self::current_url() . '#collection',
			'url'      => self::current_url(),
			'name'     => wp_strip_all_tags( get_the_archive_title() ),
			'isPartOf' => array( '@id' => home_url( '/#website' ) ),
		);

		if ( is_paged() ) {
			$page['pagination'] = (int) get_query_var( 'paged' );
		}

		return $page;
	}

	/**
	 * BreadcrumbList node, coherent with the H30 trail.
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	private static function breadcrumb() {
		$trail = Breadcrumb::trail();

		if ( count( $trail ) < 2 ) {
			return array();
		}

		$list = array();

		foreach ( $trail as $position => $crumb ) {
			$item = array(
				'@type'    => 'ListItem',
				'position' => $position + 1,
				'name'     => $crumb['title'],
			);

			if ( $crumb['url'] ) {
				$item['item'] = $crumb['url'];
			}

			$list[] = $item;
		}

		return array(
			'@type'           => 'BreadcrumbList',
			'@id'             => self::current_url() . '#breadcrumb',
			'itemListElement' => $list,
		);
	}

	/**
	 * The canonical-ish current URL.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	private static function current_url() {
		$host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : parse_url( home_url(), PHP_URL_HOST );
		$path = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';

		return set_url_scheme( '//' . $host . $path );
	}
}
