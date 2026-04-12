<?php
/**
 * Customer completed order email
 *
 * Custom template for TJ's Gymnastics Club - Branded booking confirmation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 10.4.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

$is_class_booking = false;
$booking_data = array();
$debug_info = '';

if (function_exists('tjs_order_has_class_product')) {
    $is_class_booking = tjs_order_has_class_product($order);
    $debug_info .= "Function tjs_order_has_class_product EXISTS. Result: " . ($is_class_booking ? 'TRUE' : 'FALSE') . "\n";
} else {
    $debug_info .= "Function tjs_order_has_class_product NOT FOUND\n";
}

if ($is_class_booking && function_exists('tjs_get_order_booking_data')) {
    $booking_data = tjs_get_order_booking_data($order);
    $debug_info .= "Booking data extracted. Count: " . count($booking_data) . "\n";
    $debug_info .= "Keys: " . implode(', ', array_keys($booking_data)) . "\n";
} elseif ($is_class_booking) {
    $debug_info .= "Function tjs_get_order_booking_data NOT FOUND\n";
}

// Log debug info to file (only in debug mode)
if (defined('WP_DEBUG') && WP_DEBUG) {
    $log_file = WP_CONTENT_DIR . '/debug-email-template.log';
    $log_entry = "[" . date('Y-m-d H:i:s') . "] Order #{$order->get_id()}\n";
    $log_entry .= $debug_info . "\n" . str_repeat('-', 50) . "\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php if ($is_class_booking && !empty($booking_data)) : ?>
<?php
$logo_url = get_template_directory_uri() . '/assets/images/logo.png';
$custom_logo_id = get_theme_mod('custom_logo');
if ($custom_logo_id) {
	$custom_logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
	if ($custom_logo_url) {
		$logo_url = $custom_logo_url;
	}
}
?>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%; border-collapse:collapse; background-color:#fbf6fb;">
	<tr>
		<td align="center" style="padding:32px 16px;">
			<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%; max-width:640px; border-collapse:collapse;">
				<tr>
					<td align="center" style="padding:0 0 24px 0;">
						<img src="<?php echo esc_url($logo_url); ?>" alt="TJ's Gymnastics Club" width="120" style="display:block; width:120px; max-width:100%; height:auto; margin:0 auto 22px auto; border:0; outline:none;">
						<span style="display:inline-block; padding:8px 14px; border-radius:999px; background-color:#f8dfef; color:#c63b8f; font-size:12px; font-weight:700; letter-spacing:1.4px; text-transform:uppercase;">Booking Confirmed</span>
						<h1 style="margin:18px 0 12px 0; color:#4a214d; font-size:34px; line-height:1.15; font-weight:700;">Your Booking Is Confirmed</h1>
						<p style="margin:0 auto; max-width:520px; color:#6e5a77; font-size:17px; line-height:1.7;">
							Thank you for booking with TJ's Gymnastics Club. Your place has been confirmed and the details of your booking are included below.
						</p>
					</td>
				</tr>

				<tr>
					<td style="padding:0 0 18px 0;">
						<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%; border-collapse:separate; border-spacing:0; background-color:#ffffff; border:1px solid #efd7e8; border-top:5px solid #d94a9f; border-radius:22px;">
							<tr>
								<td style="padding:28px 28px 16px 28px;">
									<p style="margin:0 0 12px 0; color:#4a214d; font-size:22px; line-height:1.3; font-weight:700;">Booking Details</p>
									<p style="margin:0; color:#6e5a77; font-size:15px; line-height:1.75;">
										Please keep this email for your records. If anything needs to change, contact us using the details at the bottom of this email.
									</p>
								</td>
							</tr>
							<tr>
								<td style="padding:0 28px 28px 28px;">
									<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%; border-collapse:collapse;">
										<tr>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Class</td>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo esc_html($booking_data['class_name']); ?></td>
										</tr>
										<?php if (!empty($booking_data['booking_type'])) : ?>
										<tr>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Booking Type</td>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo esc_html($booking_data['booking_type']); ?></td>
										</tr>
										<?php endif; ?>
										<?php if (!empty($booking_data['term'])) : ?>
										<tr>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Term</td>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo esc_html($booking_data['term']); ?></td>
										</tr>
										<?php endif; ?>
										<?php if (!empty($booking_data['session_day'])) : ?>
										<tr>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Day</td>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo esc_html($booking_data['session_day']); ?></td>
										</tr>
										<?php endif; ?>
										<?php if (!empty($booking_data['session_time'])) : ?>
										<tr>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Time</td>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo esc_html($booking_data['session_time']); ?></td>
										</tr>
										<?php endif; ?>
										<?php if (!empty($booking_data['venue_name'])) : ?>
										<tr>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Venue</td>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo esc_html($booking_data['venue_name']); ?></td>
										</tr>
										<?php endif; ?>
										<tr>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Price</td>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo wp_kses_post($booking_data['price']); ?></td>
										</tr>
										<tr>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Booking Date</td>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo esc_html($booking_data['booking_date']); ?></td>
										</tr>
										<tr>
											<td style="padding:14px 0 0 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Booking Reference</td>
											<td style="padding:14px 0 0 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo esc_html($booking_data['booking_reference']); ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td style="padding:0 0 18px 0;">
						<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%; border-collapse:separate; border-spacing:0; background-color:#ffffff; border:1px solid #efd7e8; border-radius:22px;">
							<tr>
								<td style="padding:28px 28px 18px 28px;">
									<p style="margin:0; color:#4a214d; font-size:22px; line-height:1.3; font-weight:700;">Your Details</p>
								</td>
							</tr>
							<tr>
								<td style="padding:0 28px 28px 28px;">
									<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%; border-collapse:collapse;">
										<tr>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Parent / Carer Name</td>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo esc_html($booking_data['parent_name']); ?></td>
										</tr>
										<?php if (!empty($booking_data['child_name'])) : ?>
										<tr>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Child's Name</td>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo esc_html($booking_data['child_name']); ?></td>
										</tr>
										<?php endif; ?>
										<?php if (!empty($booking_data['child_dob'])) : ?>
										<tr>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Child's Date of Birth</td>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo esc_html($booking_data['child_dob']); ?></td>
										</tr>
										<?php endif; ?>
										<tr>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Email Address</td>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo esc_html($booking_data['email']); ?></td>
										</tr>
										<?php if (!empty($booking_data['phone'])) : ?>
										<tr>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Phone Number</td>
											<td style="padding:14px 0; border-top:1px solid #f1e5ef; color:#34203d; font-size:16px; line-height:1.5; text-align:right;"><?php echo esc_html($booking_data['phone']); ?></td>
										</tr>
										<?php endif; ?>
										<?php if (!empty($booking_data['message'])) : ?>
										<tr>
											<td valign="top" style="padding:14px 0 0 0; border-top:1px solid #f1e5ef; color:#8e6c8a; font-size:12px; line-height:1.4; font-weight:700; letter-spacing:1px; text-transform:uppercase;">Additional Message</td>
											<td style="padding:14px 0 0 0; border-top:1px solid #f1e5ef; text-align:right;">
												<span style="display:inline-block; max-width:360px; color:#34203d; font-size:16px; line-height:1.65; text-align:left;"><?php echo esc_html($booking_data['message']); ?></span>
											</td>
										</tr>
										<?php endif; ?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td style="padding:14px 10px 0 10px; text-align:center;">
						<p style="margin:0 0 10px 0; color:#4a214d; font-size:18px; line-height:1.4; font-weight:700;">TJ's Gymnastics Club</p>
						<p style="margin:0 0 14px 0; color:#6e5a77; font-size:14px; line-height:1.7;">Building confidence through gymnastics since 1988.</p>
						<p style="margin:0 0 6px 0; color:#6e5a77; font-size:14px; line-height:1.7;">Raynes Park Sports Pavilion, Taunton Avenue, London SW20 0BH</p>
						<p style="margin:0 0 6px 0; color:#6e5a77; font-size:14px; line-height:1.7;">
							<a href="tel:01252702295" style="color:#c63b8f; text-decoration:none;">01252 702295</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a href="tel:07885103080" style="color:#c63b8f; text-decoration:none;">07885 103080</a>
						</p>
						<p style="margin:0; color:#6e5a77; font-size:14px; line-height:1.7;">
							<a href="mailto:info@tjsgymclub.co.uk" style="color:#c63b8f; text-decoration:none;">info@tjsgymclub.co.uk</a>
						</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?php else : ?>

<?php echo $email_improvements_enabled ? '<div class="email-introduction">' : ''; ?>
<p>
<?php
if ( ! empty( $order->get_billing_first_name() ) ) {
	printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) );
} else {
	printf( esc_html__( 'Hi,', 'woocommerce' ) );
}
?>
</p>
<p><?php esc_html_e( 'We have finished processing your order.', 'woocommerce' ); ?></p>
<?php if ( $email_improvements_enabled ) : ?>
	<p><?php esc_html_e( 'Here\'s a reminder of what you\'ve ordered:', 'woocommerce' ); ?></p>
<?php endif; ?>
<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<?php
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

if ( $additional_content ) {
	echo $email_improvements_enabled ? '<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation"><tr><td class="email-additional-content">' : '';
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
	echo $email_improvements_enabled ? '</td></tr></table>' : '';
}

endif;

do_action( 'woocommerce_email_footer', $email );
