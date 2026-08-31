/**
 * Minimal CRC32 implementation for ZIP writer.
 */
const TABLE = new Uint32Array( 256 );
for ( let i = 0; i < 256; i++ ) {
	let c = i;
	for ( let k = 0; k < 8; k++ ) {c = c & 1 ? 0xEDB88320 ^ ( c >>> 1 ) : c >>> 1;}
	TABLE[ i ] = c >>> 0;
}

export function CRC32( buf ) {
	let c = 0xFFFFFFFF;
	const b = Buffer.isBuffer( buf ) ? buf : Buffer.from( buf );
	for ( let i = 0; i < b.length; i++ ) {c = TABLE[ ( c ^ b[ i ] ) & 0xFF ] ^ ( c >>> 8 );}
	return ( c ^ 0xFFFFFFFF ) >>> 0;
}
