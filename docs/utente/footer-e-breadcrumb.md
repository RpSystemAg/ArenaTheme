# Footer e breadcrumb (H30)

## Zone footer

Tre varianti pattern — 3, 4 o 5 colonne — selezionabili e sostituibili
dall'editor senza toccare template: il footer è un template *part* e le
colonne sono gruppi core.

## Breadcrumb

Posizione selezionabile dal pannello layout (H30): sopra l'header, sotto,
dentro il contenuto, o nascosto. La traccia è un unico componente
server-side (`class-breadcrumb.php`) che alimenta **sia** il markup visibile
**sia** il `BreadcrumbList` JSON-LD: le due cose non possono mai
contraddirsi.

## SEO

Vedi [seo-e-dati-strutturati.md](seo-e-dati-strutturati.md): il breadcrumb
strutturato cede il passo automaticamente a Yoast/Rank Math quando attivi.
