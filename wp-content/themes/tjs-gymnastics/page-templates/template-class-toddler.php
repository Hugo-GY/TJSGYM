<?php
/**
 * Template Name: Toddler Gym Class
 */
get_header();

// Get product data
$product = tjs_get_class_product('toddler-gym-product');

// Get ACF fields or use defaults
$age_range = ($product && function_exists('get_field')) ? get_field('age_range', $product->get_id()) : '1–3 Years';
$about_title = ($product && function_exists('get_field')) ? get_field('about_title', $product->get_id()) : 'Action songs, circuits and <em>adventure</em> — together';
$about_lead = ($product && function_exists('get_field')) ? get_field('about_lead', $product->get_id()) : 'A parent-accompanied class for 1 to 3 year olds. Each session opens with action songs and a structured warm-up — teaching toddlers to listen, copy and join in before the equipment circuit begins.';
$about_content = ($product && function_exists('get_field')) ? get_field('about_content', $product->get_id()) : '<p>Coaches are on hand throughout, helping with climbing, encouraging safe and varied use of the equipment, and building a warm rapport with the children. Sessions close with calming songs to bring little ones together gently before the end of class.</p>';

// Get variations data
$sessions = $product ? tjs_get_class_sessions($product, 18) : array();

// Fallback sessions if no variations
if (empty($sessions)) {
    $sessions = array(
        array('day' => 'Tuesday', 'time' => '9:40 – 10:20', 'price' => '£143 / term', 'availability' => '3 / 18', 'status' => 'limited', 'variation_id' => 0),
        array('day' => 'Tuesday', 'time' => '10:30 – 11:10', 'price' => '£143 / term', 'availability' => '1 / 18', 'status' => 'limited', 'variation_id' => 0),
        array('day' => 'Wednesday', 'time' => '9:30 – 10:10', 'price' => '£143 / term', 'availability' => '1 / 18', 'status' => 'limited', 'variation_id' => 0),
        array('day' => 'Thursday', 'time' => '9:30 – 10:10', 'price' => '£143 / term', 'availability' => 'Full', 'status' => 'full', 'variation_id' => 0),
    );
}

$modifier = $product ? tjs_get_class_modifier($product->get_id()) : 'toddler';

$class_data = array(
    'name' => 'Toddler Gym',
    'age' => $age_range ?: '1–3 Years',
    'about_title' => $about_title ?: 'Action songs, circuits and <em>adventure</em> — together',
    'about_lead' => $about_lead ?: 'A parent-accompanied class for 1 to 3 year olds. Each session opens with action songs and a structured warm-up — teaching toddlers to listen, copy and join in before the equipment circuit begins. The gym layout changes every single week, so there\'s always something new to climb, balance on and explore.',
    'about_content' => $about_content ?: '<p>Coaches are on hand throughout, helping with climbing, encouraging safe and varied use of the equipment, and building a warm rapport with the children. Sessions close with calming songs to bring little ones together gently before the end of class.</p>',
    'modifier' => $modifier,
);

// Gallery images
$gallery_images = array(
    array('src' => 'gallery-1.jpg', 'alt' => 'Toddler Gym class photo 1'),
    array('src' => 'gallery-2.jpg', 'alt' => 'Toddler Gym class photo 2'),
    array('src' => 'gallery-3.jpg', 'alt' => 'Toddler Gym class photo 3'),
    array('src' => 'gallery-4.jpg', 'alt' => 'Toddler Gym class photo 4'),
    array('src' => 'gallery-5.jpg', 'alt' => 'Toddler Gym class photo 5'),
);

// Get term info from ACF or use defaults
$term_info = ($product && function_exists('get_field')) ? get_field('term_info', $product->get_id()) : array();

// Default term data if ACF not set
if (empty($term_info)) {
    $term_info = array(
        array(
            'term_season' => 'Summer 2026',
            'term_status' => 'Teaching now',
            'term_weeks' => '13 weeks',
            'term_dates' => array('13 Apr – 21 May', '1 Jun – 16 Jul'),
            'term_halfterm' => 'Half term: w/k 25 May · No class 4 May',
            'term_payment_due' => 'Payment due by 12 March'
        ),
        array(
            'term_season' => 'Winter 2026',
            'term_status' => 'Next term',
            'term_weeks' => '12 weeks',
            'term_dates' => array('7 Sep – 16 Oct', '2 Nov – 10 Dec'),
            'term_halfterm' => '2-week half term: w/k 19 October',
            'term_payment_due' => 'Payment due by 26 June'
        ),
        array(
            'term_season' => 'Spring 2027',
            'term_status' => 'Planning ahead',
            'term_weeks' => '11 weeks',
            'term_dates' => array('4 Jan – 11 Feb', '22 Feb – 25 Mar'),
            'term_halfterm' => 'Half term: w/k 15 February',
            'term_payment_due' => 'Payment due by 27 November'
        )
    );
}

// Format term data for display
$terms = array();
foreach ($term_info as $term) {
    $dates = isset($term['term_dates']) ? $term['term_dates'] : array();
    if (!is_array($dates)) {
        $dates = explode("\n", $dates);
    }
    $terms[] = array(
        'season' => isset($term['term_season']) ? $term['term_season'] : '',
        'status' => isset($term['term_status']) ? $term['term_status'] : '',
        'weeks' => isset($term['term_weeks']) ? $term['term_weeks'] : '',
        'dates' => $dates,
        'halfterm' => isset($term['term_halfterm']) ? $term['term_halfterm'] : '',
        'payment_due' => isset($term['term_payment_due']) ? $term['term_payment_due'] : ''
    );
}

$current_term = !empty($terms) ? $terms[0] : array(
    'season' => '', 'status' => '', 'weeks' => '', 'dates' => array(), 'halfterm' => '', 'payment_due' => ''
);
$upcoming_terms = count($terms) > 1 ? array_slice($terms, 1) : array();
?>

<div data-page-root="toddler-gym">
    <div class="cd-back-wrap">
        <div class="container">
            <a href="<?php echo esc_url(home_url('/classes/')); ?>" class="cd-back-btn">← Back to Classes</a>
        </div>
    </div>

    <section class="cd-hero" aria-label="<?php echo esc_attr($class_data['name']); ?>">
        <div class="container">
            <div class="cd-hero-card cd-hero-card--imageless cd-hero-card--tiddler card-accent">
                <div class="cd-hero-meta">
                    <span class="cd-hero-age"><?php echo esc_html($class_data['age']); ?></span>
                    <h1 class="cd-hero-title"><?php echo esc_html($class_data['name']); ?></h1>
                    <div class="cd-hero-actions">
                        <a href="#book" class="btn btn-magenta">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section cd-about cd-about--single" aria-label="<?php _e('About', 'tjs-gymnastics'); ?> <?php echo esc_attr($class_data['name']); ?>">
        <div class="container">
            <div class="cd-about-header cd-about-header--centered">
                <span class="section-label"><?php _e('About This Class', 'tjs-gymnastics'); ?></span>
                <h2><?php echo wp_kses_post($class_data['about_title']); ?></h2>
            </div>
            <div class="cd-about-inner">
                <div class="cd-about-content">
                    <p class="cd-about-lead"><?php echo esc_html($class_data['about_lead']); ?></p>
                    <?php echo wp_kses_post($class_data['about_content']); ?>
                </div>
            </div>
        </div>
    </section>

    <section class="cd-booking cd-booking--<?php echo esc_attr($class_data['modifier']); ?> section" id="book" aria-label="<?php _e('Book a place', 'tjs-gymnastics'); ?>">
        <div class="container">
            <div class="cd-booking-header">
                <span class="section-label"><?php _e('Book a Place', 'tjs-gymnastics'); ?></span>
                <h2><?php _e('Times, Prices and', 'tjs-gymnastics'); ?> <em><?php _e('Term Dates', 'tjs-gymnastics'); ?></em></h2>
                <p><?php _e('Current term sessions are shown below, with the next term dates underneath for planning ahead.', 'tjs-gymnastics'); ?></p>
            </div>

            <div class="cd-booking-table-wrap">
                <table class="cd-booking-table" aria-label="<?php echo esc_attr($current_term['season'] . ' ' . $class_data['name'] . ' sessions'); ?>">
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
                                        <a href="<?php echo esc_url(add_query_arg('variation', $session['variation_id'], home_url('/toddler-gym-booking/'))); ?>" class="btn btn-magenta btn-sm cd-book-btn"><?php _e('Book Now', 'tjs-gymnastics'); ?></a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm cd-waitlist-btn" disabled><?php _e('Fully Booked', 'tjs-gymnastics'); ?></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <section class="cd-booking-term-current" aria-label="<?php _e('Current term details', 'tjs-gymnastics'); ?>">
                <span class="section-label"><?php _e('Current Term', 'tjs-gymnastics'); ?></span>
                <article class="cd-term-card cd-term-card--static cd-term-card--current">
                    <div class="cd-term-top">
                        <span class="cd-term-season"><?php echo esc_html($current_term['season']); ?></span>
                        <span class="cd-term-status"><?php echo esc_html($current_term['status']); ?></span>
                    </div>
                    <hr class="cd-term-divider">
                    <div class="cd-term-schedule">
                        <span class="cd-term-weeks"><?php echo esc_html($current_term['weeks']); ?></span>
                        <?php foreach ($current_term['dates'] as $date): ?>
                            <span class="cd-term-daterange"><?php echo esc_html($date); ?></span>
                        <?php endforeach; ?>
                        <span class="cd-term-halfterm"><?php echo esc_html($current_term['halfterm']); ?></span>
                        <span class="cd-term-payment"><?php echo esc_html($current_term['payment_due']); ?></span>
                    </div>
                </article>
            </section>

            <section class="cd-booking-term-upcoming" aria-label="<?php _e('Upcoming term details', 'tjs-gymnastics'); ?>">
                <h2><em><?php _e('Next', 'tjs-gymnastics'); ?></em> <?php _e('Terms', 'tjs-gymnastics'); ?></h2>
                <div class="cd-booking-term-grid">
                    <?php foreach ($upcoming_terms as $term): ?>
                        <article class="cd-term-card cd-term-card--static cd-term-card--future">
                            <div class="cd-term-top">
                                <span class="cd-term-season"><?php echo esc_html($term['season']); ?></span>
                                <span class="cd-term-status"><?php echo esc_html($term['status']); ?></span>
                            </div>
                            <hr class="cd-term-divider">
                            <div class="cd-term-schedule">
                                <span class="cd-term-weeks"><?php echo esc_html($term['weeks']); ?></span>
                                <?php foreach ($term['dates'] as $date): ?>
                                    <span class="cd-term-daterange"><?php echo esc_html($date); ?></span>
                                <?php endforeach; ?>
                                <span class="cd-term-halfterm"><?php echo esc_html($term['halfterm']); ?></span>
                                <span class="cd-term-payment"><?php echo esc_html($term['payment_due']); ?></span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </section>

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

    <section class="cd-gallery section" aria-label="<?php _e('Toddler Gym photos', 'tjs-gymnastics'); ?>">
        <div class="container">
            <div class="section-header">
                <span class="section-label"><?php _e('Gallery', 'tjs-gymnastics'); ?></span>
                <h2><em><?php _e('Life', 'tjs-gymnastics'); ?></em> <?php _e('in the Gym', 'tjs-gymnastics'); ?></h2>
            </div>

            <div class="comp-carousel" aria-label="<?php _e('Toddler Gym photos', 'tjs-gymnastics'); ?>" tabindex="0">
                <div class="comp-carousel-main">
                    <?php 
                    $gallery_base_url = get_template_directory_uri() . '/assets/images/classes/toddler/';
                    foreach ($gallery_images as $index => $img): 
                    ?>
                        <img class="comp-carousel-img <?php echo $index === 0 ? 'is-active' : ''; ?>" src="<?php echo esc_url($gallery_base_url . $img['src']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy">
                    <?php endforeach; ?>
                    <button class="comp-carousel-btn comp-carousel-prev" aria-label="<?php _e('Previous photo', 'tjs-gymnastics'); ?>">&#8249;</button>
                    <button class="comp-carousel-btn comp-carousel-next" aria-label="<?php _e('Next photo', 'tjs-gymnastics'); ?>">&#8250;</button>
                    <span class="comp-carousel-counter" aria-live="polite">1 / <?php echo count($gallery_images); ?></span>
                </div>
                <div class="comp-carousel-thumbs" role="tablist" aria-label="<?php _e('Photo thumbnails', 'tjs-gymnastics'); ?>">
                    <?php foreach ($gallery_images as $index => $img): ?>
                        <button class="comp-thumb <?php echo $index === 0 ? 'is-active' : ''; ?>" data-index="<?php echo $index; ?>" role="tab" aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>" aria-label="<?php printf(__('Photo %d', 'tjs-gymnastics'), $index + 1); ?>">
                            <img src="<?php echo esc_url($gallery_base_url . $img['src']); ?>" alt="" loading="lazy">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>
