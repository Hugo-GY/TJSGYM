<?php
/**
 * Template Name: Class Booking Confirmation
 *
 * Enhanced version: Server-side rendered session info + client-side form data.
 * Auto-detects product from variation_id and loads session data from database.
 */
get_header();

$variation_id = isset($_GET['variation']) ? intval($_GET['variation']) : 0;
$show_fallback = false;
$booking_data = null;

if ($variation_id > 0) {
    $booking_data = tjs_get_booking_session_data($variation_id);
    
    if (is_wp_error($booking_data)) {
        $show_fallback = true;
    }
} else {
    $show_fallback = true;
}

$class_name = ($booking_data && !is_wp_error($booking_data)) ? $booking_data['class_name'] : 'Class';
$product_permalink = ($booking_data && !is_wp_error($booking_data) && isset($booking_data['permalink'])) ? $booking_data['permalink'] : home_url('/classes/');

$current_page = get_post();
$page_slug = $current_page ? get_post_field('post_name', $current_page->ID) : 'confirmation';

$booking_url = str_replace('-confirmation', '-booking', get_permalink($current_page->ID));
?>

<div data-page-root="<?php echo sanitize_html_class($page_slug); ?>-confirmation"
     data-variation-id="<?php echo esc_attr($variation_id); ?>">
    
    <div class="cd-back-wrap">
        <div class="container">
            <a href="<?php echo esc_url(add_query_arg('variation', $variation_id, $booking_url)); ?>" 
               class="cd-back-btn">
                ← <?php _e('Back to Booking Form', 'tjs-gymnastics'); ?>
            </a>
        </div>
    </div>

    <section class="cd-booking-confirmation-hero" aria-label="<?php _e('Booking confirmation', 'tjs-gymnastics'); ?>">
        <div class="container">
            <p class="page-hero-eyebrow" data-confirmation-class-label><?php echo esc_html($class_name); ?></p>
            <h1><?php _e('Booking Request Received', 'tjs-gymnastics'); ?></h1>
            <p class="page-hero-sub"><?php _e('Review your selected session and any available booking details below.', 'tjs-gymnastics'); ?></p>
        </div>
    </section>

    <section class="cd-booking-confirmation section" aria-label="<?php echo esc_attr($class_name); ?> <?php _e('booking confirmation details', 'tjs-gymnastics'); ?>">
        <div class="container">
            
            <?php if ($show_fallback): ?>
            
            <div class="cd-confirmation-fallback contact-card" data-confirmation-fallback hidden>
                <h2 data-confirmation-fallback-title><?php _e('Booking details unavailable', 'tjs-gymnastics'); ?></h2>
                <p data-confirmation-fallback-session hidden><?php _e('We could not find a selected session for this confirmation page.', 'tjs-gymnastics'); ?></p>
                <p data-confirmation-fallback-booking hidden><?php _e('We could not find the submitted booking form details for this confirmation page.', 'tjs-gymnastics'); ?></p>
                <p data-confirmation-fallback-copy><?php _e('You can return to the booking form and complete the enquiry again, or go back to the class page.', 'tjs-gymnastics'); ?></p>
                <div class="contact-submit-row">
                    <a href="<?php echo esc_url(add_query_arg('variation', $variation_id, $booking_url)); ?>" class="btn btn-ghost-magenta cd-fallback-link"><?php _e('Return to Booking Form', 'tjs-gymnastics'); ?></a>
                    <a href="<?php echo esc_url($product_permalink); ?>" class="btn btn-ghost-magenta cd-fallback-link"><?php _e('Return to', 'tjs-gymnastics'); ?> <?php echo esc_html($class_name); ?></a>
                </div>
            </div>

            <?php else: ?>

            <div class="cd-flow-grid" data-confirmation-session-shell>
                <div class="cd-submitted-summary" aria-labelledby="confirmation-session-title">
                    <h2 id="confirmation-session-title"><?php _e('Selected Session', 'tjs-gymnastics'); ?></h2>
                    <dl class="cd-session-summary-list">
                        <div>
                            <dt><?php _e('Class', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="class"><?php echo esc_html($booking_data['class_name']); ?></dd>
                        </div>
                        <div>
                            <dt><?php _e('Booking Type', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="bookingType">—</dd>
                        </div>
                        <div>
                            <dt><?php _e('Term', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="term"><?php echo esc_html($booking_data['term'] ?: 'Current Term'); ?></dd>
                        </div>
                        <div>
                            <dt><?php _e('Day', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="day"><?php echo esc_html($booking_data['day']); ?></dd>
                        </div>
                        <div>
                            <dt><?php _e('Time', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="time"><?php echo esc_html($booking_data['time']); ?></dd>
                        </div>
                        <div>
                            <dt><?php _e('Price', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="price">—</dd>
                        </div>
                        <div>
                            <dt><?php _e('Availability', 'tjs-gymnastics'); ?></dt>
                            <dd data-session-field="availability"><?php echo esc_html($booking_data['availability']); ?></dd>
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

            <?php endif; ?>
        </div>
    </section>
</div>

<?php get_footer(); ?>
