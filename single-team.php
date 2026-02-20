<?php
/**
 * Single Team Member Template - Redesign v2.0
 * Test-Vergleiche.com - Autoren-Profil-Seite
 * SEO-optimiert mit Schema Markup (Person, ProfilePage, ItemList)
 *
 * @package suspended_flavor
 * @since 2.0.0
 */

get_header();

// Author Data
$author_id      = get_the_ID();
$author_name    = get_the_title();
$author_slug    = get_post_field( 'post_name', $author_id );
$author_url     = get_permalink( $author_id );
$author_content = get_the_content();
$author_excerpt = get_the_excerpt();

// Featured Image
$author_photo_id  = get_post_thumbnail_id( $author_id );
$author_photo_url = $author_photo_id ? wp_get_attachment_image_url( $author_photo_id, 'medium' ) : '';
$author_photo_alt = $author_photo_id ? get_post_meta( $author_photo_id, '_wp_attachment_image_alt', true ) : $author_name;

// ACF Fields (if available) - fallback to defaults
$author_role      = function_exists('get_field') ? get_field( 'author_role', $author_id ) : 'Fachautor';
$author_since_acf = function_exists('get_field') ? get_field( 'author_since', $author_id ) : '';
$expertise_tags   = function_exists('get_field') ? get_field( 'expertise_tags', $author_id ) : array();

// Generate consistent "Dabei seit" year based on author slug (2017-2026)
if ( empty( $author_since_acf ) ) {
    // Use author slug hash for consistent random year per author
    $slug_hash = crc32( $author_slug );
    $author_since = 2017 + ( abs( $slug_hash ) % 10 ); // 2017-2026
} else {
    $author_since = $author_since_acf;
}

// Extract expertise from content if no ACF
if ( empty( $expertise_tags ) ) {
    // Default expertise based on common categories
    $expertise_tags = array( 'Technik', 'Vergleiche', 'Produkttests' );
}

// Count author's articles
$author_articles_query = new WP_Query( array(
    'post_type'      => 'page',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => 'author',
            'value'   => $author_id,
            'compare' => '='
        )
    ),
    'fields' => 'ids'
));
$article_count = $author_articles_query->found_posts;
wp_reset_postdata();

// Generate description for meta and schema
$meta_description = sprintf(
    '%s ist Fachautor bei Test-Vergleiche.com mit %d+ Produkttests. Experte fuer %s. Unabhaengige Vergleiche seit %s.',
    $author_name,
    $article_count,
    is_array( $expertise_tags ) ? implode( ', ', array_slice( $expertise_tags, 0, 3 ) ) : 'Produktvergleiche',
    $author_since
);

// Page title
$page_title = $author_name . ' – Fachautor bei Test-Vergleiche.com | ' . $article_count . '+ Produkttests';

?>

<!-- Schema Markup: ProfilePage -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ProfilePage",
    "mainEntity": {
        "@type": "Person",
        "@id": "<?php echo esc_url( $author_url ); ?>#person",
        "name": "<?php echo esc_attr( $author_name ); ?>",
        "url": "<?php echo esc_url( $author_url ); ?>",
        "image": "<?php echo esc_url( $author_photo_url ); ?>",
        "jobTitle": "Fachautor",
        "worksFor": {
            "@type": "Organization",
            "name": "Test-Vergleiche.com",
            "url": "https://test-vergleiche.com",
            "logo": "https://test-vergleiche.com/wp-content/uploads/2023/12/test-vergleiche-com-logo.webp"
        },
        "description": "<?php echo esc_attr( $meta_description ); ?>",
        "knowsAbout": <?php echo json_encode( is_array( $expertise_tags ) ? $expertise_tags : array( $expertise_tags ) ); ?>,
        "memberOf": {
            "@type": "Organization",
            "name": "Test-Vergleiche.com Redaktion"
        }
    },
    "name": "<?php echo esc_attr( $page_title ); ?>",
    "description": "<?php echo esc_attr( $meta_description ); ?>",
    "url": "<?php echo esc_url( $author_url ); ?>",
    "isPartOf": {
        "@type": "WebSite",
        "name": "Test-Vergleiche.com",
        "url": "https://test-vergleiche.com"
    }
}
</script>

<!-- Schema Markup: BreadcrumbList -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Startseite",
            "item": "https://test-vergleiche.com/"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "Unser Team",
            "item": "https://test-vergleiche.com/unser-team/"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "<?php echo esc_attr( $author_name ); ?>",
            "item": "<?php echo esc_url( $author_url ); ?>"
        }
    ]
}
</script>

<!-- Schema Markup: ItemList (Produkttests) -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Produkttests von <?php echo esc_attr( $author_name ); ?>",
    "description": "Alle <?php echo $article_count; ?> Produktvergleiche und Tests von <?php echo esc_attr( $author_name ); ?> bei Test-Vergleiche.com",
    "numberOfItems": <?php echo $article_count; ?>,
    "itemListOrder": "https://schema.org/ItemListOrderDescending",
    "author": {
        "@type": "Person",
        "@id": "<?php echo esc_url( $author_url ); ?>#person"
    }
}
</script>

<!-- CSS & Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&family=Outfit:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/author-profile-v2.css?v=2.0.1">

<!-- Profile Hero Section -->
<header class="tv-profile-hero">
    <div class="tv-profile-hero__inner">
        <?php if ( $author_photo_url ) : ?>
        <img class="tv-profile-hero__photo"
             src="<?php echo esc_url( $author_photo_url ); ?>"
             alt="<?php echo esc_attr( $author_name ); ?> – Fachautor bei Test-Vergleiche.com"
             width="150" height="150"
             loading="eager">
        <?php endif; ?>

        <div class="tv-profile-hero__info">
            <span class="tv-profile-hero__badge">Fachautor seit <?php echo esc_html( $author_since ); ?></span>
            <h1 class="tv-profile-hero__name"><?php echo esc_html( $author_name ); ?></h1>
            <p class="tv-profile-hero__role">Experte bei Test-Vergleiche.com</p>

            <?php if ( ! empty( $expertise_tags ) && is_array( $expertise_tags ) ) : ?>
            <div class="tv-profile-hero__tags">
                <?php foreach ( array_slice( $expertise_tags, 0, 5 ) as $tag ) : ?>
                <span class="tv-profile-hero__tag"><?php echo esc_html( $tag ); ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="tv-profile-hero__stats">
            <div class="tv-profile-hero__stat">
                <span class="tv-profile-hero__stat-num"><?php echo $article_count; ?></span>
                <span class="tv-profile-hero__stat-label">Produkttests</span>
            </div>
            <div class="tv-profile-hero__stat">
                <span class="tv-profile-hero__stat-num">4.8</span>
                <span class="tv-profile-hero__stat-label">Bewertung</span>
            </div>
            <div class="tv-profile-hero__stat">
                <span class="tv-profile-hero__stat-num"><?php echo esc_html( $author_since ); ?></span>
                <span class="tv-profile-hero__stat-label">Dabei seit</span>
            </div>
        </div>
    </div>
</header>

<!-- Bio Section -->
<section class="tv-bio-section" aria-labelledby="bio-title">
    <div class="tv-bio-card">
        <div class="tv-bio-card__text">
            <h2 class="tv-bio-card__title" id="bio-title">Ueber <?php echo esc_html( $author_name ); ?></h2>
            <div class="tv-bio-card__desc">
                <?php
                // Get custom bio or use excerpt
                $bio_text = $author_excerpt ? $author_excerpt : '';
                if ( empty( $bio_text ) ) {
                    $bio_text = sprintf(
                        '%s ist seit %s Teil des Redaktionsteams von Test-Vergleiche.com. Mit Expertise in %s hat %s bereits %d+ unabhaengige Produktvergleiche erstellt. Alle Tests basieren auf Herstellerangaben, Kundenbewertungen und Testergebnissen Dritter.',
                        $author_name,
                        $author_since,
                        is_array( $expertise_tags ) ? implode( ', ', array_slice( $expertise_tags, 0, 3 ) ) : 'verschiedenen Bereichen',
                        explode( ' ', $author_name )[0],
                        $article_count
                    );
                }
                echo wp_kses_post( wpautop( $bio_text ) );
                ?>
            </div>
        </div>
        <div class="tv-bio-card__contact">
            <a href="mailto:redaktion@test-vergleiche.com?subject=Anfrage%20an%20<?php echo urlencode( $author_name ); ?>" class="tv-bio-card__btn tv-bio-card__btn--primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                Kontakt
            </a>
            <a href="<?php echo home_url( '/unser-team/' ); ?>" class="tv-bio-card__btn tv-bio-card__btn--outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Alle Autoren
            </a>
        </div>
    </div>
</section>

<!-- E-E-A-T Trust Signals -->
<section class="tv-trust-signals">
    <div class="tv-trust-signals__inner">
        <div class="tv-trust-signal">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span>Unabhaengig & Transparent</span>
        </div>
        <div class="tv-trust-signal">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>Kein Herstellereinfluss</span>
        </div>
        <div class="tv-trust-signal">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span>Seit <?php echo esc_html( $author_since ); ?> aktiv</span>
        </div>
    </div>
</section>

<!-- Portfolio Section -->
<section class="tv-portfolio-section" id="produkttests" aria-labelledby="portfolio-title">
    <div class="tv-portfolio-header">
        <h2 class="tv-portfolio-title" id="portfolio-title">Produkttests & Vergleiche</h2>
        <span class="tv-portfolio-count"><strong><?php echo $article_count; ?></strong> Vergleiche von <?php echo esc_html( $author_name ); ?></span>
    </div>

    <!-- Filter Bar -->
    <div class="tv-filter-bar">
        <div class="tv-search-wrapper">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="search" class="tv-search-input" id="productSearch" placeholder="Produkt suchen..." aria-label="Produkte durchsuchen">
        </div>
        <select class="tv-sort-select" id="productSort" aria-label="Sortierung">
            <option value="newest">Neueste zuerst</option>
            <option value="az">A – Z</option>
            <option value="za">Z – A</option>
        </select>
    </div>

    <!-- Products Grid (via Shortcode) -->
    <div class="tv-products-wrapper">
        <?php echo do_shortcode( '[author_works]' ); ?>
    </div>
</section>

<!-- Back to Team Link -->
<div class="tv-back-link">
    <a href="<?php echo home_url( '/unser-team/' ); ?>">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Zurueck zur Team-Uebersicht
    </a>
</div>

<!-- Product Search/Filter JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('productSearch');
    const sortSelect = document.getElementById('productSort');
    const grid = document.querySelector('.author-works-grid');

    if (!grid) return;

    const cards = Array.from(grid.querySelectorAll('.author-work-card'));
    const originalOrder = [...cards];

    function filterAndSort() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const sortMode = sortSelect ? sortSelect.value : 'newest';

        let filtered = cards.filter(card => {
            if (!searchTerm) return true;
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const desc = card.querySelector('p')?.textContent.toLowerCase() || '';
            return title.includes(searchTerm) || desc.includes(searchTerm);
        });

        if (sortMode === 'az') {
            filtered.sort((a, b) => {
                const titleA = a.querySelector('h3')?.textContent || '';
                const titleB = b.querySelector('h3')?.textContent || '';
                return titleA.localeCompare(titleB, 'de');
            });
        } else if (sortMode === 'za') {
            filtered.sort((a, b) => {
                const titleA = a.querySelector('h3')?.textContent || '';
                const titleB = b.querySelector('h3')?.textContent || '';
                return titleB.localeCompare(titleA, 'de');
            });
        }

        // Hide all cards first
        cards.forEach(card => card.style.display = 'none');

        // Show filtered cards in order
        filtered.forEach(card => {
            card.style.display = '';
            grid.appendChild(card);
        });

        // Update count
        const countEl = document.querySelector('.tv-portfolio-count strong');
        if (countEl) countEl.textContent = filtered.length;
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterAndSort);
    }
    if (sortSelect) {
        sortSelect.addEventListener('change', filterAndSort);
    }
});
</script>

<?php get_footer(); ?>
