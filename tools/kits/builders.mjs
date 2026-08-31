/**
 * Structural builders for kit pages (H19/AP8).
 *
 * Everything is core blocks + the theme's registered `arena/*` block or
 * pattern includes (`{{pattern:slug}}`) — zero proprietary shortcodes, zero
 * custom tables (H23). Each home variant is a genuinely different skeleton,
 * not a recolour: different block sequences, grids and hierarchy.
 */

const t = ( key ) => `{{t:${key}}}`;

/* ------------------------------------------------------------------ */
/* Home intro skeletons — 12 distinct structures (AP8).                 */
/* ------------------------------------------------------------------ */

/** Split hero: asymmetric columns, serif display, portrait image right. */
const splitSerifHero = ( k ) => `
<!-- wp:group {"tagName":"main","align":"full","layout":{"type":"constrained"}} -->
<main class="wp-block-group alignfull" data-arena-pattern="kit-${k.slug}-home">
<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center">
<!-- wp:column {"width":"55%"} -->
<div class="wp-block-column" style="flex-basis:55%">
<!-- wp:paragraph {"fontSize":"caption","style":{"typography":{"letterSpacing":"0.14em","textTransform":"uppercase"}}} -->
<p class="has-caption-font-size">${t( 'home.eyebrow' )}</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"display"} -->
<h1 class="wp-block-heading has-display-font-size">${t( 'home.title' )}</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">${t( 'home.copy' )}</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{t:link.collection}}">${t( 'home.cta' )}</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="{{t:link.about}}">${t( 'home.cta2' )}</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"45%"} -->
<div class="wp-block-column" style="flex-basis:45%">
<!-- wp:image {"aspectRatio":"3/4","scale":"cover","sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="${t( 'home.image' )}" alt="${t( 'home.imageAlt' )}" style="aspect-ratio:3/4;object-fit:cover"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->`;

/** Bento hero: 2×2 grid of unequal tiles, headline spanning two. */
const bentoHero = ( k ) => `
<!-- wp:group {"tagName":"main","align":"full","layout":{"type":"constrained"}} -->
<main class="wp-block-group alignfull" data-arena-pattern="kit-${k.slug}-home">
<!-- wp:group {"layout":{"type":"grid","minimumColumnWidth":"18rem"}} -->
<div class="wp-block-group" data-arena-module="bento">
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"caption","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em"}}} -->
<p class="has-caption-font-size">${t( 'home.eyebrow' )}</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"display"} -->
<h1 class="wp-block-heading has-display-font-size">${t( 'home.title' )}</h1>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"border":{"radius":"16px"}}} -->
<div class="wp-block-group" style="border-radius:16px">
<!-- wp:image {"aspectRatio":"1/1","scale":"cover"} -->
<figure class="wp-block-image"><img src="${t( 'home.image' )}" alt="${t( 'home.imageAlt' )}" style="aspect-ratio:1/1;object-fit:cover"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:group -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">${t( 'home.copy' )}</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{t:link.collection}}">${t( 'home.cta' )}</a></div><!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<!-- wp:group -->
<div class="wp-block-group">
<!-- wp:heading {"level":2,"fontSize":"xl"} -->
<h2 class="wp-block-heading has-xl-font-size">${t( 'home.tile.title' )}</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"md"} -->
<p class="has-md-font-size">${t( 'home.tile.copy' )}</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->`;

/** Data hero: headline + copy, then a 4-metric stat strip in a bordered row. */
const dataHero = ( k ) => `
<!-- wp:group {"tagName":"main","align":"full","layout":{"type":"constrained"}} -->
<main class="wp-block-group alignfull" data-arena-pattern="kit-${k.slug}-home">
<!-- wp:group {"align":"wide","layout":{"type":"constrained","contentSize":"40rem"}} -->
<div class="wp-block-group alignwide">
<!-- wp:paragraph {"fontSize":"caption","style":{"typography":{"textTransform":"uppercase","fontWeight":"700"}}} -->
<p class="has-caption-font-size">${t( 'home.eyebrow' )}</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"display"} -->
<h1 class="wp-block-heading has-display-font-size">${t( 'home.title' )}</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">${t( 'home.copy' )}</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","style":{"border":{"top":{"color":"var:preset|color|foreground","width":"2px"}}},"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"},"data-arena-module":"metrics"} -->
<div class="wp-block-group alignwide" data-arena-module="metrics" style="border-top-color:var(--wp--preset--color--foreground);border-top-width:2px">
<!-- wp:group -->
<div class="wp-block-group">
<!-- wp:heading {"level":2,"fontSize":"h1"} -->
<h2 class="wp-block-heading has-h1-font-size">${t( 'home.stat1.value' )}</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"caption"} -->
<p class="has-caption-font-size">${t( 'home.stat1.label' )}</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group -->
<div class="wp-block-group">
<!-- wp:heading {"level":2,"fontSize":"h1"} -->
<h2 class="wp-block-heading has-h1-font-size">${t( 'home.stat2.value' )}</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"caption"} -->
<p class="has-caption-font-size">${t( 'home.stat2.label' )}</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group -->
<div class="wp-block-group">
<!-- wp:heading {"level":2,"fontSize":"h1"} -->
<h2 class="wp-block-heading has-h1-font-size">${t( 'home.stat3.value' )}</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"caption"} -->
<p class="has-caption-font-size">${t( 'home.stat3.label' )}</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->`;

/** Cover hero + sticky story: full-bleed cover with dim overlay, then story. */
const coverHeroStory = ( k ) => `
<!-- wp:group {"tagName":"main","align":"full"} -->
<main class="wp-block-group alignfull" data-arena-pattern="kit-${k.slug}-home">
<!-- wp:cover {"dimRatio":50,"minHeight":72,"minHeightUnit":"vh","isDark":true,"align":"full"} -->
<div class="wp-block-cover alignfull is-dark" style="min-height:72vh">
<span aria-hidden="true" class="wp-block-cover__background has-background-dim-40 has-background-dim"></span>
<img class="wp-block-cover__image-background" src="${t( 'home.image' )}" alt="${t( 'home.imageAlt' )}" data-object-fit="cover"/>
<div class="wp-block-cover__inner-container">
<!-- wp:group {"layout":{"type":"constrained","contentSize":"38rem"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"caption","style":{"typography":{"textTransform":"uppercase"}}} -->
<p class="has-caption-font-size">${t( 'home.eyebrow' )}</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"display"} -->
<h1 class="wp-block-heading has-display-font-size">${t( 'home.title' )}</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">${t( 'home.copy' )}</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{t:link.collection}}">${t( 'home.cta' )}</a></div><!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
</div>
<!-- /wp:cover -->`;

/** Compare hero: headline, then a grading comparison table as first content. */
const compareHero = ( k ) => `
<!-- wp:group {"tagName":"main","align":"full","layout":{"type":"constrained"}} -->
<main class="wp-block-group alignfull" data-arena-pattern="kit-${k.slug}-home">
<!-- wp:group {"align":"wide"} -->
<div class="wp-block-group alignwide">
<!-- wp:paragraph {"fontSize":"caption","style":{"typography":{"textTransform":"uppercase"}}} -->
<p class="has-caption-font-size">${t( 'home.eyebrow' )}</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"display"} -->
<h1 class="wp-block-heading has-display-font-size">${t( 'home.title' )}</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">${t( 'home.copy' )}</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:table {"align":"wide","className":"has-fixed-layout"} -->
<figure class="wp-block-table alignwide has-fixed-layout">
<table>
<thead><tr><th>${t( 'compare.col1' )}</th><th>${t( 'compare.col2' )}</th><th>${t( 'compare.col3' )}</th><th>${t( 'compare.col4' )}</th></tr></thead>
<tbody>
<tr><td>${t( 'compare.row1.a' )}</td><td>${t( 'compare.row1.b' )}</td><td>${t( 'compare.row1.c' )}</td><td>${t( 'compare.row1.d' )}</td></tr>
<tr><td>${t( 'compare.row2.a' )}</td><td>${t( 'compare.row2.b' )}</td><td>${t( 'compare.row2.c' )}</td><td>${t( 'compare.row2.d' )}</td></tr>
<tr><td>${t( 'compare.row3.a' )}</td><td>${t( 'compare.row3.b' )}</td><td>${t( 'compare.row3.c' )}</td><td>${t( 'compare.row3.d' )}</td></tr>
</tbody>
</table>
</figure>
<!-- /wp:table -->`;

/** Gallery hero: masonry gallery leads, headline stacked after. */
const galleryHero = ( k ) => `
<!-- wp:group {"tagName":"main","align":"full"} -->
<main class="wp-block-group alignfull" data-arena-pattern="kit-${k.slug}-home">
<!-- wp:gallery {"columns":3,"linkTo":"none","className":"is-style-masonry"} -->
<figure class="wp-block-gallery has-nested-images columns-3 is-style-masonry">
<!-- wp:image {"aspectRatio":"1/1","scale":"cover"} -->
<figure class="wp-block-image"><img src="${t( 'home.image' )}" alt="${t( 'home.imageAlt' )}" style="aspect-ratio:1/1;object-fit:cover"/></figure>
<!-- /wp:image -->
<!-- wp:image {"aspectRatio":"3/4","scale":"cover"} -->
<figure class="wp-block-image"><img src="${t( 'home.image2' )}" alt="${t( 'home.imageAlt2' )}" style="aspect-ratio:3/4;object-fit:cover"/></figure>
<!-- /wp:image -->
<!-- wp:image {"aspectRatio":"3/4","scale":"cover"} -->
<figure class="wp-block-image"><img src="${t( 'home.image3' )}" alt="${t( 'home.imageAlt3' )}" style="aspect-ratio:3/4;object-fit:cover"/></figure>
<!-- /wp:image -->
</figure>
<!-- /wp:gallery -->
<!-- wp:group {"layout":{"type":"constrained","contentSize":"36rem"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"caption","style":{"typography":{"textTransform":"uppercase"}}} -->
<p class="has-caption-font-size">${t( 'home.eyebrow' )}</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"display","style":{"typography":{"fontStyle":"italic"}}} -->
<h1 class="wp-block-heading has-display-font-size" style="font-style:italic">${t( 'home.title' )}</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">${t( 'home.copy' )}</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->`;

/** Catalog hero: left rail headline, right stacked category links (directory feel). */
const catalogHero = ( k ) => `
<!-- wp:group {"tagName":"main","align":"full","layout":{"type":"constrained"}} -->
<main class="wp-block-group alignfull" data-arena-pattern="kit-${k.slug}-home">
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"38%"} -->
<div class="wp-block-column" style="flex-basis:38%">
<!-- wp:paragraph {"fontSize":"caption","style":{"typography":{"textTransform":"uppercase","fontWeight":"700"}}} -->
<p class="has-caption-font-size">${t( 'home.eyebrow' )}</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"display"} -->
<h1 class="wp-block-heading has-display-font-size">${t( 'home.title' )}</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"md"} -->
<p class="has-md-font-size">${t( 'home.copy' )}</p>
<!-- /wp:paragraph -->
<!-- wp:search {"label":"${t( 'home.search' )}","buttonText":"${t( 'home.search' )}"} /-->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"62%"} -->
<div class="wp-block-column" style="flex-basis:62%">
<!-- wp:list {"className":"arena-directory"} -->
<ul class="wp-block-list arena-directory" data-arena-module="directory">
<li><a href="{{t:link.collection}}">${t( 'home.dir1' )}</a></li>
<li><a href="{{t:link.collection}}">${t( 'home.dir2' )}</a></li>
<li><a href="{{t:link.collection}}">${t( 'home.dir3' )}</a></li>
<li><a href="{{t:link.guide}}">${t( 'home.dir4' )}</a></li>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->`;

/** Ritual hero: centered stack, numbered steps as an ordered list. */
const ritualHero = ( k ) => `
<!-- wp:group {"tagName":"main","align":"full","layout":{"type":"constrained","contentSize":"42rem"}} -->
<main class="wp-block-group alignfull" data-arena-pattern="kit-${k.slug}-home">
<!-- wp:group {"style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"layout":{"type":"constrained","contentSize":"34rem"}} -->
<div class="wp-block-group" style="padding-top:4rem;padding-bottom:4rem">
<!-- wp:paragraph {"align":"center","fontSize":"caption","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.18em"}}} -->
<p class="has-text-align-center has-caption-font-size">${t( 'home.eyebrow' )}</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"align":"center","fontSize":"display"} -->
<h1 class="wp-block-heading aligncenter has-display-font-size">${t( 'home.title' )}</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"lg"} -->
<p class="has-text-align-center has-lg-font-size">${t( 'home.copy' )}</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:list {"ordered":true,"className":"arena-steps"} -->
<ol class="wp-block-list arena-steps" data-arena-module="steps">
<li>${t( 'home.step1' )}</li>
<li>${t( 'home.step2' )}</li>
<li>${t( 'home.step3' )}</li>
</ol>
<!-- /wp:list -->`;

/** Magazine hero: featured issue cover left, two stacked reviews right. */
const magazineHero = ( k ) => `
<!-- wp:group {"tagName":"main","align":"full","layout":{"type":"constrained"}} -->
<main class="wp-block-group alignfull" data-arena-pattern="kit-${k.slug}-home">
<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide">
<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%">
<!-- wp:image {"aspectRatio":"2/3","scale":"cover","style":{"border":{"radius":"8px"}}} -->
<figure class="wp-block-image" style="border-radius:8px"><img src="${t( 'home.image' )}" alt="${t( 'home.imageAlt' )}" style="aspect-ratio:2/3;object-fit:cover"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%">
<!-- wp:paragraph {"fontSize":"caption","style":{"typography":{"textTransform":"uppercase"}}} -->
<p class="has-caption-font-size">${t( 'home.eyebrow' )}</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"display"} -->
<h1 class="wp-block-heading has-display-font-size">${t( 'home.title' )}</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">${t( 'home.copy' )}</p>
<!-- /wp:paragraph -->
<!-- wp:quote {"className":"is-style-plain"} -->
<blockquote class="wp-block-quote is-style-plain"><p>${t( 'home.quote' )}</p><cite>${t( 'home.quoteCite' )}</cite></blockquote>
<!-- /wp:quote -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{t:link.collection}}">${t( 'home.cta' )}</a></div><!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->`;

/** Bold cover hero: high-contrast cover, huge type, single loud CTA. */
const boldCoverHero = ( k ) => `
<!-- wp:group {"tagName":"main","align":"full"} -->
<main class="wp-block-group alignfull" data-arena-pattern="kit-${k.slug}-home">
<!-- wp:cover {"dimRatio":70,"minHeight":80,"minHeightUnit":"vh","align":"full","style":{"typography":{"fontWeight":"900"}}} -->
<div class="wp-block-cover alignfull" style="min-height:80vh">
<span aria-hidden="true" class="wp-block-cover__background has-background-dim-70 has-background-dim"></span>
<div class="wp-block-cover__inner-container">
<!-- wp:group {"layout":{"type":"constrained","contentSize":"44rem"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","fontWeight":"900"}},"fontSize":"caption"} -->
<p class="has-caption-font-size" style="text-transform:uppercase;font-weight:900">${t( 'home.eyebrow' )}</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"display","style":{"typography":{"fontWeight":"900","lineHeight":"0.95"}}} -->
<h1 class="wp-block-heading has-display-font-size" style="font-weight:900;line-height:0.95">${t( 'home.title' )}</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">${t( 'home.copy' )}</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"0px"}}} --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{t:link.collection}}" style="border-radius:0px">${t( 'home.cta' )}</a></div><!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
</div>
<!-- /wp:cover -->`;

/** Service hero: split with booking form (search-like) + status line. */
const serviceHero = ( k ) => `
<!-- wp:group {"tagName":"main","align":"full","layout":{"type":"constrained"}} -->
<main class="wp-block-group alignfull" data-arena-pattern="kit-${k.slug}-home">
<!-- wp:columns {"style":{"spacing":{"blockGap":"4rem"}}} -->
<div class="wp-block-columns">
<!-- wp:column {"width":"58%"} -->
<div class="wp-block-column" style="flex-basis:58%">
<!-- wp:paragraph {"fontSize":"caption","style":{"typography":{"textTransform":"uppercase"}}} -->
<p class="has-caption-font-size">${t( 'home.eyebrow' )}</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"display"} -->
<h1 class="wp-block-heading has-display-font-size">${t( 'home.title' )}</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">${t( 'home.copy' )}</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{t:link.contact}}">${t( 'home.cta' )}</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"42%"} -->
<div class="wp-block-column" style="flex-basis:42%">
<!-- wp:group {"style":{"border":{"width":"1px"},"spacing":{"padding":"1.5rem"}}} -->
<div class="wp-block-group" style="border-width:1px;padding:1.5rem" data-arena-module="booking">
<!-- wp:heading {"level":2,"fontSize":"lg"} -->
<h2 class="wp-block-heading has-lg-font-size">${t( 'home.booking.title' )}</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>${t( 'home.booking.copy' )}</p>
<!-- /wp:paragraph -->
<!-- wp:search {"label":"${t( 'home.booking.search' )}","buttonText":"${t( 'home.cta' )}"} /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->`;

/** Quiet hero: minimal centered statement, one link, whitespace-led. */
const quietHero = ( k ) => `
<!-- wp:group {"tagName":"main","align":"full","layout":{"type":"constrained","contentSize":"34rem"}} -->
<main class="wp-block-group alignfull" data-arena-pattern="kit-${k.slug}-home">
<!-- wp:group {"style":{"spacing":{"padding":{"top":"6rem","bottom":"6rem"}}}} -->
<div class="wp-block-group" style="padding-top:6rem;padding-bottom:6rem">
<!-- wp:heading {"level":1,"fontSize":"display"} -->
<h1 class="wp-block-heading has-display-font-size">${t( 'home.title' )}</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">${t( 'home.copy' )}</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"caption","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.16em"}}} -->
<p class="has-caption-font-size">${t( 'home.eyebrow' )}</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><a href="{{t:link.collection}}">${t( 'home.cta' )} →</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->`;

const HOME_VARIANTS = {
	'split-serif-hero': splitSerifHero,
	'bento-hero': bentoHero,
	'data-hero': dataHero,
	'cover-hero-story': coverHeroStory,
	'compare-hero': compareHero,
	'gallery-hero': galleryHero,
	'catalog-hero': catalogHero,
	'ritual-hero': ritualHero,
	'magazine-hero': magazineHero,
	'bold-cover-hero': boldCoverHero,
	'service-hero': serviceHero,
	'quiet-hero': quietHero,
};

/* ------------------------------------------------------------------ */
/* Internal page builders — three structural variants per type.         */
/* ------------------------------------------------------------------ */

const pageHeader = ( level = 1 ) => `<!-- wp:heading {"level":${level},"fontSize":"display"} -->
<h${level} class="wp-block-heading has-display-font-size">{{t:page.title}}</h${level}>
<!-- /wp:heading -->`;

/** About variants. */
const aboutVariants = [
	/* v0 — narrative: header, two-column story, image, closing paragraph */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="about">
${pageHeader()}
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">{{t:page.intro}}</p>
<!-- /wp:paragraph -->
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph --><p>{{t:page.body1}}</p><!-- /wp:paragraph --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph --><p>{{t:page.body2}}</p><!-- /wp:paragraph --></div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
<!-- wp:image {"aspectRatio":"16/9","scale":"cover","align":"wide"} -->
<figure class="wp-block-image alignwide"><img src="{{t:page.image}}" alt="{{t:page.imageAlt}}" style="aspect-ratio:16/9;object-fit:cover"/></figure>
<!-- /wp:image -->
<!-- wp:paragraph -->
<p>{{t:page.body3}}</p>
<!-- /wp:paragraph -->
{{pattern:trust-check-list}}
</main>
<!-- /wp:group -->`,
	/* v1 — manifesto: header, numbered list, quote, stats pattern */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="about">
${pageHeader()}
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">{{t:page.intro}}</p>
<!-- /wp:paragraph -->
<!-- wp:list {"ordered":true} -->
<ol class="wp-block-list">
<li>{{t:page.point1}}</li>
<li>{{t:page.point2}}</li>
<li>{{t:page.point3}}</li>
</ol>
<!-- /wp:list -->
<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>{{t:page.quote}}</p><cite>{{t:page.quoteCite}}</cite></blockquote>
<!-- /wp:quote -->
{{pattern:trust-stats}}
</main>
<!-- /wp:group -->`,
	/* v2 — timeline: header, editorial timeline pattern, image + closing */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="about">
${pageHeader()}
<!-- wp:image {"aspectRatio":"21/9","scale":"cover","align":"wide"} -->
<figure class="wp-block-image alignwide"><img src="{{t:page.image}}" alt="{{t:page.imageAlt}}" style="aspect-ratio:21/9;object-fit:cover"/></figure>
<!-- /wp:image -->
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">{{t:page.intro}}</p>
<!-- /wp:paragraph -->
{{pattern:editorial-timeline}}
<!-- wp:paragraph -->
<p>{{t:page.body1}}</p>
<!-- /wp:paragraph -->
</main>
<!-- /wp:group -->`,
];

/** Contact variants. */
const contactVariants = [
	/* v0 — split: info left, form right */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="contact">
${pageHeader()}
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:paragraph --><p>{{t:page.intro}}</p><!-- /wp:paragraph -->
<!-- wp:list -->
<ul class="wp-block-list"><li>{{t:page.info1}}</li><li>{{t:page.info2}}</li><li>{{t:page.info3}}</li></ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:search {"label":"{{t:page.form.label}}","buttonText":"{{t:page.form.submit}}"} /-->
<!-- wp:paragraph {"fontSize":"caption"} -->
<p class="has-caption-font-size">{{t:page.form.note}}</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</main>
<!-- /wp:group -->`,
	/* v1 — map-first: image, then details grid */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="contact">
${pageHeader()}
<!-- wp:image {"aspectRatio":"16/9","scale":"cover","align":"wide"} -->
<figure class="wp-block-image alignwide"><img src="{{t:page.image}}" alt="{{t:page.imageAlt}}" style="aspect-ratio:16/9;object-fit:cover"/></figure>
<!-- /wp:image -->
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">{{t:page.intro}}</p>
<!-- /wp:paragraph -->
<!-- wp:group {"layout":{"type":"grid","minimumColumnWidth":"16rem"}} -->
<div class="wp-block-group">
<!-- wp:group --><div class="wp-block-group"><!-- wp:heading {"level":2,"fontSize":"md"} --><h2 class="wp-block-heading has-md-font-size">{{t:page.block1.title}}</h2><!-- /wp:heading --><!-- wp:paragraph --><p>{{t:page.block1.copy}}</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group --><div class="wp-block-group"><!-- wp:heading {"level":2,"fontSize":"md"} --><h2 class="wp-block-heading has-md-font-size">{{t:page.block2.title}}</h2><!-- /wp:heading --><!-- wp:paragraph --><p>{{t:page.block2.copy}}</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group --><div class="wp-block-group"><!-- wp:heading {"level":2,"fontSize":"md"} --><h2 class="wp-block-heading has-md-font-size">{{t:page.block3.title}}</h2><!-- /wp:heading --><!-- wp:paragraph --><p>{{t:page.block3.copy}}</p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div>
<!-- /wp:group -->
</main>
<!-- /wp:group -->`,
	/* v2 — FAQ-adjacent: heading, hours table, form below */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="contact">
${pageHeader()}
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">{{t:page.intro}}</p>
<!-- /wp:paragraph -->
<!-- wp:table -->
<figure class="wp-block-table"><table><tbody>
<tr><td>{{t:page.hours1.day}}</td><td>{{t:page.hours1.time}}</td></tr>
<tr><td>{{t:page.hours2.day}}</td><td>{{t:page.hours2.time}}</td></tr>
<tr><td>{{t:page.hours3.day}}</td><td>{{t:page.hours3.time}}</td></tr>
</tbody></table></figure>
<!-- /wp:table -->
<!-- wp:search {"label":"{{t:page.form.label}}","buttonText":"{{t:page.form.submit}}"} /-->
</main>
<!-- /wp:group -->`,
];

/** FAQ variants. */
const faqVariants = [
	/* v0 — accordion pattern */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="faq">
${pageHeader()}
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">{{t:page.intro}}</p>
<!-- /wp:paragraph -->
{{pattern:faq-accordion}}
</main>
<!-- /wp:group -->`,
	/* v1 — definition list style: heading + paragraph pairs */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="faq">
${pageHeader()}
<!-- wp:heading {"level":2,"fontSize":"lg"} -->
<h2 class="wp-block-heading has-lg-font-size">{{t:page.q1}}</h2>
<!-- /wp:heading -->
<!-- wp:paragraph --><p>{{t:page.a1}}</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"fontSize":"lg"} -->
<h2 class="wp-block-heading has-lg-font-size">{{t:page.q2}}</h2>
<!-- /wp:heading -->
<!-- wp:paragraph --><p>{{t:page.a2}}</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"fontSize":"lg"} -->
<h2 class="wp-block-heading has-lg-font-size">{{t:page.q3}}</h2>
<!-- /wp:heading -->
<!-- wp:paragraph --><p>{{t:page.a3}}</p><!-- /wp:paragraph -->
</main>
<!-- /wp:group -->`,
	/* v2 — two-column Q/A grid */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="faq">
${pageHeader()}
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">{{t:page.intro}}</p>
<!-- /wp:paragraph -->
<!-- wp:group {"layout":{"type":"grid","minimumColumnWidth":"22rem"}} -->
<div class="wp-block-group">
<!-- wp:group --><div class="wp-block-group"><!-- wp:heading {"level":2,"fontSize":"md"} --><h2 class="wp-block-heading has-md-font-size">{{t:page.q1}}</h2><!-- /wp:heading --><!-- wp:paragraph --><p>{{t:page.a1}}</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group --><div class="wp-block-group"><!-- wp:heading {"level":2,"fontSize":"md"} --><h2 class="wp-block-heading has-md-font-size">{{t:page.q2}}</h2><!-- /wp:heading --><!-- wp:paragraph --><p>{{t:page.a2}}</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group --><div class="wp-block-group"><!-- wp:heading {"level":2,"fontSize":"md"} --><h2 class="wp-block-heading has-md-font-size">{{t:page.q3}}</h2><!-- /wp:heading --><!-- wp:paragraph --><p>{{t:page.a3}}</p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div>
<!-- /wp:group -->
</main>
<!-- /wp:group -->`,
];

/** Legal (shipping/returns) variants. */
const legalVariants = [
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained","contentSize":"38rem"}} -->
<main class="wp-block-group" data-arena-page="legal">
${pageHeader()}
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">{{t:page.intro}}</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"fontSize":"lg"} -->
<h2 class="wp-block-heading has-lg-font-size">{{t:page.section1.title}}</h2>
<!-- /wp:heading -->
<!-- wp:paragraph --><p>{{t:page.section1.copy}}</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"fontSize":"lg"} -->
<h2 class="wp-block-heading has-lg-font-size">{{t:page.section2.title}}</h2>
<!-- /wp:heading -->
<!-- wp:paragraph --><p>{{t:page.section2.copy}}</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"fontSize":"lg"} -->
<h2 class="wp-block-heading has-lg-font-size">{{t:page.section3.title}}</h2>
<!-- /wp:heading -->
<!-- wp:paragraph --><p>{{t:page.section3.copy}}</p><!-- /wp:paragraph -->
</main>
<!-- /wp:group -->`,
];

/** Collection / guide variants. */
const collectionVariants = [
	/* v0 — gallery-masonry of items */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="collection">
${pageHeader()}
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">{{t:page.intro}}</p>
<!-- /wp:paragraph -->
{{pattern:gallery-masonry}}
</main>
<!-- /wp:group -->`,
	/* v1 — category tiles */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="collection">
${pageHeader()}
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">{{t:page.intro}}</p>
<!-- /wp:paragraph -->
{{pattern:category-tiles}}
{{pattern:trust-check-list}}
</main>
<!-- /wp:group -->`,
	/* v2 — editorial grid of entries */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="collection">
${pageHeader()}
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">{{t:page.intro}}</p>
<!-- /wp:paragraph -->
{{pattern:product-editorial-grid}}
</main>
<!-- /wp:group -->`,
];

/** Journal / blog variants. */
const journalVariants = [
	/* v0 — loop part (grid) + featured pattern */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="journal">
${pageHeader()}
<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">{{t:page.intro}}</p>
<!-- /wp:paragraph -->
<!-- wp:template-part {"slug":"loop-grid","tagName":"div"} /-->
</main>
<!-- /wp:group -->`,
	/* v1 — query loop inline list */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="journal">
${pageHeader()}
<!-- wp:query {"queryId":0,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
<div class="wp-block-query">
<!-- wp:post-template -->
<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:post-featured-image {"aspectRatio":"16/9"} /-->
<!-- wp:post-title {"isLink":true,"fontSize":"h3"} /-->
<!-- wp:post-date {"fontSize":"caption"} /-->
<!-- wp:post-excerpt {"excerptLength":24} /-->
</div>
<!-- /wp:group -->
<!-- /wp:post-template -->
<!-- wp:query-pagination -->
<!-- wp:query-pagination-previous /-->
<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
</main>
<!-- /wp:group -->`,
	/* v2 — masonry loop part */
	`<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group" data-arena-page="journal">
${pageHeader()}
<!-- wp:template-part {"slug":"loop-masonry","tagName":"div"} /-->
</main>
<!-- /wp:group -->`,
];

const PAGE_BUILDERS = {
	about: aboutVariants,
	contact: contactVariants,
	faq: faqVariants,
	legal: legalVariants,
	collection: collectionVariants,
	journal: journalVariants,
};

/**
 * Builds one kit home: unique intro + the kit's pattern set + closing.
 *
 * @param {Object} kit Kit spec.
 * @return {string} Home markup.
 */
export function buildHome( kit ) {
	const intro = HOME_VARIANTS[ kit.home.variant ]( kit );
	const sections = kit.home.patterns
		.map( ( pattern ) => `\n{{pattern:${pattern}}}` )
		.join( '' );

	return `${intro}${sections}\n</main>\n<!-- /wp:group -->\n`;
}

/**
 * Builds one internal page.
 *
 * @param {string} pageKey Page key from the spec.
 * @param {Object} spec    PAGE_TYPES entry.
 * @return {string} Page markup.
 */
export function buildPage( pageKey, spec ) {
	const builders = PAGE_BUILDERS[ spec.type ];
	return builders[ spec.variant % builders.length ];
}
