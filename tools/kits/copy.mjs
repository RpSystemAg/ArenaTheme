/**
 * Kit copy generator (H42): every kit ships complete en_US + it_IT content.
 *
 * The generic templates are parameterised by each kit's voice (name, hero
 * copy, products, family) so two kits never read like the same brochure, and
 * every token consumed by tools/kits/builders.mjs exists in BOTH locales.
 */

const img = ( seed, w, h ) => `https://picsum.photos/seed/${ seed }/${ w }/${ h }`;

/**
 * Returns the full i18n map for one kit.
 *
 * @param {Object} kit   Kit spec from specs.mjs.
 * @param {Object} pages PAGE_TYPES entry map.
 * @return {{en_US: Object, it_IT: Object}} The resolved copy map for both locales.
 */
export function makeCopy( kit, pages ) {
	const byType = ( type, fallback ) => kit.pages.find( ( p ) => pages[ p ] && pages[ p ].type === type ) || fallback;
	const aboutKey = kit.pages[ 0 ];
	const contactKey = byType( 'contact', aboutKey );
	const faqKey = byType( 'faq', aboutKey );
	const collectionKey = byType( 'collection', kit.pages[ 2 ] || aboutKey );
	const journalKey = byType( 'journal', kit.pages[ 5 ] || aboutKey );

	const en = {};
	const it = {};

	const set = ( key, enValue, itValue ) => {
		en[ key ] = enValue;
		it[ key ] = itValue;
	};

	/* ---------------------------------------------------- identity */
	set( 'kit.name', kit.name.en_US, kit.name.it_IT );
	set( 'kit.description', kit.description, kit.description );

	/* ------------------------------------------------------------ home */
	set( 'home.eyebrow', kit.home.eyebrow.en_US, kit.home.eyebrow.it_IT );
	set( 'home.title', kit.home.title.en_US, kit.home.title.it_IT );
	set( 'home.copy', kit.home.copy.en_US, kit.home.copy.it_IT );
	set( 'home.cta', kit.campaign.cta.en_US, kit.campaign.cta.it_IT );
	set( 'home.cta2', 'Our story', 'La nostra storia' );
	set( 'home.image', img( `kit-${ kit.slug }-hero`, 1200, 1500 ), img( `kit-${ kit.slug }-hero`, 1200, 1500 ) );
	set( 'home.imageAlt', `${ kit.name.en_US } — hero`, `${ kit.name.it_IT } — hero` );
	set( 'home.image2', img( `kit-${ kit.slug }-hero-2`, 900, 1200 ), img( `kit-${ kit.slug }-hero-2`, 900, 1200 ) );
	set( 'home.image3', img( `kit-${ kit.slug }-hero-3`, 900, 1200 ), img( `kit-${ kit.slug }-hero-3`, 900, 1200 ) );
	set( 'home.imageAlt2', `${ kit.name.en_US } — detail`, `${ kit.name.it_IT } — dettaglio` );
	set( 'home.imageAlt3', `${ kit.name.en_US } — detail`, `${ kit.name.it_IT } — dettaglio` );
	set( 'home.search', 'Search', 'Cerca' );

	set( 'home.tile.title', 'Why it works', 'Perché funziona' );
	set( 'home.tile.copy',
		'Designed once, improved for years: spare parts, repair guides and a support line answered by the people who built it.',
		'Designato una volta, migliorato per anni: ricambi, guide di riparazione e un supporto risposto da chi lo costruisce.' );

	set( 'home.stat1.value', '100%', '100%' );
	set( 'home.stat1.label', 'Batches tested', 'Lotti testati' );
	set( 'home.stat2.value', '0', '0' );
	set( 'home.stat2.label', 'Proprietary blends', 'Blend proprietari' );
	set( 'home.stat3.value', '72 h', '72 h' );
	set( 'home.stat3.label', 'Results published', 'Pubblicazione dei risultati' );

	set( 'home.step1', 'Cleanse — sixty seconds, lukewarm water.', 'Detergere — sessanta secondi, acqua tiepida.' );
	set( 'home.step2', 'Treat — three drops, pressed not rubbed.', 'Trattare — tre gocce, premute non sfregate.' );
	set( 'home.step3', 'Seal — lock it in while the skin is still damp.', 'Sigillare — chiudi mentre la pelle è ancora umida.' );

	set( 'home.quote', 'I walked in for one thing and left understanding what I actually needed.',
		'Sono entrato per una cosa e sono uscito capendo cosa mi serviva davvero.' );
	set( 'home.quoteCite', 'A customer, Tuesday', 'Un cliente, martedì' );

	set( 'home.booking.title', 'Next available slot', 'Prossimo slot disponibile' );
	set( 'home.booking.copy', 'Book in thirty seconds. We confirm by message and text you when it is your turn.',
		'Prenota in trenta secondi. Confermiamo via messaggio e ti avvisiamo quando è il tuo turno.' );
	set( 'home.booking.search', 'Your name or plate', 'Nome o targa' );

	set( 'compare.col1', 'Grade', 'Grado' );
	set( 'compare.col2', 'Cosmetic', 'Estetica' );
	set( 'compare.col3', 'Battery', 'Batteria' );
	set( 'compare.col4', 'Warranty', 'Garanzia' );
	set( 'compare.row1.a', 'A', 'A' );
	set( 'compare.row1.b', 'As new', 'Come nuovo' );
	set( 'compare.row1.c', '≥ 90%', '≥ 90%' );
	set( 'compare.row1.d', '24 months', '24 mesi' );
	set( 'compare.row2.a', 'B', 'B' );
	set( 'compare.row2.b', 'Light marks', 'Segni leggeri' );
	set( 'compare.row2.c', '≥ 85%', '≥ 85%' );
	set( 'compare.row2.d', '18 months', '18 mesi' );
	set( 'compare.row3.a', 'C', 'C' );
	set( 'compare.row3.b', 'Visible wear', 'Usura visibile' );
	set( 'compare.row3.c', '≥ 80%', '≥ 80%' );
	set( 'compare.row3.d', '12 months', '12 mesi' );

	const firstProduct = kit.products[ 0 ];
	set( 'home.dir1', firstProduct.category.en_US, firstProduct.category.it_IT );
	set( 'home.dir2', kit.products[ 1 ].category.en_US, kit.products[ 1 ].category.it_IT );
	set( 'home.dir3', kit.products[ 2 ].category.en_US, kit.products[ 2 ].category.it_IT );
	set( 'home.dir4', 'All guides', 'Tutte le guide' );

	/* ------------------------------------------------------------ links */
	set( 'link.collection', `/${ collectionKey }/`, `/${ collectionKey }/` );
	set( 'link.about', `/${ aboutKey }/`, `/${ aboutKey }/` );
	set( 'link.guide', `/${ collectionKey }/`, `/${ collectionKey }/` );
	set( 'link.contact', `/${ contactKey }/`, `/${ contactKey }/` );
	set( 'link.faq', `/${ faqKey }/`, `/${ faqKey }/` );
	set( 'link.shipping', '/shipping-returns/', '/shipping-returns/' );

	/* ------------------------------------------------------------ menu */
	set( 'menu.home', 'Home', 'Home' );
	set( 'menu.about', pages[ aboutKey ].title.en_US, pages[ aboutKey ].title.it_IT );
	set( 'menu.collection', pages[ collectionKey ].title.en_US, pages[ collectionKey ].title.it_IT );
	set( 'menu.journal', pages[ journalKey ].title.en_US, pages[ journalKey ].title.it_IT );
	set( 'menu.contact', pages[ contactKey ].title.en_US, pages[ contactKey ].title.it_IT );

	/* --------------------------------------------------------- products */
	kit.products.forEach( ( product, index ) => {
		set( `product.${ index }.name`, product.name.en_US, product.name.it_IT );
		set( `product.${ index }.category`, product.category.en_US, product.category.it_IT );
		set( `product.${ index }.description`, product.description.en_US, product.description.it_IT );
	} );

	/* ----------------------------------------------------------- pages */
	for ( const pageKey of kit.pages ) {
		const spec = pages[ pageKey ];

		set( `${ pageKey }.title`, spec.title.en_US, spec.title.it_IT );
		set( `${ pageKey }.image`, img( `kit-${ kit.slug }-${ pageKey }`, 1200, 700 ), img( `kit-${ kit.slug }-${ pageKey }`, 1200, 700 ) );
		set( `${ pageKey }.imageAlt`, `${ spec.title.en_US } — ${ kit.name.en_US }`, `${ spec.title.it_IT } — ${ kit.name.it_IT }` );

		pageCopy( set, pageKey, spec, kit );
	}

	return { en_US: en, it_IT: it };
}

/**
 * Page-type copy templates.
 *
 * @param {Function} set  Setter.
 * @param {string}   key  Page key.
 * @param {Object}   spec PAGE_TYPES entry.
 * @param {Object}   kit  Kit spec.
 */
function pageCopy( set, key, spec, kit ) {
	const brand = kit.name.en_US;
	const brandIt = kit.name.it_IT;
	const product = kit.products[ 0 ];

	switch ( spec.type ) {
		case 'about':
			set( `${ key }.intro`,
				`${ brand } started small and stayed deliberate. This page tells you who makes what you buy, and why it costs what it costs.`,
				`${ brandIt} è nato piccolo ed è rimasto ponderato. Questa pagina racconta chi realizza ciò che compri e perché costa quanto costa.` );
			set( `${ key }.body1`,
				`Everything you see is made in limited runs, by people whose names we can spell. When a batch sells out, it sells out.`,
				`Tutto ciò che vedi è prodotto in serie limitate, da persone di cui sappiamo pronunciare il nome. Quando un lotto finisce, finisce.` );
			set( `${ key }.body2`,
				`We publish our materials, our margins and our mistakes. The first two are on this page; the third is in the journal.`,
				`Pubblichiamo materiali, margini ed errori. I primi due sono su questa pagina; il terzo è nel diario.` );
			set( `${ key }.body3`,
				`If you want to see the work in person, book a visit: the kettle is always on.`,
				`Se vuoi vedere il lavoro dal vivo, prenota una visita: il bollitore è sempre acceso.` );
			set( `${ key }.point1`, 'Make fewer things, better.', 'Fare meno cose, meglio.' );
			set( `${ key }.point2`, 'Publish what things cost to make.', 'Pubblicare quanto costa produrle.' );
			set( `${ key }.point3`, 'Repair before replace, always.', 'Riparare prima che sostituire, sempre.' );
			set( `${ key }.quote`, 'Good work is slow work that respects you back.', 'Il buon lavoro è lavoro lento che ti rispetta.' );
			set( `${ key }.quoteCite`, `— the ${ brand } team`, `— il team ${ brandIt }` );
			break;

		case 'contact':
			set( `${ key }.intro`,
				`Write, call or walk in. A human answers within one working day — usually sooner.`,
				`Scrivi, chiama o passa di persona. Un umano risponde entro un giorno lavorativo — di solito prima.` );
			set( `${ key }.info1`, 'Mon–Fri, 9:00–18:00', 'Lun–Ven, 9:00–18:00' );
			set( `${ key }.info2`, '+39 02 0000 0000', '+39 02 0000 0000' );
			set( `${ key }.info3`, `ciao@${ kit.slug }.example`, `ciao@${ kit.slug }.example` );
			set( `${ key }.form.label`, 'How can we help?', 'Come possiamo aiutarti?' );
			set( `${ key }.form.submit`, 'Send', 'Invia' );
			set( `${ key }.form.note`, 'No newsletters unless you ask twice.', 'Nessuna newsletter a meno che tu non la chieda due volte.' );
			set( `${ key }.block1.title`, 'Visit', 'Vieni a trovarci' );
			set( `${ key }.block1.copy`, 'Via dell’Artigianato 12 — ring twice.', 'Via dell’Artigianato 12 — suonare due volte.' );
			set( `${ key }.block2.title`, 'Call', 'Chiamaci' );
			set( `${ key }.block2.copy`, 'A person picks up, not a tree of options.', 'Risponde una persona, non un albero di opzioni.' );
			set( `${ key }.block3.title`, 'Write', 'Scrivici' );
			set( `${ key }.block3.copy`, 'One working day, with a real answer.', 'Un giorno lavorativo, con una risposta vera.' );
			set( `${ key }.hours1.day`, 'Monday – Friday', 'Lunedì – Venerdì' );
			set( `${ key }.hours1.time`, '9:00 – 18:00', '9:00 – 18:00' );
			set( `${ key }.hours2.day`, 'Saturday', 'Sabato' );
			set( `${ key }.hours2.time`, '10:00 – 13:00', '10:00 – 13:00' );
			set( `${ key }.hours3.day`, 'Sunday', 'Domenica' );
			set( `${ key }.hours3.time`, 'Closed (we rest)', 'Chiuso (riposiamo)' );
			break;

		case 'faq':
			set( `${ key }.intro`, `The questions people actually ask ${ brand }, answered without marketing.`,
				`Le domande che le persone fanno davvero a ${ brandIt }, risposte senza marketing.` );
			set( `${ key }.q1`, 'How long does delivery take?', 'Quanto dura la consegna?' );
			set( `${ key }.a1`, 'Two to four working days in Italy; you get a tracking link the moment the parcel moves.',
				'Da due a quattro giorni lavorativi in Italia; ricevi il link di tracciamento appena il pacco si muove.' );
			set( `${ key }.q2`, 'Can I return it if I change my mind?', 'Posso cambiarlo idea?' );
			set( `${ key }.a2`, 'Yes — thirty days, any reason, and the return label is already in the box.',
				'Sì — trenta giorni, per qualsiasi motivo, e l’etichetta di reso è già nella scatola.' );
			set( `${ key }.q3`, `What if it breaks?`, `E se si rompe?` );
			set( `${ key }.a3`, `Write to us with a photo. Nine times out of ten we ship a spare part the same day; the tenth time we replace it.`,
				`Scrivici con una foto. Nove volte su dieci spediamo il ricambio lo stesso giorno; la decima lo sostituiamo.` );
			break;

		case 'legal':
			set( `${ key }.intro`, 'The short, honest version: shipping costs what it costs us, and returns are free.',
				'La versione breve e onesta: la spedizione costa quanto costa a noi, e i resi sono gratuiti.' );
			set( `${ key }.section1.title`, 'Shipping', 'Spedizioni' );
			set( `${ key }.section1.copy`, 'Free over €60. Standard delivery in 2–4 working days; you can follow the parcel at every step.',
				'Gratuita sopra i 60 €. Consegna standard in 2–4 giorni lavorativi; puoi seguire il pacco a ogni passaggio.' );
			set( `${ key }.section2.title`, 'Returns', 'Resi' );
			set( `${ key }.section2.copy`, 'Thirty days from delivery, for any reason. The return label is in the box; the refund lands within five working days of pickup.',
				'Trenta giorni dalla consegna, per qualsiasi motivo. L’etichetta è nella scatola; il rimborso arriva entro cinque giorni lavorativi dal ritiro.' );
			set( `${ key }.section3.title`, 'Damaged or wrong items', 'Articoli danneggiati o errati' );
			set( `${ key }.section3.copy`, 'Our mistake or the courier’s — either way we fix it at our cost, immediately, without a form in triplicate.',
				`Errore nostro o del corriere — in entrambi i casi risolviamo a nostre spese, subito, senza moduli in triplice copia.` );
			break;

		case 'collection':
			set( `${ key }.intro`, `Browse the full ${ product.category.en_US.toLowerCase() } selection and the rest of the catalogue, organised the way people actually shop it.`,
				`Sfoglia la selezione completa di ${ product.category.it_IT.toLowerCase() } e il resto del catalogo, organizzato come le persone lo cercano davvero.` );
			break;

		case 'journal':
			set( `${ key }.intro`, 'Notes from the workbench: what we changed, what broke, and what we are trying next.',
				'Appunti dal banco di lavoro: cosa abbiamo cambiato, cosa si è rotto e cosa stiamo provando adesso.' );
			break;
	}
}
