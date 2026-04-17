<?php
/**
 * Custom Thankyou Page Template for TJS Gymnastics
 *
 * Overrides WooCommerce default thankyou.php to match Demo design:
 * - Shows Selected Session info (from cart item data)
 * - Shows Submitted Details (child name, parent, contact)
 * - Preserves Order details, Billing address, etc.
 *
 * Template: yourtheme/woocommerce/checkout/thankyou.php
 */

defined( 'ABSPATH' ) || exit;

$order = false;
$key_is_valid = false;
$order_id = 0;

if ( isset( $_GET['key'] ) ) {
    $order_id = absint( get_query_var( 'order-received' ) );
    if ( ! $order_id && isset( $_GET['order_id'] ) ) {
        $order_id = absint( wp_unslash( $_GET['order_id'] ) );
    }
}

if ( $order_id > 0 ) {
    $order = wc_get_order( $order_id );
    $key_is_valid = $order && hash_equals( $order->get_order_key(), wc_clean( wp_unslash( $_GET['key'] ?? '' ) ) );
}

$session_data = array();
$customer_data = array();
$has_valid_order = false;
$is_failed_order = false;

if ( $order && $key_is_valid ) {
    $has_valid_order = true;
    $is_failed_order = $order->has_status( 'failed' );

    foreach ( $order->get_items() as $item_id => $item ) {
        $session_data['class_name'] = $item->get_name();
        $session_data['quantity'] = $item->get_quantity();

        $variation = new WC_Product_Variation( $item->get_variation_id() );
        if ( $variation ) {
            $schedule = function_exists( 'tjs_get_variation_schedule_data' ) ? tjs_get_variation_schedule_data( $variation ) : array();
            $session_data['day'] = isset( $schedule['day'] ) ? $schedule['day'] : '';
            $session_data['time'] = isset( $schedule['time'] ) ? $schedule['time'] : '';
        }

        $session_data['price'] = $item->get_total() > 0 ? '£' . number_format( $item->get_total(), 0 ) . ' / term' : '';

        $meta_data = $item->get_meta_data();
        foreach ( $meta_data as $meta ) {
            $key = $meta->key;
            $value = $meta->value;
            if ( in_array( $key, array( 'child_name', 'child_dob', 'parent_name', 'email', 'phone', 'message', 'booking-type' ), true ) ) {
                $customer_data[ str_replace( '-', '', $key ) ] = $value;
            }
        }
    }
}

$hero_eyebrow = $session_data['class_name'] ?? 'Class Booking';
$hero_title = 'Order Not Found';
$hero_subtitle = 'We could not find this order. Please check your email for confirmation or return to the classes page.';

if ( $is_failed_order ) {
    $hero_title = 'Payment Failed';
    $hero_subtitle = 'Unfortunately your order cannot be processed as the originating bank has declined your transaction.';
} elseif ( $has_valid_order ) {
    $hero_title = 'Booking Confirmed';
    $hero_subtitle = 'Thank you! Your booking has been received. Review your selected session and details below.';
}
?>

<div class="tjs-woocommerce-order" data-page-root="booking-confirmation">

    <div class="cd-back-wrap">
        <div class="container">
            <a href="<?php echo esc_url( home_url( '/classes/' ) ); ?>" class="cd-back-btn">
                &larr; Back to Classes
            </a>
        </div>
    </div>

    <section class="cd-booking-confirmation-hero" aria-label="Booking confirmation">
        <div class="container">
            <p class="page-hero-eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></p>
            <h1><?php echo esc_html( $hero_title ); ?></h1>
            <p class="page-hero-sub"><?php echo esc_html( $hero_subtitle ); ?></p>
        </div>
    </section>

    <section class="cd-booking-confirmation section" aria-label="Booking confirmation details">
        <div class="container">

            <?php if ( $has_valid_order ) :

                if ( $is_failed_order ) : ?>

                    <div class="contact-card cd-confirmation-fallback">
                        <h2>Try Payment Again</h2>
                        <p>Unfortunately your order cannot be processed as the originating bank has declined your transaction.</p>
                        <div class="contact-submit-row">
                            <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn btn-magenta">Try Payment Again</a>
                            <?php if ( is_user_logged_in() ) : ?>
                                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn btn-ghost-magenta">My Account</a>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php else : ?>

                    <div class="cd-flow-grid">

                        <div class="cd-submitted-summary" aria-labelledby="confirmation-session-title">

                            <h2 id="confirmation-session-title">Selected Session</h2>
                            <dl class="cd-session-summary-list">
                                <div>
                                    <dt><?php esc_html_e( 'Class', 'woocommerce' ); ?></dt>
                                    <dd data-session-field="class"><?php echo esc_html( $session_data['class_name'] ?? 'N/A' ); ?></dd>
                                </div>
                                <div>
                                    <dt><?php esc_html_e( 'Day', 'tjs-gymnastics' ); ?></dt>
                                    <dd data-session-field="day"><?php echo esc_html( $session_data['day'] ?? 'N/A' ); ?></dd>
                                </div>
                                <div>
                                    <dt><?php esc_html_e( 'Time', 'tjs-gymnastics' ); ?></dt>
                                    <dd data-session-field="time"><?php echo esc_html( $session_data['time'] ?? 'N/A' ); ?></dd>
                                </div>
                                <div>
                                    <dt><?php esc_html_e( 'Price', 'tjs-gymnastics' ); ?></dt>
                                    <dd data-session-field="price"><?php echo esc_html( $session_data['price'] ?? 'N/A' ); ?></dd>
                                </div>
                            </dl>

                            <div class="cd-confirmation-section" aria-labelledby="submitted-details-title" data-confirmation-submitted-shell>
                                <h2 id="submitted-details-title">Submitted Details</h2>
                                <dl class="cd-submitted-summary-list">
                                    <div>
                                        <dt>Child's Name</dt>
                                        <dd data-submitted-field="childName"><?php echo esc_html( $customer_data['childname'] ?? $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></dd>
                                    </div>
                                    <div>
                                        <dt>Child's date of birth</dt>
                                        <dd data-submitted-field="childDob"><?php echo esc_html( $customer_data['childdob'] ?? 'N/A' ); ?></dd>
                                    </div>
                                    <div>
                                        <dt>Parent / Carer Name</dt>
                                        <dd data-submitted-field="parentName"><?php echo esc_html( $customer_data['parentname'] ?? $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></dd>
                                    </div>
                                    <div>
                                        <dt>Email Address</dt>
                                        <dd data-submitted-field="email"><?php echo esc_html( $customer_data['email'] ?? $order->get_billing_email() ); ?></dd>
                                    </div>
                                    <div>
                                        <dt>Phone Number</dt>
                                        <dd data-submitted-field="phone"><?php echo esc_html( $customer_data['phone'] ?? $order->get_billing_phone() ); ?></dd>
                                    </div>
                                    <?php if ( ! empty( $customer_data['message'] ) ) : ?>
                                    <div>
                                        <dt>Additional Message</dt>
                                        <dd data-submitted-field="message"><?php echo esc_html( $customer_data['message'] ); ?></dd>
                                    </div>
                                    <?php endif; ?>
                                </dl>
                            </div>

                        </div>

                        <aside class="cd-booking-form-card" aria-label="Order information">

                            <h2>Order Details</h2>

                            <dl class="cd-session-summary-list">
                                <div>
                                    <dt>Order Number</dt>
                                    <dd>#<?php echo esc_html( $order->get_order_number() ); ?></dd>
                                </div>
                                <div>
                                    <dt>Date</dt>
                                    <dd><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></dd>
                                </div>
                                <div>
                                    <dt>Total</dt>
                                    <dd><strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong></dd>
                                </div>
                                <div>
                                    <dt>Payment Method</dt>
                                    <dd><?php echo wp_kses_post( $order->get_payment_method_title() ?: 'N/A' ); ?></dd>
                                </div>
                            </dl>

                            <?php if ( $order->get_billing_address_1() || $order->get_billing_postcode() ) : ?>
                            <div class="cd-confirmation-section" style="margin-top: 2rem;">
                                <h3>Billing Address</h3>
                                <address style="font-style: normal; line-height: 1.6;">
                                    <?php echo wp_kses_post( $order->get_formatted_billing_address() ); ?>
                                </address>
                            </div>
                            <?php endif; ?>

                            <div style="margin-top: 2rem;">
                                <a href="<?php echo esc_url( home_url( '/classes/' ) ); ?>" class="btn btn-ghost-magenta" style="width: 100%; text-align: center;">
                                    View All Classes
                                </a>
                            </div>

                        </aside>

                    </div>

                <?php endif; ?>

            <?php else : ?>

                <div class="contact-card cd-confirmation-fallback">
                    <h2>Need Help?</h2>
                    <p>We could not find this order. Please check your email for confirmation or return to the classes page.</p>
                    <div class="contact-submit-row">
                        <a href="<?php echo esc_url( home_url( '/classes/' ) ); ?>" class="btn btn-magenta">View Classes</a>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </section>

</div>
