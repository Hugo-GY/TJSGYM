<?php
/**
 * Template Name: News Page
 */
get_header();

$hero_eyebrow = get_field('hero_eyebrow') ?: 'Latest Updates';
$hero_title = get_field('hero_title') ?: 'Club <em>News</em>';
$hero_subtitle = get_field('hero_subtitle') ?: 'Seasonal updates, reopening guidance, and practical notices from TJ\'s Gymnastics Club.';
?>

<section class="page-hero" aria-label="<?php _e('Club news', 'tjs-gymnastics'); ?>">
    <div class="container">
        <p class="page-hero-eyebrow"><?php echo esc_html($hero_eyebrow); ?></p>
        <h1><?php echo wp_kses_post($hero_title); ?></h1>
        <p class="page-hero-sub"><?php echo esc_html($hero_subtitle); ?></p>
    </div>
</section>

<section class="news-list-section" aria-labelledby="news-list-title">
    <div class="container">
        <div class="news-list" id="news-list-title">
            <?php
            // Query news posts
            $news_query = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 10,
                'category_name' => 'news',
            ));

            if ($news_query->have_posts()):
                while ($news_query->have_posts()): $news_query->the_post();
            ?>
                <article class="news-card card-accent">
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                    <a href="<?php the_permalink(); ?>" class="btn btn-magenta btn-sm"><?php _e('Continue Reading', 'tjs-gymnastics'); ?></a>
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            else:
                // Default news items
                $default_news = array(
                    array(
                        'title' => 'Congratulations!',
                        'excerpt' => 'Both Natalie and Jade recently passed their Level 3, Module 1 assessment. The British Gymnastics assessor gave some extremely good feedback and said TJ\'s "is such a wonderful unique club that is incredible". We\'ll take that!',
                        'link' => '#'
                    ),
                    array(
                        'title' => 'Summer 2026',
                        'excerpt' => 'Our Tiddler class for 6-12 months returns in April, with pay-as-you-go and multi-week booking options available for families.',
                        'link' => '#'
                    ),
                    array(
                        'title' => 'Parents Guidance for Re-opening of TJ\'s Gymnastics Club',
                        'excerpt' => 'Guidance for families returning to class, including COVID-19 safety measures and key expectations for parents, carers, and children.',
                        'link' => '#'
                    ),
                );
                foreach ($default_news as $news):
            ?>
                <article class="news-card card-accent">
                    <h3><?php echo esc_html($news['title']); ?></h3>
                    <p><?php echo esc_html($news['excerpt']); ?></p>
                    <a href="<?php echo esc_url($news['link']); ?>" class="btn btn-magenta btn-sm"><?php _e('Continue Reading', 'tjs-gymnastics'); ?></a>
                </article>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
