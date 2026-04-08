<?php
/**
 * Class Stock Synchronization
 * Syncs WooCommerce variation stock with ACF fields for display
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Update ACF stock field when WooCommerce stock changes
 */
function tjs_sync_stock_to_acf($product_id) {
    // Check if this is a variation
    $product = wc_get_product($product_id);
    if (!$product) return;
    
    // Get stock quantity
    $stock = $product->get_stock_quantity();
    if ($stock === null) $stock = 0;
    
    // Update ACF field if available
    if (function_exists('update_field')) {
        update_field('variation_stock', $stock, $product_id);
    }
    
    // Also update parent product's total capacity
    if ($product->is_type('variation')) {
        $parent_id = $product->get_parent_id();
        if ($parent_id) {
            tjs_update_parent_stock($parent_id);
        }
    }
}
add_action('woocommerce_update_product_stock', 'tjs_sync_stock_to_acf');
add_action('woocommerce_variation_set_stock', 'tjs_sync_stock_to_acf');

/**
 * Update parent product stock summary
 */
function tjs_update_parent_stock($parent_id) {
    $parent = wc_get_product($parent_id);
    if (!$parent || !$parent->is_type('variable')) return;
    
    $variations = $parent->get_available_variations();
    $total_stock = 0;
    $total_capacity = 0;
    
    foreach ($variations as $variation_data) {
        $variation = wc_get_product($variation_data['variation_id']);
        if (!$variation) continue;
        
        $stock = $variation->get_stock_quantity();
        if ($stock === null || $stock === '') {
            $stock = 20; // Default capacity
        }
        
        $total_stock += $stock;
        $total_capacity += 20; // Assuming default capacity of 20
    }
    
    // Update parent product meta
    update_post_meta($parent_id, '_class_total_stock', $total_stock);
    update_post_meta($parent_id, '_class_total_capacity', $total_capacity);
}

/**
 * Get variation availability status
 */
function tjs_get_variation_availability($variation_id) {
    $variation = wc_get_product($variation_id);
    if (!$variation) return array('status' => 'unknown', 'availability' => 'N/A');
    
    $stock = $variation->get_stock_quantity();
    $max_capacity = 20; // Default max
    
    // Get category-specific capacity
    $parent_id = $variation->get_parent_id();
    if ($parent_id) {
        $categories = wp_get_post_terms($parent_id, 'product_cat', array('fields' => 'slugs'));
        if (in_array('toddler-gym', $categories)) {
            $max_capacity = 18;
        } elseif (in_array('tiddler-gym', $categories)) {
            $max_capacity = 10;
        } elseif (in_array('mini-gym', $categories)) {
            $max_capacity = 10;
        }
    }
    
    if ($stock === null || $stock === '') {
        $stock = $max_capacity;
    }
    
    $availability = $stock . ' / ' . $max_capacity;
    
    if ($stock <= 0) {
        $status = 'full';
        $availability = 'Full';
    } elseif ($stock <= 3) {
        $status = 'limited';
    } else {
        $status = 'available';
    }
    
    return array(
        'status' => $status,
        'availability' => $availability,
        'stock' => $stock,
        'max_capacity' => $max_capacity
    );
}

/**
 * Check if class is full
 */
function tjs_is_class_full($variation_id) {
    $availability = tjs_get_variation_availability($variation_id);
    return $availability['status'] === 'full';
}

/**
 * Get waitlist URL for a variation
 */
function tjs_get_waitlist_url($variation_id) {
    $variation = wc_get_product($variation_id);
    if (!$variation) return '#';
    
    $parent_id = $variation->get_parent_id();
    $parent = wc_get_product($parent_id);
    if (!$parent) return '#';
    
    // Build waitlist URL
    $base_url = home_url('/waitlist/');
    $args = array(
        'class' => $parent->get_slug(),
        'variation' => $variation_id
    );
    
    return add_query_arg($args, $base_url);
}

/**
 * Add stock status to variation data
 */
function tjs_add_stock_to_variation_data($data, $product, $variation) {
    $availability = tjs_get_variation_availability($variation->get_id());
    
    $data['tjs_stock_status'] = $availability['status'];
    $data['tjs_availability'] = $availability['availability'];
    $data['tjs_waitlist_url'] = tjs_get_waitlist_url($variation->get_id());
    
    return $data;
}
add_filter('woocommerce_available_variation', 'tjs_add_stock_to_variation_data', 10, 3);

/**
 * Display stock info on product page
 */
function tjs_display_stock_info() {
    global $product;
    
    if (!$product || !$product->is_type('variable')) return;
    
    echo '<div class="tjs-stock-info" style="margin: 20px 0; padding: 15px; background: #f5f5f5; border-radius: 8px;">';
    echo '<h4>Class Availability</h4>';
    echo '<p>Classes have limited spaces. Book early to secure your place!</p>';
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'tjs_display_stock_info', 25);

/**
 * Prevent adding to cart if class is full
 */
function tjs_validate_class_stock($passed, $product_id, $quantity, $variation_id = 0) {
    if (!$variation_id) return $passed;
    
    $variation = wc_get_product($variation_id);
    if (!$variation) return $passed;
    
    // Check if this is a class product
    $parent = wc_get_product($product_id);
    if (!$parent) return $passed;
    
    $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'slugs'));
    $is_class = in_array('classes', $categories) || 
                in_array('tiddler-gym', $categories) ||
                in_array('toddler-gym', $categories) ||
                in_array('mini-gym', $categories) ||
                in_array('gymnastics', $categories);
    
    if (!$is_class) return $passed;
    
    // Check stock
    $stock = $variation->get_stock_quantity();
    if ($stock !== null && $stock <= 0) {
        wc_add_notice('Sorry, this class is currently full. Please join the waitlist to be notified when a space becomes available.', 'error');
        return false;
    }
    
    return $passed;
}
add_filter('woocommerce_add_to_cart_validation', 'tjs_validate_class_stock', 10, 4);
