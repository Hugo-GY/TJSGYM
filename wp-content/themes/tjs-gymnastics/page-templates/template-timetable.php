<?php
/**
 * Template Name: Timetable Page
 */
get_header();

$hero_eyebrow = get_field('hero_eyebrow') ?: '2025 / 2026';
$hero_title = get_field('hero_title') ?: 'Weekly <em>Timetable</em>';
$hero_subtitle = get_field('hero_subtitle') ?: 'Classes run Monday to Thursday at Raynes Park Sports Pavilion. Click any class name to view full details, book your place or join the waiting list.';

// Helper function to get all variations grouped by day
function get_timetable_data_from_products() {
    $timetable = array(
        'monday' => array(),
        'tuesday' => array(),
        'wednesday' => array(),
        'thursday' => array()
    );
    
    // Get all class products
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => 'classes',
                'include_children' => true
            )
        ),
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product = wc_get_product(get_the_ID());
            
            if (!$product || !$product->is_type('variable')) {
                continue;
            }
            
            $product_name = $product->get_name();
            $product_link = get_permalink();
            $product_slug = $product->get_slug();
            
            // Get age range from ACF or category
            $age_range = function_exists('get_field') ? get_field('age_range', get_the_ID()) : '';
            if (empty($age_range)) {
                // Fallback based on product slug
                if (strpos($product_slug, 'tiddler') !== false) $age_range = '6 – 12 months';
                elseif (strpos($product_slug, 'toddler') !== false) $age_range = '1 – 3 years';
                elseif (strpos($product_slug, 'mini') !== false) $age_range = '3 – 4½ years';
                else $age_range = '';
            }
            
            // Get all variations
            $variations = $product->get_available_variations();
            
            foreach ($variations as $variation_data) {
                $variation = wc_get_product($variation_data['variation_id']);
                if (!$variation) continue;
                
                $attributes = $variation->get_attributes();
                $day = isset($attributes['pa_class-day']) ? $attributes['pa_class-day'] : '';
                $time = isset($attributes['pa_time-slot']) ? $attributes['pa_time-slot'] : '';
                $group = isset($attributes['pa_group-level']) ? $attributes['pa_group-level'] : '';
                
                if (empty($day) || empty($time)) continue;
                
                $day_lower = strtolower($day);
                if (!isset($timetable[$day_lower])) continue;
                
                $price = $variation->get_price();
                $price_display = $price ? '£' . $price . ' / term' : '';
                
                // Determine age/level display
                $age_display = $age_range;
                if (!empty($group)) {
                    $age_display = $group;
                }
                
                $timetable[$day_lower][] = array(
                    'time' => tjs_format_time_slot($time),
                    'class' => $product_name,
                    'price' => $price_display,
                    'age' => $age_display,
                    'link' => $product_link,
                    'group' => $group,
                    'variation_id' => $variation_data['variation_id']
                );
            }
        }
        wp_reset_postdata();
    }
    
    // Sort each day's classes by time
    foreach ($timetable as $day => &$classes) {
        usort($classes, function($a, $b) {
            // Extract start time for comparison
            $time_a = strtotime(explode('–', $a['time'])[0]);
            $time_b = strtotime(explode('–', $b['time'])[0]);
            return $time_a - $time_b;
        });
    }
    
    return $timetable;
}

// Get timetable data from products
$timetable_data = get_timetable_data_from_products();

// Fallback to default data if no products found
$has_product_data = false;
foreach ($timetable_data as $day_classes) {
    if (!empty($day_classes)) {
        $has_product_data = true;
        break;
    }
}

if (!$has_product_data) {
    // Default timetable data
    $timetable_data = array(
        'monday' => array(
            array('time' => '1:20 – 2:00', 'class' => 'Mini Gym', 'price' => '£168 / term', 'age' => '3 – 4½ years', 'link' => home_url('/mini-gym/')),
            array('time' => '2:10 – 2:50', 'class' => 'Mini Gym', 'price' => '£168 / term', 'age' => '3 – 4½ years', 'link' => home_url('/mini-gym/')),
            array('time' => '4:00 – 4:45', 'class' => 'Gymnastics', 'price' => '£180 / term', 'age' => 'Mixed Intermediate', 'link' => home_url('/gymnastics/')),
            array('time' => '4:30 – 5:15', 'class' => 'Gymnastics', 'price' => '£180 / term', 'age' => 'Girls Mini Squad', 'link' => home_url('/gymnastics/'), 'note' => 'Warm-up in adjacent hall'),
            array('time' => '5:00 – 6:00', 'class' => 'Gymnastics', 'price' => '£192 / term', 'age' => 'Boys Elite', 'link' => home_url('/gymnastics/'), 'note' => 'Warm-up in adjacent hall'),
        ),
        'tuesday' => array(
            array('time' => '9:40 – 10:20', 'class' => 'Toddler Gym', 'price' => '£169 / term', 'age' => '1 – 3 years', 'link' => home_url('/toddler-gym/')),
            array('time' => '10:30 – 11:10', 'class' => 'Toddler Gym', 'price' => '£169 / term', 'age' => '1 – 3 years', 'link' => home_url('/toddler-gym/')),
            array('time' => '1:20 – 2:00', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years', 'link' => home_url('/mini-gym/')),
            array('time' => '2:10 – 2:50', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years', 'link' => home_url('/mini-gym/')),
            array('time' => '4:00 – 4:45', 'class' => 'Gymnastics', 'price' => '£195 / term', 'age' => 'Beginners', 'link' => home_url('/gymnastics/'), 'note' => 'Ex Mini Gym only'),
            array('time' => '4:45 – 5:30', 'class' => 'Gymnastics', 'price' => '£195 / term', 'age' => 'Mixed Intermediate', 'link' => home_url('/gymnastics/')),
            array('time' => '5:30 – 6:30', 'class' => 'Gymnastics', 'price' => '£208 / term', 'age' => 'Mixed Squad', 'link' => home_url('/gymnastics/')),
        ),
        'wednesday' => array(
            array('time' => '9:30 – 10:10', 'class' => 'Toddler Gym', 'price' => '£169 / term', 'age' => '1 – 3 years', 'link' => home_url('/toddler-gym/')),
            array('time' => '10:30 – 11:10', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years', 'link' => home_url('/mini-gym/')),
            array('time' => '1:20 – 2:00', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years', 'link' => home_url('/mini-gym/')),
            array('time' => '2:10 – 2:50', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years', 'link' => home_url('/mini-gym/')),
            array('time' => '4:00 – 4:45', 'class' => 'Gymnastics', 'price' => '£195 / term', 'age' => 'Beginners', 'link' => home_url('/gymnastics/'), 'note' => 'Ex Mini Gym only'),
            array('time' => '4:30 – 5:30', 'class' => 'Gymnastics', 'price' => '£208 / term', 'age' => 'Girls Squad', 'link' => home_url('/gymnastics/'), 'note' => 'Warm-up in adjacent hall'),
        ),
        'thursday' => array(
            array('time' => '9:30 – 10:10', 'class' => 'Toddler Gym', 'price' => '£169 / term', 'age' => '1 – 3 years', 'link' => home_url('/toddler-gym/')),
            array('time' => '10:30 – 11:10', 'class' => 'Tiddler Gym', 'price' => '£10 / class', 'age' => '6 – 12 months', 'link' => home_url('/tiddler-gym/'), 'note' => 'Starting 13 April'),
            array('time' => '1:20 – 2:00', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years', 'link' => home_url('/mini-gym/')),
            array('time' => '2:10 – 2:50', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years', 'link' => home_url('/mini-gym/')),
            array('time' => '4:00 – 4:45', 'class' => 'Gymnastics', 'price' => '£195 / term', 'age' => 'Beginners', 'link' => home_url('/gymnastics/'), 'note' => 'Ex Mini Gym only'),
            array('time' => '4:45 – 5:45', 'class' => 'Gymnastics', 'price' => '£208 / term', 'age' => 'Girls Junior Squad', 'link' => home_url('/gymnastics/')),
            array('time' => '4:45 – 6:45', 'class' => 'Gymnastics', 'price' => '£253 / term', 'age' => 'Girls Elite', 'link' => home_url('/gymnastics/')),
        ),
    );
}
?>

<section class="page-hero">
    <div class="container">
        <p class="page-hero-eyebrow"><?php echo esc_html($hero_eyebrow); ?></p>
        <h1><?php echo wp_kses_post($hero_title); ?></h1>
        <p class="page-hero-sub"><?php echo wp_kses_post($hero_subtitle); ?></p>
    </div>
</section>

<section class="timetable-section" aria-label="<?php _e('Weekly class timetable', 'tjs-gymnastics'); ?>">
    <div class="container">
        <div class="timetable-grid">
            <?php
            $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday');
            
            foreach ($days as $day):
                $day_lower = strtolower($day);
                $day_classes = isset($timetable_data[$day_lower]) ? $timetable_data[$day_lower] : array();
            ?>
                <div>
                    <div class="day-heading-wrap">
                        <h2 class="day-heading"><?php echo esc_html($day); ?></h2>
                        <div class="day-heading-line"></div>
                    </div>
                    <div class="tt-wrap">
                        <table class="tt-table" aria-label="<?php echo esc_attr($day . ' classes'); ?>">
                            <thead>
                                <tr>
                                    <th><?php _e('Time', 'tjs-gymnastics'); ?></th>
                                    <th><?php _e('Class', 'tjs-gymnastics'); ?></th>
                                    <th><?php _e('Age / Level', 'tjs-gymnastics'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($day_classes)): ?>
                                    <?php foreach ($day_classes as $class): ?>
                                        <tr>
                                            <td class="td-time" data-label="<?php _e('Time', 'tjs-gymnastics'); ?>"><?php echo esc_html($class['time']); ?></td>
                                            <td data-label="<?php _e('Class', 'tjs-gymnastics'); ?>">
                                                <a href="<?php echo esc_url($class['link']); ?>" class="td-class-name"><?php echo esc_html($class['class']); ?></a>
                                                <span class="td-price"><?php echo esc_html($class['price']); ?></span>
                                            </td>
                                            <td data-label="<?php _e('Age / Level', 'tjs-gymnastics'); ?>">
                                                <?php echo esc_html($class['age']); ?>
                                                <?php if (isset($class['note']) && !empty($class['note'])): ?>
                                                    <span class="td-note"><?php echo esc_html($class['note']); ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" style="text-align: center; padding: 20px;"><?php _e('No classes scheduled for this day.', 'tjs-gymnastics'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
