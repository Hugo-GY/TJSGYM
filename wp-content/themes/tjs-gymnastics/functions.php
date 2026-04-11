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
 * Enqueue Scripts and Styles
 */
function tjs_scripts() {
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
        is_page_template('page-templates/template-class-confirmation.php')) {
        wp_enqueue_style('tjs-class-detail', get_template_directory_uri() . '/assets/css/class-detail.css', array(), '1.0.0');
    }
    
    // News article styles (for single post)
    if (is_singular('post')) {
        wp_enqueue_style('tjs-news-article', get_template_directory_uri() . '/assets/css/news-article.css', array(), '1.0.0');
    }
    
    // Theme JavaScript
    wp_enqueue_script('tjs-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true);

    // Localize script for AJAX
    wp_localize_script('tjs-main', 'tjs_ajax_object', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tjs_ajax_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'tjs_scripts');

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
