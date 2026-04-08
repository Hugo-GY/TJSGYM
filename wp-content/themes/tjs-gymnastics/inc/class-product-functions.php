<?php
/**
 * Helper functions for class product data
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get class product by slug
 */
function tjs_get_class_product($slug) {
    $product_query = new WP_Query(array(
        'post_type' => 'product',
        'name' => $slug,
        'posts_per_page' => 1
    ));
    
    $product = null;
    if ($product_query->have_posts()) {
        $product_query->the_post();
        $product = wc_get_product(get_the_ID());
    }
    wp_reset_postdata();
    
    return $product;
}

/**
 * Get class sessions from product variations
 */
function tjs_get_class_sessions($product, $max_stock = 18) {
    $sessions = array();
    
    if (!$product || !$product->is_type('variable')) {
        return $sessions;
    }
    
    $variations = $product->get_available_variations();
    
    foreach ($variations as $variation_data) {
        $variation = wc_get_product($variation_data['variation_id']);
        if (!$variation) continue;
        
        $attributes = $variation->get_attributes();
        $day = isset($attributes['pa_class-day']) ? $attributes['pa_class-day'] : '';
        $time = isset($attributes['pa_time-slot']) ? $attributes['pa_time-slot'] : '';
        $group = isset($attributes['pa_group-level']) ? $attributes['pa_group-level'] : '';
        
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
            'price' => '£' . $price . ' / term',
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
    
    return $sessions;
}

/**
 * Get class modifier from product categories
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
 * Default term data
 */
function tjs_get_default_terms() {
    return array(
        array(
            'season' => 'Summer 2026',
            'status' => 'Teaching now',
            'weeks' => '13 weeks',
            'dates' => array('13 Apr – 21 May', '1 Jun – 16 Jul'),
            'halfterm' => 'Half term: w/k 25 May · No class 4 May',
            'payment_due' => 'Payment due by 12 March'
        ),
        array(
            'season' => 'Winter 2026',
            'status' => 'Next term',
            'weeks' => '12 weeks',
            'dates' => array('7 Sep – 16 Oct', '2 Nov – 10 Dec'),
            'halfterm' => '2-week half term: w/k 19 October',
            'payment_due' => 'Payment due by 26 June'
        ),
        array(
            'season' => 'Spring 2027',
            'status' => 'Planning ahead',
            'weeks' => '11 weeks',
            'dates' => array('4 Jan – 11 Feb', '22 Feb – 25 Mar'),
            'halfterm' => 'Half term: w/k 15 February',
            'payment_due' => 'Payment due by 27 November'
        )
    );
}
