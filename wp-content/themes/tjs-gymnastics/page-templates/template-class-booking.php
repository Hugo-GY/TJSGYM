<?php
/**
 * Template Name: Class Booking
 */
get_header();

// Get class type from URL parameter or default to Toddler Gym
$class_type = isset($_GET['class']) ? sanitize_text_field($_GET['class']) : 'toddler-gym';

$classes = array(
    'toddler-gym' => array('name' => 'Toddler Gym', 'back_link' => '/toddler-gym/', 'confirmation_link' => '/toddler-gym-confirmation/'),
    'tiddler-gym' => array('name' => 'Tiddler Gym', 'back_link' => '/tiddler-gym/', 'confirmation_link' => '/tiddler-gym-confirmation/'),
    'mini-gym' => array('name' => 'Mini Gym', 'back_link' => '/mini-gym/', 'confirmation_link' => '/mini-gym-confirmation/'),
);

$class = isset($classes[$class_type]) ? $classes[$class_type] : $classes['toddler-gym'];
?>

<div data-page-root="class-booking">
    <div class="cd-back-wrap">
        <div class="container">
            <a href="<?php echo esc_url(home_url($class['back_link'])); ?>" class="cd-back-btn">← Back to <?php echo esc_html($class['name']); ?></a>
        </div>
    </div>

    <section class="cd-booking-flow-hero" aria-label="<?php echo esc_attr($class['name']); ?> <?php _e('booking', 'tjs-gymnastics'); ?>">
        <div class="container">
            <p class="page-hero-eyebrow"><?php echo esc_html($class['name']); ?></p>
            <h1><?php _e('Complete Your Booking', 'tjs-gymnastics'); ?></h1>
            <p class="page-hero-sub"><?php _e('Review your selected session below, then enter your family\'s details before continuing to the next step.', 'tjs-gymnastics'); ?></p>
        </div>
    </section>

    <section class="cd-booking-flow section" aria-label="<?php echo esc_attr($class['name']); ?> <?php _e('booking form', 'tjs-gymnastics'); ?>">
        <div class="container">
            <div class="cd-booking-fallback contact-card" data-booking-fallback hidden>
                <h2><?php _e('No session selected', 'tjs-gymnastics'); ?></h2>
                <p data-booking-fallback-copy><?php _e('We could not find a selected session for this booking page.', 'tjs-gymnastics'); ?></p>
                <p><?php _e('Please return to the timetable and choose a session before continuing.', 'tjs-gymnastics'); ?></p>
                <div class="contact-submit-row">
                    <a href="<?php echo esc_url(home_url($class['back_link'])); ?>" class="btn btn-ghost-magenta cd-fallback-link"><?php _e('Return to', 'tjs-gymnastics'); ?> <?php echo esc_html($class['name']); ?></a>
                </div>
            </div>

            <div class="cd-flow-grid" data-booking-session-shell>
                <div class="contact-card cd-booking-form-card" data-booking-form-shell>
                    <div class="cd-booking-summary-block" aria-labelledby="selected-session-title">
                        <h2 id="selected-session-title"><?php _e('Selected Session', 'tjs-gymnastics'); ?></h2>
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
                    </div>

                    <form class="cd-booking-form" data-booking-form action="<?php echo esc_url(home_url($class['confirmation_link'])); ?>" method="get">
                        <div class="cd-booking-form-section">
                            <h2><?php _e('Choose Booking Type', 'tjs-gymnastics'); ?></h2>
                            <fieldset class="cd-booking-type-fieldset">
                                <legend class="cd-sr-only"><?php _e('Choose Booking Type', 'tjs-gymnastics'); ?></legend>
                                <div class="cd-booking-type-options" data-booking-type-options>
                                    <label class="cd-booking-type-option" for="booking-type-full">
                                        <input type="radio" id="booking-type-full" name="booking-type" value="full" checked>
                                        <span class="cd-booking-type-copy">
                                            <strong><?php _e('Full-term Booking', 'tjs-gymnastics'); ?></strong>
                                            <small><?php _e('Reserve your regular place for this selected session.', 'tjs-gymnastics'); ?></small>
                                        </span>
                                    </label>
                                    <label class="cd-booking-type-option" for="booking-type-trial">
                                        <input type="radio" id="booking-type-trial" name="booking-type" value="trial">
                                        <span class="cd-booking-type-copy">
                                            <strong><?php _e('Trial lesson', 'tjs-gymnastics'); ?></strong>
                                            <small><?php _e('Pay for a first visit before committing to the full term.', 'tjs-gymnastics'); ?></small>
                                        </span>
                                    </label>
                                </div>
                            </fieldset>
                        </div>

                        <div class="cd-booking-form-section">
                            <h2><?php _e('Application Info', 'tjs-gymnastics'); ?></h2>
                            <div>
                                <label for="child-name"><?php _e('Child\'s Name', 'tjs-gymnastics'); ?></label>
                                <input type="text" id="child-name" name="child-name" required autocomplete="off">
                            </div>

                            <div>
                                <label for="child-dob"><?php _e('Child\'s date of birth', 'tjs-gymnastics'); ?></label>
                                <input type="date" id="child-dob" name="child-dob" required autocomplete="bday">
                            </div>

                            <div>
                                <label for="parent-name"><?php _e('Parent / Carer Name', 'tjs-gymnastics'); ?></label>
                                <input type="text" id="parent-name" name="parent-name" required autocomplete="name">
                            </div>

                            <div>
                                <label for="email"><?php _e('Email Address', 'tjs-gymnastics'); ?></label>
                                <input type="email" id="email" name="email" required autocomplete="email">
                            </div>

                            <div>
                                <label for="phone"><?php _e('Phone Number', 'tjs-gymnastics'); ?></label>
                                <input type="tel" id="phone" name="phone" required autocomplete="tel">
                            </div>

                            <div>
                                <label for="message"><?php _e('Additional Message', 'tjs-gymnastics'); ?></label>
                                <textarea id="message" name="message" rows="6"></textarea>
                            </div>

                            <div class="contact-submit-row">
                                <button type="submit" class="btn btn-magenta"><?php _e('Pay', 'tjs-gymnastics'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>
