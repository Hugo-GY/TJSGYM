<?php
/**
 * Helper functions for class product data
 *
 * Enhanced with caching for better performance
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Cache expiry time (1 hour)
 */
define('TJS_PRODUCT_CACHE_EXPIRY', HOUR_IN_SECONDS);

/**
 * Format time slot from compact format (1030-1110) to display format (10:30 – 11:10)
 */
function tjs_format_time_slot($time) {
    if (empty($time)) {
        return '';
    }

    // If already in correct format, just normalize the dash
    if (preg_match('/\d{1,2}:\d{2}\s*[–-]\s*\d{1,2}:\d{2}/', $time)) {
        return str_replace(array('–', '-'), ' – ', $time);
    }

    // Handle compact format like 1030-1110 or 1030–1110
    if (preg_match('/(\d{3,4})\s*[–-]\s*(\d{3,4})/', $time, $matches)) {
        $start = $matches[1];
        $end = $matches[2];

        // Pad to 4 digits if needed
        $start = str_pad($start, 4, '0', STR_PAD_LEFT);
        $end = str_pad($end, 4, '0', STR_PAD_LEFT);

        // Format as HH:MM
        $start_formatted = substr($start, 0, 2) . ':' . substr($start, 2, 2);
        $end_formatted = substr($end, 0, 2) . ':' . substr($end, 2, 2);

        return $start_formatted . ' – ' . $end_formatted;
    }

    return $time;
}

/**
 * Get class product by slug (with caching)
 *
 * @param string $slug Product slug
 * @param bool $force_refresh Force cache refresh
 * @return WC_Product|null Product object or null
 */
function tjs_get_class_product($slug, $force_refresh = false) {
    $cache_key = 'tjs_product_' . md5($slug);

    // Try to get from cache first
    if (!$force_refresh) {
        $cached_product_id = get_transient($cache_key);
        if ($cached_product_id && is_numeric($cached_product_id)) {
            $product = wc_get_product($cached_product_id);
            if ($product) {
                return $product;
            }
        }
    }

    // Not in cache or force refresh - query database
    $product_query = new WP_Query(array(
        'post_type' => 'product',
        'name' => $slug,
        'posts_per_page' => 1
    ));

    $product = null;
    if ($product_query->have_posts()) {
        $product_query->the_post();
        $product = wc_get_product(get_the_ID());

        // Cache the product ID
        if ($product) {
            set_transient($cache_key, $product->get_id(), TJS_PRODUCT_CACHE_EXPIRY);
        }
    }
    wp_reset_postdata();

    return $product;
}

/**
 * Clear product cache for a specific slug
 *
 * @param string $slug Product slug
 */
function tjs_clear_product_cache($slug) {
    $cache_key = 'tjs_product_' . md5($slug);
    delete_transient($cache_key);

    // Also clear sessions cache
    $sessions_cache_key = 'tjs_sessions_' . md5($slug);
    delete_transient($sessions_cache_key);
}

/**
 * Get class sessions from product variations (with caching)
 *
 * @param WC_Product $product The product object
 * @param int $max_stock Maximum stock quantity (default: 18)
 * @param string $pay_type Payment type: 'per_term' or 'per_class' (default: 'per_term')
 * @param bool $force_refresh Force cache refresh
 * @return array Array of session data
 */
function tjs_get_class_sessions($product, $max_stock = 18, $pay_type = 'per_term', $force_refresh = false) {
    if (!$product || !$product->is_type('variable')) {
        return array();
    }

    $sessions_cache_key = 'tjs_sessions_' . md5($product->get_slug() . '_' . $max_stock . '_' . $pay_type);

    // Try cache first
    if (!$force_refresh) {
        $cached_sessions = get_transient($sessions_cache_key);
        if ($cached_sessions !== false && is_array($cached_sessions)) {
            return $cached_sessions;
        }
    }

    $sessions = array();

    // Auto-detect pay_type from ACF field if available
    if (function_exists('get_field') && empty($pay_type)) {
        $acf_pay_type = get_field('pay_type', $product->get_id());
        if ($acf_pay_type) {
            $pay_type = $acf_pay_type;
        }
    }

    $price_suffix = ($pay_type === 'per_class') ? ' / class' : ' / term';
    $variations = $product->get_available_variations();

    foreach ($variations as $variation_data) {
        $variation = wc_get_product($variation_data['variation_id']);
        if (!$variation) continue;

        $attributes = $variation->get_attributes();
        // Support both taxonomy attributes (pa_*) and custom attributes
        $day = isset($attributes['pa_class-day']) ? $attributes['pa_class-day'] :
               (isset($attributes['class-day']) ? $attributes['class-day'] : '');
        $time_raw = isset($attributes['pa_time-slot']) ? $attributes['pa_time-slot'] :
                (isset($attributes['time-slot']) ? $attributes['time-slot'] : '');
        $time = tjs_format_time_slot($time_raw);
        $group = isset($attributes['pa_group-level']) ? $attributes['pa_group-level'] :
                 (isset($attributes['group-level']) ? $attributes['group-level'] : '');

        if (empty($day) || empty($time)) continue;

        $price = $variation->get_price();
        $stock = $variation->get_stock_quantity();

        if ($stock === null || $stock === '') {
            $stock = $max_stock;
        }

        $availability = $stock . ' / ' . $max_stock;
        $status = 'available';
        if ($stock <= 0) {
            $availability = 'Full';
            $status = 'full';
        } elseif ($stock <= 3) {
            $status = 'limited';
        }

        $sessions[] = array(
            'day' => $day,
            'time' => $time,
            'price' => '£' . $price . $price_suffix,
            'availability' => $availability,
            'status' => $status,
            'variation_id' => $variation_data['variation_id'],
            'group' => $group
        );
    }

    // Sort sessions
    $day_order = array('Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4);
    usort($sessions, function($a, $b) use ($day_order) {
        $day_a = isset($day_order[$a['day']]) ? $day_order[$a['day']] : 99;
        $day_b = isset($day_order[$b['day']]) ? $day_order[$b['day']] : 99;

        if ($day_a !== $day_b) {
            return $day_a - $day_b;
        }

        $time_a = strtotime(explode('–', $a['time'])[0]);
        $time_b = strtotime(explode('–', $b['time'])[0]);
        return $time_a - $time_b;
    });

    // Cache results (shorter expiry for stock data: 15 minutes)
    set_transient($sessions_cache_key, $sessions, 15 * MINUTE_IN_SECONDS);

    return $sessions;
}

/**
 * Get class modifier from product categories
 *
 * @param int $product_id The product ID
 * @return string Modifier string (tiddler, toddler, minigym, gym)
 */
function tjs_get_class_modifier($product_id) {
    $modifier = 'gym';
    $class_categories = wp_get_post_terms($product_id, 'product_cat');

    foreach ($class_categories as $cat) {
        if ($cat->slug === 'tiddler-gym') $modifier = 'tiddler';
        if ($cat->slug === 'toddler-gym') $modifier = 'toddler';
        if ($cat->slug === 'mini-gym') $modifier = 'minigym';
        if ($cat->slug === 'gymnastics') $modifier = 'gym';
    }

    return $modifier;
}

/**
 * Default term data (fallback when ACF not configured)
 *
 * @return array Array of term information
 */
if (!function_exists('tjs_get_default_terms')) {
    function tjs_get_default_terms() {
        return array(
        array(
            'term_season' => 'Summer 2026',
            'term_status' => 'Teaching now',
            'term_weeks' => '13 weeks',
            'term_dates' => "13 Apr – 21 May\n1 Jun – 16 Jul",
            'term_halfterm' => 'Half term: w/k 25 May · No class 4 May',
            'term_payment_due' => 'Payment due by 12 March'
        ),
        array(
            'term_season' => 'Winter 2026',
            'term_status' => 'Next term',
            'term_weeks' => '12 weeks',
            'term_dates' => "7 Sep – 16 Oct\n2 Nov – 10 Dec",
            'term_halfterm' => '2-week half term: w/k 19 October',
            'term_payment_due' => 'Payment due by 26 June'
        ),
        array(
            'term_season' => 'Spring 2027',
            'term_status' => 'Planning ahead',
            'term_weeks' => '11 weeks',
            'term_dates' => "4 Jan – 11 Feb\n22 Feb – 25 Mar",
            'term_halfterm' => 'Half term: w/k 15 February',
            'term_payment_due' => 'Payment due by 27 November'
        )
    );
    }
}

/**
 * Clear all class product caches (useful after product updates)
 */
function tjs_clear_all_class_caches() {
    global $wpdb;

    // Delete all transients starting with 'tjs_'
    $wpdb->query(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_tjs_%'"
    );

    // Also delete timeout entries
    $wpdb->query(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_tjs_%'"
    );
}

// Hook to clear caches when products are updated
add_action('save_post_product', function($post_id) {
    $product = wc_get_product($post_id);
    if ($product) {
        tjs_clear_product_cache($product->get_slug());
    }
});

// Clear booking cache when variation is updated
add_action('save_post_product_variation', function($post_id) {
    delete_transient('tjs_booking_v' . $post_id);
});

/**
 * Get complete booking session data from variation ID (with caching)
 *
 * Server-side data query for booking pages. Returns all needed info
 * for rendering the booking form, including dual pricing support.
 *
 * @param int $variation_id The WooCommerce variation ID
 * @param bool $force_refresh Force cache refresh
 * @return array|WP_Error Associative array of booking data or error
 */
function tjs_get_booking_session_data($variation_id, $force_refresh = false) {
    if (!$variation_id || !is_numeric($variation_id)) {
        return new WP_Error('invalid_input', 'Invalid variation ID provided');
    }

    $cache_key = 'tjs_booking_v' . intval($variation_id);

    if (!$force_refresh) {
        $cached = get_transient($cache_key);
        if ($cached !== false && is_array($cached)) {
            return $cached;
        }
    }

    $variation = wc_get_product(intval($variation_id));
    if (!$variation || !$variation->is_type('variation')) {
        return new WP_Error('invalid_variation', 'Variation not found or invalid type');
    }

    $parent_id = $variation->get_parent_id();
    if (!$parent_id) {
        return new WP_ERROR('no_parent', 'Variation has no parent product');
    }

    $product = wc_get_product($parent_id);
    if (!$product) {
        return new WP_ERROR('invalid_product', 'Parent product not found');
    }

    $attributes = $variation->get_attributes();

    $day = isset($attributes['pa_class-day']) ? $attributes['pa_class-day'] :
           (isset($attributes['class-day']) ? $attributes['class-day'] : '');
    $time_raw = isset($attributes['pa_time-slot']) ? $attributes['pa_time-slot'] :
              (isset($attributes['time-slot']) ? $attributes['time-slot'] : '');
    $group = isset($attributes['pa_group-level']) ? $attributes['pa_group-level'] :
            (isset($attributes['group-level']) ? $attributes['group-level'] : '');

    if (empty($day) || empty($time_raw)) {
        return new WP_ERROR('missing_attrs', 'Required attributes missing on variation');
    }

    $time = tjs_format_time_slot($time_raw);

    $price = floatval($variation->get_price());
    $stock = $variation->get_stock_quantity();

    $modifier = tjs_get_class_modifier($parent_id);
    $max_stock_map = array(
        'tiddler' => 10,
        'toddler' => 18,
        'minigym' => 10,
        'gym' => 20
    );
    $max_stock = isset($max_stock_map[$modifier]) ? $max_stock_map[$modifier] : 18;

    if ($stock === null || $stock === '') {
        $stock = $max_stock;
    }

    $availability = ($stock > 0) ? "{$stock} / {$max_stock}" : 'Full';

    $pay_type = 'per_term';
    $enable_trial = false;
    $trial_price = 0;
    $current_term_season = '';
    $age_range = '';

    if (function_exists('get_field')) {
        $acf_pay_type = get_field('pay_type', $parent_id);
        if ($acf_pay_type) {
            $pay_type = $acf_pay_type;
        }

        $enable_trial_val = get_field('enable_trial', $parent_id);
        $enable_trial = ($enable_trial_val === true || $enable_trial_val === '1' || $enable_trial_val === 1);

        $trial_price_raw = get_field('trial_price', $parent_id);
        if (is_numeric($trial_price_raw) && $trial_price_raw > 0) {
            $trial_price = floatval($trial_price_raw);
        }

        $term_info = get_field('term_info', $parent_id);
        if (is_array($term_info) && !empty($term_info) && isset($term_info[0]['term_season'])) {
            $current_term_season = $term_info[0]['term_season'];
        }

        $age_range_val = get_field('age_range', $parent_id);
        if ($age_range_val) {
            $age_range = $age_range_val;
        }
    }

    $price_suffix = ($pay_type === 'per_class') ? ' / class' : ' / term';

    $data = array(
        'variation_id' => intval($variation_id),
        'product_id' => intval($parent_id),
        'class_name' => $product->get_name(),
        'class_slug' => $product->get_slug(),
        'age_range' => $age_range,
        'term' => $current_term_season,
        'day' => $day,
        'time' => $time,
        'time_raw' => $time_raw,
        'group' => $group,
        'price_full' => '£' . number_format($price, 0) . $price_suffix,
        'price_trial' => ($enable_trial && $trial_price > 0)
            ? '£' . number_format($trial_price, 0) . ' / class'
            : '',
        'price_full_raw' => $price,
        'price_trial_raw' => $trial_price,
        'availability' => $availability,
        'stock' => intval($stock),
        'max_stock' => intval($max_stock),
        'is_available' => ($stock > 0),
        'enable_trial' => $enable_trial,
        'pay_type' => $pay_type,
        'modifier' => $modifier,
        'permalink' => get_permalink($parent_id)
    );

    set_transient($cache_key, $data, 15 * MINUTE_IN_SECONDS);

    return $data;
}

/**
 * Get class configuration by slug
 *
 * Returns default configuration for a specific class type.
 * Used by the Dynamic Class Template to load class-specific settings.
 *
 * @param string $class_slug The class product slug (e.g., 'tiddler-gym', 'toddler-gym-product')
 * @return array|false Configuration array or false if not found
 */
function tjs_get_class_config($class_slug) {
    $configs = array(
        'tiddler-gym' => array(
            'name' => 'Tiddler Gym',
            'settings' => array(
                'max_slots' => 10,
                'modifier' => 'tiddler',
                'pricing_type' => 'per_class'
            ),
            'defaults' => array(
                'age_range' => '6–12 Months',
                'about_title' => '<em>Parent & Baby</em> movement, music and play',
                'about_lead' => 'Tiddler Gym is designed to offer a fun, safe environment for parent or carer and baby — encouraging physical play and interaction through music, rolling, rocking, crawling and balancing. The equipment is carefully chosen for the Tiddler age range: tactile, bright and engaging for young explorers.',
                'about_content' => '<p>The circuit changes every single week, so there\'s always something fresh to discover. It\'s also a lovely chance for parents and carers to relax, have a chat, and watch their little ones thrive.</p>',
                'sessions' => array(
                    array('day' => 'Thursday', 'time' => '10:30 – 11:10', 'price' => '£10 / class', 'availability' => '8 / 10', 'status' => 'available', 'variation_id' => 0)
                ),
                'gallery' => array(
                    array('src' => 'gallery-1.jpg', 'alt' => 'Tiddler Gym class photo 1'),
                    array('src' => 'gallery-2.jpg', 'alt' => 'Tiddler Gym class photo 2'),
                    array('src' => 'gallery-3.jpg', 'alt' => 'Tiddler Gym class photo 3'),
                    array('src' => 'gallery-4.jpg', 'alt' => 'Tiddler Gym class photo 4'),
                    array('src' => 'hero.jpg', 'alt' => 'Tiddler Gym class photo 5')
                )
            )
        ),
        'toddler-gym-product' => array(
            'name' => 'Toddler Gym',
            'settings' => array(
                'max_slots' => 18,
                'modifier' => 'tiddler',
                'pricing_type' => 'term'
            ),
            'defaults' => array(
                'age_range' => '1–3 Years',
                'about_title' => 'Action songs, circuits and <em>adventure</em> — together',
                'about_lead' => 'A parent-accompanied class for 1 to 3 year olds. Each session opens with action songs and a structured warm-up — teaching toddlers to listen, copy and join in before the equipment circuit begins. The gym layout changes every single week, so there\'s always something new to climb, balance on and explore.',
                'about_content' => '<p>Coaches are on hand throughout, helping with climbing, encouraging safe and varied use of the equipment, and building a warm rapport with the children. Sessions close with calming songs to bring little ones together gently before the end of class.</p>',
                'sessions' => array(
                    array('day' => 'Tuesday', 'time' => '9:40 – 10:20', 'price' => '£143 / term', 'availability' => '3 / 18', 'status' => 'limited', 'variation_id' => 0),
                    array('day' => 'Tuesday', 'time' => '10:30 – 11:10', 'price' => '£143 / term', 'availability' => '1 / 18', 'status' => 'limited', 'variation_id' => 0),
                    array('day' => 'Wednesday', 'time' => '9:30 – 10:10', 'price' => '£143 / term', 'availability' => '1 / 18', 'status' => 'limited', 'variation_id' => 0),
                    array('day' => 'Thursday', 'time' => '9:30 – 10:10', 'price' => '£143 / term', 'availability' => 'Full', 'status' => 'full', 'variation_id' => 0)
                ),
                'gallery' => array(
                    array('src' => 'gallery-1.jpg', 'alt' => 'Toddler Gym class photo 1'),
                    array('src' => 'gallery-2.jpg', 'alt' => 'Toddler Gym class photo 2'),
                    array('src' => 'gallery-3.jpg', 'alt' => 'Toddler Gym class photo 3'),
                    array('src' => 'gallery-4.jpg', 'alt' => 'Toddler Gym class photo 4'),
                    array('src' => 'gallery-5.jpg', 'alt' => 'Toddler Gym class photo 5')
                )
            )
        )
    );

    return isset($configs[$class_slug]) ? $configs[$class_slug] : false;
}

/**
 * AJAX Handler: Add variation to cart and redirect to checkout
 *
 * Handles the booking form submission, adds the selected variation
 * to WooCommerce cart, saves customer data, and returns checkout URL.
 */
add_action('wp_ajax_tjs_add_to_cart', 'tjs_ajax_add_to_cart');
add_action('wp_ajax_nopriv_tjs_add_to_cart', 'tjs_ajax_add_to_cart');

function tjs_ajax_add_to_cart() {
    // Verify nonce
    if (!isset($_POST['booking_nonce']) || !wp_verify_nonce($_POST['booking_nonce'], 'tjs_booking_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed. Please refresh the page and try again.'));
    }

    // Get variation ID
    $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
    if ($variation_id <= 0) {
        wp_send_json_error(array('message' => 'Invalid variation selected.'));
    }

    // Get product ID
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    if ($product_id <= 0) {
        wp_send_json_error(array('message' => 'Invalid product.'));
    }

    // Validate product and variation
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(array('message' => 'Product not found.'));
    }

    $variation = wc_get_product($variation_id);
    if (!$variation || !$variation->is_type('variation')) {
        wp_send_json_error(array('message' => 'Variation not found.'));
    }

    // Check stock
    if (!$variation->is_in_stock()) {
        wp_send_json_error(array('message' => 'This session is fully booked. Please select another session.'));
    }

    // Get quantity (always 1 for class bookings)
    $quantity = 1;

    // Get variation attributes
    $var_attributes = $variation->get_attributes();
    $cart_item_data = array();

    // Add customer information to cart item data
    $customer_fields = array(
        'child_name',
        'child_dob',
        'parent_name',
        'email',
        'phone',
        'message',
        'booking-type'
    );

    foreach ($customer_fields as $field) {
        if (isset($_POST[$field]) && !empty($_POST[$field])) {
            $cart_item_data[$field] = sanitize_text_field($_POST[$field]);
        }
    }

    // Add metadata for order processing
    $cart_item_data['unique_key'] = md5($variation_id . '_' . time());

    // Clear cart first (one booking at a time)
    WC()->cart->empty_cart();

    // Add to cart
    $cart_item_key = WC()->cart->add_to_cart(
        $product_id,
        $quantity,
        $variation_id,
        $var_attributes,
        $cart_item_data
    );

    if (is_wp_error($cart_item_key)) {
        wp_send_json_error(array('message' => $cart_item_key->get_error_message()));
    }

    if (!$cart_item_key) {
        wp_send_json_error(array('message' => 'Failed to add item to cart. Please try again.'));
    }

    // Success - return checkout URL
    $checkout_url = wc_get_checkout_url();

    wp_send_json_success(array(
        'message' => 'Item added to cart successfully!',
        'redirect_url' => $checkout_url,
        'cart_url' => wc_get_cart_url()
    ));
}
