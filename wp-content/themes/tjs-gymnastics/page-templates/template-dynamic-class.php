<?php
/**
 * Template Name: Dynamic Class Page (Unified)
 *
 * Unified template for all class booking pages.
 * Replaces individual templates like tiddler-gym-booking and toddler-gym-booking.
 *
 * Usage:
 * 1. Create a WordPress Page with this template
 * 2. Access via: /your-page/?class=tiddler-gym
 * 3. Or: /your-page/?class=toddler-gym-product
 * 4. All data loads automatically from WooCommerce + ACF!
 *
 * Features:
 * - Dynamic product loading via ?class parameter
 * - Automatic fallback to default values
 * - Supports unlimited class types
 * - Maintains exact same layout and styling as original templates
 */
get_header();

$class_slug = isset($_GET['class']) ? sanitize_text_field($_GET['class']) : '';
$show_error = false;
$error_message = '';

if (empty($class_slug)) {
    $show_error = true;
    $error_message = 'No class specified. Please provide a class parameter (e.g., ?class=tiddler-gym)';
}

$class_config = tjs_get_class_config($class_slug);

if (!$class_config && !$show_error) {
    $show_error = true;
    $error_message = sprintf('Class "%s" not found. Please check the class parameter.', esc_html($class_slug));
}

if ($show_error):
?>
<div class="container" style="padding: 100px 20px; text-align: center;">
    <h1><?php _e('Class Not Found', 'tjs-gymnastics'); ?></h1>
    <p><?php echo esc_html($error_message); ?></p>
    <a href="<?php echo esc_url(home_url('/classes/')); ?>" class="btn btn-magenta"><?php _e('View All Classes', 'tjs-gymnastics'); ?></a>
</div>
<?php
    get_footer();
    return;
endif;

$product = tjs_get_class_product($class_slug);

$age_range = ($product && function_exists('get_field')) ? get_field('age_range', $product->get_id()) : $class_config['defaults']['age_range'];
$about_title = ($product && function_exists('get_field')) ? get_field('about_title', $product->get_id()) : $class_config['defaults']['about_title'];
$about_lead = ($product && function_exists('get_field')) ? get_field('about_lead', $product->get_id()) : $class_config['defaults']['about_lead'];
$about_content = ($product && function_exists('get_field')) ? get_field('about_content', $product->get_id()) : $class_config['defaults']['about_content'];

$max_slots = $class_config['settings']['max_slots'];
$pricing_type = isset($class_config['settings']['pricing_type']) ? $class_config['settings']['pricing_type'] : 'term';

$sessions = array();
if ($product) {
    if ($pricing_type === 'per_class') {
        $sessions = tjs_get_class_sessions($product, $max_slots, 'per_class');
    } else {
        $sessions = tjs_get_class_sessions($product, $max_slots);
    }
}

if (empty($sessions)) {
    $sessions = $class_config['defaults']['sessions'];
}

$modifier = $product ? tjs_get_class_modifier($product->get_id()) : $class_config['settings']['modifier'];

$class_data = array(
    'name' => $class_config['name'],
    'age' => $age_range ?: $class_config['defaults']['age_range'],
    'about_title' => $about_title ?: $class_config['defaults']['about_title'],
    'about_lead' => $about_lead ?: $class_config['defaults']['about_lead'],
    'about_content' => $about_content ?: $class_config['defaults']['about_content'],
    'modifier' => $modifier,
);

$gallery_images = $class_config['defaults']['gallery'];

$ct_season = ($product && function_exists('get_field')) ? get_field('ct_season', $product->get_id()) : '';
$ct_status = ($product && function_exists('get_field')) ? get_field('ct_status', $product->get_id()) : '';
$ct_weeks = ($product && function_exists('get_field')) ? get_field('ct_weeks', $product->get_id()) : '';
$ct_dates = ($product && function_exists('get_field')) ? get_field('ct_dates', $product->get_id()) : '';
$ct_halfterm = ($product && function_exists('get_field')) ? get_field('ct_halfterm', $product->get_id()) : '';
$ct_payment_due = ($product && function_exists('get_field')) ? get_field('ct_payment_due', $product->get_id()) : '';

$nt1_season = ($product && function_exists('get_field')) ? get_field('nt1_season', $product->get_id()) : '';
$nt1_status = ($product && function_exists('get_field')) ? get_field('nt1_status', $product->get_id()) : '';
$nt1_weeks = ($product && function_exists('get_field')) ? get_field('nt1_weeks', $product->get_id()) : '';
$nt1_dates = ($product && function_exists('get_field')) ? get_field('nt1_dates', $product->get_id()) : '';
$nt1_halfterm = ($product && function_exists('get_field')) ? get_field('nt1_halfterm', $product->get_id()) : '';
$nt1_payment_due = ($product && function_exists('get_field')) ? get_field('nt1_payment_due', $product->get_id()) : '';

$nt2_season = ($product && function_exists('get_field')) ? get_field('nt2_season', $product->get_id()) : '';
$nt2_status = ($product && function_exists('get_field')) ? get_field('nt2_status', $product->get_id()) : '';
$nt2_weeks = ($product && function_exists('get_field')) ? get_field('nt2_weeks', $product->get_id()) : '';
$nt2_dates = ($product && function_exists('get_field')) ? get_field('nt2_dates', $product->get_id()) : '';
$nt2_halfterm = ($product && function_exists('get_field')) ? get_field('nt2_halfterm', $product->get_id()) : '';
$nt2_payment_due = ($product && function_exists('get_field')) ? get_field('nt2_payment_due', $product->get_id()) : '';

function tjs_format_term_dates($dates_field) {
    if (empty($dates_field)) {
        return array();
    }
    if (is_array($dates_field)) {
        return $dates_field;
    }
    return explode("\n", trim($dates_field));
}

$current_term = array(
    'season' => !empty($ct_season) ? $ct_season : 'Summer 2026',
    'status' => !empty($ct_status) ? $ct_status : 'Teaching now',
    'weeks' => !empty($ct_weeks) ? $ct_weeks : '13 weeks',
    'dates' => tjs_format_term_dates(!empty($ct_dates) ? $ct_dates : "13 Apr – 21 May\n1 Jun – 16 Jul"),
    'halfterm' => !empty($ct_halfterm) ? $ct_halfterm : 'Half term: w/k 25 May · No class 4 May',
    'payment_due' => !empty($ct_payment_due) ? $ct_payment_due : 'Payment due by 12 March'
);

$upcoming_terms = array();

$upcoming_terms[] = array(
    'season' => !empty($nt1_season) ? $nt1_season : 'Winter 2026',
    'status' => !empty($nt1_status) ? $nt1_status : 'Next term',
    'weeks' => !empty($nt1_weeks) ? $nt1_weeks : '12 weeks',
    'dates' => tjs_format_term_dates(!empty($nt1_dates) ? $nt1_dates : "7 Sep – 16 Oct\n2 Nov – 10 Dec"),
    'halfterm' => !empty($nt1_halfterm) ? $nt1_halfterm : '2-week half term: w/k 19 October',
    'payment_due' => !empty($nt1_payment_due) ? $nt1_payment_due : 'Payment due by 26 June'
);

$upcoming_terms[] = array(
    'season' => !empty($nt2_season) ? $nt2_season : 'Spring 2027',
    'status' => !empty($nt2_status) ? $nt2_status : 'Planning ahead',
    'weeks' => !empty($nt2_weeks) ? $nt2_weeks : '11 weeks',
    'dates' => tjs_format_term_dates(!empty($nt2_dates) ? $nt2_dates : "4 Jan – 11 Feb\n22 Feb – 25 Mar"),
    'halfterm' => !empty($nt2_halfterm) ? $nt2_halfterm : 'Half term: w/k 15 February',
    'payment_due' => !empty($nt2_payment_due) ? $nt2_payment_due : 'Payment due by 27 November'
);

$current_page_url = get_permalink(get_the_ID());
?>

<div data-page-root="<?php echo esc_attr($class_slug); ?>">
    <div class="cd-back-wrap">
        <div class="container">
            <a href="<?php echo esc_url(home_url('/classes/')); ?>" class="cd-back-btn">← Back to Classes</a>
        </div>
    </div>

    <section class="cd-hero" aria-label="<?php echo esc_attr($class_data['name']); ?>">
        <div class="container">
            <div class="cd-hero-card cd-hero-card--imageless cd-hero-card--<?php echo esc_attr($class_data['modifier']); ?> card-accent">
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
                                        <a href="<?php echo esc_url(add_query_arg(array('class' => $class_slug, 'variation' => $session['variation_id']), $current_page_url)); ?>" class="btn btn-magenta btn-sm cd-book-btn"><?php _e('Book Now', 'tjs-gymnastics'); ?></a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm cd-waitlist-btn" disabled><?php _e('Fully Booked', 'tjs-gymnastics'); ?></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Mobile Cards -->
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
                                    <a href="<?php echo esc_url(add_query_arg(array('class' => $class_slug, 'variation' => $session['variation_id']), $current_page_url)); ?>" class="btn btn-magenta btn-sm cd-book-btn"><?php _e('Book Now', 'tjs-gymnastics'); ?></a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm cd-waitlist-btn" disabled><?php _e('Fully Booked', 'tjs-gymnastics'); ?></button>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
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

    <section class="cd-gallery section" aria-label="<?php echo esc_attr($class_data['name']); ?> photos">
        <div class="container">
            <div class="section-header">
                <span class="section-label"><?php _e('Gallery', 'tjs-gymnastics'); ?></span>
                <h2><em><?php _e('Life', 'tjs-gymnastics'); ?></em> <?php _e('in the Gym', 'tjs-gymnastics'); ?></h2>
            </div>

            <div class="comp-carousel" aria-label="<?php echo esc_attr($class_data['name']); ?> photos" tabindex="0">
                <div class="comp-carousel-main">
                    <?php
                    $gallery_base_url = get_template_directory_uri() . '/assets/images/classes/' . $class_slug . '/';
                    foreach ($gallery_images as $index => $img):
                    ?>
                        <img class="comp-carousel-img <?php echo $index === 0 ? 'is-active' : ''; ?>" src="<?php echo esc_url($gallery_base_url . $img['src']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy">
                    <?php endforeach; ?>
                </div>

                <div class="comp-carousel-thumbs">
                    <?php foreach ($gallery_images as $index => $img): ?>
                        <button class="comp-carousel-thumb <?php echo $index === 0 ? 'is-active' : ''; ?>" data-index="<?php echo $index; ?>" aria-label="<?php echo esc_attr($img['alt']); ?>">
                            <img src="<?php echo esc_url($gallery_base_url . $img['src']); ?>" alt="" loading="lazy">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>
