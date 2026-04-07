<?php
/**
 * Template Name: Class Booking Confirmation
 */
get_header();

// Get class type from URL parameter or default to Toddler Gym
$class_type = isset($_GET['class']) ? sanitize_text_field($_GET['class']) : 'toddler-gym';

$classes = array(
    'toddler-gym' => array('name' => 'Toddler Gym', 'back_link' => '/toddler-gym-booking/', 'class_link' => '/toddler-gym/'),
    'tiddler-gym' => array('name' => 'Tiddler Gym', 'back_link' => '/tiddler-gym-booking/', 'class_link' => '/tiddler-gym/'),
    'mini-gym' => array('name' => 'Mini Gym', 'back_link' => '/mini-gym-booking/', 'class_link' => '/mini-gym/'),
);

$class = isset($classes[$class_type]) ? $classes[$class_type] : $classes['toddler-gym'];
?>

<div data-page-root="class-booking-confirmation">
    <div class="cd-back-wrap">
        <div class="container">
            <a href="<?php echo esc_url(home_url($class['back_link'] . '?class=' . $class_type)); ?>" class="cd-back-btn">← <?php _e('Back to Booking Form', 'tjs-gymnastics'); ?></a>
        </div>
    </div>

    <section class="cd-booking-confirmation-hero" aria-label="<?php _e('Booking confirmation', 'tjs-gymnastics'); ?>">
        <div class="container">
            <p class="page-hero-eyebrow"><?php echo esc_html($class['name']); ?></p>
            <h1><?php _e('Booking Request Received', 'tjs-gymnastics'); ?></h1>
            <p class="page-hero-sub"><?php _e('Review your selected session and any available booking details below.', 'tjs-gymnastics'); ?></p>
        </div>
    </section>

    <section class="cd-booking-confirmation section" aria-label="<?php echo esc_attr($class['name']); ?> <?php _e('booking confirmation details', 'tjs-gymnastics'); ?>">
        <div class="container">
            <div class="cd-confirmation-fallback contact-card" data-confirmation-fallback hidden>
                <h2 data-confirmation-fallback-title><?php _e('Booking details unavailable', 'tjs-gymnastics'); ?></h2>
                <p data-confirmation-fallback-session hidden><?php _e('We could not find a selected session for this confirmation page.', 'tjs-gymnastics'); ?></p>
                <p data-confirmation-fallback-booking hidden><?php _e('We could not find the submitted booking form details for this confirmation page.', 'tjs-gymnastics'); ?></p>
                <p data-confirmation-fallback-copy><?php _e('You can return to the booking form and complete the enquiry again, or go back to the class page.', 'tjs-gymnastics'); ?></p>
                <div class="contact-submit-row">
                    <a href="<?php echo esc_url(home_url($class['back_link'] . '?class=' . $class_type)); ?>" class="btn btn-ghost-magenta cd-fallback-link"><?php _e('Return to Booking Form', 'tjs-gymnastics'); ?></a>
                    <a href="<?php echo esc_url(home_url($class['class_link'])); ?>" class="btn btn-ghost-magenta cd-fallback-link"><?php _e('Return to', 'tjs-gymnastics'); ?> <?php echo esc_html($class['name']); ?></a>
                </div>
            </div>

            <div class="cd-flow-grid" data-confirmation-session-shell>
                <div class="cd-submitted-summary" aria-labelledby="confirmation-session-title">
                    <h2 id="confirmation-session-title"><?php _e('Selected Session', 'tjs-gymnastics'); ?></h2>
                    <dl class="cd-session-summary-list">
                        <div>
                            <dt><?php _e('Class', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="class"><?php _e('No session selected', 'tjs-gymnastics'); ?></dd>
                        </div>
                        <div>
                            <dt><?php _e('Booking Type', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="bookingType"><?php _e('No session selected', 'tjs-gymnastics'); ?></dd>
                        </div>
                        <div>
                            <dt><?php _e('Term', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="term"><?php _e('No session selected', 'tjs-gymnastics'); ?></dd>
                        </div>
                        <div>
                            <dt><?php _e('Day', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="day"><?php _e('No session selected', 'tjs-gymnastics'); ?></dd>
                        </div>
                        <div>
                            <dt><?php _e('Time', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="time"><?php _e('No session selected', 'tjs-gymnastics'); ?></dd>
                        </div>
                        <div>
                            <dt><?php _e('Price', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="price"><?php _e('No session selected', 'tjs-gymnastics'); ?></dd>
                        </div>
                        <div>
                            <dt><?php _e('Availability', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="availability"><?php _e('No session selected', 'tjs-gymnastics'); ?></dd>
                        </div>
                    </dl>

                    <div class="cd-confirmation-section" aria-labelledby="submitted-details-title" data-confirmation-submitted-shell>
                        <h2 id="submitted-details-title"><?php _e('Submitted Details', 'tjs-gymnastics'); ?></h2>
                        <dl class="cd-submitted-summary-list">
                            <div>
                                <dt><?php _e('Child\'s Name', 'tjs-gymnastics'); ?></dt>
                                <dd data-submitted-field="childName"><?php _e('No details available', 'tjs-gymnastics'); ?></dd>
                            </div>
                            <div>
                                <dt><?php _e('Child\'s date of birth', 'tjs-gymnastics'); ?></dt>
                                <dd data-submitted-field="childDob"><?php _e('No details available', 'tjs-gymnastics'); ?></dd>
                            </div>
                            <div>
                                <dt><?php _e('Parent / Carer Name', 'tjs-gymnastics'); ?></dt>
                                <dd data-submitted-field="parentName"><?php _e('No details available', 'tjs-gymnastics'); ?></dd>
                            </div>
                            <div>
                                <dt><?php _e('Email Address', 'tjs-gymnastics'); ?></dt>
                                <dd data-submitted-field="email"><?php _e('No details available', 'tjs-gymnastics'); ?></dd>
                            </div>
                            <div>
                                <dt><?php _e('Phone Number', 'tjs-gymnastics'); ?></dt>
                                <dd data-submitted-field="phone"><?php _e('No details available', 'tjs-gymnastics'); ?></dd>
                            </div>
                            <div>
                                <dt><?php _e('Additional Message', 'tjs-gymnastics'); ?></dt>
                                <dd data-submitted-field="message"><?php _e('No details available', 'tjs-gymnastics'); ?></dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>
