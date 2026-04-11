<?php
/**
 * Front Page Template
 */
get_header();
?>

<!-- ── Hero ───────────────────────────────────────── -->
<section class="hero" aria-label="<?php _e('Welcome to TJ\'s Gymnastics Club', 'tjs-gymnastics'); ?>">

    <!-- Decorative blobs (CSS-only, no inline styles) -->
    <span class="deco hero-deco-1" aria-hidden="true"></span>
    <span class="deco hero-deco-2" aria-hidden="true"></span>

    <div class="hero-inner">

        <!-- Content -->
        <div class="hero-content">
            <h1><?php _e('Building<br><em>Confidence</em><br>Through Gymnastics', 'tjs-gymnastics'); ?></h1>

            <p class="hero-sub">
                <?php _e('Structured gymnastics classes for children from 6 months to 12+.
                Warm, encouraging coaches. British Gymnastics registered.
                Serving Wimbledon families since 1988.', 'tjs-gymnastics'); ?>
            </p>

            <div class="hero-ctas">
                <a href="<?php echo esc_url(home_url('/timetable')); ?>" class="btn btn-ghost-magenta"><?php _e('View Timetable', 'tjs-gymnastics'); ?></a>
                <a href="<?php echo esc_url(home_url('/classes')); ?>" class="btn btn-magenta"><?php _e('Book Now', 'tjs-gymnastics'); ?></a>
            </div>
        </div>

        <!-- Visual collage -->
        <div class="hero-visual">
            <span class="hero-blob" aria-hidden="true"></span>
            <span class="hero-blob-2" aria-hidden="true"></span>
            <div class="hero-collage" aria-label="<?php _e('Photos from TJ\'s Gymnastics Club', 'tjs-gymnastics'); ?>">
                <figure class="hero-collage-card hero-collage-card--main">
                    <img
                        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/hero-photo.jpg'); ?>"
                        alt="<?php _e('Coach supporting a young child during gymnastics practice', 'tjs-gymnastics'); ?>"
                        class="hero-collage-img hero-collage-img--main"
                        width="1200" height="1350"
                    >
                </figure>
                <figure class="hero-collage-card hero-collage-card--archive">
                    <img
                        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/hero-collage-lucy.jpg'); ?>"
                        alt="<?php _e('Lucy enjoying one of TJ\'s archive class moments', 'tjs-gymnastics'); ?>"
                        class="hero-collage-img hero-collage-img--archive"
                        width="1024" height="768"
                    >
                </figure>
                <figure class="hero-collage-card hero-collage-card--space">
                    <img
                        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/facilities.png'); ?>"
                        alt="<?php _e('The fully matted gymnastics hall at Raynes Park Sports Pavilion', 'tjs-gymnastics'); ?>"
                        class="hero-collage-img hero-collage-img--space"
                        width="580" height="435"
                    >
                </figure>
            </div>
        </div>

    </div>

</section>

<!-- ── Accreditation Trust Strip ─────────────────── -->
<section class="trust-section" aria-label="<?php _e('Our accreditations and credentials', 'tjs-gymnastics'); ?>">
    <div class="container">
        <div class="trust-strip-inner">

            <div class="trust-item">
                <div class="trust-visual">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/shared/british-gymnastics-logo.png'); ?>"
                         class="trust-logo-bg" alt="<?php _e('British Gymnastics', 'tjs-gymnastics'); ?>" loading="lazy">
                </div>
                <span class="trust-item-label"><?php _e('British Gymnastics', 'tjs-gymnastics'); ?></span>
                <span class="trust-item-sub"><?php _e('Registered Member', 'tjs-gymnastics'); ?></span>
            </div>

            <div class="trust-divider" aria-hidden="true"></div>

            <div class="trust-item">
                <div class="trust-visual">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/shared/sen-icon.png'); ?>" alt="" class="trust-icon-img" loading="lazy">
                </div>
                <span class="trust-item-label"><?php _e('SEN Inclusive', 'tjs-gymnastics'); ?></span>
                <span class="trust-item-sub"><?php _e('All abilities welcome', 'tjs-gymnastics'); ?></span>
            </div>

            <div class="trust-divider" aria-hidden="true"></div>

            <div class="trust-item">
                <div class="trust-visual">
                    <span class="trust-years-number">36+</span>
                </div>
                <span class="trust-item-label"><?php _e('Years Established', 'tjs-gymnastics'); ?></span>
                <span class="trust-item-sub"><?php _e('Founded in 1988', 'tjs-gymnastics'); ?></span>
            </div>

        </div>
    </div>
</section>

<!-- ── Classes ─────────────────────────────────────── -->
<section class="section" aria-label="<?php _e('Our classes', 'tjs-gymnastics'); ?>">
    <div class="container">

        <div class="classes-header">
            <span class="section-label"><?php _e('Our Programmes', 'tjs-gymnastics'); ?></span>
            <h2><?php _e('Classes for <em>Every</em> Stage', 'tjs-gymnastics'); ?></h2>
            <p class="classes-header-sub"><?php _e('From crawling tiddlers to confident gymnasts — we have a class designed for your child\'s development.', 'tjs-gymnastics'); ?></p>
        </div>

        <!-- Dynamic class cards grid — synced with /classes page -->
        <div class="class-cards-grid">
            <?php
            // Query WooCommerce products in Classes category (same as /classes page)
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

<!-- ── Facilities ──────────────────────────────────── -->
<section class="section" aria-label="<?php _e('Our facilities', 'tjs-gymnastics'); ?>">
    <div class="container">
        <div class="facilities-layout">

            <div class="facilities-gallery-wrap">
                <div class="comp-carousel facilities-carousel" aria-label="<?php _e('Photos of TJ\'s facilities', 'tjs-gymnastics'); ?>" tabindex="0">
                    <div class="comp-carousel-main">
                        <img class="comp-carousel-img is-active" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/facilities/2026-01-12.jpg'); ?>" alt="<?php _e('Children using the gymnastics hall at TJ\'s Gymnastics Club', 'tjs-gymnastics'); ?>" loading="lazy">
                        <img class="comp-carousel-img" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/facilities/2026-01-18.jpg'); ?>" alt="<?php _e('Children and coaches in the TJ\'s gymnastics space', 'tjs-gymnastics'); ?>" loading="lazy">
                        <img class="comp-carousel-img" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/facilities/2026-02-02.jpg'); ?>" alt="<?php _e('Children moving through equipment in the TJ\'s gymnastics hall', 'tjs-gymnastics'); ?>" loading="lazy">
                        <img class="comp-carousel-img" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/facilities/2026-02-23.jpg'); ?>" alt="<?php _e('Gymnastics equipment set up inside TJ\'s home venue', 'tjs-gymnastics'); ?>" loading="lazy">
                        <img class="comp-carousel-img" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/facilities/2026-03-16.jpg'); ?>" alt="<?php _e('Children taking part in class inside the TJ\'s gymnastics hall', 'tjs-gymnastics'); ?>" loading="lazy">
                        <button class="comp-carousel-btn comp-carousel-prev" aria-label="<?php _e('Previous photo', 'tjs-gymnastics'); ?>">&#8249;</button>
                        <button class="comp-carousel-btn comp-carousel-next" aria-label="<?php _e('Next photo', 'tjs-gymnastics'); ?>">&#8250;</button>
                        <span class="comp-carousel-counter" aria-live="polite">1 / 5</span>
                    </div>
                    <div class="comp-carousel-thumbs" role="tablist" aria-label="<?php _e('Facilities photo thumbnails', 'tjs-gymnastics'); ?>">
                        <button class="comp-thumb is-active" data-index="0" role="tab" aria-selected="true" aria-label="<?php _e('Photo 1', 'tjs-gymnastics'); ?>"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/facilities/2026-01-12.jpg'); ?>" alt="" loading="lazy"></button>
                        <button class="comp-thumb" data-index="1" role="tab" aria-selected="false" aria-label="<?php _e('Photo 2', 'tjs-gymnastics'); ?>"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/facilities/2026-01-18.jpg'); ?>" alt="" loading="lazy"></button>
                        <button class="comp-thumb" data-index="2" role="tab" aria-selected="false" aria-label="<?php _e('Photo 3', 'tjs-gymnastics'); ?>"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/facilities/2026-02-02.jpg'); ?>" alt="" loading="lazy"></button>
                        <button class="comp-thumb" data-index="3" role="tab" aria-selected="false" aria-label="<?php _e('Photo 4', 'tjs-gymnastics'); ?>"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/facilities/2026-02-23.jpg'); ?>" alt="" loading="lazy"></button>
                        <button class="comp-thumb" data-index="4" role="tab" aria-selected="false" aria-label="<?php _e('Photo 5', 'tjs-gymnastics'); ?>"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/facilities/2026-03-16.jpg'); ?>" alt="" loading="lazy"></button>
                    </div>
                </div>
            </div>

            <div class="facilities-content">
                <span class="section-label"><?php _e('Our Facilities', 'tjs-gymnastics'); ?></span>
                <h2><?php _e('A Space Made for <em>Movement</em>', 'tjs-gymnastics'); ?></h2>
                <p><?php _e('Our home at Raynes Park Sports Pavilion is a warm, safe and fully-equipped environment where children love to learn.', 'tjs-gymnastics'); ?></p>

                <div class="facilities-features">
                    <div class="facilities-feature card-feature">
                        <strong><?php _e('Fully Matted Hall', 'tjs-gymnastics'); ?></strong>
                    </div>
                    <div class="facilities-feature card-feature">
                        <strong><?php _e('Parent Waiting Area', 'tjs-gymnastics'); ?></strong>
                    </div>
                    <div class="facilities-feature card-feature">
                        <strong><?php _e('Extensive Grounds', 'tjs-gymnastics'); ?></strong>
                    </div>
                    <div class="facilities-feature card-feature">
                        <strong><?php _e('Free Parking', 'tjs-gymnastics'); ?></strong>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ── Location ────────────────────────────────────── -->
<section class="section location-section" aria-label="<?php _e('Find us', 'tjs-gymnastics'); ?>">
    <div class="container">
        <div class="location-layout">

            <div class="location-info">
                <span class="section-label"><?php _e('Find Us', 'tjs-gymnastics'); ?></span>
                <h2><?php _e('<em>Easy</em> to Reach in SW London', 'tjs-gymnastics'); ?></h2>
                <p><?php _e('Based at Raynes Park Sports Pavilion with free parking on site and good transport links from Wimbledon, Raynes Park, and Merton.', 'tjs-gymnastics'); ?></p>

                <div class="location-contact">
                    <address class="location-address">
                        <?php _e('Raynes Park Sports Pavilion<br>Taunton Avenue<br>London SW20 0BH', 'tjs-gymnastics'); ?>
                    </address>
                    <div class="location-links">
                        <a href="tel:01252702295">01252 702295</a>
                        <a href="tel:07885103080">07885 103080</a>
                        <a href="mailto:info@tjsgymclub.co.uk">info@tjsgymclub.co.uk</a>
                    </div>
                </div>
            </div>

            <div class="location-map-wrap">
                <iframe
                    src="https://www.google.com/maps?q=TJ%27s%20Gymnastics%20Club%2C%20SW20%200BH&output=embed"
                    title="<?php _e('Map showing TJ\'s Gymnastics Club, SW20 0BH', 'tjs-gymnastics'); ?>"
                    loading="lazy"
                    allowfullscreen
                ></iframe>
            </div>

        </div>
    </div>
</section>

<!-- ── Testimonials ────────────────────────────────── -->
<section class="section" aria-label="<?php _e('What parents say', 'tjs-gymnastics'); ?>">
    <div class="container">
        <div class="section-header">
            <span class="section-label"><?php _e('Parent Reviews', 'tjs-gymnastics'); ?></span>
            <h2><?php _e('What <em>Families</em> Say About Us', 'tjs-gymnastics'); ?></h2>
        </div>

        <!-- 3-equal-column grid -->
        <div class="testimonials-grid">

            <div class="testimonial-card card-accent">
                <div class="testimonial-stars" aria-label="<?php _e('5 out of 5 stars', 'tjs-gymnastics'); ?>">★★★★★</div>
                <p class="testimonial-text">"<?php _e('TJ\'s has been amazing for my SEN child. They were so understanding and made him feel completely included from day one. The coaches take time to know every single child — it\'s clear this isn\'t just a job for them.', 'tjs-gymnastics'); ?>"</p>
            </div>

            <div class="testimonial-card card-accent">
                <div class="testimonial-stars" aria-label="<?php _e('5 out of 5 stars', 'tjs-gymnastics'); ?>">★★★★★</div>
                <p class="testimonial-text">"<?php _e('My daughter has been coming since she was 18 months old and absolutely loves it. The coaches are so warm and encouraging — she\'s gained so much confidence.', 'tjs-gymnastics'); ?>"</p>
            </div>

            <div class="testimonial-card card-accent">
                <div class="testimonial-stars" aria-label="<?php _e('5 out of 5 stars', 'tjs-gymnastics'); ?>">★★★★★</div>
                <p class="testimonial-text">"<?php _e('We\'ve been members for 6 years. The progression from Mini Gym to Gymnastics is seamless — the team clearly know and care about every child.', 'tjs-gymnastics'); ?>"</p>
            </div>

        </div>
    </div>
</section>

<?php get_footer(); ?>
