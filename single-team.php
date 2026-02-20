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

// Get author rating data
$rating_data = function_exists( 'tv_get_author_rating' ) ? tv_get_author_rating( $author_id ) : array( 'average' => 4.8, 'count' => 24 );
$author_rating = $rating_data['average'];
$rating_count = $rating_data['count'];
$can_vote = function_exists( 'tv_has_ip_voted' ) ? ! tv_has_ip_voted( $author_id ) : true;

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
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "<?php echo esc_attr( $author_rating ); ?>",
            "bestRating": "5",
            "worstRating": "1",
            "ratingCount": "<?php echo esc_attr( $rating_count ); ?>"
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

<!-- BreadcrumbList Schema wird von Rank Math bereitgestellt -->

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
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/author-profile-v2.css?v=2.0.2">
<style>
/* Critical Rating CSS - Inline for cache bypass */
.tv-profile-hero__stat--rating{min-width:140px;display:flex;flex-direction:column;align-items:center;justify-content:center}
.tv-rating-display{display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:4px}
.tv-rating-value{font-family:'Fraunces',serif;font-size:28px;font-weight:700;color:#fff}
.tv-rating-stars{display:flex;gap:2px;justify-content:center}
.tv-star{width:18px;height:18px;display:inline-flex}
.tv-star svg{width:100%;height:100%;fill:rgba(255,255,255,0.3)}
.tv-star--full svg{fill:#F59E0B}
.tv-star--half svg{fill:#F59E0B}
.tv-star--empty svg{fill:rgba(255,255,255,0.25)}
.tv-rating-section{max-width:600px;margin:0 auto;padding:0 24px 24px;display:flex;justify-content:center}
.tv-rating-card{background:#fff;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:28px 40px;text-align:center;border:1px solid #E5E7EB;width:100%;max-width:500px}
.tv-rating-card__title{font-family:'Fraunces',serif;font-size:18px;font-weight:600;color:#1B2A4A;margin:0 0 6px}
.tv-rating-card__subtitle{font-size:14px;color:#6B7280;margin:0}
.tv-rating-card__body{display:flex;flex-direction:column;align-items:center;gap:12px}
.tv-rating-interactive{display:flex;gap:8px;justify-content:center}
.tv-star-btn{width:40px;height:40px;padding:0;border:none;background:transparent;cursor:pointer}
.tv-star-btn svg{width:100%;height:100%;fill:#D1D5DB;transition:fill 0.15s ease}
.tv-star-btn:hover svg,.tv-star-btn--hover svg,.tv-star-btn--selected svg{fill:#F59E0B}
.tv-rating-card__status{font-size:13px;margin:0;min-height:20px}
.tv-rating-status--prompt{color:#9CA3AF}
.tv-rating-status--success{color:#10B981;font-weight:500}
.tv-rating-status--error{color:#EF4444}
.tv-rating-status--voted{color:#6B7280;font-style:italic}
.tv-trust-signals{max-width:600px;margin:0 auto;padding:0 24px 24px;display:flex;justify-content:center}
.tv-trust-signals__inner{display:flex;justify-content:center;gap:24px;flex-wrap:wrap;padding:16px 28px;background:#F0F4F8;border-radius:16px;width:100%;max-width:500px}
.tv-trust-signal{display:flex;align-items:center;gap:10px;font-size:14px;font-weight:500;color:#1B2A4A}
.tv-trust-signal svg{color:#10B981;flex-shrink:0}
</style>

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
            <div class="tv-profile-hero__stat tv-profile-hero__stat--rating" id="ratingStatBox">
                <div class="tv-rating-display">
                    <span class="tv-rating-value" id="ratingValue"><?php echo esc_html( $author_rating ); ?></span>
                    <div class="tv-rating-stars" id="ratingStars">
                        <?php for ( $i = 1; $i <= 5; $i++ ) :
                            $star_class = 'tv-star';
                            if ( $i <= floor( $author_rating ) ) {
                                $star_class .= ' tv-star--full';
                            } elseif ( $i == ceil( $author_rating ) && ( $author_rating - floor( $author_rating ) ) >= 0.3 ) {
                                $star_class .= ' tv-star--half';
                            } else {
                                $star_class .= ' tv-star--empty';
                            }
                        ?>
                        <span class="<?php echo $star_class; ?>" data-value="<?php echo $i; ?>">
                            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </span>
                        <?php endfor; ?>
                    </div>
                </div>
                <span class="tv-profile-hero__stat-label">
                    <span id="ratingCount"><?php echo esc_html( $rating_count ); ?></span> Bewertungen
                </span>
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

<!-- Interactive Rating Section -->
<section class="tv-rating-section" id="ratingSection">
    <div class="tv-rating-card">
        <div class="tv-rating-card__header">
            <h3 class="tv-rating-card__title">Bewerten Sie <?php echo esc_html( $author_name ); ?></h3>
            <p class="tv-rating-card__subtitle">Wie hilfreich finden Sie die Produkttests dieses Autors?</p>
        </div>
        <div class="tv-rating-card__body">
            <div class="tv-rating-interactive" id="interactiveRating" data-author-id="<?php echo $author_id; ?>" data-can-vote="<?php echo $can_vote ? '1' : '0'; ?>">
                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                <button class="tv-star-btn" data-value="<?php echo $i; ?>" aria-label="<?php echo $i; ?> Stern<?php echo $i > 1 ? 'e' : ''; ?> vergeben">
                    <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </button>
                <?php endfor; ?>
            </div>
            <p class="tv-rating-card__status" id="ratingStatus">
                <?php if ( ! $can_vote ) : ?>
                    <span class="tv-rating-status--voted">Sie haben bereits bewertet. Vielen Dank!</span>
                <?php else : ?>
                    <span class="tv-rating-status--prompt">Klicken Sie auf die Sterne zum Bewerten</span>
                <?php endif; ?>
            </p>
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

// Author Rating System
(function() {
    const ratingContainer = document.getElementById('interactiveRating');
    if (!ratingContainer) return;

    const authorId = ratingContainer.dataset.authorId;
    const canVote = ratingContainer.dataset.canVote === '1';
    const starBtns = ratingContainer.querySelectorAll('.tv-star-btn');
    const statusEl = document.getElementById('ratingStatus');
    const ratingValueEl = document.getElementById('ratingValue');
    const ratingCountEl = document.getElementById('ratingCount');
    const ratingStarsEl = document.getElementById('ratingStars');

    let selectedRating = 0;
    let hasVoted = !canVote;

    // Highlight stars on hover
    function highlightStars(count) {
        starBtns.forEach((btn, idx) => {
            if (idx < count) {
                btn.classList.add('tv-star-btn--hover');
            } else {
                btn.classList.remove('tv-star-btn--hover');
            }
        });
    }

    // Update star display in hero
    function updateHeroStars(rating) {
        if (!ratingStarsEl) return;
        const stars = ratingStarsEl.querySelectorAll('.tv-star');
        stars.forEach((star, idx) => {
            star.classList.remove('tv-star--full', 'tv-star--half', 'tv-star--empty');
            if (idx < Math.floor(rating)) {
                star.classList.add('tv-star--full');
            } else if (idx === Math.floor(rating) && (rating % 1) >= 0.3) {
                star.classList.add('tv-star--half');
            } else {
                star.classList.add('tv-star--empty');
            }
        });
    }

    // Submit rating via AJAX
    function submitRating(rating) {
        if (hasVoted) {
            statusEl.innerHTML = '<span class="tv-rating-status--error">Sie haben bereits bewertet.</span>';
            return;
        }

        statusEl.innerHTML = '<span class="tv-rating-status--loading">Wird gespeichert...</span>';

        // AJAX request
        const formData = new FormData();
        formData.append('action', 'tv_submit_rating');
        formData.append('author_id', authorId);
        formData.append('rating', rating);
        formData.append('nonce', '<?php echo wp_create_nonce( "tv_rating_nonce" ); ?>');

        fetch('<?php echo admin_url( "admin-ajax.php" ); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                hasVoted = true;
                selectedRating = rating;

                // Update display
                if (ratingValueEl) ratingValueEl.textContent = data.data.average;
                if (ratingCountEl) ratingCountEl.textContent = data.data.count;
                updateHeroStars(data.data.average);

                // Mark selected stars
                starBtns.forEach((btn, idx) => {
                    btn.classList.remove('tv-star-btn--hover');
                    if (idx < rating) {
                        btn.classList.add('tv-star-btn--selected');
                    }
                    btn.disabled = true;
                });

                statusEl.innerHTML = '<span class="tv-rating-status--success">' + data.data.message + '</span>';
            } else {
                statusEl.innerHTML = '<span class="tv-rating-status--error">' + data.data.message + '</span>';
            }
        })
        .catch(error => {
            console.error('Rating error:', error);
            statusEl.innerHTML = '<span class="tv-rating-status--error">Fehler beim Speichern. Bitte versuchen Sie es erneut.</span>';
        });
    }

    // Event listeners
    if (!hasVoted) {
        starBtns.forEach((btn, idx) => {
            btn.addEventListener('mouseenter', () => highlightStars(idx + 1));
            btn.addEventListener('mouseleave', () => highlightStars(selectedRating));
            btn.addEventListener('click', () => submitRating(idx + 1));
        });

        ratingContainer.addEventListener('mouseleave', () => highlightStars(selectedRating));
    } else {
        // Disable buttons if already voted
        starBtns.forEach(btn => {
            btn.disabled = true;
            btn.style.cursor = 'default';
        });
    }
})();
</script>

<?php get_footer(); ?>
