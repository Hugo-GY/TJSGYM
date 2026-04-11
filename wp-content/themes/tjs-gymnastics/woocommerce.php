<?php
/**
 * The template for displaying all WooCommerce pages
 *
 * This template ensures WooCommerce pages use the same header/footer as the rest of the site.
 */

get_header();
?>

<main>
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
            <?php the_content(); ?>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer();
