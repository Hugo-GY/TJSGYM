<?php
/**
 * Main template file
 */
get_header();
?>

<div class="container">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class(); ?>>
                <h1><?php the_title(); ?></h1>
                <?php the_content(); ?>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <p><?php _e('No content found.', 'tjs-gymnastics'); ?></p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
