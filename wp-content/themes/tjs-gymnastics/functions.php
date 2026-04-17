<?php
/**
 * TJ's Gymnastics Club Theme Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

// ACF Local JSON - Load and save field groups from theme
add_filter('acf/settings/load_json', function($paths) {
    $paths[] = get_template_directory() . '/acf-field-groups';
    return $paths;
});

add_filter('acf/settings/save_json', function($path) {
    return get_template_directory() . '/acf-field-groups';
});

// Include custom walker class
require_once get_template_directory() . '/class-walker-nav-menu.php';

// Include class product functions
require_once get_template_directory() . '/inc/class-product-functions.php';

// Include stock sync functions
require_once get_template_directory() . '/inc/class-stock-sync.php';

// Include waitlist integration
require_once get_template_directory() . '/inc/class-waitlist-integration.php';

// Include checkout fields
require_once get_template_directory() . '/inc/class-checkout-fields.php';

/**
 * ACF Compatibility Functions
 * These functions provide fallbacks when ACF is not installed
 * They are loaded after plugins_loaded to avoid conflicts
 */
add_action('plugins_loaded', function() {
    if (!function_exists('get_field')) {
        function get_field($field_name, $post_id = false) {
            return false;
        }
    }

    if (!function_exists('have_rows')) {
        function have_rows($field_name, $post_id = false) {
            return false;
        }
    }

    if (!function_exists('the_row')) {
        function the_row() {
            return false;
        }
    }

    if (!function_exists('get_sub_field')) {
        function get_sub_field($sub_field_name) {
            return false;
        }
    }
});

/**
 * Theme Setup
 */
function tjs_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    // Enable Order field on standard posts (used by History timeline)
    add_post_type_support('post', 'page-attributes');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('block-templates');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'tjs-gymnastics'),
        'footer'  => __('Footer Menu', 'tjs-gymnastics'),
    ));
}
add_action('after_setup_theme', 'tjs_setup');

/**
 * Redirect legacy dedicated booking/confirmation page slugs to the unified flow.
 */
function tjs_redirect_legacy_booking_pages() {
    if (is_admin() || wp_doing_ajax()) {
        return;
    }

    global $wp;

    $request_path = isset($wp->request) ? trim((string) $wp->request, '/') : '';
    if ($request_path === '') {
        return;
    }

    $legacy_routes = array(
        'toddler-gym-booking' => '/class-booking/',
        'tiddler-gym-booking' => '/class-booking/',
        'mini-gym-booking' => '/class-booking/',
        'toddler-gym-confirmation' => '/class-confirmation/',
        'tiddler-gym-confirmation' => '/class-confirmation/',
        'mini-gym-confirmation' => '/class-confirmation/',
    );

    if (!isset($legacy_routes[$request_path])) {
        return;
    }

    $query_args = array();
    foreach (wp_unslash($_GET) as $key => $value) {
        $query_args[$key] = is_scalar($value) ? sanitize_text_field((string) $value) : $value;
    }

    $redirect_url = home_url($legacy_routes[$request_path]);
    if (!empty($query_args)) {
        $redirect_url = add_query_arg($query_args, $redirect_url);
    }

    wp_safe_redirect($redirect_url, 301);
    exit;
}
add_action('template_redirect', 'tjs_redirect_legacy_booking_pages', 1);

/**
 * Enqueue Scripts and Styles
 */
function tjs_scripts() {
    if (is_page_template('page-templates/template-class-booking.php')) {
        $flatpickr_js_path = WP_CONTENT_DIR . '/plugins/fluentform/assets/libs/flatpickr/flatpickr.min.js';
        $flatpickr_css_path = WP_CONTENT_DIR . '/plugins/fluentform/assets/libs/flatpickr/flatpickr.min.css';

        if (file_exists($flatpickr_css_path)) {
            wp_enqueue_style(
                'tjs-flatpickr',
                content_url('plugins/fluentform/assets/libs/flatpickr/flatpickr.min.css'),
                array(),
                filemtime($flatpickr_css_path)
            );
        }

        if (file_exists($flatpickr_js_path)) {
            wp_enqueue_script(
                'tjs-flatpickr',
                content_url('plugins/fluentform/assets/libs/flatpickr/flatpickr.min.js'),
                array(),
                filemtime($flatpickr_js_path),
                true
            );
        }
    }

    // Google Fonts
    wp_enqueue_style('tjs-fonts', 'https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=Fredoka:wght@400;500;600&display=swap', array(), null);
    
    // Theme styles
    wp_enqueue_style('tjs-global', get_template_directory_uri() . '/assets/css/global.css', array(), '1.0.0');
    wp_enqueue_style('tjs-components', get_template_directory_uri() . '/assets/css/components.css', array(), '1.0.0');
    
    // Home page styles
    if (is_front_page() || is_home()) {
        wp_enqueue_style('tjs-home', get_template_directory_uri() . '/assets/css/home.css', array(), '1.0.0');
    }
    
    // Page-specific styles
    if (is_page_template('page-templates/template-coaches.php') || is_page('coaches')) {
        wp_enqueue_style('tjs-coaches', get_template_directory_uri() . '/assets/css/coaches.css', array(), '1.0.0');
    }
    if (is_page_template('page-templates/template-classes.php') || is_page('classes')) {
        wp_enqueue_style('tjs-classes', get_template_directory_uri() . '/assets/css/classes.css', array(), '1.0.0');
    }
    if (is_page_template('page-templates/template-timetable.php') || is_page('timetable')) {
        wp_enqueue_style('tjs-timetable', get_template_directory_uri() . '/assets/css/timetable.css', array(), '1.0.0');
    }
    if (is_page_template('page-templates/template-contact.php') || is_page('contact')) {
        wp_enqueue_style('tjs-contact', get_template_directory_uri() . '/assets/css/contact.css', array(), '1.0.0');
    }
    if (is_page_template('page-templates/template-faq.php') || is_page('faq')) {
        wp_enqueue_style('tjs-faq', get_template_directory_uri() . '/assets/css/faq.css', array(), '1.0.0');
    }
    if (is_page_template('page-templates/template-news.php') || is_page('news')) {
        wp_enqueue_style('tjs-news', get_template_directory_uri() . '/assets/css/news.css', array(), '1.0.0');
    }
    if (is_page_template('page-templates/template-history.php') || is_page('history')) {
        wp_enqueue_style('tjs-history', get_template_directory_uri() . '/assets/css/history.css', array(), '1.0.0');
    }
    if (is_page_template('page-templates/template-club-kit.php') || is_page('club-kit')) {
        wp_enqueue_style('tjs-club-kit', get_template_directory_uri() . '/assets/css/club-kit.css', array(), '1.0.0');
    }
    if (is_page_template('page-templates/template-club-policy.php') || is_page('club-policy')) {
        wp_enqueue_style('tjs-club-policy', get_template_directory_uri() . '/assets/css/club-policy.css', array(), '1.0.0');
    }
    if (is_page_template('page-templates/template-child-info-sheet.php') || is_page('child-info-sheet')) {
        wp_enqueue_style('tjs-child-info-sheet', get_template_directory_uri() . '/assets/css/child-info-sheet.css', array(), '1.0.0');
    }
    
    // Class detail pages (Toddler, Tiddler, Mini Gym, Gymnastics, Dynamic)
    if (is_page_template('page-templates/template-class-toddler.php') ||
        is_page_template('page-templates/template-class-tiddler.php') ||
        is_page_template('page-templates/template-class-mini-gym.php') ||
        is_page_template('page-templates/template-class-gymnastics.php') ||
        is_page_template('page-templates/template-class-dynamic.php') ||
        is_page_template('page-templates/template-class-booking.php') ||
        is_page_template('page-templates/template-class-confirmation.php') ||
        function_exists('is_checkout') && is_checkout()) {
        wp_enqueue_style('tjs-class-detail', get_template_directory_uri() . '/assets/css/class-detail.css', array(), '1.0.0');
    }

    // WooCommerce pages (Cart & Checkout)
    if (function_exists('is_cart') && is_cart() || (function_exists('is_checkout') && is_checkout()) || (function_exists('is_account_page') && is_account_page())) {
        wp_enqueue_style('tjs-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', array(), '1.0.0');
    }

    // News article styles (for single post)
    if (is_singular('post')) {
        wp_enqueue_style('tjs-news-article', get_template_directory_uri() . '/assets/css/news-article.css', array(), '1.0.0');
    }

    // Theme JavaScript
    $main_script_dependencies = array();
    if (wp_script_is('tjs-flatpickr', 'registered') || wp_script_is('tjs-flatpickr', 'enqueued')) {
        $main_script_dependencies[] = 'tjs-flatpickr';
    }

    wp_enqueue_script('tjs-main', get_template_directory_uri() . '/assets/js/main.js', $main_script_dependencies, filemtime(get_template_directory() . '/assets/js/main.js'), true);

    // Localize script for AJAX
    wp_localize_script('tjs-main', 'tjs_ajax_object', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tjs_ajax_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'tjs_scripts');

/**
 * Render classic Stripe card mount targets after the Stripe gateway fields.
 */
function tjs_render_classic_stripe_mount($gateway_id) {
    if ('stripe' !== $gateway_id) {
        return;
    }
    ?>
    <div id="tjs-stripe-classic-form" class="tjs-stripe-classic" aria-label="<?php echo esc_attr__('Card details', 'tjs-gymnastics'); ?>">
        <div class="tjs-stripe-classic__group tjs-stripe-classic__group--full">
            <label for="tjs-stripe-card-element"><?php esc_html_e('Card number', 'tjs-gymnastics'); ?></label>
            <div id="tjs-stripe-card-element" class="tjs-stripe-classic__field"></div>
        </div>

        <div class="tjs-stripe-classic__split">
            <div class="tjs-stripe-classic__group">
                <label for="tjs-stripe-exp-element"><?php esc_html_e('Expiration date', 'tjs-gymnastics'); ?></label>
                <div id="tjs-stripe-exp-element" class="tjs-stripe-classic__field"></div>
            </div>

            <div class="tjs-stripe-classic__group">
                <label for="tjs-stripe-cvc-element"><?php esc_html_e('Security code', 'tjs-gymnastics'); ?></label>
                <div id="tjs-stripe-cvc-element" class="tjs-stripe-classic__field"></div>
            </div>
        </div>

        <div id="tjs-stripe-errors" class="stripe-source-errors" role="alert"></div>
    </div>
    <?php
}
add_action('wc_stripe_payment_fields_stripe', 'tjs_render_classic_stripe_mount');

/**
 * Enqueue Stripe field mounting for the class-booking page.
 */
function tjs_enqueue_booking_stripe_form() {
    if (!is_page_template('page-templates/template-class-booking.php')) {
        return;
    }

    if (!class_exists('WC_Stripe')) {
        return;
    }

    $gateway = WC_Stripe::get_instance()->get_main_stripe_gateway();

    if (!$gateway || !method_exists($gateway, 'javascript_params')) {
        return;
    }

    wp_register_script('stripe', 'https://js.stripe.com/clover/stripe.js', array(), null, true);
    wp_enqueue_script('stripe');

    wp_enqueue_script(
        'tjs-booking-stripe-form',
        get_template_directory_uri() . '/assets/js/booking-stripe-form.js',
        array('stripe'),
        filemtime(get_template_directory() . '/assets/js/booking-stripe-form.js'),
        true
    );

    wp_localize_script(
        'tjs-booking-stripe-form',
        'tjsBookingStripeParams',
        $gateway->javascript_params()
    );
}
add_action('wp_enqueue_scripts', 'tjs_enqueue_booking_stripe_form', 130);

/**
 * Remove "New in store" section from Cart page
 */
function tjs_remove_new_in_store_from_cart($content) {
    if (function_exists('is_cart') && is_cart()) {
        $content = preg_replace('/<h2[^>]*class="[^"]*wp-block-heading[^"]*"[^>]*>\s*New in store\s*<\/h2>/i', '', $content);

        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8">' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xpath = new DOMXPath($dom);

        $nodes_to_remove = array();

        $product_new_nodes = $xpath->query('//*[contains(@data-block-name, "woocommerce/product-new") or contains(@class, "wp-block-woocommerce-product-new")]');
        foreach ($product_new_nodes as $node) {
            $nodes_to_remove[] = $node;
        }

        foreach ($nodes_to_remove as $node) {
            $node->parentNode->removeChild($node);
        }

        $content = $dom->saveHTML();
        $content = str_replace('<?xml encoding="utf-8">', '', $content);
    }
    return $content;
}
add_filter('the_content', 'tjs_remove_new_in_store_from_cart');

/**
 * Remove "New in store" via output buffer (fallback)
 */
function tjs_remove_new_in_store_buffer_start() {
    if (function_exists('is_cart') && is_cart()) {
        ob_start(function($html) {
            $patterns = array(
                '/<div[^>]*data-block-name=["\']woocommerce\/product-new["\'][^>]*>.*?<\/div>\s*/is',
                '/<div[^>]*class=["\'][^"\']*wp-block-woocommerce-product-new[^"\']*["\'][^>]*>.*?<\/div>\s*/is',
                '/<!-- wp:woocommerce\/product-new -->.*?<!-- \/wp:woocommerce\/product-new -->/is',
                '/<!-- wp:heading.*?-->.*?New in store.*?<!-- \/wp:heading -->/is'
            );
            return preg_replace($patterns, '', $html);
        });
    }
}
add_action('template_redirect', 'tjs_remove_new_in_store_buffer_start');

/**
 * Add custom body classes
 */
function tjs_body_classes($classes) {
    if (is_page()) {
        $classes[] = 'page-' . get_post_field('post_name', get_the_ID());

        // Add class-detail-page for class detail templates
        $template = get_page_template_slug(get_the_ID());
        if ($template && strpos($template, 'template-class-') !== false) {
            $classes[] = 'class-detail-page';

            // Add specific modifier class based on template
            if ($template === 'page-templates/template-class-toddler.php') {
                $classes[] = 'class-detail-page--tiddler';
            } elseif ($template === 'page-templates/template-class-tiddler.php') {
                $classes[] = 'class-detail-page--tiddler';
            } elseif ($template === 'page-templates/template-class-mini.php') {
                $classes[] = 'class-detail-page--mini';
            } elseif ($template === 'page-templates/template-class-gymnastics.php') {
                $classes[] = 'class-detail-page--gymnastics';
            } elseif ($template === 'page-templates/template-class-dynamic.php') {
                // For dynamic template, try to detect modifier from linked product
                $page_slug = get_post_field('post_name', get_the_ID());
                if (function_exists('tjs_get_class_product')) {
                    $product = tjs_get_class_product($page_slug);
                    if (!$product) {
                        // Try mapping
                        $mappings = array(
                            'toddler-gym' => 'toddler-gym-product',
                            'mini-gym' => 'mini-gym-product',
                            'gymnastics' => 'gymnastics-product'
                        );
                        $mapped_slug = isset($mappings[$page_slug]) ? $mappings[$page_slug] : $page_slug;
                        $product = tjs_get_class_product($mapped_slug);
                    }
                    if ($product && function_exists('tjs_get_class_modifier')) {
                        $modifier = tjs_get_class_modifier($product->get_id());
                        $classes[] = 'class-detail-page--' . $modifier;
                    }
                }
            }
        }
    }
    return $classes;
}
add_filter('body_class', 'tjs_body_classes');

/**
 * Hide the Product Description editor for class products.
 * Class content is managed via ACF fields (About Description).
 * Only targets products in tiddler-gym, toddler-gym, mini-gym, gymnastics categories.
 */
function tjs_hide_class_product_description() {
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'product') {
        return;
    }

    $post_id = isset($_GET['post']) ? absint($_GET['post']) : 0;
    if (!$post_id) {
        return;
    }

    if (tjs_is_class_product($post_id)) {
        echo '<style>#postdivrich { display: none !important; }</style>';
    }
}
add_action('admin_head', 'tjs_hide_class_product_description');

/**
 * Custom excerpt length
 */
function tjs_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'tjs_excerpt_length');

/**
 * Custom excerpt more
 */
function tjs_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'tjs_excerpt_more');
