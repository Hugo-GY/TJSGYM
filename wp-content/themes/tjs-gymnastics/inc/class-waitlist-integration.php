<?php
/**
 * Wishlist and Waitlist Integration
 * Integrates with "Wishlist and Waitlist for WooCommerce" plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if waitlist plugin is active
 */
function tjs_is_waitlist_plugin_active() {
    return class_exists('WLFMC_Wishlist_and_Waitlist_for_WooCommerce');
}

/**
 * Add waitlist button to class detail pages
 */
function tjs_add_waitlist_button($variation_id) {
    if (!tjs_is_waitlist_plugin_active()) {
        tjs_custom_waitlist_form($variation_id);
        return;
    }
    
    $variation = wc_get_product($variation_id);
    if (!$variation) return;
    
    if ($variation->is_in_stock()) return;
    
    echo '<div class="tjs-waitlist-wrap">';
    echo '<p class="tjs-waitlist-note">This class is currently full. Join the waitlist to be notified when a space becomes available.</p>';
    
    if (shortcode_exists('waitlist_button')) {
        echo do_shortcode('[waitlist_button product_id="' . $variation_id . '"]');
    } else {
        $waitlist_url = tjs_get_waitlist_page_url($variation_id);
        echo '<a href="' . esc_url($waitlist_url) . '" class="btn btn-secondary">Join Waitlist</a>';
    }
    
    echo '</div>';
}

/**
 * Custom waitlist form (fallback when plugin is not active)
 */
function tjs_custom_waitlist_form($variation_id) {
    $variation = wc_get_product($variation_id);
    if (!$variation) return;
    
    if ($variation->is_in_stock()) return;
    ?>
    <div class="tjs-waitlist-wrap">
        <p class="tjs-waitlist-note">This class is currently full. Join the waitlist to be notified when a space becomes available.</p>
        <form class="tjs-waitlist-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="tjs_join_waitlist">
            <input type="hidden" name="variation_id" value="<?php echo esc_attr($variation_id); ?>">
            <?php wp_nonce_field('tjs_waitlist_nonce', 'waitlist_nonce'); ?>
            
            <div class="form-row">
                <label for="waitlist_email">Email Address</label>
                <input type="email" name="waitlist_email" id="waitlist_email" required class="input-text" placeholder="your@email.com">
            </div>
            
            <div class="form-row">
                <label for="waitlist_name">Your Name</label>
                <input type="text" name="waitlist_name" id="waitlist_name" required class="input-text" placeholder="John Smith">
            </div>
            
            <button type="submit" class="btn btn-secondary">Join Waitlist</button>
        </form>
    </div>
    <?php
}

/**
 * Handle waitlist form submission
 */
function tjs_handle_waitlist_submission() {
    if (!isset($_POST['waitlist_nonce']) || !wp_verify_nonce($_POST['waitlist_nonce'], 'tjs_waitlist_nonce')) {
        wp_die('Security check failed');
    }
    
    $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
    $email = isset($_POST['waitlist_email']) ? sanitize_email($_POST['waitlist_email']) : '';
    $name = isset($_POST['waitlist_name']) ? sanitize_text_field($_POST['waitlist_name']) : '';
    
    if (!$variation_id || !$email) {
        wp_redirect(add_query_arg('waitlist_error', '1', wp_get_referer()));
        exit;
    }
    
    $waitlist_entry = array(
        'variation_id' => $variation_id,
        'email' => $email,
        'name' => $name,
        'date_added' => current_time('mysql'),
        'notified' => false
    );
    
    $waitlist = get_post_meta($variation_id, '_tjs_waitlist', true);
    if (!is_array($waitlist)) {
        $waitlist = array();
    }
    
    $exists = false;
    foreach ($waitlist as $entry) {
        if ($entry['email'] === $email) {
            $exists = true;
            break;
        }
    }
    
    if (!$exists) {
        $waitlist[] = $waitlist_entry;
        update_post_meta($variation_id, '_tjs_waitlist', $waitlist);
        tjs_send_waitlist_confirmation($email, $name, $variation_id);
        wp_redirect(add_query_arg('waitlist_success', '1', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('waitlist_exists', '1', wp_get_referer()));
    }
    
    exit;
}
add_action('admin_post_tjs_join_waitlist', 'tjs_handle_waitlist_submission');
add_action('admin_post_nopriv_tjs_join_waitlist', 'tjs_handle_waitlist_submission');

/**
 * Send waitlist confirmation email
 */
function tjs_send_waitlist_confirmation($email, $name, $variation_id) {
    $variation = wc_get_product($variation_id);
    if (!$variation) return;
    
    $parent = wc_get_product($variation->get_parent_id());
    $class_name = $parent ? $parent->get_name() : 'Class';
    
    $subject = 'Waitlist Confirmation - ' . get_bloginfo('name');
    
    $message = "Hi " . $name . ",\n\n";
    $message .= "You have been added to the waitlist for " . $class_name . ".\n\n";
    $message .= "We will notify you as soon as a space becomes available.\n\n";
    $message .= "Best regards,\n";
    $message .= get_bloginfo('name');
    
    wp_mail($email, $subject, $message);
}

/**
 * Notify waitlist when stock becomes available
 */
function tjs_notify_waitlist($product_id) {
    $product = wc_get_product($product_id);
    if (!$product) return;
    
    if (!$product->is_type('variation')) return;
    
    $stock = $product->get_stock_quantity();
    if ($stock === null || $stock <= 0) return;
    
    $waitlist = get_post_meta($product_id, '_tjs_waitlist', true);
    if (!is_array($waitlist) || empty($waitlist)) return;
    
    $parent = wc_get_product($product->get_parent_id());
    $class_name = $parent ? $parent->get_name() : 'Class';
    
    foreach ($waitlist as $key => $entry) {
        if (!$entry['notified']) {
            $subject = 'Space Available - ' . $class_name;
            
            $message = "Hi " . $entry['name'] . ",\n\n";
            $message .= "Good news! A space has become available in " . $class_name . ".\n\n";
            $message .= "Please visit our website to book your place as soon as possible.\n\n";
            $message .= "Best regards,\n";
            $message .= get_bloginfo('name');
            
            wp_mail($entry['email'], $subject, $message);
            
            $waitlist[$key]['notified'] = true;
            $waitlist[$key]['notified_date'] = current_time('mysql');
            update_post_meta($product_id, '_tjs_waitlist', $waitlist);
            break;
        }
    }
}
add_action('woocommerce_update_product_stock', 'tjs_notify_waitlist');

/**
 * Get waitlist page URL
 */
function tjs_get_waitlist_page_url($variation_id) {
    $waitlist_page = get_page_by_path('waitlist');
    if ($waitlist_page) {
        return add_query_arg('variation', $variation_id, get_permalink($waitlist_page->ID));
    }
    return home_url('/waitlist/?variation=' . $variation_id);
}

/**
 * Display waitlist count on admin product page
 */
function tjs_display_waitlist_count($post) {
    if ($post->post_type !== 'product') return;
    
    global $post;
    $product = wc_get_product($post->ID);
    if (!$product || !$product->is_type('variable')) return;
    
    $variations = $product->get_available_variations();
    
    echo '<div class="tjs-waitlist-admin-box">';
    echo '<h3>Waitlist Summary</h3>';
    
    foreach ($variations as $variation_data) {
        $variation = wc_get_product($variation_data['variation_id']);
        if (!$variation) continue;
        
        $waitlist = get_post_meta($variation_data['variation_id'], '_tjs_waitlist', true);
        $count = is_array($waitlist) ? count($waitlist) : 0;
        
        $schedule = function_exists('tjs_get_variation_schedule_data') ? tjs_get_variation_schedule_data($variation) : array();
        $name = ucfirst($schedule['day'] ?? 'N/A') . ' ' . ($schedule['time'] ?? 'N/A');
        
        echo '<p><strong>' . esc_html($name) . ':</strong> ' . $count . ' on waitlist</p>';
    }
    
    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'tjs_display_waitlist_count');
