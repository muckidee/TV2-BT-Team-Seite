<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 */

get_header();
$content_class = woodmart_get_content_class();
?>

<div class="site-content <?php echo esc_attr( $content_class ); ?>" role="main">

<?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>


    <?php if ( ! is_front_page() ) : ?>
        <?php echo do_shortcode('[lmt-post-modified-info]'); ?>
    <?php endif; ?>

		<?php /* The loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>

            <?php
            $pageId=get_the_ID();
            // comparison Table
            $is_comparison_page=false;
            $mainColClass='col-12';
            if (is_page() && is_object_in_term($pageId, 'product_type')) {
                $term = get_the_terms($pageId, 'product_type' );
                if (count($term)==1) {
                    $is_comparison_page=true;
                    $mainColClass='col-12 col-md-6 order-1';
                    $productType = $term[0]->name; // used in page_includes
                    $productTypeId=$term[0]->term_id;
                }
            }
            ?>
            <?php if ($is_comparison_page):?>
                <?php
                $synonym = !empty(get_field('synonym'))?get_field('synonym'):get_the_title();
                $title_pool_count = get_generic_content_by_pool_count('h1');
                $title_offset = $pageId%$title_pool_count;
                $generic_titles = get_generic_content_by_pool('h1', ['s'=>['%keyword%','%synonym%'], 'r'=>[get_the_title(), $synonym]], $title_offset);
                $pageTitle = (isset($generic_titles['title']) && !empty($generic_titles['title']))?$generic_titles['title']:get_the_title();
                $pageSubTitle = (isset($generic_titles['content']) && !empty($generic_titles['content']))?'<h2 class="comparison-page-subtitle">'.$generic_titles['content'].'</h2>':'';
                ?>
                <div class="row">
                    <div class="col-12">
                        <h1 class="comparison-page-title"><?php echo $pageTitle;?></h1>
                        <?php echo $pageSubTitle;?>
                    </div>
                </div>
                <?php
                // additional custom fields
                $heading_introduction=get_field('heading_introduction');
                $introduction=get_field('introduction');
                $introduction_csv=get_field('introduction_csv');
                $introduction_random = '';
                if (1==2 && empty(trim(strip_tags($introduction))) && empty(trim(strip_tags($introduction_csv)))) {
                    $introduction_pool_count = get_generic_content_by_pool_count('introduction');
                    $introduction_offset = $pageId%$introduction_pool_count;
                    $generic_introduction = get_generic_content_by_pool('introduction', ['s'=>['%keyword%'], 'r'=>[$productType]], $introduction_offset);

                    if (!empty($generic_introduction['content'])) {
                        $introduction_random = str_replace('%keyword%', $productType, $generic_introduction['content']);
                    }
                }
                ?>
                <?php if (!empty($introduction)||!empty($introduction_csv)||!empty($introduction_random)):?>
                <div class="row comparison-introduction-wrapper">
                    <div class="col-12">
                <?php endif;?>
                <?php if (!empty($introduction) && 1==2):?>
                    <div class="row">
                        <div class="col-12 col-lg-9 text-justify mb-3 comparison-introduction">
                            <?php echo $introduction;?>
                        </div>
                    </div>
                <?php endif;?>
                <?php if (!empty($introduction_csv)):?>
                    <div class="row">
                        <div class="col-12 col-lg-9 text-justify mb-3 comparison-introduction">
                            <?php echo $introduction_csv;?>
                        </div>
                    </div>
                <?php endif;?>
                <?php if (!empty($introduction_random)):?>
                    <div class="row">
                        <div class="col-12 col-lg-9 text-justify mb-3 comparison-introduction">
                            <?php echo $introduction_random;?>
                        </div>
                    </div>
                <?php endif;?>
                <?php if (!empty($introduction)||!empty($introduction_csv)||!empty($introduction_random)):?>
                    </div>
                </div>
                <?php endif;?>
                <div class="row mt-5  comparison-related-pages">
                    <div class="col-12">
                        <?php echo do_shortcode("[related_pages id='{$pageId}']");?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <?php echo do_shortcode("[vergleichstabelle id='{$productTypeId}']");?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row hauptcontainer <?= $is_comparison_page?'comparison-page-content':'';?>">
                <?php if ($is_comparison_page):?>
                    <?php
                        ob_start();
                        echo do_shortcode('[lwptoc]');
                        $toc = ob_get_contents();
                        ob_end_clean();
                    ?>
                    <div class="col-12 col-md-3 order-2 order-md-0">
                        <div class="author-card">
                            <?php
                            $authorId = get_field('author', $pageId);
                            if (empty($authorId)):
                                $authorIds = get_posts(['post_type'=>'team', 'numberposts'=>-1, 'fields' => 'ids']);
                                $randomNumber = mt_rand(1, count($authorIds));
                                $selectedAuthorKey = $pageId % $randomNumber;
                                $authorId = $authorIds[$selectedAuthorKey];
                                update_field('author', $authorId, $pageId);
                            endif;
                            $author = get_post($authorId);
                            if(!empty($author)):

                            ?>
                            <div class="row author-card-head">
                                <div class="col author-image"><?= get_the_post_thumbnail($author->ID); ?></div>
                                <div class="col author-name"><a href="<?= get_the_permalink($author->ID); ?>"><?= get_the_title($author->ID);?></a></div>
                            </div>
                            <div class="author-content"><?= get_the_excerpt($author->ID);?></a></div>
                            <?php 
                            // Author Rating Section
                            $author_rating_data = function_exists("bt_get_author_rating") ? bt_get_author_rating($author->ID) : array("average" => 4.5, "count" => 12);
                            $author_avg_rating = $author_rating_data["average"];
                            $author_rating_count = $author_rating_data["count"];
                            $author_can_vote = function_exists("bt_has_ip_voted") ? !bt_has_ip_voted($author->ID) : true;
                            ?>
                            <div class="author-rating-box" data-author-id="<?php echo $author->ID; ?>" data-can-vote="<?php echo $author_can_vote ? "1" : "0"; ?>">
                                <div class="author-rating-header">
                                    <span class="author-rating-label">Autoren-Bewertung</span>
                                    <span class="author-rating-score"><?php echo number_format($author_avg_rating, 1); ?></span>
                                </div>
                                <div class="author-rating-stars-display">
                                    <?php for ($i = 1; $i <= 5; $i++): 
                                        $fill_class = ($i <= round($author_avg_rating)) ? "filled" : "";
                                    ?>
                                    <svg class="author-star <?php echo $fill_class; ?>" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <?php endfor; ?>
                                </div>
                                <span class="author-rating-count">(<?php echo $author_rating_count; ?> Bewertungen)</span>
                                <?php if ($author_can_vote): ?>
                                <div class="author-rating-interactive">
                                    <span class="author-rating-cta">Jetzt bewerten:</span>
                                    <div class="author-rating-buttons">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <button class="author-star-btn" data-value="<?php echo $i; ?>" aria-label="<?php echo $i; ?> Stern<?php echo $i > 1 ? "e" : ""; ?>">
                                            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        </button>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="author-rating-status"></p>
                                <?php else: ?>
                                <p class="author-rating-voted">Vielen Dank für Ihre Bewertung!</p>
                                <?php endif; ?>
                            </div>
                            <?php endif;?>
                        </div>
                        <?php if (is_active_sidebar('comparison_page_sidebar_left')) { ?>
                            <div class="comparison-sidebar-left">
                            <?php dynamic_sidebar('comparison_page_sidebar_left'); ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php endif;?>
                <div class="<?php echo $mainColClass; ?>">
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="entry-content">
                            <?php
                            if ($is_comparison_page):

                                $generic_mainContentHeading_count = get_generic_content_by_pool_count('ratgeber');
                                $generic_mainContentHeading_offset = $pageId%$generic_mainContentHeading_count;
                                $generic_mainContentHeading = get_generic_content_by_pool('ratgeber', ['s'=>['%keyword%'], 'r'=>[get_the_title()]], $generic_mainContentHeading_offset);
                                $mainContentHeading = '';
                                if (isset($generic_mainContentHeading['title']) && !empty($generic_mainContentHeading['title'])) {
                                    $mainContentHeading = '<h2 class="comparison-main-content-heading mt-5"><strong>'.$generic_mainContentHeading['title'].'</strong>';
                                    $mainContentHeading .= (isset($generic_mainContentHeading['content']) && !empty($generic_mainContentHeading['content']))?'<br />'.$generic_mainContentHeading['content']:'';
                                    $mainContentHeading .= '</h2>';
                                }
                                echo $mainContentHeading;
                                include (get_stylesheet_directory() . '/inc/page_at_a_glance.php');
                            endif;
                            ?>
                            <div class="comparison-main-content">
                                <?php
                                $htmlPageContent = str_replace(['%keyword%'],[get_the_title()], woodmart_get_the_content());
                                echo $htmlPageContent;
                                if ($is_comparison_page):
                                    bts_extract_faq_from_content($htmlPageContent);
                                endif;
                                ?>
                            </div>
                        </div>

                        <?php woodmart_entry_meta(); ?>

                    </article><!-- #post -->
                    <?php if ($is_comparison_page): ?>
                    <div class="row mt-5">
                        <div class="col-12">
                            <?php echo do_shortcode("[bts_youtube_video id='{$pageId}' type='{$productType}']");?>
                        </div>
                    </div>
                    <?php endif;?>
                    <?php
                    if ($is_comparison_page):
                        include(get_stylesheet_directory() . '/inc/page_include_inner.php');
                    endif;
                    ?>
                </div>
                <?php if ($is_comparison_page):?>
                <div class="col-12 col-md-3 order-0 order-md-2 comparison-toc">
                    <div class="toc-content"><?php echo $toc;?></div>
                    <?php if (is_active_sidebar('comparison_page_sidebar_right')) { ?>
                        <div class="comparison-sidebar-right">
                            <?php dynamic_sidebar('comparison_page_sidebar_right'); ?>
                        </div>
                    <?php } ?>
                </div>
                <?php endif;?>
            </div>
            <?php if ($is_comparison_page):?>
            <div class="row">
                <div class="col-12">
                    <?php include(get_stylesheet_directory() . '/inc/page_include_outer.php'); ?>
                </div>
            </div>
            <?php endif;?>
            <div class="row">
                <div class="col-12 col-lg-8 offset-lg-2">
				<?php 
					// If comments are open or we have at least one comment, load up the comment template.
					if ( woodmart_get_opt('page_comments') && (comments_open() || get_comments_number()) ) :
						comments_template();
					endif;
				 ?>
                </div>
            </div>
			<?php if ($is_comparison_page):?>
            <div class="row">
                <div class="col-12">
                    <?php echo do_shortcode("[insert page='162352' display='content']");?>
                </div>
            </div>
            <?php endif;?>
		<?php endwhile; ?>

</div><!-- .site-content -->

<script>
    (function($){
        $('.comparison-introduction-wrapper').after().click(function () {
            $(this).removeClass('comparison-introduction-wrapper');
        });
    })(jQuery);
</script>
<?php //get_sidebar(); ?>

<?php get_footer(); ?>



