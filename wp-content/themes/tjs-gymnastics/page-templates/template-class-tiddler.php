<?php
/**
 * Template Name: Tiddler Gym Class
 */
get_header();

$class_data = array(
    'name' => 'Tiddler Gym',
    'age' => '6–12 Months',
    'about_title' => '<em>Parent & Baby</em> movement, music and play',
    'about_lead' => 'Tiddler Gym is designed to offer a fun, safe environment for parent or carer and baby — encouraging physical play and interaction through music, rolling, rocking, crawling and balancing. The equipment is carefully chosen for the Tiddler age range: tactile, bright and engaging for young explorers.',
    'about_content' => '<p>The circuit changes every single week, so there\'s always something fresh to discover. It\'s also a lovely chance for parents and carers to relax, have a chat, and watch their little ones thrive.</p>',
    'modifier' => 'tiddler',
);

$sessions = array(
    array('day' => 'Thursday', 'time' => '10:30 – 11:10', 'price' => '£10 / class', 'availability' => '8 / 10', 'status' => 'available'),
);

$current_term = array(
    'season' => 'Summer 2026',
    'status' => 'Teaching now',
    'weeks' => '13 weeks',
    'dates' => array('13 Apr – 21 May', '1 Jun – 16 Jul'),
    'halfterm' => 'Half term: w/k 25 May · No class 4 May',
    'payment_due' => 'Payment due by 12 March'
);

$upcoming_terms = array(
    array(
        'season' => 'Winter 2026',
        'status' => 'Next term',
        'weeks' => '12 weeks',
        'dates' => array('7 Sep – 16 Oct', '2 Nov – 10 Dec'),
        'halfterm' => '2-week half term: w/k 19 October',
        'payment_due' => 'Payment due by 26 June'
    ),
    array(
        'season' => 'Spring 2027',
        'status' => 'Planning ahead',
        'weeks' => '11 weeks',
        'dates' => array('4 Jan – 11 Feb', '22 Feb – 25 Mar'),
        'halfterm' => 'Half term: w/k 15 February',
        'payment_due' => 'Payment due by 27 November'
    )
);
?>

<div data-page-root="tiddler-gym">
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
                                    <a href="<?php echo esc_url(home_url('/tiddler-gym-booking/')); ?>" class="btn btn-magenta btn-sm cd-book-btn"><?php _e('Book Now', 'tjs-gymnastics'); ?></a>
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
</div>

<?php get_footer(); ?>
