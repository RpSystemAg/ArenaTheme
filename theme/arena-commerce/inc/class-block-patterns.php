<?php
/**
 * Pattern categories and block style variations.
 *
 * Patterns live in /patterns and are auto-registered by core from their file
 * headers; this class only adds the categories merchants browse in the inserter.
 *
 * @package Arena_Theme
 * @since   1.0.0
 */

namespace Arena_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Registers Arena pattern categories and block styles.
 *
 * @since 1.0.0
 */
final class Block_Patterns {

	/**
	 * Attaches the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_categories' ), 15 );
		add_action( 'init', array( __CLASS__, 'register_block_styles' ), 20 );
	}

	/**
	 * Adds the Arena categories to the inserter.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function register_categories() {
		$categories = array(
			'arena-commerce'   => __( 'Arena: commerce', 'arena-commerce' ),
			'arena-motion'     => __( 'Arena: motion', 'arena-commerce' ),
			'arena-conversion' => __( 'Arena: conversion', 'arena-commerce' ),
		);

		foreach ( $categories as $slug => $label ) {
			register_block_pattern_category( $slug, array( 'label' => $label ) );
		}
	}

	/**
	 * Adds named block styles used by the shipped patterns.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function register_block_styles() {
		$styles = array(
			'core/button'  => array(
				'arena-pill'  => __( 'Pill', 'arena-commerce' ),
				'arena-block' => __( 'Full width', 'arena-commerce' ),
			),
			'core/group'   => array(
				'arena-card'   => __( 'Card', 'arena-commerce' ),
				'arena-sticky' => __( 'Sticky column', 'arena-commerce' ),
			),
			'core/heading' => array(
				'arena-eyebrow' => __( 'Eyebrow', 'arena-commerce' ),
			),
			'core/image'   => array(
				'arena-frame' => __( 'Framed', 'arena-commerce' ),
			),
		);

		foreach ( $styles as $block => $variations ) {
			foreach ( $variations as $name => $label ) {
				register_block_style(
					$block,
					array(
						'name'  => $name,
						'label' => $label,
					)
				);
			}
		}
	}
}
