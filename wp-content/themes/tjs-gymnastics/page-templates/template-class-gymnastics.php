<?php
/**
 * Template Name: Gymnastics Class
 */
get_header();

$class_data = array(
    'name' => 'Gymnastics',
    'age' => '5+ Years',
    'about_title' => '<em>Progressive</em> gymnastics for children aged 5+',
    'about_lead' => 'Our Gymnastics classes are for children aged 5 years. Sessions are built around floor and vault work, helping gymnasts develop confidence, technique, body control and strength in a structured but encouraging environment.',
    'about_content' => '<p>We use our own badge progression system, moving from Levels 7 to 1 and then on to Bronze, Silver, Gold, Platinum and Diamond awards.</p><p>We are limited with times and space as we accommodate a large age range after school. Children need to have attended a preschool class to be eligible for our Gymnastics classes. Anyone attending our Mini Gym classes in the Summer term who are starting Reception in the September will automatically be placed on our waiting list for a Beginners Gymnastics class. Spaces are allocated on a random name-out-of-the-hat basis, and all will be offered a space by the Summer term.</p><p>We also have a second waiting list for those who attend a TJ\'s class but leave before the Summer term before starting Reception. Spaces will only be offered to this list when everyone on the first list has been offered a space. WE THEREFORE SADLY CANNOT OFFER ANYONE A GYMNASTICS CLASS WHO HAS NOT ATTENDED OUR PRESCHOOL PROGRAMME.</p>',
    'modifier' => 'tiddler',
);

$sessions = array(
    array('day' => 'Monday', 'time' => '4:00 – 4:45', 'price' => '£165 / term', 'group' => 'Mixed Intermediate', 'status' => 'full'),
    array('day' => 'Monday', 'time' => '4:30 – 5:15', 'price' => '£165 / term', 'group' => 'Girls Mini Squad', 'status' => 'full'),
    array('day' => 'Monday', 'time' => '5:00 – 6:00', 'price' => '£176 / term', 'group' => 'Boys Elite', 'status' => 'full'),
    array('day' => 'Tuesday', 'time' => '4:00 – 4:45', 'price' => '£165 / term', 'group' => 'Beginners', 'status' => 'full'),
    array('day' => 'Tuesday', 'time' => '4:45 – 5:30', 'price' => '£165 / term', 'group' => 'Mixed Intermediate', 'status' => 'full'),
    array('day' => 'Tuesday', 'time' => '5:30 – 6:30', 'price' => '£176 / term', 'group' => 'Mixed Squad', 'status' => 'full'),
    array('day' => 'Wednesday', 'time' => '4:00 – 4:45', 'price' => '£165 / term', 'group' => 'Beginners', 'status' => 'full'),
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

<div data-page-root="gymnastics">
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
                    <p class="cd-about-lead"><?php echo esc_html($class_data['about_lead']); ?></p>
                    <?php echo wp_kses_post($class_data['about_content']); ?>
                </div>
            </div>
        </div>
    </section>

    <section class="cd-booking cd-booking--gymnastics section" id="book" aria-label="<?php _e('Gymnastics times and prices', 'tjs-gymnastics'); ?>">
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

            <div class="cd-booking-table-wrap">
                <table class="cd-booking-table" aria-label="<?php echo esc_attr($current_term['season'] . ' ' . $class_data['name'] . ' sessions'); ?>">
                    <thead>
                        <tr>
                            <th><?php _e('Time', 'tjs-gymnastics'); ?></th>
                            <th><?php _e('Price', 'tjs-gymnastics'); ?></th>
                            <th><?php _e('Group', 'tjs-gymnastics'); ?></th>
                            <th><?php _e('Status', 'tjs-gymnastics'); ?></th>
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
                                <td data-label="<?php _e('Group', 'tjs-gymnastics'); ?>"><?php echo esc_html($session['group']); ?></td>
                                <td data-label="<?php _e('Status', 'tjs-gymnastics'); ?>"><span class="cd-avail is-<?php echo esc_attr($session['status']); ?>"><?php _e('Class Full', 'tjs-gymnastics'); ?></span></td>
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
