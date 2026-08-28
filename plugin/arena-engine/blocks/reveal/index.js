/** Editor registration for the Arena Reveal block. */
( function ( blocks, blockEditor, element ) {
	var el = element.createElement;
	var useBlockProps = blockEditor.useBlockProps;
	var InnerBlocks = blockEditor.InnerBlocks;

	blocks.registerBlockType( 'arena/reveal', {
		edit() {
			return el( 'div', useBlockProps( { className: 'arena-reveal' } ), el( InnerBlocks, {} ) );
		},
		save() {
			return el( InnerBlocks.Content, {} );
		},
	} );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.element );
