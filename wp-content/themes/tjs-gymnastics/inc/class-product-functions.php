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
