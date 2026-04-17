<?php
/**
 * Template Name: Dynamic Class Booking
 *
 * Enhanced version: Server-side rendered booking form.
 * Auto-detects product from variation_id and loads all data from database.
 *
 * Usage:
 * 1. Create a single WordPress Page (e.g., /class-booking)
 * 2. Apply this template to the page
 * 3. Access via ?variation=123 parameter
 * 4. All data loads automatically from WooCommerce + ACF!
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

$class_slug = ($booking_data && !is_wp_error($booking_data) && isset($booking_data['class_slug'])) ? $booking_data['class_slug'] : '';

$back_url = home_url('/classes/');
if (!empty($class_slug)) {
    $class_page = get_page_by_path($class_slug);
    if ($class_page) {
        $back_url = get_permalink($class_page->ID);
    }
} elseif ($booking_data && !is_wp_error($booking_data) && isset($booking_data['permalink'])) {
    $back_url = $booking_data['permalink'];
}

$current_page = get_post();
$page_slug = $current_page ? get_post_field('post_name', $current_page->ID) : 'booking';
$confirmation_url = get_permalink($current_page->ID);
$confirmation_url = str_replace('-booking', '-confirmation', $confirmation_url);
?>

<div data-page-root="<?php echo sanitize_html_class($page_slug); ?>-booking"
     data-variation-id="<?php echo esc_attr($variation_id); ?>">
    
    <div class="cd-back-wrap">
        <div class="container">
            <a href="<?php echo esc_url($back_url); ?>"
               class="cd-back-btn">
                ← Back to <?php echo esc_html($class_name); ?>
            </a>
        </div>
    </div>

    <section class="cd-booking-flow-hero" aria-label="<?php echo esc_attr($class_name); ?> booking">
        <div class="container">
            <p class="page-hero-eyebrow" data-booking-class-label><?php echo esc_html($class_name); ?></p>
            <h1><?php _e('Complete Your Booking', 'tjs-gymnastics'); ?></h1>
            <p class="page-hero-sub"><?php _e('Review your selected session below, then enter your family\'s details before continuing to the next step.', 'tjs-gymnastics'); ?></p>
        </div>
    </section>

    <section class="cd-booking-flow section" aria-label="<?php echo esc_attr($class_name); ?> booking form">
        <div class="container">
            
            <?php if ($show_fallback): ?>
            
            <div class="cd-booking-fallback contact-card" data-booking-fallback>
                <h2><?php _e('No session selected', 'tjs-gymnastics'); ?></h2>
                <p data-booking-fallback-copy><?php _e('We could not find a valid session for this booking page. Please return to the class page and select a session to book.', 'tjs-gymnastics'); ?></p>
                <div class="contact-submit-row">
                    <a href="<?php echo esc_url(home_url('/classes/')); ?>" class="btn btn-ghost-magenta cd-fallback-link" data-booking-fallback-link>
                        <?php _e('View All Classes', 'tjs-gymnastics'); ?>
                    </a>
                </div>
            </div>

            <?php else: ?>

            <div class="cd-flow-grid" data-booking-session-shell>
                <div class="contact-card cd-booking-form-card" data-booking-form-shell>
                    
                    <div class="cd-booking-summary-block" aria-labelledby="selected-session-title">
                        <h2 id="selected-session-title"><?php _e('Selected Session', 'tjs-gymnastics'); ?></h2>
                        <dl class="cd-session-summary-list">
                            <div>
                                <dt><?php _e('Class', 'tjs-gymnastics'); ?></dt>
                                <dd data-session-field="class"><?php echo esc_html($booking_data['class_name']); ?></dd>
                            </div>
                            <?php if ($booking_data['enable_trial']): ?>
                            <div>
                                <dt><?php _e('Booking Type', 'tjs-gymnastics'); ?></dt>
                                <dd data-session-field="bookingType"
                                    data-default-type="full"
                                    data-label-full="<?php esc_attr_e('Full-term Booking', 'tjs-gymnastics'); ?>"
                                    data-label-trial="<?php esc_attr_e('Trial lesson', 'tjs-gymnastics'); ?>">
                                    <?php _e('Full-term Booking', 'tjs-gymnastics'); ?>
                                </dd>
                            </div>
                            <?php endif; ?>
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
                                <dd data-session-field="price"
                                    data-price-full="<?php echo esc_attr($booking_data['price_full']); ?>"
                                    data-price-trial="<?php echo esc_attr($booking_data['price_trial']); ?>"
                                    data-enable-trial="<?php echo $booking_data['enable_trial'] ? 'true' : 'false'; ?>">
                                    <?php echo esc_html($booking_data['price_full']); ?>
                                </dd>
                            </div>
                            <div>
                                <dt><?php _e('Availability', 'tjs-gymnastics'); ?></dt>
                                <dd data-session-field="availability"><?php echo esc_html($booking_data['availability']); ?></dd>
                            </div>
                        </dl>
                    </div>

                      <form class="cd-booking-form" data-booking-form
                          action=""
                          method="post"
                          novalidate
                          data-checkout-url="<?php echo esc_url(wc_get_checkout_url()); ?>"
                          data-variation-id="<?php echo esc_attr($variation_id); ?>"
                          data-product-id="<?php echo esc_attr($booking_data['product_id']); ?>"
                          data-price-full="<?php echo esc_attr($booking_data['price_full_raw']); ?>"
                          data-price-trial="<?php echo esc_attr($booking_data['price_trial_raw']); ?>"
                          data-enable-trial="<?php echo $booking_data['enable_trial'] ? 'true' : 'false'; ?>">
                        
                        <?php if ($booking_data['enable_trial']): ?>
                        <div class="cd-booking-form-section">
                            <h2><?php _e('Choose Booking Type', 'tjs-gymnastics'); ?></h2>
                            <fieldset class="cd-booking-type-fieldset">
                                <legend class="cd-sr-only"><?php _e('Choose Booking Type', 'tjs-gymnastics'); ?></legend>
                                <div class="cd-booking-type-options" data-booking-type-options>
                                    <label class="cd-booking-type-option" for="booking-type-full">
                                        <input type="radio" id="booking-type-full" name="booking-type" value="full" checked>
                                        <span class="cd-booking-type-copy">
                                            <strong><?php _e('Full-term Booking', 'tjs-gymnastics'); ?></strong>
                                            <small><?php echo esc_html($booking_data['price_full']); ?></small>
                                        </span>
                                    </label>
                                    <label class="cd-booking-type-option" for="booking-type-trial">
                                        <input type="radio" id="booking-type-trial" name="booking-type" value="trial">
                                        <span class="cd-booking-type-copy">
                                            <strong><?php _e('Trial lesson', 'tjs-gymnastics'); ?></strong>
                                            <small><?php echo esc_html($booking_data['price_trial']); ?></small>
                                        </span>
                                    </label>
                                </div>
                            </fieldset>
                        </div>
                        <?php endif; ?>

                        <div class="cd-booking-form-section">
                            <h2><?php _e('Application Info', 'tjs-gymnastics'); ?></h2>
                            <div>
                                <label for="child-name"><?php _e('Child\'s Name', 'tjs-gymnastics'); ?></label>
                                <input type="text" id="child-name" name="child-name" required maxlength="100" autocomplete="off" aria-describedby="child-name-error">
                                <p class="cd-field-error" id="child-name-error" data-field-error-for="child-name" role="alert" hidden></p>
                            </div>

                            <div>
                                <label for="child-dob"><?php _e('Child\'s date of birth', 'tjs-gymnastics'); ?></label>
                                <input type="text" id="child-dob" name="child-dob" required autocomplete="off" inputmode="none" data-lock-date-input="true" data-type-datepicker data-format="Y-m-d" aria-haspopup="dialog" aria-describedby="child-dob-error">
                                <p class="cd-field-error" id="child-dob-error" data-field-error-for="child-dob" role="alert" hidden></p>
                            </div>

                            <div>
                                <label for="parent-name"><?php _e('Parent / Carer Name', 'tjs-gymnastics'); ?></label>
                                <input type="text" id="parent-name" name="parent-name" required maxlength="100" autocomplete="name" aria-describedby="parent-name-error">
                                <p class="cd-field-error" id="parent-name-error" data-field-error-for="parent-name" role="alert" hidden></p>
                            </div>

                            <div>
                                <label for="email"><?php _e('Email Address', 'tjs-gymnastics'); ?></label>
                                <input type="email" id="email" name="email" required maxlength="100" autocomplete="email" aria-describedby="email-error">
                                <p class="cd-field-error" id="email-error" data-field-error-for="email" role="alert" hidden></p>
                            </div>

                            <div>
                                <label for="phone"><?php _e('Phone Number', 'tjs-gymnastics'); ?></label>
                                <input type="tel" id="phone" name="phone" required maxlength="50" autocomplete="tel" aria-describedby="phone-error">
                                <p class="cd-field-error" id="phone-error" data-field-error-for="phone" role="alert" hidden></p>
                            </div>

                            <div>
                                <label for="message"><?php _e('Additional Message', 'tjs-gymnastics'); ?></label>
                                <textarea id="message" name="message" rows="6" maxlength="500" aria-describedby="message-error"></textarea>
                                <p class="cd-field-error" id="message-error" data-field-error-for="message" role="alert" hidden></p>
                            </div>
                        </div>

                        <div class="cd-booking-form-section cd-booking-payment-section">
                            <h2><?php _e('Payment Details', 'tjs-gymnastics'); ?></h2>
                            <div class="cd-booking-payment-copy">
                                <p><?php _e('Enter your card details below to continue with payment.', 'tjs-gymnastics'); ?></p>
                            </div>

                            <div id="tjs-booking-stripe-form" class="tjs-booking-stripe" aria-label="<?php esc_attr_e('Card details', 'tjs-gymnastics'); ?>">
                                <div class="tjs-booking-stripe__group tjs-booking-stripe__group--full">
                                    <label for="tjs-booking-stripe-card-element"><?php _e('Card number', 'tjs-gymnastics'); ?></label>
                                    <div id="tjs-booking-stripe-card-element" class="tjs-booking-stripe__field"></div>
                                    <p class="cd-field-error cd-stripe-field-error" id="tjs-booking-stripe-card-error" data-stripe-error-for="cardNumber" role="alert" hidden></p>
                                </div>

                                <div class="tjs-booking-stripe__split">
                                    <div class="tjs-booking-stripe__group">
                                        <label for="tjs-booking-stripe-exp-element"><?php _e('Expiration date', 'tjs-gymnastics'); ?></label>
                                        <div id="tjs-booking-stripe-exp-element" class="tjs-booking-stripe__field"></div>
                                        <p class="cd-field-error cd-stripe-field-error" id="tjs-booking-stripe-exp-error" data-stripe-error-for="cardExpiry" role="alert" hidden></p>
                                    </div>

                                    <div class="tjs-booking-stripe__group">
                                        <label for="tjs-booking-stripe-cvc-element"><?php _e('Security code', 'tjs-gymnastics'); ?></label>
                                        <div id="tjs-booking-stripe-cvc-element" class="tjs-booking-stripe__field"></div>
                                        <p class="cd-field-error cd-stripe-field-error" id="tjs-booking-stripe-cvc-error" data-stripe-error-for="cardCvc" role="alert" hidden></p>
                                    </div>
                                </div>

                                <div id="tjs-booking-stripe-errors" class="stripe-source-errors" role="alert" aria-live="polite"></div>
                            </div>

                            <div class="cd-booking-payment-privacy woocommerce-privacy-policy-text">
                                <p>
                                    <?php
                                    printf(
                                        wp_kses(
                                            __('Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="%s">privacy policy</a>.', 'woocommerce'),
                                            array(
                                                'a' => array(
                                                    'href' => array(),
                                                ),
                                            )
                                        ),
                                        esc_url(get_privacy_policy_url())
                                    );
                                    ?>
                                </p>
                            </div>
                        </div>

                        <div class="contact-submit-row">
                            <button type="submit" class="btn btn-magenta cd-pay-button">
                                <span class="cd-pay-text"><?php _e('Pay', 'tjs-gymnastics'); ?></span>
                                <span class="cd-pay-loading" style="display:none;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-dasharray="31.4 31.4" stroke-dashoffset="0">
                                            <animateTransform attributeName="transform" type="rotate" values="0 12 12;360 12 12" dur="1s" repeatCount="indefinite"/>
                                        </circle>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>

                        <input type="hidden" name="variation_id" value="<?php echo esc_attr($variation_id); ?>">
                        <input type="hidden" name="product_id" value="<?php echo esc_attr($booking_data['product_id']); ?>">
                        <input type="hidden" name="action" value="tjs_create_booking_payment">
                        <?php wp_nonce_field('tjs_booking_nonce', 'booking_nonce'); ?>
                    </form>
                </div>
            </div>

            <?php endif; ?>
        </div>
    </section>
</div>

<?php get_footer(); ?>
