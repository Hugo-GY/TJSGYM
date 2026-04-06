<?php
/**
 * Single Post Template - for News Articles
 */
get_header();

// Get post categories to determine if this is a news article
$categories = get_the_category();
$is_news = false;
foreach ($categories as $category) {
    if ($category->slug === 'news') {
        $is_news = true;
        break;
    }
}
?>

<section class="page-hero" aria-label="<?php _e('News article', 'tjs-gymnastics'); ?>">
    <div class="container">
        <p class="page-hero-eyebrow"><?php _e('Club News', 'tjs-gymnastics'); ?></p>
        <h1><em><?php the_title(); ?></em></h1>
    </div>
</section>

<section class="news-article-section" aria-label="<?php _e('Article content', 'tjs-gymnastics'); ?>">
    <div class="container">
        <article class="news-article-card">
            <div class="news-article-body">
                <?php the_content(); ?>
            </div>
        </article>
    </div>
</section>

<section class="news-article-back-section" aria-label="<?php _e('Back to news', 'tjs-gymnastics'); ?>">
    <div class="container">
        <a href="<?php echo esc_url(home_url('/news')); ?>" class="btn btn-ghost-magenta"><?php _e('Back to News', 'tjs-gymnastics'); ?></a>
    </div>
</section>

<?php get_footer(); ?>
