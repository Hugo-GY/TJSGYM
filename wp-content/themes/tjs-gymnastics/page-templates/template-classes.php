<?php
/**
 * Template Name: Classes Page
 */
get_header();

$hero_eyebrow = get_field('hero_eyebrow') ?: 'Our Programmes';
$hero_title = get_field('hero_title') ?: 'Classes for <em>Every</em> Stage';
$hero_subtitle = get_field('hero_subtitle') ?: 'From Babies and Crawling Toddlers to Confident Walkers — we have a structured, fun programme designed for every stage of your child\'s development.';
?>

<!-- ── Page Hero ──────────────────────────────────── -->
<section class="page-hero" aria-label="<?php _e('Classes overview', 'tjs-gymnastics'); ?>">
    <div class="container">
        <p class="page-hero-eyebrow"><?php echo esc_html($hero_eyebrow); ?></p>
        <h1><?php echo wp_kses_post($hero_title); ?></h1>
        <p class="page-hero-sub"><?php echo esc_html($hero_subtitle); ?></p>
    </div>
</section>

<!-- ── Class Cards ─────────────────────────────────── -->
<section class="class-cards-section" aria-label="<?php _e('All classes', 'tjs-gymnastics'); ?>">
    <div class="container">
        <div class="class-cards-grid">
            <?php
            // Query WooCommerce products in Classes category
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => 'classes',
                        'include_children' => true
                    )
                ),
                'orderby' => 'menu_order',
                'order' => 'ASC'
            );
            
            $class_products = new WP_Query($args);
            
            if ($class_products->have_posts()):
                while ($class_products->have_posts()): $class_products->the_post();
                    $product = wc_get_product(get_the_ID());
                    if (!$product) continue;
                    
                    $class_name = $product->get_name();
                    $class_slug = $product->get_slug();
                    $description = $product->get_short_description() ?: $product->get_description();
                    
                    // Map product slug to class detail page URL
                    $class_page_map = array(
                        'tiddler-gym' => '/tiddler-gym/',
                        'toddler-gym-product' => '/toddler-gym/',
                        'mini-gym-product' => '/mini-gym/',
                        'gymnastics-product' => '/gymnastics/',
                    );
                    
                    if (isset($class_page_map[$class_slug])) {
                        $button_link = home_url($class_page_map[$class_slug]);
                    } else {
                        $button_link = get_permalink();
                    }
                    
                    $image = $product->get_image_id() ? wp_get_attachment_image_src($product->get_image_id(), 'full') : null;
                    
                    // Get ACF fields if available
                    $age_range = function_exists('get_field') ? get_field('age_range', get_the_ID()) : '';
                    $class_type = function_exists('get_field') ? get_field('class_type', get_the_ID()) : '';
                    $pay_type = function_exists('get_field') ? get_field('pay_type', get_the_ID()) : '';
                    
                    // Determine modifier based on class type or category
                    $modifier = 'gym';
                    $class_categories = wp_get_post_terms(get_the_ID(), 'product_cat');
                    foreach ($class_categories as $cat) {
                        if ($cat->slug === 'tiddler-gym') $modifier = 'tiddler';
                        if ($cat->slug === 'toddler-gym') $modifier = 'toddler';
                        if ($cat->slug === 'mini-gym') $modifier = 'minigym';
                        if ($cat->slug === 'gymnastics') $modifier = 'gym';
                    }
                    
                    // Get pricing info
                    if ($product->is_type('variable')) {
                        $prices = $product->get_variation_prices();
                        $min_price = !empty($prices['price']) ? min($prices['price']) : 0;
                        $max_price = !empty($prices['price']) ? max($prices['price']) : 0;
                        
                        if ($pay_type === 'per_class') {
                            $price_display = '£' . $min_price;
                            $price_label = 'per class';
                            $price_note = 'Pay as you go';
                        } else {
                            if ($min_price == $max_price) {
                                $price_display = '£' . $min_price;
                            } else {
                                $price_display = 'From £' . $min_price;
                            }
                            $price_label = 'per term';
                            $price_note = '';
                        }
                    } else {
                        $price_display = $product->get_price_html();
                        $price_label = '';
                        $price_note = '';
                    }
                    
                    // Default image mapping
                    $image_file = 'class-card-gymnastics.png';
                    if ($modifier === 'tiddler') $image_file = 'class-card-tiddler.png';
                    if ($modifier === 'toddler') $image_file = 'class-card-toddler.png';
                    if ($modifier === 'minigym') $image_file = 'class-card-mini-gym.png';
                    
                    // Fallback age ranges if ACF not set
                    if (empty($age_range)) {
                        if ($modifier === 'tiddler') $age_range = '6–12 Months';
                        if ($modifier === 'toddler') $age_range = '1–3 Years';
                        if ($modifier === 'minigym') $age_range = '3–4½ Years';
                        if ($modifier === 'gym') $age_range = '5+ Years';
                    }
            ?>
                <article class="class-card class-card--<?php echo esc_attr($modifier); ?>">
                    <div class="class-card-img-wrap" aria-hidden="true">
                        <?php if ($image): ?>
                            <img src="<?php echo esc_url($image[0]); ?>" alt="" class="class-card-img" loading="lazy">
                        <?php else: ?>
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/' . $image_file); ?>" alt="" class="class-card-img" loading="lazy">
                        <?php endif; ?>
                        <span class="age-pill-overlay"><?php echo esc_html($age_range); ?></span>
                    </div>
                    <div class="class-card-body">
                        <h3><?php echo esc_html($class_name); ?></h3>
                        <p class="class-card-desc"><?php echo esc_html(wp_trim_words($description, 30)); ?></p>
                        <div class="class-card-cta">
                            <div class="class-pricing">
                                <span class="class-pricing-amount"><?php echo wp_kses_post($price_display); ?></span>
                                <div class="class-pricing-meta">
                                    <span class="class-pricing-label"><?php echo esc_html($price_label); ?></span>
                                    <span class="class-pricing-note"><?php echo esc_html($price_note); ?></span>
                                </div>
                            </div>
                            <a href="<?php echo esc_url($button_link); ?>" class="btn btn-magenta btn-sm">Details & Book</a>
                        </div>
                    </div>
                </article>
            <?php 
                endwhile;
                wp_reset_postdata();
            else:
                echo '<p>No classes found.</p>';
            endif;
            ?>
        </div>
    </div>
</section>

<?php
// Terms and Conditions Section
$terms_title = get_field('terms_title') ?: 'Terms and Conditions on <em>Bookings</em>';
$terms_content = get_field('terms_content');
$terms_date = get_field('terms_date') ?: 'March 2026';
?>

<section class="terms-bookings-section" aria-labelledby="terms-bookings-title">
    <div class="container">
        <article class="terms-bookings-card">
            <span class="section-label"><?php _e('Important Information', 'tjs-gymnastics'); ?></span>
            <h2 id="terms-bookings-title"><?php echo wp_kses_post($terms_title); ?></h2>
            <div class="terms-bookings-body">
                <?php if ($terms_content): ?>
                    <?php echo wp_kses_post($terms_content); ?>
                <?php else: ?>
                    <p>Our classes are run on a termly basis. The classes are coached, structured and progressive classes, the equipment set ups and skills become more demanding the term progresses.</p>
                    <p>We want to work with you and your child to give them the best possible experience at TJ's and we know that it can sometimes take a few weeks for the children to get to know us and what to expect. We also need to ensure the class numbers are limited. Therefore, we prefer that bookings are made for the term. If after few weeks you feel the class is not right for your child, we are happy to refund remaining classes.</p>
                    <p>Trial classes are only available if we have spaces in a particular class. Only 2 trials per term per child can be booked.</p>
                    <p>If you sign up to a trial class, consideration must be given to the fact there is a lot to see and do and, for some, the first class can be quite intense! It can take a few weeks for children to settle into the classes, the first class is not always the best guide for how your child will enjoy our classes.</p>
                    <p>We cannot offer refunds or 'make up' classes for those missed. However, in serious medical circumstances, we can make exceptions to this rule.</p>
                <?php endif; ?>
            </div>
            <p class="terms-bookings-date"><?php echo esc_html($terms_date); ?></p>
        </article>
    </div>
</section>

<?php get_footer(); ?>
