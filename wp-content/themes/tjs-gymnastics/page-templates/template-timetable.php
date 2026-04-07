<?php
/**
 * Template Name: Timetable Page
 */
get_header();

$hero_eyebrow = get_field('hero_eyebrow') ?: '2025 / 2026';
$hero_title = get_field('hero_title') ?: 'Weekly <em>Timetable</em>';
$hero_subtitle = get_field('hero_subtitle') ?: 'Classes run Monday to Thursday at Raynes Park Sports Pavilion. Click any class name to view full details, book your place or join the waiting list.';
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
                                <?php
                                if (have_rows($day_lower . '_classes')):
                                    while (have_rows($day_lower . '_classes')): the_row();
                                        $time = get_sub_field('time');
                                        $class_name = get_sub_field('class_name');
                                        $class_link = get_sub_field('class_link');
                                        $price = get_sub_field('price');
                                        $age_level = get_sub_field('age_level');
                                        $note = get_sub_field('note');
                                ?>
                                    <tr>
                                        <td class="td-time" data-label="<?php _e('Time', 'tjs-gymnastics'); ?>"><?php echo esc_html($time); ?></td>
                                        <td data-label="<?php _e('Class', 'tjs-gymnastics'); ?>">
                                            <a href="<?php echo esc_url($class_link); ?>" class="td-class-name"><?php echo esc_html($class_name); ?></a>
                                            <span class="td-price"><?php echo esc_html($price); ?></span>
                                        </td>
                                        <td data-label="<?php _e('Age / Level', 'tjs-gymnastics'); ?>">
                                            <?php echo esc_html($age_level); ?>
                                            <?php if ($note): ?><span class="td-note"><?php echo esc_html($note); ?></span><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php
                                    endwhile;
                                else:
                                    // Default timetable data - matching demo page
                                    $default_classes = array(
                                        'monday' => array(
                                            array('time' => '1:20 – 2:00', 'class' => 'Mini Gym', 'price' => '£168 / term', 'age' => '3 – 4½ years'),
                                            array('time' => '2:10 – 2:50', 'class' => 'Mini Gym', 'price' => '£168 / term', 'age' => '3 – 4½ years'),
                                            array('time' => '4:00 – 4:45', 'class' => 'Gymnastics', 'price' => '£180 / term', 'age' => 'Mixed Intermediate'),
                                            array('time' => '4:30 – 5:15', 'class' => 'Gymnastics', 'price' => '£180 / term', 'age' => 'Girls Mini Squad', 'note' => 'Warm-up in adjacent hall'),
                                            array('time' => '5:00 – 6:00', 'class' => 'Gymnastics', 'price' => '£192 / term', 'age' => 'Boys Elite', 'note' => 'Warm-up in adjacent hall'),
                                        ),
                                        'tuesday' => array(
                                            array('time' => '9:40 – 10:20', 'class' => 'Toddler Gym', 'price' => '£169 / term', 'age' => '1 – 3 years'),
                                            array('time' => '10:30 – 11:10', 'class' => 'Toddler Gym', 'price' => '£169 / term', 'age' => '1 – 3 years'),
                                            array('time' => '1:20 – 2:00', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years'),
                                            array('time' => '2:10 – 2:50', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years'),
                                            array('time' => '4:00 – 4:45', 'class' => 'Gymnastics', 'price' => '£195 / term', 'age' => 'Beginners', 'note' => 'Ex Mini Gym only'),
                                            array('time' => '4:45 – 5:30', 'class' => 'Gymnastics', 'price' => '£195 / term', 'age' => 'Mixed Intermediate'),
                                            array('time' => '5:30 – 6:30', 'class' => 'Gymnastics', 'price' => '£208 / term', 'age' => 'Mixed Squad'),
                                        ),
                                        'wednesday' => array(
                                            array('time' => '9:30 – 10:10', 'class' => 'Toddler Gym', 'price' => '£169 / term', 'age' => '1 – 3 years'),
                                            array('time' => '10:30 – 11:10', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years'),
                                            array('time' => '1:20 – 2:00', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years'),
                                            array('time' => '2:10 – 2:50', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years'),
                                            array('time' => '4:00 – 4:45', 'class' => 'Gymnastics', 'price' => '£195 / term', 'age' => 'Beginners', 'note' => 'Ex Mini Gym only'),
                                            array('time' => '4:30 – 5:30', 'class' => 'Gymnastics', 'price' => '£208 / term', 'age' => 'Girls Squad', 'note' => 'Warm-up in adjacent hall'),
                                        ),
                                        'thursday' => array(
                                            array('time' => '9:30 – 10:10', 'class' => 'Toddler Gym', 'price' => '£169 / term', 'age' => '1 – 3 years'),
                                            array('time' => '10:30 – 11:10', 'class' => 'Tiddler Gym', 'price' => '£10 / class', 'age' => '6 – 12 months', 'note' => 'Starting 13 April'),
                                            array('time' => '1:20 – 2:00', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years'),
                                            array('time' => '2:10 – 2:50', 'class' => 'Mini Gym', 'price' => '£182 / term', 'age' => '3 – 4½ years'),
                                            array('time' => '4:00 – 4:45', 'class' => 'Gymnastics', 'price' => '£195 / term', 'age' => 'Beginners', 'note' => 'Ex Mini Gym only'),
                                            array('time' => '4:45 – 5:45', 'class' => 'Gymnastics', 'price' => '£208 / term', 'age' => 'Girls Junior Squad'),
                                            array('time' => '4:45 – 6:45', 'class' => 'Gymnastics', 'price' => '£253 / term', 'age' => 'Girls Elite'),
                                        ),
                                    );
                                    
                                    if (isset($default_classes[$day_lower])):
                                        foreach ($default_classes[$day_lower] as $class):
                                ?>
                                    <tr>
                                        <td class="td-time" data-label="<?php _e('Time', 'tjs-gymnastics'); ?>"><?php echo esc_html($class['time']); ?></td>
                                        <td data-label="<?php _e('Class', 'tjs-gymnastics'); ?>">
                                            <a href="#" class="td-class-name"><?php echo esc_html($class['class']); ?></a>
                                            <span class="td-price"><?php echo esc_html($class['price']); ?></span>
                                        </td>
                                        <td data-label="<?php _e('Age / Level', 'tjs-gymnastics'); ?>">
                                            <?php echo esc_html($class['age']); ?>
                                            <?php if (isset($class['note'])): ?><span class="td-note"><?php echo esc_html($class['note']); ?></span><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php 
                                        endforeach;
                                    endif;
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
