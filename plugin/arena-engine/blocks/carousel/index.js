/**
 * Editor registration for the Arena Carousel block.
 * Uses the server-side render, so there is no duplicated save markup to drift.
 */
( function ( blocks, blockEditor, element, i18n ) {
	var el = element.createElement;
	var __ = i18n.__;
	var useBlockProps = blockEditor.useBlockProps;
	var InnerBlocks = blockEditor.InnerBlocks;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var RangeControl = wp.components.RangeControl;
	var TextControl = wp.components.TextControl;

	blocks.registerBlockType( 'arena/carousel', {
		edit: function ( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			return el(
				element.Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Carousel', 'arena-engine' ), initialOpen: true },
						el( TextControl, {
							label: __( 'Accessible name', 'arena-engine' ),
							help: __( 'Announced by screen readers. Describe the collection, not the widget.', 'arena-engine' ),
							value: attributes.label,
							onChange: function ( value ) {
								setAttributes( { label: value } );
							},
						} ),
						el( RangeControl, {
							label: __( 'Slides visible', 'arena-engine' ),
							min: 1,
							max: 6,
							value: attributes.perView,
							onChange: function ( value ) {
								setAttributes( { perView: value } );
							},
						} )
					)
				),
				el( 'div', useBlockProps( { className: 'arena-carousel' } ), el( InnerBlocks, {} ) )
			);
		},
		save: function () {
			return el( InnerBlocks.Content, {} );
		},
	} );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.element, window.wp.i18n );
