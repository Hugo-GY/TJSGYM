<?php
/**
 * Template Name: Gymnastics Class
 */
get_header();

$product = tjs_get_class_product('gymnastics-product');

$age_range = ($product && function_exists('get_field')) ? get_field('age_range', $product->get_id()) : '5+ Years';
$about_title = ($product && function_exists('get_field')) ? get_field('about_title', $product->get_id()) : '<em>Progressive</em> gymnastics for children aged 5+';
$about_content = ($product && function_exists('get_field')) ? get_field('about_content', $product->get_id()) : '<p>Our Gymnastics classes are for children aged 5 years. Sessions are built around floor and vault work, helping gymnasts develop confidence, technique, body control and strength in a structured but encouraging environment.</p><p>We use our own badge progression system, moving from Levels 7 to 1 and then on to Bronze, Silver, Gold, Platinum and Diamond awards.</p><p>We are limited with times and space as we accommodate a large age range after school. Children need to have attended a preschool class to be eligible for our Gymnastics classes. Anyone attending our Mini Gym classes in the Summer term who are starting Reception in the September will automatically be placed on our waiting list for a Beginners Gymnastics class. Spaces are allocated on a random name-out-of-the-hat basis, and all will be offered a space by the Summer term.</p><p>We also have a second waiting list for those who attend a TJ\'s class but leave before the Summer term before starting Reception. Spaces will only be offered to this list when everyone on the first list has been offered a space. WE THEREFORE SADLY CANNOT OFFER ANYONE A GYMNASTICS CLASS WHO HAS NOT ATTENDED OUR PRESCHOOL PROGRAMME.</p>';

$sessions = $product ? tjs_get_class_sessions($product, 20) : array();
$schedule_data_unavailable = empty($sessions);

$class_data = array(
    'name' => 'Gymnastics',
    'age' => $age_range ?: '5+ Years',
    'about_title' => $about_title ?: '<em>Progressive</em> gymnastics for children aged 5+',
    'about_content' => $about_content ?: '<p>Our Gymnastics classes are for children aged 5 years. Sessions are built around floor and vault work, helping gymnasts develop confidence, technique, body control and strength in a structured but encouraging environment.</p><p>We use our own badge progression system, moving from Levels 7 to 1 and then on to Bronze, Silver, Gold, Platinum and Diamond awards.</p><p>We are limited with times and space as we accommodate a large age range after school. Children need to have attended a preschool class to be eligible for our Gymnastics classes. Anyone attending our Mini Gym classes in the Summer term who are starting Reception in the September will automatically be placed on our waiting list for a Beginners Gymnastics class. Spaces are allocated on a random name-out-of-the-hat basis, and all will be offered a space by the Summer term.</p><p>We also have a second waiting list for those who attend a TJ\'s class but leave before the Summer term before starting Reception. Spaces will only be offered to this list when everyone on the first list has been offered a space. WE THEREFORE SADLY CANNOT OFFER ANYONE A GYMNASTICS CLASS WHO HAS NOT ATTENDED OUR PRESCHOOL PROGRAMME.</p>',
    'modifier' => 'gymnastics',
);

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

function tjs_format_term_dates_gymnastics($dates_field) {
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
    'dates' => tjs_format_term_dates_gymnastics(!empty($ct_dates) ? $ct_dates : "13 Apr – 21 May\n1 Jun – 16 Jul"),
    'halfterm' => !empty($ct_halfterm) ? $ct_halfterm : 'Half term: w/k 25 May · No class 4 May',
    'payment_due' => !empty($ct_payment_due) ? $ct_payment_due : 'Payment due by 12 March'
);

$upcoming_terms = array(
    array(
        'season' => !empty($nt1_season) ? $nt1_season : 'Winter 2026',
        'status' => !empty($nt1_status) ? $nt1_status : 'Next term',
        'weeks' => !empty($nt1_weeks) ? $nt1_weeks : '12 weeks',
        'dates' => tjs_format_term_dates_gymnastics(!empty($nt1_dates) ? $nt1_dates : "7 Sep – 15 Oct\n2 Nov – 10 Dec"),
        'halfterm' => !empty($nt1_halfterm) ? $nt1_halfterm : '2-week half term: w/k 19 October',
        'payment_due' => !empty($nt1_payment_due) ? $nt1_payment_due : 'Payment due by 26 June'
    ),
    array(
        'season' => !empty($nt2_season) ? $nt2_season : 'Spring 2027',
        'status' => !empty($nt2_status) ? $nt2_status : 'Planning ahead',
        'weeks' => !empty($nt2_weeks) ? $nt2_weeks : '11 weeks',
        'dates' => tjs_format_term_dates_gymnastics(!empty($nt2_dates) ? $nt2_dates : "4 Jan – 11 Feb\n22 Feb – 25 Mar"),
        'halfterm' => !empty($nt2_halfterm) ? $nt2_halfterm : 'Half term: w/k 15 February',
        'payment_due' => !empty($nt2_payment_due) ? $nt2_payment_due : 'Payment due by 27 November'
    )
);

$booking_page_url = home_url('/class-booking/');
$waitlist_url = home_url('/contact/');

$gallery_images = array(
    array('src' => 'gallery-1.jpg', 'alt' => 'TJ\'s gymnastics display — outdoor competition'),
    array('src' => 'gallery-2.jpg', 'alt' => 'TJ\'s gymnastics competition'),
    array('src' => 'gallery-3.jpg', 'alt' => 'TJ\'s gymnastics coaches at competition'),
    array('src' => 'gallery-4.jpg', 'alt' => 'TJ\'s gymnasts at competition'),
    array('src' => 'gallery-5.jpg', 'alt' => 'TJ\'s gymnastics display'),
    array('src' => 'gallery-6.jpg', 'alt' => 'TJ\'s gymnastics display'),
    array('src' => 'gallery-7.jpg', 'alt' => 'TJ\'s gymnastics display'),
    array('src' => 'gallery-8.jpg', 'alt' => 'TJ\'s gymnastics display')
);

$gallery_base_url = get_template_directory_uri() . '/assets/images/classes/gymnastics/';
?>

<div data-page-root="gymnastics">
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
                        <a href="#book" class="btn btn-magenta"><?php _e('View Class Times', 'tjs-gymnastics'); ?></a>
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
                    <?php echo wp_kses_post($class_data['about_content']); ?>
                </div>
            </div>
        </div>
    </section>

    <section class="cd-booking cd-booking--<?php echo esc_attr($class_data['modifier']); ?> section" id="book" aria-label="<?php _e('Gymnastics times and prices', 'tjs-gymnastics'); ?>">
        <div class="container">
            <div class="cd-booking-header">
                <span class="section-label"><?php _e('Book a Place', 'tjs-gymnastics'); ?></span>
                <h2><?php _e('Times, Prices and', 'tjs-gymnastics'); ?> <em><?php _e('Term Dates', 'tjs-gymnastics'); ?></em></h2>
                <p><?php _e('Current term sessions are shown below, with the next term dates underneath for planning ahead.', 'tjs-gymnastics'); ?></p>
            </div>

            <div class="cd-table-note cd-table-note--highlight">
                <p><strong><?php _e('British Gymnastics membership required.', 'tjs-gymnastics'); ?></strong> <?php _e('All Mini Gym and Gymnastics members need British Gymnastics membership. For more information, please visit', 'tjs-gymnastics'); ?> <a href="https://mybg.british-gymnastics.org/Account.mvc/SignIn" target="_blank" rel="noopener">https://mybg.british-gymnastics.org/Account.mvc/SignIn</a>.</p>
                <p><strong><?php _e('Watching Week:', 'tjs-gymnastics'); ?></strong> <?php _e('The final week of each term is our Watching Week, when parents and carers are invited into the hall.', 'tjs-gymnastics'); ?></p>
            </div>

            <?php if (!$schedule_data_unavailable): ?>
            <div class="cd-booking-table-wrap">
                <table class="cd-booking-table" aria-label="<?php echo esc_attr($current_term['season'] . ' ' . $class_data['name'] . ' sessions'); ?>">
                    <thead>
                        <tr>
                            <th><?php _e('Time', 'tjs-gymnastics'); ?></th>
                            <th><?php _e('Price', 'tjs-gymnastics'); ?></th>
                            <th><?php _e('Group', 'tjs-gymnastics'); ?></th>
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
                                <td><?php echo esc_html($session['day']); ?></td><td colspan="4"></td>
                            </tr>
                        <?php endif; ?>
                            <tr>
                                <td data-label="<?php _e('Time', 'tjs-gymnastics'); ?>"><?php echo esc_html($session['time']); ?></td>
                                <td data-label="<?php _e('Price', 'tjs-gymnastics'); ?>"><?php echo esc_html($session['price']); ?></td>
                                <td data-label="<?php _e('Group', 'tjs-gymnastics'); ?>"><?php echo esc_html(isset($session['group']) ? $session['group'] : ''); ?></td>
                                <td data-label="<?php _e('Availability', 'tjs-gymnastics'); ?>"><span class="cd-avail is-<?php echo esc_attr($session['status']); ?>"><?php echo esc_html($session['availability']); ?></span></td>
                                <td data-label="<?php _e('Book Now', 'tjs-gymnastics'); ?>">
                                    <?php if ($session['status'] !== 'full'): ?>
                                        <a href="<?php echo esc_url(add_query_arg('variation', $session['variation_id'], $booking_page_url)); ?>" class="btn btn-magenta btn-sm cd-book-btn"><?php _e('Book Now', 'tjs-gymnastics'); ?></a>
                                    <?php else: ?>
                                        <a href="<?php echo esc_url($waitlist_url); ?>" class="btn btn-magenta btn-sm cd-waitlist-btn"><?php _e('Join the Waiting List', 'tjs-gymnastics'); ?></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

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
                                    <span class="cd-booking-mobile-label"><?php _e('Group', 'tjs-gymnastics'); ?></span>
                                    <span class="cd-booking-mobile-value"><?php echo esc_html(isset($session['group']) ? $session['group'] : ''); ?></span>
                                </div>
                            </div>
                            <div class="cd-booking-mobile-actions">
                                <?php if ($session['status'] !== 'full'): ?>
                                    <a href="<?php echo esc_url(add_query_arg('variation', $session['variation_id'], $booking_page_url)); ?>" class="btn btn-magenta btn-sm cd-book-btn"><?php _e('Book Now', 'tjs-gymnastics'); ?></a>
                                <?php else: ?>
                                    <a href="<?php echo esc_url($waitlist_url); ?>" class="btn btn-magenta btn-sm cd-waitlist-btn"><?php _e('Join the Waiting List', 'tjs-gymnastics'); ?></a>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
                <?php tjs_render_schedule_unavailable_notice(); ?>
            <?php endif; ?>

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

    <?php $hide_terms = ($product && function_exists('get_field')) ? get_field('hide_terms', $product->get_id()) : false; ?>
    <?php if (!$hide_terms): ?>
    <section class="cd-terms-summary" aria-labelledby="cd-terms-summary-title">
        <div class="container">
            <article class="cd-terms-summary-card">
                <span class="section-label"><?php _e('Important Information', 'tjs-gymnastics'); ?></span>
                <h2 id="cd-terms-summary-title"><?php _e('Terms and Conditions on', 'tjs-gymnastics'); ?> <em><?php _e('Bookings', 'tjs-gymnastics'); ?></em></h2>
                <div class="cd-terms-summary-body">
                    <?php
                    $terms_content = ($product && function_exists('get_field')) ? get_field('terms_content', $product->get_id()) : '';
                    $plain_terms = wp_strip_all_tags(html_entity_decode((string) $terms_content, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                    $plain_terms = trim(preg_replace('/[\s\x{00A0}]+/u', '', $plain_terms));
                    if ($plain_terms !== ''):
                        echo wp_kses_post($terms_content);
                    else: ?>
                    <p><?php _e('Our classes are run on a termly basis. The classes are coached, structured and progressive classes, the equipment set ups and skills become more demanding the term progresses.', 'tjs-gymnastics'); ?></p>
                    <p><?php _e('We want to work with you and your child to give them the best possible experience at TJ\'s and we know that it can sometimes take a few weeks for the children to get to know us and what to expect. We also need to ensure the class numbers are limited. Therefore, we prefer that bookings are made for the term. If after few weeks you feel the class is not right for your child, we are happy to refund remaining classes.', 'tjs-gymnastics'); ?></p>
                    <p><?php _e('Trial classes are only available if we have spaces in a particular class. Only 2 trials per term per child can be booked.', 'tjs-gymnastics'); ?></p>
                    <p><?php _e('If you sign up to a trial class, consideration must be given to the fact there is a lot to see and do and, for some, the first class can be quite intense! It can take a few weeks for children to settle into the classes, the first class is not always the best guide for how your child will enjoy our classes.', 'tjs-gymnastics'); ?></p>
                    <p><?php _e('We cannot offer refunds or \'make up\' classes for those missed. However, in serious medical circumstances, we can make exceptions to this rule.', 'tjs-gymnastics'); ?></p>
                    <?php endif; ?>
                </div>
                <p class="cd-terms-summary-date"><?php _e('March 2026', 'tjs-gymnastics'); ?></p>
            </article>
        </div>
    </section>
    <?php endif; ?>

    <section class="competition-section section" aria-label="<?php _e('Competition and displays', 'tjs-gymnastics'); ?>">
        <div class="container">
            <div class="competition-inner">
                <div class="competition-content competition-content--full">
                    <span class="section-label"><?php _e('Going Further', 'tjs-gymnastics'); ?></span>
                    <h2><em><?php _e('Competition', 'tjs-gymnastics'); ?></em> &amp; <?php _e('Displays', 'tjs-gymnastics'); ?></h2>
                    <p><?php _e('For gymnasts who want to go further, we enter floor and vault and tumbling competitions throughout the year. These are open to children at appropriate levels in our Gymnastics programme and entirely optional — never a requirement.', 'tjs-gymnastics'); ?></p>
                    <ul class="competition-list">
                        <li><?php _e('Annual Spring end of term display in all Gymnastics classes', 'tjs-gymnastics'); ?></li>
                        <li><?php _e('Local competitions for those who want them', 'tjs-gymnastics'); ?></li>
                        <li><?php _e('Squad-level preparation for gymnasts pursuing higher levels', 'tjs-gymnastics'); ?></li>
                        <li><?php _e('Always optional — we celebrate participation at every level', 'tjs-gymnastics'); ?></li>
                    </ul>
                </div>
                <div class="comp-carousel" aria-label="<?php _e('Competition and display photos', 'tjs-gymnastics'); ?>" tabindex="0">
                    <div class="comp-carousel-main">
                        <?php foreach ($gallery_images as $index => $img): ?>
                            <img class="comp-carousel-img <?php echo $index === 0 ? 'is-active' : ''; ?>" src="<?php echo esc_url($gallery_base_url . $img['src']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>">
                        <?php endforeach; ?>
                        <button class="comp-carousel-btn comp-carousel-prev" aria-label="<?php _e('Previous photo', 'tjs-gymnastics'); ?>">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <button class="comp-carousel-btn comp-carousel-next" aria-label="<?php _e('Next photo', 'tjs-gymnastics'); ?>">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M7.5 5L12.5 10L7.5 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <span class="comp-carousel-counter" aria-live="polite">1 / <?php echo count($gallery_images); ?></span>
                    </div>
                    <div class="comp-carousel-thumbs" role="tablist" aria-label="<?php _e('Photo thumbnails', 'tjs-gymnastics'); ?>">
                        <?php foreach ($gallery_images as $index => $img): ?>
                            <button class="comp-thumb <?php echo $index === 0 ? 'is-active' : ''; ?>" data-index="<?php echo $index; ?>" role="tab" aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>" aria-label="<?php printf(__('Photo %d', 'tjs-gymnastics'), $index + 1); ?>"><img src="<?php echo esc_url($gallery_base_url . $img['src']); ?>" alt="" loading="lazy"></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<?php get_footer(); ?>
