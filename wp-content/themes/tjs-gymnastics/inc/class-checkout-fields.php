<?php
/**
 * WooCommerce Checkout Fields
 * Adds student information fields to checkout
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom checkout fields for class bookings
 */
function tjs_add_checkout_fields($checkout) {
    // Check if cart contains class products
    if (!tjs_cart_has_class_product()) {
        return;
    }
    
    echo '<div id="tjs-student-info">';
    echo '<h2>Student Information</h2>';
    
    // Student Name
    woocommerce_form_field('student_name', array(
        'type'        => 'text',
        'class'       => array('form-row-wide'),
        'label'       => 'Child\'s Full Name',
        'placeholder' => 'Enter child\'s full name',
        'required'    => true,
    ), $checkout->get_value('student_name'));
    
    // Date of Birth
    woocommerce_form_field('student_dob', array(
        'type'        => 'text',
        'class'       => array('form-row-first'),
        'label'       => 'Date of Birth',
        'placeholder' => 'DD/MM/YYYY',
        'required'    => true,
    ), $checkout->get_value('student_dob'));
    
    // Age
    woocommerce_form_field('student_age', array(
        'type'        => 'number',
        'class'       => array('form-row-last'),
        'label'       => 'Age',
        'placeholder' => 'Age in years',
        'required'    => false,
        'min'         => 0,
        'max'         => 18,
    ), $checkout->get_value('student_age'));
    
    // Additional Notes
    woocommerce_form_field('student_notes', array(
        'type'        => 'textarea',
        'class'       => array('form-row-wide'),
        'label'       => 'Medical / Special Requirements',
        'placeholder' => 'Please note any medical conditions, allergies, or special requirements we should be aware of',
        'required'    => false,
    ), $checkout->get_value('student_notes'));
    
    echo '</div>';
}
add_action('woocommerce_checkout_before_customer_details', 'tjs_add_checkout_fields');

/**
 * Check if cart contains class products
 */
function tjs_cart_has_class_product() {
    if (function_exists('WC') && WC()->cart) {
        foreach (WC()->cart->get_cart() as $cart_item) {
            $product_id = $cart_item['product_id'];
            $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'slugs'));
            
            if (in_array('classes', $categories) ||
                in_array('tiddler-gym', $categories) ||
                in_array('toddler-gym', $categories) ||
                in_array('mini-gym', $categories) ||
                in_array('gymnastics', $categories)) {
                return true;
            }
        }
    }
    return false;
}

/**
 * Save custom checkout fields
 */
function tjs_save_checkout_fields($order_id) {
    if (isset($_POST['student_name']) && !empty($_POST['student_name'])) {
        update_post_meta($order_id, 'student_name', sanitize_text_field($_POST['student_name']));
    }
    
    if (isset($_POST['student_dob']) && !empty($_POST['student_dob'])) {
        update_post_meta($order_id, 'student_dob', sanitize_text_field($_POST['student_dob']));
    }
    
    if (isset($_POST['student_age']) && !empty($_POST['student_age'])) {
        update_post_meta($order_id, 'student_age', sanitize_text_field($_POST['student_age']));
    }
    
    if (isset($_POST['student_notes']) && !empty($_POST['student_notes'])) {
        update_post_meta($order_id, 'student_notes', sanitize_textarea_field($_POST['student_notes']));
    }
}
add_action('woocommerce_checkout_create_order', 'tjs_save_checkout_fields');

/**
 * Display student info in admin order details
 */
function tjs_display_admin_order_meta($order) {
    $student_name = get_post_meta($order->get_id(), 'student_name', true);
    $student_dob = get_post_meta($order->get_id(), 'student_dob', true);
    $student_age = get_post_meta($order->get_id(), 'student_age', true);
    $student_notes = get_post_meta($order->get_id(), 'student_notes', true);
    
    if (empty($student_name) && empty($student_dob) && empty($student_age) && empty($student_notes)) {
        return;
    }
    
    echo '<div class="tjs-student-admin" style="margin: 20px 0; padding: 15px; background: #f5f5f5; border-radius: 5px;">';
    echo '<h4>Student Information</h4>';
    
    if (!empty($student_name)) {
        echo '<p><strong>Child\'s Name:</strong> ' . esc_html($student_name) . '</p>';
    }
    
    if (!empty($student_dob)) {
        echo '<p><strong>Date of Birth:</strong> ' . esc_html($student_dob) . '</p>';
    }
    
    if (!empty($student_age)) {
        echo '<p><strong>Age:</strong> ' . esc_html($student_age) . ' years</p>';
    }
    
    if (!empty($student_notes)) {
        echo '<p><strong>Medical/Notes:</strong> ' . esc_html($student_notes) . '</p>';
    }
    
    echo '</div>';
}
add_action('woocommerce_admin_order_data_after_order_details', 'tjs_display_admin_order_meta');

/**
 * Add student info to order emails
 */
function tjs_email_order_meta($order, $sent_to_admin, $plain_text) {
    $student_name = get_post_meta($order->get_id(), 'student_name', true);
    $student_dob = get_post_meta($order->get_id(), 'student_dob', true);
    $student_age = get_post_meta($order->get_id(), 'student_age', true);
    $student_notes = get_post_meta($order->get_id(), 'student_notes', true);
    
    if (empty($student_name) && empty($student_dob) && empty($student_age) && empty($student_notes)) {
        return;
    }
    
    if ($plain_text) {
        echo "Student Information\n";
        echo "-------------------\n";
        if (!empty($student_name)) echo "Child's Name: " . $student_name . "\n";
        if (!empty($student_dob)) echo "Date of Birth: " . $student_dob . "\n";
        if (!empty($student_age)) echo "Age: " . $student_age . " years\n";
        if (!empty($student_notes)) echo "Notes: " . $student_notes . "\n";
    } else {
        echo '<table style="margin: 20px 0; width: 100%; border-collapse: collapse;">';
        echo '<tr><td style="padding: 10px; background: #f5f5f5; font-weight: bold;" colspan="2">Student Information</td></tr>';
        if (!empty($student_name)) echo '<tr><td style="padding: 5px;">Child\'s Name:</td><td style="padding: 5px;">' . esc_html($student_name) . '</td></tr>';
        if (!empty($student_dob)) echo '<tr><td style="padding: 5px;">Date of Birth:</td><td style="padding: 5px;">' . esc_html($student_dob) . '</td></tr>';
        if (!empty($student_age)) echo '<tr><td style="padding: 5px;">Age:</td><td style="padding: 5px;">' . esc_html($student_age) . ' years</td></tr>';
        if (!empty($student_notes)) echo '<tr><td style="padding: 5px;">Notes:</td><td style="padding: 5px;">' . esc_html($student_notes) . '</td></tr>';
        echo '</table>';
    }
}
add_action('woocommerce_email_order_meta', 'tjs_email_order_meta', 10, 3);

/**
 * Validate checkout fields
 */
function tjs_validate_checkout_fields($fields, $errors) {
    if (!tjs_cart_has_class_product()) {
        return;
    }

    if (empty($_POST['student_name'])) {
        $errors->add('student_name', 'Please enter the child\'s full name');
    }

    if (empty($_POST['student_dob'])) {
        $errors->add('student_dob', 'Please enter the child\'s date of birth');
    }
}
add_action('woocommerce_after_checkout_validation', 'tjs_validate_checkout_fields');

/**
 * =============================================================================
 * EMAIL TEMPLATE DATA EXTRACTION FUNCTIONS
 * =============================================================================
 * These functions extract booking data from orders for use in custom email templates
 */

/**
 * Check if an order contains class products
 *
 * @param WC_Order $order The order object
 * @return bool True if order contains class products
 */
function tjs_order_has_class_product($order) {
    $items = $order->get_items();

    foreach ($items as $item) {
        $product = $item->get_product();
        if (!$product) continue;

        // Handle variation products - get parent product ID
        $product_id = $product->get_id();
        if ($product->is_type('variation')) {
            $parent_id = $product->get_parent_id();
            if ($parent_id) {
                $product_id = $parent_id;
            }
        }

        $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'slugs'));

        if (in_array('classes', $categories) ||
            in_array('tiddler-gym', $categories) ||
            in_array('toddler-gym', $categories) ||
            in_array('mini-gym', $categories) ||
            in_array('gymnastics', $categories)) {
            return true;
        }
    }
    return false;
}

/**
 * Get booking type from product
 *
 * @param WC_Product $product The product object
 * @return string Booking type label
 */
function tjs_get_booking_type($product) {
    if (!$product) return 'Full-term Booking';
    
    if (function_exists('get_field')) {
        $pay_type = get_field('pay_type', $product->get_id());
        if ($pay_type === 'per_class') {
            return 'Per Class Booking';
        }
    }
    
    return 'Full-term Booking';
}

/**
 * Get term/season information from product
 *
 * @param WC_Product $product The product object
 * @return string Term name (e.g., "Summer 2026")
 */
function tjs_get_product_term($product) {
    if (!$product) return '';
    
    if (function_exists('get_field')) {
        $season = get_field('ct_season', $product->get_id());
        if (!empty($season)) {
            return esc_html($season);
        }
    }
    
    return '';
}

/**
 * Get session day from order item or product attributes
 *
 * @param WC_Order $order The order object
 * @param WC_Product $product The product object
 * @return string Session day (e.g., "Saturday")
 */
function tjs_get_session_day($order, $product) {
    if (!$product) return '';
    
    $items = $order->get_items();
    foreach ($items as $item) {
        if ($item->get_product_id() === $product->get_id()) {
            $product_obj = $item->get_product();
            if (!$product_obj) continue;
            
            // Check if it's a variation
            if ($product_obj->is_type('variation')) {
                $attributes = $product_obj->get_attributes();
                if (isset($attributes['pa_class-day'])) {
                    $day = $attributes['pa_class-day'];
                    return ucwords(str_replace('-', ' ', $day));
                }
            }
            
            // Also try to get from item meta (for backward compatibility)
            $meta_day = $item->get_meta('pa_class-day');
            if (!empty($meta_day)) {
                return ucwords(str_replace('-', ' ', $meta_day));
            }
        }
    }
    
    return '';
}

/**
 * Get session time from order item
 *
 * @param WC_Order $order The order object
 * @param WC_Product $product The product object
 * @return string Formatted session time (e.g., "09:40 – 10:20")
 */
function tjs_get_session_time($order, $product) {
    if (!$product || !function_exists('tjs_format_time_slot')) return '';
    
    $items = $order->get_items();
    foreach ($items as $item) {
        if ($item->get_product_id() === $product->get_id()) {
            // Method 1: Try to get from item meta directly (most reliable)
            $time_meta = $item->get_meta('_time_slot');
            if (!empty($time_meta)) {
                return tjs_format_time_slot($time_meta);
            }
            
            // Method 2: Try common time-related meta keys
            $possible_keys = array(
                'time_slot',
                'session_time',
                'class_time',
                '_time_slot',
                '_session_time'
            );
            
            foreach ($possible_keys as $key) {
                $value = $item->get_meta($key);
                if (!empty($value)) {
                    return tjs_format_time_slot($value);
                }
            }
            
            // Method 3: Get from product variation attributes or meta
            $product_obj = $item->get_product();
            if ($product_obj && $product_obj->is_type('variation')) {
                // Check variation attributes for time info
                $attributes = $product_obj->get_attributes();
                foreach ($attributes as $attr_key => $attr_value) {
                    if (strpos($attr_key, 'time') !== false && !empty($attr_value)) {
                        return tjs_format_time_slot($attr_value);
                    }
                }
                
                // Check variation meta
                $variation_time = $product_obj->get_meta('time_slot');
                if (!empty($variation_time)) {
                    return tjs_format_time_slot($variation_time);
                }
            }
        }
    }
    
    return '';
}

/**
 * Get venue name from product ACF fields
 *
 * @param WC_Product $product The product object
 * @return string Venue name
 */
function tjs_get_venue_name($product) {
    if (!$product) return '';
    
    if (function_exists('get_field')) {
        $venue = get_field('venue_name', $product->get_id());
        if (!empty($venue)) {
            return esc_html($venue);
        }
        
        $venue_alt = get_field('ct_venue', $product->get_id());
        if (!empty($venue_alt)) {
            return esc_html($venue_alt);
        }
    }
    
    return 'Raynes Park Sports Pavilion';
}

/**
 * Get complete booking data array for email template
 *
 * @param WC_Order $order The WooCommerce order object
 * @return array Associative array of all booking data
 */
function tjs_get_order_booking_data($order) {
    if (!$order) return array();
    
    $order_id = $order->get_id();
    $items = $order->get_items();
    $first_item = reset($items);
    $product = null;
    
    if ($first_item) {
        $product = $first_item->get_product();
        if ($product->is_type('variation')) {
            $product = wc_get_product($product->get_parent_id());
        }
    }
    
    $booking_date = $order->get_date_created();
    $date_formatted = $booking_date ? $booking_date->format('d F Y') : date('d F Y');
    $date_for_ref = $booking_date ? $booking_date->format('Y-m-d') : date('Y-m-d');
    
    $data = array(
        'class_name'          => $first_item ? $first_item->get_name() : '',
        'booking_type'        => tjs_get_booking_type($product),
        'term'                => tjs_get_product_term($product),
        'session_day'         => tjs_get_session_day($order, $product),
        'session_time'        => tjs_get_session_time($order, $product),
        'venue_name'          => tjs_get_venue_name($product),
        'price'               => html_entity_decode($order->get_formatted_order_total()),
        'booking_date'        => $date_formatted,
        'booking_reference'   => sprintf('TJ-%s-%03d', $date_for_ref, $order_id),
        'parent_name'         => trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()),
        'child_name'          => get_post_meta($order_id, 'student_name', true),
        'child_dob'           => get_post_meta($order_id, 'student_dob', true),
        'email'               => $order->get_billing_email(),
        'phone'               => $order->get_billing_phone(),
        'message'             => get_post_meta($order_id, 'student_notes', true)
    );
    
    return $data;
}
