<?php
/**
 * Template Name: Dynamic Class Detail
 *
 * Enhanced version: Unified template for all class products.
 * Supports dynamic data loading from WooCommerce + ACF.
 *
 * Usage:
 * 1. Create a WordPress Page (e.g., /product/tiddler-gym)
 * 2. Set page slug to match product slug (or use mapping)
 * 3. Apply this template to the page
 * 4. All product data loads automatically!
 */
get_header();

$page_slug = get_post_field('post_name', get_post());
$product = null;

// Try direct slug match first
if ($page_slug) {
    $product = tjs_get_class_product($page_slug);
}

// Fallback: use predefined mappings
if (!$product) {
    $slug_mappings = array(
        'tiddler-gym' => 'tiddler-gym',
        'toddler-gym' => 'toddler-gym-product',
        'mini-gym' => 'mini-gym-product',
        'gymnastics' => 'gymnastics-product'
    );

    $mapped_slug = isset($slug_mappings[$page_slug]) ? $slug_mappings[$page_slug] : $page_slug;
    $product = tjs_get_class_product($mapped_slug);
}

// Final fallback: try ACF field on current page
if (!$product && function_exists('get_field')) {
    $acf_product_id = get_field('linked_product', get_the_ID());
    if ($acf_product_id) {
        $product = wc_get_product($acf_product_id);
    }
}

// Error state: no product found
if (!$product) {
    echo '<div class="container" style="padding: 100px 20px; text-align: center;">';
    echo '<h1>Class Not Found</h1>';
    echo '<p>Sorry, we couldn\'t find the class you\'re looking for.</p>';
    echo '<a href="' . esc_url(home_url('/classes/')) . '" class="btn btn-magenta">View All Classes</a>';
    echo '</div>';
    get_footer();
    exit;
}

// Product basics
$product_id = $product->get_id();
$product_name = $product->get_name();
$product_slug = $product->get_slug();

// ACF fields with defaults
$age_range = (function_exists('get_field') && get_field('age_range', $product_id)) ? get_field('age_range', $product_id) : '';
$about_title = (function_exists('get_field') && get_field('about_title', $product_id)) ? get_field('about_title', $product_id) : '';
$about_lead = (function_exists('get_field') && get_field('about_lead', $product_id)) ? get_field('about_lead', $product_id) : '';
$about_content = (function_exists('get_field') && get_field('about_content', $product_id)) ? get_field('about_content', $product_id) : '';
$pay_type = (function_exists('get_field') && get_field('pay_type', $product_id)) ? get_field('pay_type', $product_id) : '';

// Term info from ACF or use defaults
$term_info_raw = (function_exists('get_field')) ? get_field('term_info', $product_id) : array();
if (!is_array($term_info_raw)) {
    $term_info_raw = array();
}

// Determine modifier from category
$modifier = tjs_get_class_modifier($product_id);

// Fallback values based on modifier type
if (empty($age_range)) {
    $default_ages = array(
        'tiddler' => '6–12 Months',
        'toddler' => '1–3 Years',
        'minigym' => '3–4½ Years',
        'gym' => '5+ Years'
    );
    $age_range = isset($default_ages[$modifier]) ? $default_ages[$modifier] : 'All Ages';
}

if (empty($about_title)) {
    $default_titles = array(
        'tiddler' => '<em>Parent & Baby</em> movement, music and play',
        'toddler' => 'Action songs, circuits and <em>adventure</em> — together',
        'minigym' => 'Building independence, <em>confidence</em> and skill',
        'gym' => 'Progressive gymnastics for <em>every</em> level'
    );
    $about_title = isset($default_titles[$modifier]) ? $default_titles[$modifier] : $product_name;
}

if (empty($about_lead)) {
    $about_lead = $product->get_short_description() ?: $product->get_description();
}

// Session/booking config
$max_stock_map = array(
    'tiddler' => 10,
    'toddler' => 18,
    'minigym' => 10,
    'gym' => 20
);
$max_stock = isset($max_stock_map[$modifier]) ? $max_stock_map[$modifier] : 18;

$detected_pay_type = ($modifier === 'tiddler') ? 'per_class' : (($pay_type) ? $pay_type : 'per_term');

$sessions = $product->is_type('variable') ? tjs_get_class_sessions($product, $max_stock, $detected_pay_type) : array();

// Term info processing
if (empty($term_info_raw)) {
    $term_info_raw = tjs_get_default_terms();
}

$current_term = isset($term_info_raw[0]) ? $term_info_raw[0] : array();
$upcoming_terms = array_slice($term_info_raw, 1);

// Helper to format term dates
function tjs_format_term_dates_dynamic($dates_field) {
    if (empty($dates_field)) return array();
    if (is_array($dates_field)) return $dates_field;
    return explode("\n", trim($dates_field));
}

// Smart booking URL resolution - Use unified /class-booking/ page
$booking_url = home_url('/class-booking/');
if (function_exists('get_field')) {
    $custom_booking_url = get_field('booking_page_url', $product_id);
    if ($custom_booking_url) {
        $booking_url = $custom_booking_url;
    }
}

// Gallery setup - 3-tier fallback system
$gallery_images = array();

// Tier 1: ACF Gallery field (highest priority)
if (function_exists('get_field')) {
    $acf_gallery = get_field('gallery_images', $product_id);
    if (is_array($acf_gallery) && !empty($acf_gallery)) {
        foreach ($acf_gallery as $img) {
            $gallery_images[] = array(
                'src' => isset($img['url']) ? $img['url'] : '',
                'alt' => isset($img['alt']) ? $img['alt'] : $product_name . ' photo'
            );
        }
    }
}

// Tier 2: WooCommerce Product Gallery (if ACF not configured)
if (empty($gallery_images) && method_exists($product, 'get_gallery_image_ids')) {
    $wc_gallery_ids = $product->get_gallery_image_ids();
    if (!empty($wc_gallery_ids)) {
        foreach ($wc_gallery_ids as $attachment_id) {
            $img_url = wp_get_attachment_image_url($attachment_id, 'large');
            $img_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
            if ($img_url) {
                $gallery_images[] = array(
                    'src' => $img_url,
                    'alt' => $img_alt ?: $product_name . ' photo'
                );
            }
        }
    }
}

// Tier 3: Default theme images (fallback)
if (empty($gallery_images)) {
    $gallery_base = get_template_directory_uri() . '/assets/images/classes/' . $modifier . '/';
    $default_gallery_count = 5;
    for ($i = 1; $i <= $default_gallery_count; $i++) {
        $gallery_images[] = array(
            'src' => $gallery_base . 'gallery-' . $i . '.jpg',
            'alt' => $product_name . ' photo ' . $i
        );
    }
}
?>

<div data-page-root="<?php echo esc_attr($page_slug); ?>">
    <!-- Back navigation -->
    <div class="cd-back-wrap">
        <div class="container">
            <a href="<?php echo esc_url(home_url('/classes/')); ?>" class="cd-back-btn">← Back to Classes</a>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="cd-hero" aria-label="<?php echo esc_attr($product_name); ?>">
        <div class="container">
            <div class="cd-hero-card cd-hero-card--imageless cd-hero-card--<?php echo esc_attr($modifier); ?> card-accent">
                <div class="cd-hero-meta">
                    <span class="cd-hero-age"><?php echo esc_html($age_range); ?></span>
                    <h1 class="cd-hero-title"><?php echo esc_html($product_name); ?></h1>
                    <div class="cd-hero-actions">
                        <a href="#book" class="btn btn-magenta">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section cd-about cd-about--single" aria-label="<?php _e('About', 'tjs-gymnastics'); ?> <?php echo esc_attr($product_name); ?>">
        <div class="container">
            <div class="cd-about-header cd-about-header--centered">
                <span class="section-label"><?php _e('About This Class', 'tjs-gymnastics'); ?></span>
                <h2><?php echo wp_kses_post($about_title); ?></h2>
            </div>
            <div class="cd-about-inner">
                <div class="cd-about-content">
                    <p class="cd-about-lead"><?php echo esc_html($about_lead); ?></p>
                    <?php if ($about_content): ?>
                        <?php echo wp_kses_post($about_content); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php if (!empty($sessions)): ?>
    <!-- Booking Section -->
    <section class="cd-booking cd-booking--<?php echo esc_attr($modifier); ?> section" id="book" aria-label="<?php _e('Book a place', 'tjs-gymnastics'); ?>">
        <div class="container">
            <div class="cd-booking-header">
                <span class="section-label"><?php _e('Book a Place', 'tjs-gymnastics'); ?></span>
                <h2><?php _e('Times, Prices and', 'tjs-gymnastics'); ?> <em><?php _e('Term Dates', 'tjs-gymnastics'); ?></em></h2>
                <p><?php _e('Current term sessions are shown below, with the next term dates underneath for planning ahead.', 'tjs-gymnastics'); ?></p>
            </div>

            <!-- Desktop Table View -->
            <div class="cd-booking-table-wrap">
                <table class="cd-booking-table" aria-label="<?php echo esc_attr((isset($current_term['term_season']) ? $current_term['term_season'] : 'Current') . ' ' . $product_name . ' sessions'); ?>">
                    <thead>
                        <tr>
                            <th><?php _e('Time', 'tjs-gymnastics'); ?></th>
                            <th><?php _e('Price', 'tjs-gymnastics'); ?></th>
                            <th><?php _e('Availability', 'tjs-gymnastics'); ?></th>
                            <th><?php _e('Book Now', 'tjs-gymnastics'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $current_day = '';
                        foreach ($sessions as $session):
                            if ($current_day !== $session['day']):
                                $current_day = $session['day'];
                        ?>
                            <tr class="cd-day-group">
                                <td><?php echo esc_html($session['day']); ?><td colspan="3"></td></td>
                            </tr>
                        <?php endif; ?>
                            <tr>
                                <td data-label="<?php _e('Time', 'tjs-gymnastics'); ?>"><?php echo esc_html($session['time']); ?></td>
                                <td data-label="<?php _e('Price', 'tjs-gymnastics'); ?>"><?php echo esc_html($session['price']); ?></td>
                                <td data-label="<?php _e('Availability', 'tjs-gymnastics'); ?>"><span class="cd-avail is-<?php echo esc_attr($session['status']); ?>"><?php echo esc_html($session['availability']); ?></span></td>
                                <td data-label="<?php _e('Book Now', 'tjs-gymnastics'); ?>">
                                    <?php if ($session['status'] !== 'full'): ?>
                                        <a href="<?php echo esc_url(add_query_arg('variation', $session['variation_id'], $booking_url)); ?>" class="btn btn-magenta btn-sm cd-book-btn"><?php _e('Book Now', 'tjs-gymnastics'); ?></a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm cd-waitlist-btn" disabled><?php _e('Fully Booked', 'tjs-gymnastics'); ?></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Mobile Cards View -->
                <div class="cd-booking-cards-mobile" aria-hidden="true">
                    <?php
                    $current_day = '';
                    foreach ($sessions as $session):
                        if ($current_day !== $session['day']):
                            $current_day = $session['day'];
                    ?>
                        <p class="cd-booking-mobile-day"><?php echo esc_html($session['day']); ?></p>
                    <?php endif; ?>
                        <article class="cd-booking-mobile-card">
                            <div class="cd-booking-mobile-stats">
                                <div class="cd-booking-mobile-stat">
                                    <span class="cd-booking-mobile-label"><?php _e('Time', 'tjs-gymnastics'); ?></span>
                                    <span class="cd-booking-mobile-value"><?php echo esc_html($session['time']); ?></span>
                                </div>
                                <div class="cd-booking-mobile-stat">
                                    <span class="cd-booking-mobile-label"><?php _e('Price', 'tjs-gymnastics'); ?></span>
                                    <span class="cd-booking-mobile-value"><?php echo esc_html($session['price']); ?></span>
                                </div>
                                <div class="cd-booking-mobile-stat">
                                    <span class="cd-booking-mobile-label"><?php _e('Availability', 'tjs-gymnastics'); ?></span>
                                    <span class="cd-booking-mobile-value"><?php echo esc_html($session['availability']); ?></span>
                                </div>
                            </div>
                            <div class="cd-booking-mobile-actions">
                                <?php if ($session['status'] !== 'full'): ?>
                                    <a href="<?php echo esc_url(add_query_arg('variation', $session['variation_id'], $booking_url)); ?>" class="btn btn-magenta btn-sm cd-book-btn"><?php _e('Book Now', 'tjs-gymnastics'); ?></a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm cd-waitlist-btn" disabled><?php _e('Fully Booked', 'tjs-gymnastics'); ?></button>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (!empty($current_term)): ?>
            <!-- Current Term Info -->
            <section class="cd-booking-term-current" aria-label="<?php _e('Current term details', 'tjs-gymnastics'); ?>">
                <span class="section-label"><?php _e('Current Term', 'tjs-gymnastics'); ?></span>
                <article class="cd-term-card cd-term-card--static cd-term-card--current">
                    <div class="cd-term-top">
                        <span class="cd-term-season"><?php echo esc_html(isset($current_term['term_season']) ? $current_term['term_season'] : ''); ?></span>
                        <span class="cd-term-status"><?php echo esc_html(isset($current_term['term_status']) ? $current_term['term_status'] : ''); ?></span>
                    </div>
                    <hr class="cd-term-divider">
                    <div class="cd-term-schedule">
                        <span class="cd-term-weeks"><?php echo esc_html(isset($current_term['term_weeks']) ? $current_term['term_weeks'] : ''); ?></span>
                        <?php
                        if (isset($current_term['term_dates'])):
                            $dates = tjs_format_term_dates_dynamic($current_term['term_dates']);
                            foreach ($dates as $date):
                                if (trim($date)):
                        ?>
                            <span class="cd-term-daterange"><?php echo esc_html(trim($date)); ?></span>
                        <?php
                                endif;
                            endforeach;
                        endif;
                        ?>
                        <span class="cd-term-halfterm"><?php echo esc_html(isset($current_term['term_halfterm']) ? $current_term['term_halfterm'] : ''); ?></span>
                        <span class="cd-term-payment"><?php echo esc_html(isset($current_term['term_payment_due']) ? $current_term['term_payment_due'] : ''); ?></span>
                    </div>
                </article>
            </section>
            <?php endif; ?>

            <?php if (!empty($upcoming_terms)): ?>
            <!-- Upcoming Terms -->
            <section class="cd-booking-term-upcoming" aria-label="<?php _e('Upcoming term details', 'tjs-gymnastics'); ?>">
                <h2><em><?php _e('Next', 'tjs-gymnastics'); ?></em> <?php _e('Terms', 'tjs-gymnastics'); ?></h2>
                <div class="cd-booking-term-grid">
                    <?php foreach ($upcoming_terms as $term): ?>
                        <article class="cd-term-card cd-term-card--static cd-term-card--future">
                            <div class="cd-term-top">
                                <span class="cd-term-season"><?php echo esc_html(isset($term['term_season']) ? $term['term_season'] : ''); ?></span>
                                <span class="cd-term-status"><?php echo esc_html(isset($term['term_status']) ? $term['term_status'] : ''); ?></span>
                            </div>
                            <hr class="cd-term-divider">
                            <div class="cd-term-schedule">
                                <span class="cd-term-weeks"><?php echo esc_html(isset($term['term_weeks']) ? $term['term_weeks'] : ''); ?></span>
                                <?php
                                if (isset($term['term_dates'])):
                                    $dates = tjs_format_term_dates_dynamic($term['term_dates']);
                                    foreach ($dates as $date):
                                        if (trim($date)):
                                ?>
                                    <span class="cd-term-daterange"><?php echo esc_html(trim($date)); ?></span>
                                <?php
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                                <span class="cd-term-halfterm"><?php echo esc_html(isset($term['term_halfterm']) ? $term['term_halfterm'] : ''); ?></span>
                                <span class="cd-term-payment"><?php echo esc_html(isset($term['term_payment_due']) ? $term['term_payment_due'] : ''); ?></span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Terms & Conditions Summary -->
    <section class="cd-terms-summary" aria-labelledby="cd-terms-summary-title">
        <div class="container">
            <article class="cd-terms-summary-card">
                <span class="section-label"><?php _e('Important Information', 'tjs-gymnastics'); ?></span>
                <h2 id="cd-terms-summary-title"><?php _e('Terms and Conditions on', 'tjs-gymnastics'); ?> <em><?php _e('Bookings', 'tjs-gymnastics'); ?></em></h2>
                <div class="cd-terms-summary-body">
                    <p><?php _e('Our classes are run on a termly basis. The classes are coached, structured and progressive classes, the equipment set ups and skills become more demanding the term progresses.', 'tjs-gymnastics'); ?></p>
                    <p><?php _e('We want to work with you and your child to give them the best possible experience at TJ\'s and we know that it can sometimes take a few weeks for the children to get to know us and what to expect. We also need to ensure the class numbers are limited. Therefore, we prefer that bookings are made for the term. If after few weeks you feel the class is not right for your child, we are happy to refund remaining classes.', 'tjs-gymnastics'); ?></p>
                    <p><?php _e('Trial classes are only available if we have spaces in a particular class. Only 2 trials per term per child can be booked.', 'tjs-gymnastics'); ?></p>
                    <p><?php _e('If you sign up to a trial class, consideration must be given to the fact there is a lot to see and do and, for some, the first class can be quite intense! It can take a few weeks for children to settle into the classes, the first class is not always the best guide for how your child will enjoy our classes.', 'tjs-gymnastics'); ?></p>
                    <p><?php _e('We cannot offer refunds or \'make up\' classes for those missed. However, in serious medical circumstances, we can make exceptions to this rule.', 'tjs-gymnastics'); ?></p>
                </div>
                <p class="cd-terms-summary-date"><?php _e('March 2026', 'tjs-gymnastics'); ?></p>
            </article>
        </div>
    </section>

    <?php if (!empty($gallery_images)): ?>
    <!-- Photo Gallery -->
    <section class="cd-gallery section" aria-label="<?php echo esc_attr($product_name); ?> photos">
        <div class="container">
            <div class="section-header">
                <span class="section-label"><?php _e('Gallery', 'tjs-gymnastics'); ?></span>
                <h2><em><?php _e('Life', 'tjs-gymnastics'); ?></em> <?php _e('in the Gym', 'tjs-gymnastics'); ?></h2>
            </div>

            <div class="comp-carousel" aria-label="<?php echo esc_attr($product_name); ?> photos" tabindex="0">
                <div class="comp-carousel-main">
                    <?php foreach ($gallery_images as $index => $img): ?>
                        <img class="comp-carousel-img <?php echo $index === 0 ? 'is-active' : ''; ?>"
                             src="<?php echo esc_url($img['src']); ?>"
                             alt="<?php echo esc_attr($img['alt']); ?>"
                             loading="lazy">
                    <?php endforeach; ?>
                    <button class="comp-carousel-btn comp-carousel-prev" aria-label="Previous photo">&#8249;</button>
                    <button class="comp-carousel-btn comp-carousel-next" aria-label="Next photo">&#8250;</button>
                    <span class="comp-carousel-counter" aria-live="polite">1 / <?php echo count($gallery_images); ?></span>
                </div>
                <div class="comp-carousel-thumbs" role="tablist" aria-label="Photo thumbnails">
                    <?php foreach ($gallery_images as $index => $img): ?>
                        <button class="comp-thumb <?php echo $index === 0 ? 'is-active' : ''; ?>"
                                data-index="<?php echo $index; ?>"
                                role="tab"
                                aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                                aria-label="<?php echo esc_attr(sprintf(__('Photo %d', 'tjs-gymnastics'), $index + 1)); ?>">
                            <img src="<?php echo esc_url($img['src']); ?>" alt="" loading="lazy">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
