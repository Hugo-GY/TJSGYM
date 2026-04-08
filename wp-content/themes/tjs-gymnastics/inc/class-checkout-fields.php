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
    
    echo '<div id="tjs-student-info" style="margin: 20px 0; padding: 20px; background: #f9f9f9; border-radius: 8px;">';
    echo '<h2 style="margin-bottom: 20px;">Student Information</h2>';
    
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
 * Add custom CSS for checkout fields
 */
function tjs_checkout_fields_css() {
    if (is_checkout()) {
        echo '<style>
            #tjs-student-info h2 {
                font-size: 1.5em;
                color: #333;
            }
            #tjs-student-info .form-row {
                margin-bottom: 15px;
            }
            #tjs-student-info label {
                display: block;
                margin-bottom: 5px;
                font-weight: 600;
            }
            #tjs-student-info input,
            #tjs-student-info textarea {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            #tjs-student-info input:focus,
            #tjs-student-info textarea:focus {
                border-color: #0073aa;
                outline: none;
            }
            .tjs-student-admin p {
                margin: 5px 0;
            }
        </style>';
    }
}
add_action('wp_head', 'tjs_checkout_fields_css');
