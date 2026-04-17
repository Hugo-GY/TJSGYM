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
 * Check if a product belongs to the 'classes' category tree.
 *
 * Tests whether the product is assigned to any child category of 'classes'
 * (tiddler-gym, toddler-gym, mini-gym, gymnastics). Products should not be
 * assigned directly to the parent 'classes' category.
 *
 * @param int $product_id The product (or parent product) ID.
 * @return bool
 */
function tjs_is_class_product($product_id) {
    $class_cats = array('tiddler-gym', 'toddler-gym', 'mini-gym', 'gymnastics');
    return has_term($class_cats, 'product_cat', $product_id);
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

    tjs_clear_class_runtime_caches();
}

/**
 * Clear runtime caches used by class session and booking lookups.
 */
function tjs_clear_class_runtime_caches() {
    global $wpdb;

    $patterns = array(
        '_transient_tjs_sessions_%',
        '_transient_timeout_tjs_sessions_%',
        '_transient_tjs_booking_v%',
        '_transient_timeout_tjs_booking_v%'
    );

    foreach ($patterns as $pattern) {
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                $pattern
            )
        );
    }
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

        $schedule = tjs_get_variation_schedule_data($variation);
        $day = $schedule['day'];
        $time = $schedule['time'];
        $group = $schedule['group'];

        if (empty($day) || empty($time)) continue;

        $price = $variation->get_price();
        $capacity = tjs_get_variation_capacity($variation, $max_stock);
        $stock = $variation->get_stock_quantity();

        if ($stock === null || $stock === '') {
            $stock = $capacity;
        }

        $availability = $stock . ' / ' . $capacity;
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
            'length' => $schedule['length'],
            'availability' => $availability,
            'status' => $status,
            'variation_id' => $variation_data['variation_id'],
            'group' => $group,
            'stock' => intval($stock),
            'max_stock' => intval($capacity)
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
 * Get the default class capacity for a product based on its category.
 *
 * @param int|WC_Product $product_or_id Product or product ID.
 * @return int
 */
function tjs_get_default_class_capacity($product_or_id) {
    $product = null;
    $product_id = 0;

    if ($product_or_id instanceof WC_Product) {
        $product = $product_or_id;
        $product_id = $product->get_id();
    } else {
        $product_id = intval($product_or_id);
        if ($product_id > 0) {
            $product = wc_get_product($product_id);
        }
    }

    if ($product && $product->is_type('variation')) {
        $product_id = $product->get_parent_id();
    }

    $max_stock_map = array(
        'tiddler' => 10,
        'toddler' => 18,
        'minigym' => 10,
        'gym' => 20
    );

    $modifier = $product_id ? tjs_get_class_modifier($product_id) : 'gym';

    return isset($max_stock_map[$modifier]) ? $max_stock_map[$modifier] : 18;
}

/**
 * Get the configured total capacity for a variation.
 *
 * Stock quantity continues to represent the remaining places.
 * Capacity is stored separately so each variation can show its own total.
 *
 * @param int|WC_Product $variation Variation object or ID.
 * @param int|null $fallback_capacity Optional fallback capacity.
 * @return int
 */
function tjs_get_variation_capacity($variation, $fallback_capacity = null) {
    if (is_numeric($variation)) {
        $variation = wc_get_product(intval($variation));
    }

    if (!$variation || !$variation->is_type('variation')) {
        return max(0, intval($fallback_capacity));
    }

    $configured_capacity = get_post_meta($variation->get_id(), '_tjs_class_capacity', true);
    if ($configured_capacity !== '' && $configured_capacity !== null) {
        return max(0, intval($configured_capacity));
    }

    if ($fallback_capacity !== null && $fallback_capacity !== '') {
        return max(0, intval($fallback_capacity));
    }

    return tjs_get_default_class_capacity($variation);
}

/**
 * Get the fallback session row for a variation from class config defaults.
 *
 * Some legacy class products only store the class day on the variation. In
 * that case the theme defaults are used to recover the matching session time.
 *
 * @param int|WC_Product $variation Variation object or ID.
 * @return array
 */
function tjs_get_variation_fallback_session($variation) {
    if (is_numeric($variation)) {
        $variation = wc_get_product(intval($variation));
    }

    if (!$variation || !$variation->is_type('variation')) {
        return array();
    }

    $parent = wc_get_product($variation->get_parent_id());
    if (!$parent) {
        return array();
    }

    $config = tjs_get_class_config($parent->get_slug());
    if (!$config || empty($config['defaults']['sessions']) || !is_array($config['defaults']['sessions'])) {
        return array();
    }

    $children = array_values($parent->get_children());
    $index = array_search($variation->get_id(), $children, true);

    if ($index === false || !isset($config['defaults']['sessions'][$index]) || !is_array($config['defaults']['sessions'][$index])) {
        return array();
    }

    return $config['defaults']['sessions'][$index];
}

/**
 * Resolve a variation's schedule data with fallback support.
 *
 * @param int|WC_Product $variation Variation object or ID.
 * @return array
 */
function tjs_get_variation_schedule_data($variation) {
    if (is_numeric($variation)) {
        $variation = wc_get_product(intval($variation));
    }

    if (!$variation || !$variation->is_type('variation')) {
        return array(
            'day' => '',
            'time_raw' => '',
            'time' => '',
            'group' => ''
        );
    }

    $attributes = $variation->get_attributes();
    $day = isset($attributes['pa_class-day']) ? $attributes['pa_class-day'] :
           (isset($attributes['class-day']) ? $attributes['class-day'] : '');
    $time_raw = isset($attributes['pa_time-slot']) ? $attributes['pa_time-slot'] :
                (isset($attributes['time-slot']) ? $attributes['time-slot'] : '');
    $group = isset($attributes['pa_group-level']) ? $attributes['pa_group-level'] :
             (isset($attributes['group-level']) ? $attributes['group-level'] : '');

    $fallback = tjs_get_variation_fallback_session($variation);
    if (empty($day) && !empty($fallback['day'])) {
        $day = $fallback['day'];
    }
    if (empty($time_raw) && !empty($fallback['time'])) {
        $time_raw = $fallback['time'];
    }
    if (empty($group) && !empty($fallback['group'])) {
        $group = $fallback['group'];
    }

    $length = get_post_meta($variation->get_id(), '_tjs_session_length', true);
    if (empty($length)) {
        $parent_id = $variation->get_parent_id();
        if ($parent_id && function_exists('get_field')) {
            $ct_weeks = get_field('ct_weeks', $parent_id);
            if ($ct_weeks) {
                $length = $ct_weeks;
            }
        }
    }

    return array(
        'day' => $day,
        'time_raw' => $time_raw,
        'time' => tjs_format_time_slot($time_raw),
        'group' => $group,
        'length' => $length ?: ''
    );
}

/**
 * Render a shared notice when live schedule data is unavailable.
 *
 * Old templates used to fall back to hard-coded demo session rows, which could
 * show inaccurate availability. This notice avoids displaying fake data.
 *
 * @param string $message Optional custom message.
 */
function tjs_render_schedule_unavailable_notice($message = '') {
    if (empty($message)) {
        $message = __('Current class times and live availability are temporarily unavailable. Please contact us for the latest session information.', 'tjs-gymnastics');
    }
    ?>
    <div class="cd-booking-table-wrap">
        <div class="cd-table-note cd-table-note--highlight">
            <p><?php echo esc_html($message); ?></p>
            <p><a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-magenta btn-sm"><?php _e('Contact Us', 'tjs-gymnastics'); ?></a></p>
        </div>
    </div>
    <?php
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
    $variation = wc_get_product($post_id);
    if ($variation && $variation->is_type('variation')) {
        $parent = wc_get_product($variation->get_parent_id());
        if ($parent) {
            tjs_clear_product_cache($parent->get_slug());
            return;
        }
    }

    delete_transient('tjs_booking_v' . $post_id);
    tjs_clear_class_runtime_caches();
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

    $schedule = tjs_get_variation_schedule_data($variation);
    $day = $schedule['day'];
    $time_raw = $schedule['time_raw'];
    $group = $schedule['group'];

    if (empty($day) || empty($time_raw)) {
        return new WP_ERROR('missing_attrs', 'Required attributes missing on variation');
    }

    $time = $schedule['time'];

    $price = floatval($variation->get_price());
    $stock = $variation->get_stock_quantity();

    $modifier = tjs_get_class_modifier($parent_id);
    $max_stock = tjs_get_variation_capacity($variation);

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
        'length' => $schedule['length'],
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
                'about_content' => '<p>Tiddler Gym is designed to offer a fun, safe environment for parent or carer and baby — encouraging physical play and interaction through music, rolling, rocking, crawling and balancing. The equipment is carefully chosen for the Tiddler age range: tactile, bright and engaging for young explorers.</p><p>The circuit changes every single week, so there\'s always something fresh to discover. It\'s also a lovely chance for parents and carers to relax, have a chat, and watch their little ones thrive.</p>',
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
                'modifier' => 'toddler',
                'pricing_type' => 'term'
            ),
            'defaults' => array(
                'age_range' => '1–3 Years',
                'about_title' => 'Action songs, circuits and <em>adventure</em> — together',
                'about_content' => '<p>A parent-accompanied class for 1 to 3 year olds. Each session opens with action songs and a structured warm-up — teaching toddlers to listen, copy and join in before the equipment circuit begins. The gym layout changes every single week, so there\'s always something new to climb, balance on and explore.</p><p>Coaches are on hand throughout, helping with climbing, encouraging safe and varied use of the equipment, and building a warm rapport with the children. Sessions close with calming songs to bring little ones together gently before the end of class.</p>',
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
/**
 * Read Stripe secret key from WooCommerce Stripe plugin settings.
 */
function tjs_get_stripe_secret_key() {
    $settings = get_option('woocommerce_stripe_settings', array());
    $testmode = isset($settings['testmode']) && $settings['testmode'] === 'yes';
    $key = $testmode
        ? (isset($settings['test_secret_key']) ? $settings['test_secret_key'] : '')
        : (isset($settings['secret_key']) ? $settings['secret_key'] : '');
    return $key;
}

/**
 * Make a direct HTTP request to the Stripe REST API.
 * Uses wp_remote_post/get so no Stripe PHP SDK autoloader is needed.
 *
 * @param string $endpoint  Stripe API path, e.g. 'payment_intents' or 'payment_intents/pi_xxx'
 * @param array  $body      POST body params (ignored for GET)
 * @param string $method    'POST' or 'GET'
 * @return array|WP_Error   Decoded JSON on success, WP_Error on failure
 */
function tjs_stripe_api_request($endpoint, $body = array(), $method = 'POST') {
    $secret_key = tjs_get_stripe_secret_key();
    if (empty($secret_key)) {
        return new WP_Error('no_key', 'Stripe is not configured on this site.');
    }

    $url  = 'https://api.stripe.com/v1/' . ltrim($endpoint, '/');
    $args = array(
        'method'  => $method,
        'headers' => array(
            'Authorization' => 'Bearer ' . $secret_key,
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ),
        'timeout' => 20,
    );
    if ($method === 'POST' && !empty($body)) {
        $args['body'] = $body;
    }

    $response = ($method === 'GET') ? wp_remote_get($url, $args) : wp_remote_post($url, $args);

    if (is_wp_error($response)) {
        return $response;
    }

    $json = json_decode(wp_remote_retrieve_body($response), true);
    $code = (int) wp_remote_retrieve_response_code($response);

    if ($code < 200 || $code >= 300) {
        $msg = isset($json['error']['message']) ? $json['error']['message'] : 'Stripe API error (' . $code . ')';
        return new WP_Error('stripe_error', $msg);
    }

    return $json;
}

/**
 * AJAX: create a pending WC order + Stripe PaymentIntent for the booking form.
 *
 * Returns { client_secret, order_id, confirmation_url } on success.
 */
add_action('wp_ajax_tjs_create_booking_payment', 'tjs_ajax_create_booking_payment');
add_action('wp_ajax_nopriv_tjs_create_booking_payment', 'tjs_ajax_create_booking_payment');

function tjs_ajax_create_booking_payment() {
    if (!isset($_POST['booking_nonce']) || !wp_verify_nonce($_POST['booking_nonce'], 'tjs_booking_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed. Please refresh the page and try again.'));
    }

    $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
    $product_id   = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    if ($variation_id <= 0 || $product_id <= 0) {
        wp_send_json_error(array('message' => 'Invalid product selection.'));
    }

    $variation = wc_get_product($variation_id);
    if (!$variation || !$variation->is_type('variation')) {
        wp_send_json_error(array('message' => 'Variation not found.'));
    }
    if (!$variation->is_in_stock()) {
        wp_send_json_error(array('message' => 'This session is fully booked. Please select another session.'));
    }

    // Price is resolved server-side from the trusted session data.
    $session = tjs_get_booking_session_data($variation_id, true);
    if (is_wp_error($session)) {
        wp_send_json_error(array('message' => 'Unable to load session data.'));
    }

    $booking_type = isset($_POST['booking-type']) ? sanitize_text_field($_POST['booking-type']) : 'full';
    $use_trial    = ($booking_type === 'trial' && !empty($session['enable_trial']) && $session['price_trial_raw'] > 0);
    $unit_price   = $use_trial ? floatval($session['price_trial_raw']) : floatval($session['price_full_raw']);
    if ($unit_price <= 0) {
        wp_send_json_error(array('message' => 'Invalid price for this session.'));
    }

    $fields = array(
        'child-name'  => isset($_POST['child-name'])  ? sanitize_text_field($_POST['child-name'])  : '',
        'child-dob'   => isset($_POST['child-dob'])   ? sanitize_text_field($_POST['child-dob'])   : '',
        'parent-name' => isset($_POST['parent-name']) ? sanitize_text_field($_POST['parent-name']) : '',
        'email'       => isset($_POST['email'])       ? sanitize_email($_POST['email'])            : '',
        'phone'       => isset($_POST['phone'])       ? sanitize_text_field($_POST['phone'])       : '',
        'message'     => isset($_POST['message'])     ? sanitize_textarea_field($_POST['message']) : '',
    );

    $required_field_keys = array('child-name', 'child-dob', 'parent-name', 'email', 'phone');
    foreach ($required_field_keys as $field_key) {
        if (empty($fields[$field_key])) {
            wp_send_json_error(array('message' => 'Please fill in all required fields.'));
        }
    }

    $field_length_limits = array(
        'child-name'  => 100,
        'parent-name' => 100,
        'email'       => 100,
        'phone'       => 50,
        'message'     => 500,
    );
    $field_labels = array(
        'child-name'  => 'Child\'s Name',
        'parent-name' => 'Parent / Carer Name',
        'email'       => 'Email Address',
        'phone'       => 'Phone Number',
        'message'     => 'Additional Message',
    );

    foreach ($field_length_limits as $field_key => $max_length) {
        $value_length = function_exists('mb_strlen') ? mb_strlen($fields[$field_key]) : strlen($fields[$field_key]);
        if ($value_length > $max_length) {
            wp_send_json_error(array(
                'message' => sprintf('%s must be %d characters or fewer.', $field_labels[$field_key], $max_length),
            ));
        }
    }

    if (empty(tjs_get_stripe_secret_key())) {
        wp_send_json_error(array('message' => 'Stripe is not configured on this site.'));
    }

    // Create WC order (pending) before calling Stripe so the order_id goes into intent metadata.
    $order = wc_create_order(array('status' => 'pending'));

    $name_parts = preg_split('/\s+/', trim($fields['parent-name']), 2);
    $first_name = isset($name_parts[0]) ? $name_parts[0] : '';
    $last_name  = isset($name_parts[1]) ? $name_parts[1] : '';

    $order->set_address(array(
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'email'      => $fields['email'],
        'phone'      => $fields['phone'],
    ), 'billing');

    $order->add_product($variation, 1, array(
        'subtotal' => $unit_price,
        'total'    => $unit_price,
    ));

    $order->update_meta_data('_booking_child_name',    $fields['child-name']);
    $order->update_meta_data('_booking_child_dob',     $fields['child-dob']);
    $order->update_meta_data('_booking_parent_name',   $fields['parent-name']);
    $order->update_meta_data('_booking_message',       $fields['message']);
    $order->update_meta_data('_booking_type',          $booking_type);
    $order->update_meta_data('_booking_variation_id',  $variation_id);
    $order->update_meta_data('_tjs_booking_form_order', 'yes');

    $order->set_payment_method('stripe');
    $order->set_payment_method_title('Credit Card (Stripe)');
    $order->calculate_totals();
    $order->save();

    $currency     = strtolower(get_woocommerce_currency());
    $amount_minor = intval(round($order->get_total() * 100));

    // Create Stripe PaymentIntent via direct REST API call (no SDK autoloader needed).
    $intent = tjs_stripe_api_request('payment_intents', array(
        'amount'                                     => $amount_minor,
        'currency'                                   => $currency,
        'automatic_payment_methods[enabled]'         => 'true',
        'automatic_payment_methods[allow_redirects]' => 'never',
        'metadata[order_id]'                         => $order->get_id(),
        'metadata[variation_id]'                     => $variation_id,
        'metadata[booking_type]'                     => $booking_type,
        'metadata[child_name]'                       => $fields['child-name'],
        'metadata[parent_email]'                     => $fields['email'],
        'description'                                => sprintf('%s — %s %s', $session['class_name'], $session['day'], $session['time']),
        'receipt_email'                              => $fields['email'],
    ));

    if (is_wp_error($intent)) {
        $order->update_status('failed', 'Stripe PaymentIntent creation failed: ' . $intent->get_error_message());
        wp_send_json_error(array('message' => $intent->get_error_message()));
    }

    $order->update_meta_data('_stripe_intent_id', $intent['id']);
    $order->save();

    $confirmation_url = '';

    $confirmation_page = get_page_by_path('class-confirmation');
    if ($confirmation_page) {
        $confirmation_url = add_query_arg(array(
            'variation' => $variation_id,
            'order'     => $order->get_id(),
        ), get_permalink($confirmation_page->ID));
    }

    if (!$confirmation_url) {
        $referer = wp_get_referer();
        if ($referer) {
            $parsed = wp_parse_url($referer);
            $path   = isset($parsed['path']) ? $parsed['path'] : '';
            if ($path && preg_match('#-booking/?$#', $path)) {
                $path = preg_replace('#-booking/?$#', '-confirmation/', $path);
                $confirmation_url = home_url($path) . '?variation=' . $variation_id . '&order=' . $order->get_id();
            }
        }
    }

    if (!$confirmation_url) {
        $confirmation_url = $order->get_checkout_order_received_url();
    }

    wp_send_json_success(array(
        'client_secret'    => $intent['client_secret'],
        'order_id'         => $order->get_id(),
        'confirmation_url' => $confirmation_url,
    ));
}

/**
 * AJAX: finalise a booking after Stripe confirmCardPayment succeeds on the client.
 *
 * Verifies the PaymentIntent against Stripe, then calls $order->payment_complete().
 */
add_action('wp_ajax_tjs_finalize_booking_payment', 'tjs_ajax_finalize_booking_payment');
add_action('wp_ajax_nopriv_tjs_finalize_booking_payment', 'tjs_ajax_finalize_booking_payment');

function tjs_ajax_finalize_booking_payment() {
    if (!isset($_POST['booking_nonce']) || !wp_verify_nonce($_POST['booking_nonce'], 'tjs_booking_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed.'));
    }

    $order_id  = isset($_POST['order_id'])          ? intval($_POST['order_id'])                      : 0;
    $intent_id = isset($_POST['payment_intent_id']) ? sanitize_text_field($_POST['payment_intent_id']) : '';
    if (!$order_id || !$intent_id) {
        wp_send_json_error(array('message' => 'Missing payment details.'));
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        wp_send_json_error(array('message' => 'Order not found.'));
    }
    if ($order->get_meta('_stripe_intent_id') !== $intent_id) {
        wp_send_json_error(array('message' => 'Payment intent mismatch.'));
    }

    if (empty(tjs_get_stripe_secret_key())) {
        wp_send_json_error(array('message' => 'Stripe is not configured on this site.'));
    }

    // Verify intent status directly with Stripe REST API (no SDK needed).
    $intent = tjs_stripe_api_request('payment_intents/' . $intent_id, array(), 'GET');
    if (is_wp_error($intent)) {
        wp_send_json_error(array('message' => $intent->get_error_message()));
    }

    if ($intent['status'] !== 'succeeded') {
        wp_send_json_error(array('message' => 'Payment not yet confirmed: ' . $intent['status']));
    }

    if (!$order->is_paid()) {
        $order->payment_complete($intent_id);
        $order->add_order_note('Stripe payment completed via booking form. Intent: ' . $intent_id);

        // Auto-transition to Completed (payment_complete sets Processing).
        if ($order->get_status() !== 'completed') {
            $order->update_status('completed', 'Auto-completed: booking form payment succeeded.');
        }
    }

    wp_send_json_success(array('order_id' => $order_id));
}

/**
 * Modify cart item price for trial bookings
 *
 * Hooks into WooCommerce cart calculation to apply trial pricing
 * when booking-type is set to 'trial' in cart item data.
 */
add_action('woocommerce_before_calculate_totals', 'tjs_apply_trial_price_to_cart');

function tjs_apply_trial_price_to_cart($cart) {
    if (did_action('woocommerce_before_calculate_totals') > 1) {
        return;
    }

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        if (isset($cart_item['booking-type']) && $cart_item['booking-type'] === 'trial') {
            $variation_id = $cart_item['variation_id'];
            $product_id = $cart_item['product_id'];

            if ($variation_id && $product_id) {
                $product = wc_get_product($product_id);
                if ($product && function_exists('get_field')) {
                    $trial_price = get_field('trial_price', $product_id);
                    if (is_numeric($trial_price) && $trial_price > 0) {
                        $cart_item['data']->set_price(floatval($trial_price));
                    }
                }
            }
        }
    }
}

/**
 * Force stock reduction on order completion
 *
 * Ensures that variation stock is properly reduced when an order
 * status is changed to 'completed' or 'processing'.
 * This fixes issues where set_price() might interfere with normal stock deduction.
 */
add_action('woocommerce_order_status_completed', 'tjs_reduce_stock_on_order_complete');
add_action('woocommerce_order_status_processing', 'tjs_reduce_stock_on_order_complete');

function tjs_reduce_stock_on_order_complete($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;

    // Skip if WooCommerce's built-in stock reduction has already run for this order
    // (avoids double-reducing when the order was created outside the cart flow).
    if ($order->get_data_store()->get_stock_reduced($order)) {
        return;
    }

    foreach ($order->get_items() as $item_id => $item) {
        $product = $item->get_product();
        if (!$product) continue;

        // Only handle variations (class bookings)
        if (!$product->is_type('variation')) continue;

        $quantity = $item->get_quantity();
        
        // Check if stock needs to be reduced
        // Reduce stock only if product manages stock and is in stock
        if ($product->managing_stock() && $product->is_in_stock()) {
            // Get current stock before reduction
            $old_stock = $product->get_stock_quantity();
            
            // Reduce stock using WooCommerce's built-in method
            $new_stock = wc_update_product_stock($product->get_id(), -$quantity, 'reduce');
            
            // Log the stock change for debugging
            error_log(sprintf(
                '[TJS] Stock reduced for order %s: Variation %d | Qty: %d | Old: %d | New: %d',
                $order_id,
                $product->get_id(),
                $quantity,
                $old_stock,
                $new_stock
            ));
            
            // Update ACF field to keep in sync
            if (function_exists('update_field')) {
                update_field('variation_stock', $new_stock, $product->get_id());
            }
            
            // Add order note about stock reduction
            $order->add_order_note(sprintf(
                'Stock reduced: %s (Variation #%d) - %d unit(s) | Remaining: %d',
                $product->get_name(),
                $product->get_id(),
                $quantity,
                $new_stock
            ));
        }
    }
}

/**
 * For booking-form orders, send the "New Order" email to the customer
 * instead of only the admin, and BCC the admin.
 */
add_filter('woocommerce_email_recipient_new_order', 'tjs_booking_new_order_recipient', 10, 2);

function tjs_booking_new_order_recipient($recipient, $order) {
    if (!$order || !is_a($order, 'WC_Order')) {
        return $recipient;
    }

    if ($order->get_meta('_tjs_booking_form_order') !== 'yes') {
        return $recipient;
    }

    $customer_email = $order->get_billing_email();
    if (!$customer_email) {
        return $recipient;
    }

    return $customer_email;
}

add_filter('woocommerce_email_headers', 'tjs_booking_new_order_bcc_admin', 10, 3);

function tjs_booking_new_order_bcc_admin($headers, $email_id, $order) {
    if ($email_id !== 'new_order') {
        return $headers;
    }

    if (!$order || !is_a($order, 'WC_Order')) {
        return $headers;
    }

    if ($order->get_meta('_tjs_booking_form_order') !== 'yes') {
        return $headers;
    }

    $admin_email = get_option('woocommerce_email_from_address', get_option('admin_email'));
    if ($admin_email) {
        $headers .= 'Bcc: ' . $admin_email . "\r\n";
    }

    return $headers;
}
