<?php
/**
 * Template Name: Dynamic Class Detail
 * 
 * This template dynamically loads class data from WooCommerce products.
 * The page slug should match the product slug for automatic lookup.
 */
get_header();

// Get the current page slug and try to find matching product
$page_slug = get_post_field('post_name', get_post());
$product = null;

// Try to find product by slug
if ($page_slug) {
    $product_query = new WP_Query(array(
        'post_type' => 'product',
        'name' => $page_slug,
        'posts_per_page' => 1
    ));
    
    if ($product_query->have_posts()) {
        $product_query->the_post();
        $product = wc_get_product(get_the_ID());
    }
    wp_reset_postdata();
}

// Fallback: try to find by similar slug
if (!$product) {
    $slugs = array(
        'tiddler-gym' => 'tiddler-gym',
        'toddler-gym' => 'toddler-gym-product',
        'mini-gym' => 'mini-gym-product',
        'gymnastics' => 'gymnastics-product'
    );
    
    $product_slug = isset($slugs[$page_slug]) ? $slugs[$page_slug] : $page_slug;
    
    $product_query = new WP_Query(array(
        'post_type' => 'product',
        'name' => $product_slug,
        'posts_per_page' => 1
    ));
    
    if ($product_query->have_posts()) {
        $product_query->the_post();
        $product = wc_get_product(get_the_ID());
    }
    wp_reset_postdata();
}

// If still no product, show error
if (!$product) {
    echo '<div class="container" style="padding: 100px 20px; text-align: center;">';
    echo '<h1>Class Not Found</h1>';
    echo '<p>Sorry, we couldn\'t find the class you\'re looking for.</p>';
    echo '<a href="' . esc_url(home_url('/classes/')) . '" class="btn btn-magenta">View All Classes</a>';
    echo '</div>';
    get_footer();
    exit;
}

$product_id = $product->get_id();
$product_name = $product->get_name();
$product_slug = $product->get_slug();

// Get ACF fields
$age_range = function_exists('get_field') ? get_field('age_range', $product_id) : '';
$class_type = function_exists('get_field') ? get_field('class_type', $product_id) : '';
$pay_type = function_exists('get_field') ? get_field('pay_type', $product_id) : '';
$about_title = function_exists('get_field') ? get_field('about_title', $product_id) : '';
$about_lead = function_exists('get_field') ? get_field('about_lead', $product_id) : '';
$about_content = function_exists('get_field') ? get_field('about_content', $product_id) : '';
$term_info = function_exists('get_field') ? get_field('term_info', $product_id) : array();

// Determine modifier based on product category
$modifier = 'gym';
$class_categories = wp_get_post_terms($product_id, 'product_cat');
foreach ($class_categories as $cat) {
    if ($cat->slug === 'tiddler-gym') $modifier = 'tiddler';
    if ($cat->slug === 'toddler-gym') $modifier = 'toddler';
    if ($cat->slug === 'mini-gym') $modifier = 'minigym';
    if ($cat->slug === 'gymnastics') $modifier = 'gym';
}

// Fallback values
if (empty($age_range)) {
    if ($modifier === 'tiddler') $age_range = '6–12 Months';
    if ($modifier === 'toddler') $age_range = '1–3 Years';
    if ($modifier === 'minigym') $age_range = '3–4½ Years';
    if ($modifier === 'gym') $age_range = '5+ Years';
}

if (empty($about_title)) {
    if ($modifier === 'tiddler') $about_title = '<em>Parent & Baby</em> movement, music and play';
    if ($modifier === 'toddler') $about_title = 'Action songs, circuits and <em>adventure</em> — together';
    if ($modifier === 'minigym') $about_title = 'Building independence, <em>confidence</em> and skill';
    if ($modifier === 'gym') $about_title = 'Progressive gymnastics for <em>every</em> level';
}

if (empty($about_lead)) {
    $about_lead = $product->get_short_description() ?: $product->get_description();
}

// Get variations for booking table
$sessions = array();
if ($product->is_type('variable')) {
    $variations = $product->get_available_variations();
    
    foreach ($variations as $variation_data) {
        $variation = wc_get_product($variation_data['variation_id']);
        if (!$variation) continue;
        
        $attributes = $variation->get_attributes();
        $day = isset($attributes['pa_class-day']) ? $attributes['pa_class-day'] : '';
        $time = isset($attributes['pa_time-slot']) ? $attributes['pa_time-slot'] : '';
        $group = isset($attributes['pa_group-level']) ? $attributes['pa_group-level'] : '';
        
        if (empty($day) || empty($time)) continue;
        
        $stock = $variation->get_stock_quantity();
        $max_stock = 20; // Default max
        if ($modifier === 'toddler') $max_stock = 18;
        
        if ($stock === null || $stock === '') {
            $stock = $max_stock;
        }
        
        $availability_text = $stock . ' / ' . $max_stock;
        $status = 'available';
        if ($stock <= 0) {
            $availability_text = 'Full';
            $status = 'full';
        } elseif ($stock <= 3) {
            $status = 'limited';
        }
        
        $sessions[] = array(
            'day' => $day,
            'time' => str_replace(array('–', '-'), ' – ', $time),
            'price' => '£' . $variation->get_price() . ($pay_type === 'per_class' ? ' / class' : ' / term'),
            'availability' => $availability_text,
            'status' => $status,
            'variation_id' => $variation_data['variation_id'],
            'group' => $group
        );
    }
    
    // Sort sessions by day and time
    $day_order = array('Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4);
    usort($sessions, function($a, $b) use ($day_order) {
        $day_a = isset($day_order[$a['day']]) ? $day_order[$a['day']] : 99;
        $day_b = isset($day_order[$b['day']]) ? $day_order[$b['day']] : 99;
        
        if ($day_a !== $day_b) {
            return $day_a - $day_b;
        }
        
        $time_a = strtotime(explode('–', $a['time'])[0]);
        $time_b = strtotime(explode('–', $b['time'])[0]);
        return $time_a - $time_b;
    });
}

// Default term info if ACF not set
if (empty($term_info)) {
    $term_info = array(
        array(
            'term_season' => 'Summer 2026',
            'term_status' => 'Teaching now',
            'term_weeks' => '13 weeks',
            'term_dates' => "13 Apr – 21 May\n1 Jun – 16 Jul",
            'term_halfterm' => 'Half term: w/k 25 May · No class 4 May',
            'term_payment_due' => 'Payment due by 12 March'
        ),
        array(
            'term_season' => 'Winter 2026',
            'term_status' => 'Next term',
            'term_weeks' => '12 weeks',
            'term_dates' => "7 Sep – 16 Oct\n2 Nov – 10 Dec",
            'term_halfterm' => '2-week half term: w/k 19 October',
            'term_payment_due' => 'Payment due by 26 June'
        ),
        array(
            'term_season' => 'Spring 2027',
            'term_status' => 'Planning ahead',
            'term_weeks' => '11 weeks',
            'term_dates' => "4 Jan – 11 Feb\n22 Feb – 25 Mar",
            'term_halfterm' => 'Half term: w/k 15 February',
            'term_payment_due' => 'Payment due by 27 November'
        )
    );
}

$current_term = isset($term_info[0]) ? $term_info[0] : array();
$upcoming_terms = array_slice($term_info, 1);

// Get booking page URL
$booking_slug = $page_slug . '-booking';
$booking_page = get_page_by_path($booking_slug);
$booking_url = $booking_page ? get_permalink($booking_page->ID) : '#';
?>

<div data-page-root="<?php echo esc_attr($page_slug); ?>">
    <div class="cd-back-wrap">
        <div class="container">
            <a href="<?php echo esc_url(home_url('/classes/')); ?>" class="cd-back-btn">← Back to Classes</a>
        </div>
    </div>

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
    <section class="cd-booking cd-booking--<?php echo esc_attr($modifier); ?> section" id="book" aria-label="<?php _e('Book a place', 'tjs-gymnastics'); ?>">
        <div class="container">
            <div class="cd-booking-header">
                <span class="section-label"><?php _e('Book a Place', 'tjs-gymnastics'); ?></span>
                <h2><?php _e('Times, Prices and', 'tjs-gymnastics'); ?> <em><?php _e('Term Dates', 'tjs-gymnastics'); ?></em></h2>
                <p><?php _e('Current term sessions are shown below, with the next term dates underneath for planning ahead.', 'tjs-gymnastics'); ?></p>
            </div>

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
                                <td><?php echo esc_html($session['day']); ?></td><td colspan="3"></td>
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
                                        <a href="#waitlist" class="btn btn-secondary btn-sm cd-waitlist-btn"><?php _e('Join Waitlist', 'tjs-gymnastics'); ?></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($current_term)): ?>
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
                        if (isset($current_term['term_dates'])) {
                            $dates = is_array($current_term['term_dates']) ? $current_term['term_dates'] : explode("\n", $current_term['term_dates']);
                            foreach ($dates as $date): 
                                if (trim($date)): ?>
                                <span class="cd-term-daterange"><?php echo esc_html(trim($date)); ?></span>
                        <?php endif; endforeach; } ?>
                        <span class="cd-term-halfterm"><?php echo esc_html(isset($current_term['term_halfterm']) ? $current_term['term_halfterm'] : ''); ?></span>
                        <span class="cd-term-payment"><?php echo esc_html(isset($current_term['term_payment_due']) ? $current_term['term_payment_due'] : ''); ?></span>
                    </div>
                </article>
            </section>
            <?php endif; ?>

            <?php if (!empty($upcoming_terms)): ?>
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
                                if (isset($term['term_dates'])) {
                                    $dates = is_array($term['term_dates']) ? $term['term_dates'] : explode("\n", $term['term_dates']);
                                    foreach ($dates as $date): 
                                        if (trim($date)): ?>
                                        <span class="cd-term-daterange"><?php echo esc_html(trim($date)); ?></span>
                                <?php endif; endforeach; } ?>
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
</div>

<?php get_footer(); ?>
