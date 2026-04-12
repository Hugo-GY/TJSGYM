<?php
/**
 * The template for displaying all WooCommerce pages
 *
 * This template ensures WooCommerce pages use the same header/footer as the rest of the site.
 * Optimized layout with hero section matching other pages
 */

get_header();

// Determine page type for hero content
$page_type = '';
$hero_title = '';
$hero_subtitle = '';

if (function_exists('is_cart') && is_cart()) {
    $page_type = 'cart';
    $hero_title = 'Shopping <em>Cart</em>';
    $hero_subtitle = 'Review your selected class and proceed to booking when ready.';
} elseif (function_exists('is_checkout') && is_checkout()) {
    $page_type = 'checkout';
    $hero_title = 'Complete <em>Booking</em>';
    $hero_subtitle = 'Fill in your details below to complete your class booking.';
} elseif (function_exists('is_account_page') && is_account_page()) {
    $page_type = 'account';
    $hero_title = 'My <em>Account</em>';
    $hero_subtitle = 'Manage your bookings and account settings.';
}
?>

<main>
    <?php if ($page_type) : ?>
    <!-- ── Page Hero ──────────────────────────────────── -->
    <section class="tjs-wc-hero" aria-label="<?php echo esc_attr($page_type); ?>">
        <div class="container">
            <p class="page-hero-eyebrow"><?php echo esc_html(ucfirst($page_type)); ?></p>
            <h1><?php echo wp_kses_post($hero_title); ?></h1>
            <?php if ($hero_subtitle) : ?>
            <p class="page-hero-sub"><?php echo esc_html($hero_subtitle); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- ── Content Section ────────────────────────────── -->
    <section class="<?php echo esc_attr('tjs-' . $page_type . '-section'); ?>" aria-label="<?php echo esc_attr(ucfirst($page_type) . ' content'); ?>">
        <div class="<?php echo esc_attr('tjs-' . $page_type . '-layout'); ?> container">
    <?php else : ?>
    <div class="container">
    <?php endif; ?>

        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class(); ?>>
                <?php the_content(); ?>
            </article>
        <?php endwhile; ?>

    <?php if ($page_type) : ?>
        </div>
    </section>
    <?php else : ?>
    </div>
    <?php endif; ?>
</main>

<?php get_footer();
