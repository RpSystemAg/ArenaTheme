/** Editor registration for the Arena Reveal block. */
( function ( blocks, blockEditor, element, i18n ) {
	var el = element.createElement;
	var __ = i18n.__;
	var useBlockProps = blockEditor.useBlockProps;
	var InnerBlocks = blockEditor.InnerBlocks;

	blocks.registerBlockType( 'arena/reveal', {
		edit: function () {
			return el( 'div', useBlockProps( { className: 'arena-reveal' } ), el( InnerBlocks, {} ) );
		},
		save: function () {
			return el( InnerBlocks.Content, {} );
		},
	} );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.element, window.wp.i18n );
